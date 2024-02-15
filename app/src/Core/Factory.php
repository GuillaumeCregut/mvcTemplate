<?php

namespace Editiel98;

use Editiel98\Kernel\AbstractManager;
use Editiel98\Kernel\Database;
use Editiel98\Kernel\Entity\AbstractEntity;
use Editiel98\Templates\SmartyEditiel;

class Factory
{
    /**
     * @var Factory
     */
    private static  $_instance;
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
        if (!is_null(self::$_instance)) {
            self::$_instance = new Factory();
        }
        return self::$_instance;
    }

    /**
     * return a new Entity 
     * @param string $name : name of entity to create
     * 
     * @return AbstractEntity
     *//*
    public static function getEntity(string $name): AbstractEntity
    {
        $className = 'App\\Entity\\' . ucFirst($name);
        return new $className;
    }
*/

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
     * @param string $name
     * 
     * @return AbstractManager
     *//*
    public static function getManager(string $name): AbstractManager
    {
        if (is_null(self::$db)) {
            self::getdB();
        }
        $className = 'App\\Manager\\' . ucFirst($name);
        return new $className(self::$db);
    }*/


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
