<?php

namespace Editiel98;

use Editiel98\Kernel\Database\Database;
use Editiel98\Templates\SmartyEditiel;

class Factory
{
    /**
     * @var Factory
     */
    private static $instance;
    /**
     * @var Database
     */
    private static $db;
    /**
     * @var SmartyEditiel
     */
    private static $smarty;
    /**
     * @var Session
     */
    private static $session;


    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (!is_null(self::$instance)) {
            self::$instance = new Factory();
        }
        return self::$instance;
    }


    /**
     * return instance of Database
     * @return Database
     */
    public static function getdB(): Database
    {
        if (is_null(self::$db)) {
            self::$db = new Database();
        }
        return self::$db;
    }

    /**
     * @return SmartyEditiel
     */
    public static function getSmarty(): SmartyEditiel
    {
        if (is_null(self::$smarty)) {
            self::$smarty = new SmartyEditiel();
        }
        return self::$smarty;
    }

    /**
     * return an instance of session
     * @return Session
     */
    public static function getSession(): Session
    {
        if (is_null(self::$session)) {
            self::$session = new Session();
        }
        return self::$session;
    }
}
