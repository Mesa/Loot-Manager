<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

class BlackList
{

    protected $ip = null;
    protected $row_id = null;
    protected $last_try = null;
    protected $login_try = null;
    protected $locked_until = null;
    protected $max_false_trys = 15;
    protected $blocking_time = 3600;

    /**
     * Constructor
     *
     * @param \JackAssPHP\Core\Registry    $registry           Registry Object
     * @param \Application\Model\BlackList $data_excess_object DB acess Object
     */
    public function __construct (
        \JackAssPHP\Core\Registry $registry,
        \Application\Model\BlackList $data_excess_object
    ) {
        $this->dao = $data_excess_object;
        $this->registry = $registry;
        $this->max_false_trys = $this->registry->get("MAX_FALSE_LOGIN_TRIES");
        $this->locking_time = $this->registry->get("LOCKING_TIME");

        $this->getUserIp();
        $this->cleanDB();
        $this->getData();
    }

    /**
     * Get the User Ip
     *
     * @return void
     */
    protected function getUserIp ()
    {
        if (! empty($_SERVER["HTTP_X_FORWARDED_FOR"])
            && filter_var($_SERVER["HTTP_X_FORWARDED_FOR"], FILTER_VALIDATE_IP) !== false
        ) {
            $this->ip = filter_var($_SERVER["HTTP_X_FORWARDED_FOR"], FILTER_VALIDATE_IP);
        } else {
            $this->ip = filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP);
        }
    }

    /**
     * Fügt einen weiteren fehlerhaften login hinzu.
     *
     * @return void
     */
    public function addPenalty ()
    {
        $this->login_try++;

        if ($this->login_try >= $this->max_false_trys) {
            $this->dao->updateIp(
                $this->row_id,
                $this->login_try,
                $this->locking_time + time()
            );
        } elseif ($this->row_id !== null) {
            $this->dao->updateIp($this->row_id, $this->login_try);
        } else {
            $this->dao->addIp($this->ip);
        }
    }

    /**
     * Load Data that belong to the Ip
     *
     * @return void
     */
    protected function getData ()
    {
        $result = $this->dao->getListByIp($this->ip);

        if ( $result["Id"] > 0 ) {
            $this->locked_until = $result["locked_until"];
            $this->login_try = $result["login_try"];
            $this->last_try = $result["last_try"];
            $this->row_id = $result["Id"];
        } else {
            $this->locked_until = 0;
            $this->login_try = 0;
            $this->last_try = 0;
        }
    }

    /**
     * Prüfen ob die Ip mit einer Sperre belget ist.
     *
     * @return [Bool] Is blocked === true
     */
    public function isBlocked ()
    {
        if ( $this->locked_until >= time() ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Ip neu in die Datenbank aufnehmen.
     *
     * @return void
     */
    public function blockIp ()
    {
        if ($this->row_id == null) {
            $this->dao->addIp($this->ip);
        }

        $this->dao->updateIp(
            $this->row_id,
            $this->login_try,
            $this->locking_time + time()
        );

        $this->locked_until = $this->locking_time + time();
        $this->last_try = time();
    }

    /**
     * Alle alten und abgelaufenen Einträge in der DB löschen
     *
     * @return void
     */
    protected function cleanDB ()
    {
        $this->dao->dropOldRows(time());
    }

    /**
     * Delete Ip from Blacklist
     *
     * @return void
     */
    public function clearIp ()
    {
        $this->dao->dropIp($this->ip);
        $this->login_try = 0;
        $this->locked_until = 0;
        $this->last_try = 0;
        $this->row_id = null;
    }

    /**
     * Get the count of login attempts
     *
     * @return [Int] Count of login attempts
     */
    public function getLoginTry ()
    {
        return (int) $this->login_try;
    }

}
