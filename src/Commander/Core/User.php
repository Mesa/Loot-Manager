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
    /**
     * Ablaufdatum des Remember me Cookie
     * (60 Tage)
     *
     * @var [INT] 60 Time in seconds
     */
    protected $cookie_expire = 5184000;
    protected $remember_me = false;
    protected $user_id = null;
    protected $loginName = null;
    protected $Name = null;
    protected $Email = null;
    protected $last_login = 0;
    protected $login_redirect_path = null;
    protected $logged_in = false;
    protected $max_false_trys = 5;
    protected $blacklist = null;
    protected $dao;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $session_id = session_id();
        if (empty ($session_id)) {
            session_start();
        }
        $this->em = $em;

        $this->getUserData();
    }

    /**
     * gather userdata. Try to find existing Session and get userdata from DB
     *
     * @return void
     */
    protected function getUserData()
    {
        if (isset($_SESSION["user_id"])) {
            $result = $this->dao->getUserById((int)$_SESSION["user_id"]);
        } elseif (isset($_COOKIE["rememberMeToken4"])) {
            $result = $this->dao->getUserByToken($_COOKIE["rememberMeToken4"]);
            /**
             * Wenn der Benutzer einen gesetzten Token hat, dieser aber
             * nicht in der DB ist, ist die UserID == NULL
             */
            if ($result["Id"] == null) {
                $this->deleteUserToken();
                unset($result);
            }
        }


        if (!isset($result) or count($result) == 0) {
            $result        = $this->dao->getGuestAccount();
            $this->user_id = (int)$result["Id"];
            $this->Name    = $result["Name"];
        } else {
            $this->user_id   = (int)$result["Id"];
            $this->Name      = $result["Name"];
            $this->loginName = $result["UserName"];
            $this->Email     = $result["Email"];
            $this->logged_in = true;
            $this->dao->setLastLogin($this->user_id, time());
        }
    }

    /**
     * Get the encoded user login
     *
     * @return [String] encoded User login
     */
    public function getLoginName()
    {
        return base64_decode($this->loginName);
    }

    /**
     * Destroy the Session and logout the User
     *
     * @return void
     */
    public function logout()
    {
        $this->deleteUserToken();
        $this->logged_in = false;

        if (isset($_SESSION["user_id"])) {
            session_destroy();
        }
    }

    /**
     * Login User
     *
     * @param  [String] $username    Username
     * @param  [String] $password    Password
     * @param  [Bool]   $remember_me Set Cookie?
     *
     * @return [BOOL]
     */
    public function login($username, $password, $remember_me = false)
    {
        if ($remember_me === true) {
            $this->remember_me = true;
        }

        $result = $this->dao->getUserByNamePassword(
                            $this->encodeUsername($username),
                                $this->hashPassword($password)
        );

        if (isset($result["Id"])) {

            /**
             * Damit PHPUnit auch funktioniert, bzw kein "Header is already
             * send" fehler kommt.
             */
            @session_regenerate_id();
            $this->user_id       = (int)$result["Id"];
            $this->Name          = $result["Name"];
            $this->Email         = $result["Email"];
            $this->loginName     = $result["UserName"];
            $this->last_login    = time();
            $this->logged_in     = true;
            $_SESSION["user_id"] = $this->user_id;

            if ($this->remember_me === true) {
                $this->setUserToken();
            }

            $this->dao->setLastLogin($this->user_id, time());

            return true;
        } else {
            return false;
        }
    }

    public function setLoginName($new_name)
    {
        $response         = new \JackAssPHP\Core\Response();
        $response->status = false;

        if (strlen($new_name) < 3) {
            $response->to_short = true;

            return $response;
        }

        if (!$this->dao->isGuestAccount($this->getUserId())) {
            $response->status = $this->dao->setLoginName($this->getUserId(), $this->encodeUsername($new_name));
        } else {
            $response->is_guest = true;
        }

        return $response;
    }

    public function setPassword($new_password)
    {
        $response         = new \JackAssPHP\Core\Response();
        $response->status = false;

        if (strlen($new_password) < $this->registry->get("MIN_PASSWORD_LENGTH")) {
            $response->to_short = true;

            return $response;
        }

        if ($this->dao->isGuestAccount($this->getUserId())) {
            $response->is_guest = true;

            return $response;
        }

        $response->status = $this->dao->setPassword($this->getUserId(), $this->hashPassword($new_password));

        return $response;
    }

    /**
     * Remove all cookies from DB and user
     *
     * @return void
     */
    protected function deleteUserToken()
    {
        @setcookie("rememberMeToken1", "", time() - 1000, "/");
        @setcookie("rememberMeToken2", "", time() - 1000, "/");
        @setcookie("rememberMeToken3", "", time() - 1000, "/");
        @setcookie("rememberMeToken4", "", time() - 1000, "/");

        $this->dao->setUserToken($this->user_id, "");
    }

    /**
     * Decode Username, because the username is saved encoded in the DB
     *
     * @param  [String] $username base64 decoded username
     *
     * @return [String] Decoded Username
     */
    public function decodeUsername($username)
    {
        return base64_decode($username);
    }

    /**
     * Encode the username to search or save in the DB
     *
     * @param  [String] $username Username to encode
     *
     * @return [String] Encoded Username
     */
    public function encodeUsername($username)
    {
        return base64_encode($username);
    }

    /**
     * Encrypt Password
     *
     * @todo   use a salt in case the DB was hacked, the password could not be encrypted by a rainbow table
     *
     * @param  [String] $password Password to encrypt
     *
     * @return [String] Encrypted Password
     */
    public function hashPassword($password)
    {
        return hash('sha256', $password);
    }

    /**
     * Set Cookie and token in DB, to auto login user, the next time he
     * visit the website
     *
     * @return void
     */
    protected function setUserToken()
    {
        /**
         * Cookie 1-3 sind fake, damit der Hacker etwas mehr zu tun hat.
         * Hoffentlich beschäftigt er sich dann um einiges länger mit den
         * cookies bis er den richtigen findet.

         */
        $token = hash("sha256", $this->Email . $this->Name . time());

        setcookie(
            "rememberMeToken1",
            hash("sha256", $this->Name),
            time() + $this->cookie_expire,
            "/"
        );

        setcookie(
            "rememberMeToken2",
            hash("sha256", $_SERVER["REMOTE_ADDR"]),
            time() + $this->cookie_expire,
            "/"
        );

        setcookie(
            "rememberMeToken3",
            hash("sha256", time()),
            time() + $this->cookie_expire,
            "/"
        );

        setcookie(
            "rememberMeToken4",
            $token,
            time() + $this->cookie_expire,
            "/"
        );

        $this->dao->setUserToken($this->user_id, $token);
    }

    /**
     * Get the Username to Display on the Website
     *
     * @return [String] Name
     */
    public function getName()
    {
        return $this->Name;
    }

    public function setName($name)
    {
        $clear_name       = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $response         = new \JackAssPHP\Core\Response();
        $response->name   = $clear_name;
        $response->status = false;

        if ($this->dao->isGuestAccount($this->user_id)) {
            $response->is_guest = true;
        } elseif ($this->dao->nameExists($clear_name)) {
            $response->name_exists = true;
        } else {
            $response->status = $this->dao->setName($this->getUserId(), $clear_name);
        }

        return $response;
    }

    public function setEmail(&$email)
    {
        $clear_email        = filter_var($email, FILTER_SANITIZE_EMAIL);
        $response           = new \JackAssPHP\Core\Response();
        $response->status   = false;
        $response->is_guest = false;
        $response->email    = $clear_email;

        if ($this->dao->isGuestAccount($this->user_id)) {
            $response->is_guest = true;

            return $response;
        }

        if (Filter::validateEmail($clear_email)) {
            $response->status = $this->dao->setEmail($this->getUserId(), $clear_email);
        } else {
            $response->email = "";
        }

        return $response;
    }

    /**
     * Get the user email adress
     *
     * @return [String] E-Mail Adress
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * Get the unique user number, to identificate him in the DB
     *
     * @return [Int] User ID
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the login status for user
     *
     * @return [Bool] Is user logged in === true
     */
    public function isLoggedIn()
    {
        if ($this->logged_in === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get User id by Name
     *
     * @param  [Int] $id User Id
     *
     * @return [String]
     */
    public function getUserNameById($id)
    {
        if (!isset($this->user_list[$id])) {
            $name                 = $this->dao->getUserNameById($id);
            $this->user_list[$id] = $name;
        }

        return $this->user_list[$id];
    }
}