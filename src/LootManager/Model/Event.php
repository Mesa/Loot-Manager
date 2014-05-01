<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

/**
 * Database Model for all Events
 */
class Event
{

    protected $db = null;
    protected $table_name = "events";
    protected $event_list = array();

    /**
     * __constructor
     */
    public function __construct ()
    {
        $this->db = \Factory::getDB();
        if (false !== $this->db) {
            $this->getEventList();
        }
    }

    /**
     * Get the last inster ID from DB
     *
     * @return type [int] last insert ID
     */
    public function getFirstEventId ()
    {
        reset($this->event_list);
        $event = key($this->event_list);
        if ($event !== null) {
            return $event;
        } else {
            $request = $this->db->prepare(
                    "ALTER TABLE `events` AUTO_INCREMENT = 1 ;"
            );

            $request->execute();

            $this->addEvent("Default Event");
            return 1;
        }
    }

    /**
     * get a all Events from DB
     *
     * @return void
     */
    protected function getEventList ()
    {
        $request = $this->db->prepare("SELECT `Id`, `Name` FROM `" . $this->table_name . "` ORDER BY `Id`");

        /**
         * @todo remove catch and use a global Excaption handler
         */
        try {
            $request->execute();
        } catch (\PDOException $exc) {

        }

        $result = $request->fetchAll();

        foreach ($result as $event) {
            $this->event_list[(int) $event["Id"]] = $event["Name"];
        }
    }

    /**
     * Create new Event
     *
     * @param [String] $event_name New Eventname
     *
     * @return void
     */
    public function addEvent ( $event_name )
    {
        if (!isset($this->event_list[$event_name]) and strlen($event_name) > 0) {

            $request = $this->db->prepare(
                    "INSERT INTO `" . $this->table_name . "`
                        (`Name`)
                        VALUES
                        (:event_name)"
            );
            $request->bindParam(":event_name", $event_name);

            $request->execute();
        }
    }

    /**
     * Rename Event by Id
     *
     * @param [Integer] $event_id Event Id
     * @param [String]  $new_name New Eventname
     *
     * @return void
     */
    public function editEvent ( $event_id, $new_name )
    {
        if ($event_id > 0) {
            $request = $this->db->prepare("UPDATE `" . $this->table_name . "` SET `Name` = :new_name WHERE `Id`= :event_id;");
            $request->bindValue(":event_id", $event_id, \PDO::PARAM_INT);
            $request->bindValue(":new_name", $new_name, \PDO::PARAM_STR);

            $request->execute();
        }
    }

    /**
     * Delete Event from DB by id
     *
     * @param [Integer] $event_id Event ID
     *
     * @return void
     */
    public function deleteEvent ( $event_id )
    {
        if ($event_id > 0) {
            $request = $this->db->prepare("DELETE FROM `lootorder` WHERE `EventId`=:event_id;");
            $request->bindValue(":event_id", $event_id);
            $request->execute();

            $request = $this->db->prepare("DELETE FROM `events` WHERE `Id`=:event_id;");
            $request->bindValue(":event_id", $event_id);
            $request->execute();
        }
    }

    public function getEventName ( $id )
    {
        $eventId = (int) $id;
        if ( isset($this->event_list[$eventId]) ) {
            return $this->event_list[$eventId];
        } else {
            return false;
        }
    }

}