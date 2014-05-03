<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Commander\Core;

use Doctrine\ORM\EntityManager;

class User
{
    const LOGGED_IN  = 1;
    const LOGGED_OUT = 2;
    private $sessionName = "LootManager";
    private $useCookie = false;
    private $status = false;
    private $userEntity;
    /**
     * @var \LootManager\Entity\User
     */
    private $user;
    /**
     * @var EntityManager
     */
    private $em;
    private $userId;

    /**
     * @Inject({"EntityManager"})
     */
    public function __construct($em)
    {
        $this->userEntity = 'LootManager\Entity\User';
        $this->em         = $em;
        $session_id       = session_id();
        if (empty ($session_id)) {
            session_start();
        }

        $userData = $this->getFromSession("userData");

        if (!$userData) {
            $this->status = self::LOGGED_OUT;
            $this->userId = 0;
        } else {
            $this->status = self::LOGGED_IN;
            $this->userId = $this->getFromSession("userId");
        }
        $this->loadFromDb();
    }

    public function getFromSession($name, $defaultVal = false)
    {
        if (!isset($_SESSION[$this->sessionName][$name])) {
            return $defaultVal;
        } else {
            return $_SESSION[$this->sessionName][$name];
        }
    }

    protected function loadFromDb()
    {
        if ($this->userId == 0) {
            $this->user = $this->em->getRepository($this->userEntity)->findOneBy(['login' => 'Guest']);
            $this->status = self::LOGGED_OUT;
        } else {
            $this->user = $this->em->find($this->userEntity, $this->userId);
            $this->status = self::LOGGED_IN;
        }

        $this->userId = $this->user->getId();
    }

    public function has($role)
    {
        $role = $this->em->getRepository('LootManager\Entity\Role')->findOneBy(["name" => $role]);
        if (empty($role)) {
            return false;
        }

        return $this->user->getRoles()->contains($role);
    }

    public function getName()
    {
        return $this->user->getName();
    }

    public function isLoggedIn()
    {
        return $this->status === self::LOGGED_IN;
    }

    public function setToSession($name, $value)
    {
        $_SESSION[$this->sessionName][$name] = $value;
    }

    /**
     * Destroy the Session and logout the User
     *
     * @return void
     */
    public function logout()
    {
        $this->status = self::LOGGED_OUT;

        if (isset($_SESSION[$this->sessionName])) {
            session_destroy();
        }
    }

    /**
     * Login User
     *
     * @param      $login
     * @param      $password
     * @param bool $setCookie
     *
     * @return bool [BOOL]
     */
    public function login($login, $password, $setCookie = false)
    {
        $this->useCookie = $setCookie;

        if ($this->useCookie === true) {
            $this->setUserToken();
        }
    }

    /**
     * Remove all cookies from DB and user
     *
     * @return void
     */
    protected function deleteCookie()
    {
        setcookie($this->sessionName, "", time() - 1000, "/");
    }
}