<?php

/**
 * Loot-Manager
 * Copyright (C) 2012  Mesa <Daniel Langemann>
 *
 * @package  Loot-Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Helper;

class Installer
{

    protected $file_name = null;
    protected $xml = null;
    protected $db = null;

    public function __construct ( $file_name, $db_connection = null )
    {
        if (file_exists($file_name)) {
            $this->db = $db_connection;
            $this->file_name = $file_name;
            $this->parseXml($file_name);
        }
    }

    protected function parseXml ()
    {
        $this->xml = simplexml_load_file($this->file_name);

        foreach ($this->xml as $node_name => $node) {

            if ($this->db == null
                && ( strtolower($node_name) == "sql"
                || strtolower($node_name) == "data"
                || strtolower($node_name) == "table" )
            ) {
                continue;
            }

            switch (strtolower($node_name)) {

            case "sql":
                $this->executeSql((string) $node);
                break;

            case "data":
                $this->checkData($node);
                break;

            case "copy_file":
                $this->copyFile((string) $node->attributes()->From, (string) $node->attributes()->To);
                break;

            case "delete_file":
                $this->deleteFile((string) $node->attributes()->File_Path);
                break;

            case "move_file":
                $this->moveFile((string) $node->attributes()->From, (string) $node->attributes()->To);
                break;

            case "create_file":
                $this->createFile((string) $node->attributes()->File_Path, (string) $node);
                break;

            case "table":
                $this->checkTableStructure($node);
                break;

            case "replace_text":
                $this->replaceText((string) $node->attributes()->Search, (string) $node->attributes()->Replace, (string) $node->attributes()->File_Name, (string) $node->attributes()->RegEx);
                break;

            case "rename_table":
                $this->renameTable((string) $node->attributes()->Old_name, (string) $node->attributes()->New_name);
                break;

            case "drop_table":
                $this->dropTable((string) $node->attributes()->Name);
                break;

            case "create_folder":
                $this->createFolder((string) $node->attributes()->Path, (int) $node->attributes()->Mode);
                break;
            }
        }
    }

    protected function createFolder ( $path, $mode = 0777 )
    {
        if (!file_exists($path)) {
            mkdir($path, "0774", true);

            if (file_exists($path)) {
                echo "[Folder]: $path has been created<br />\n";
            } else {
                echo "[Error] was not able to create $path";
            }
        } else {
            echo "[Folder]: $path already exists<br>\n";
        }
    }

    /**
     * Check for the presence of data
     *
     * @param type $xml simplexml object
     *
     * @return [String/HTML]
     */
    protected function checkData ( $xml )
    {
        $table_name = $xml->attributes()->table;

        foreach ($xml->children() as $child) {

            $message = "";
            $children = (array) $child->children();
            $child_name = key($children);
            $child_value = $children[$child_name];

            $request = $this->db->query("SELECT * FROM `$table_name` WHERE `" . $child_name . "` = '" . $child_value . "'");
            $result = $request->fetch();

            if (false !== $result) {
                /**
                 * Row exists
                 */
                $diff = false;

                /**
                 * check for differences
                 */
                foreach ($children as $key => $value) {
                    if (key_exists($key, $result)) {
                        if ($result[$key] != $value and !($result[$key] == null and strlen($value) == 0 )) {
                            $diff = true;
                            $message .= " | `" . $key . "` was changed to '" . $value . "'";
                        }
                    }
                }

                if ($diff === true) {
                    /**
                     * Update entry
                     */
                    $query = "UPDATE `$table_name` SET";
                    foreach ($children as $key => $value) {
                        if (key_exists($key, $result)) {

                            if (strlen($value) == 0) {
                                $query .= " `" . $key . "` = NULL,";
                            } else {
                                $query .= " `" . $key . "` = '" . $value . "',";
                            }
                        } else {
                            /**
                             * column doesnt exist, show Error and go on.
                             */
                            echo "[Error] Column `$key` doesnt exist in $table_name<br>\n";
                        }
                    }
                    /**
                     * remove last comma
                     */
                    $query = substr($query, 0, -1);
                    $query .= " WHERE `" . $child_name . "` = '" . $child_value . "'";
                    $this->db->query($query);
                    $message = "[data] `$table_name`.`$child_name` => '$child_value' was updated. " . $message;
                    echo $message . "<br>\n";
                }
            } else {
                /**
                 * insert Row
                 */
                $message = "[data] Insert Into `$table_name` ";
                $query = "INSERT INTO `$table_name`";

                $field_names = " (";
                $values = " (";
                foreach ($children as $key => $value) {
                    $message .= " | `$key` = '$value'";
                    $field_names .= " `$key`,";
                    $values .= " '$value',";
                }

                $field_names = substr($field_names, 0, -1) . ")";
                $values = substr($values, 0, -1) . ")";
                $this->db->query($query . $field_names . " VALUES " . $values . ";");

                echo $message . "<br>";
            }
        }
    }

    protected function deleteFile ( $path )
    {
        if (file_exists($path)) {
            $test = unlink($path);
            if (false == $test) {
                echo "[File]: could not delete $path <br>\n";
            } else {
                echo "[File]: $path has been deleted<br>\n";
            }
        } else {
            echo "[File]: $path doens't exist.<br>\n";
        }
    }

    protected function copyFile ( $from, $to )
    {
        if (file_exists($from) && file_exists(dirname($to))) {
            $result = copy($from, $to);
            if ($result === true) {
                echo "Copied File from: " . $from . "  To: " . $to . "<br />\n";
            } else {
                echo "[Error] could not copy file $from To $to <br />\n";
            }
        }
    }

    protected function replaceText ( $search, $replace, $filename, $mode )
    {
        if (file_exists($filename)) {

            $content = file_get_contents($filename);

            if (strtolower($mode) == "true") {
                $new_content = preg_replace("/$search/", $replace, $content);
            } else {
                $new_content = str_replace($search, $replace, $content);
            }
            if (strlen($new_content) > 1) {
                $file_handle = fopen($filename, "w");
                fwrite($file_handle, $new_content);
                fclose($file_handle);
                echo $search . " was replaced with " . $replace . " in File " . $filename . "<br>\n";
            }
        }
    }

    protected function executeSql ( $sql )
    {
        $this->db->query($sql);
        echo "$sql<hr>";
    }

    protected function moveFile ( $from, $to )
    {
        if (file_exists($from) && file_exists(dirname($to))) {
            $this->copyFile($from, $to);
            $this->deleteFile($from);
            echo "[File]: " . $from . " was moved to: " . $to . "<br>\n";
        }
    }

    /**
     * Create new file, with specified content.
     *
     * @param type $path path to file
     * @param type $content content for file
     */
    protected function createFile ( $path, $content )
    {
        $dir = dirname($path) . DS;
        if (file_exists($dir)) {
            $file_handle = fopen($path, "w");
            if (false !== $file_handle) {
                fwrite($file_handle, $content);
                fclose($file_handle);
                echo "[File]: " . $path . " created<br>\n";
            } else {
                echo "[Error] Could not create File in $path. Check Filepermissions";
            }
        } else {
            echo "[Error]: " . $dir . " does not exist";
        }
    }

    protected function checkTableStructure ( $xml )
    {
        $table_name = (string) $xml->attributes()->Name;

        if (!$this->tableExists($table_name)) {
            $this->createTable($table_name);
        }

        $column_list = $this->db->prepare("SHOW COLUMNS FROM `$table_name`");
        $column_list->execute();

        while ($data = $column_list->fetch()) {
            $columns[$data["Field"]] = true;
        }

        foreach ($xml as $field) {

            $column_name = (string) $field->attributes()->Name;

            if (isset($columns[$column_name])) {
                unset($columns[$column_name]);
            }

            $result = $this->columnIsEqual(
                $table_name, $column_name, (string) $field->attributes()->Type, (string) $field->attributes()->Null, (string) $field->attributes()->Key, (string) $field->attributes()->Default, (string) $field->attributes()->Extra
            );

            if ($result === "new") {
                echo "`" . $table_name . "`.`" . $column_name . "` added.<br>\n";
                $this->addColumn(
                    $table_name, $column_name, (string) $field->attributes()->Type, (string) $field->attributes()->Null, (string) $field->attributes()->Key, (string) $field->attributes()->Default, (string) $field->attributes()->Extra
                );
            } elseif ($result === false) {
                echo "`" . $table_name . "`.`" . $column_name . "` changed.<br>\n";
                $this->alterColumn(
                    $table_name, $column_name, (string) $field->attributes()->Type, (string) $field->attributes()->Null, (string) $field->attributes()->Key, (string) $field->attributes()->Default, (string) $field->attributes()->Extra
                );
            }

            unset($result);
            unset($column_name);
        }

        /**
         * Delete all columns which are not defined in the xml file
         */
        foreach ($columns as $key => $value) {
            $this->dropColumn($table_name, $key);
            echo "`" . $table_name . "`.`" . $key . "` dropped.<br>\n";
        }
    }

    /**
     * Add Column to table
     *
     * @param type $table_name  Database table name
     * @param type $column_name column name
     * @param type $type        type of column
     * @param type $null        is column NULL?
     * @param string $key       something like PRI [PRIMARY KEY]
     * @param type $default     default value for column
     * @param type $extra       extra data like auto_inkrement
     *
     * @return void
     */
    protected function addColumn ( $table_name, $column_name, $type, $null, $key, $default, $extra )
    {
        if ($null == "YES") {
            $null = "NULL";
        }

        if ($null == "NO") {
            $null = "NOT NULL";
        }

        if ($key == "PRI") {
            $key = "PRIMARY KEY";
        }

        if ( strlen($default) > 0) {
            $default = "DEFAULT ". $default ."";
        }

        $request = $this->db->prepare("ALTER TABLE `$table_name` ADD COLUMN `$column_name` $type $null $key $default $extra");
        $request->execute();
    }

    protected function columnIsEqual ( $table_name, $row_name, $type, $null, $key, $default, $extra )
    {
        $request = $this->db->prepare("SHOW COLUMNS FROM `$table_name` LIKE '$row_name'");

        try {
            $request->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }

        $result = $request->fetch();

        if ($result === false) {
            return "new";
        }
        if (
            $result["Field"] == $row_name
            && strtolower($result["Type"]) == strtolower($type)
            && $result["Null"] == $null
            && $result["Key"] == $key
            && $result["Default"] == $default
            && $result["Extra"] == $extra
        ) {
            return true;
        } else {
            return false;
        }
    }

    protected function dropPrimaryKey ( $table_name )
    {
        $request = $this->db->prepare("SHOW INDEX FROM `$table_name` WHERE `Key_name` = 'PRIMARY'");
        $request->execute();
        $result = $request->fetch();
        $column_name = $result["Column_name"];

        $request = $this->db->prepare("SHOW COLUMNS FROM `$table_name` LIKE '$column_name'");
        $request->execute();
        $result = $request->fetch();

        $this->alterColumn($table_name, $column_name, $result["Type"], $result["Null"], "", $result["Default"], "");
        $request = $this->db->prepare("ALTER TABLE `$table_name` DROP PRIMARY KEY");
        $request->execute();
    }

    protected function alterColumn ( $table_name, $column_name, $type, $null, $key, $default, $extra )
    {
        if ($null == "YES") {
            $null = "NULL";
        }

        if ($null == "NO") {
            $null = "NOT NULL";
        }

        if ( strlen($default) > 0) {
            $default = "DEFAULT ". $default ."";
        }

        if ($key == "PRI") {
            $this->dropPrimaryKey($table_name);
            $request = $this->db->prepare("ALTER TABLE `$table_name` CHANGE COLUMN `$column_name` `$column_name` $type $null $default $extra, ADD PRIMARY KEY (`$column_name`);");
        } else {
            $request = $this->db->prepare("ALTER TABLE `$table_name` CHANGE COLUMN `$column_name` `$column_name` $type $null $default $extra;");
        }

        try {
            $request->execute();
        } catch (\PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    protected function tableExists ( $table_name )
    {
        $request = $this->db->prepare("SHOW TABLES LIKE :table_name");
        $request->bindValue(":table_name", $table_name);
        $request->execute();
        $result = $request->fetchAll();

        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function renameTable ( $old_name, $new_name )
    {
        $request = $this->db->prepare("ALTER TABLE `$old_name`  RENAME TO  `$new_name` ;");
        $request->execute();
    }

    protected function createTable ( $table_name, $engine = "MYISAM", $character = "utf8 COLLATE utf8_general_ci" )
    {
        $request = $this->db->prepare("CREATE TABLE `$table_name`(`Id` int(16) NOT NULL AUTO_INCREMENT , PRIMARY KEY (`Id`) ) ENGINE = $engine CHARACTER SET $character;");
        $request->execute();
    }

    protected function dropTable ( $table_name )
    {
        $request = $this->db->prepare("DROP TABLE IF EXISTS `$table_name`");
        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            echo "[<span style='color:red'>DROP TABLE</span>] Tabelle `$table_name` existiert nicht.<br>";
            $error = true;
        }

        if ( $error == false ) {
            echo "[DROP TABLE] Tabelle `$table_name` wurde gel&ouml;scht<br>";
        }
    }

    protected function dropColumn ( $table_name, $column_name )
    {
        $request = $this->db->prepare("ALTER TABLE `$table_name` DROP COLUMN `$column_name`;");
        $request->execute();
    }

}