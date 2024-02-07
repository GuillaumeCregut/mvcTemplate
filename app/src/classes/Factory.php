<?php

namespace Editiel98;

use Editiel98\Chore\Database;
use Editiel98\Chore\Entity;
use Editiel98\Chore\Manager\AbstractManager;

class Factory
{
    private static $_instance;
    private static $db;
    private static $smarty;
    private static $session;

    /**
     * create a singleton of Factory
     * @return [type] Factory
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Factory();
        }
        return self::$_instance;
    }

    /**
     * return a new Entity 
     * @param string $name : name of entity to create
     * 
     * @return Entity
     */
    public static function getEntity(string $name): Entity
    {
        $className = 'Editiel98\\Entity\\' . ucFirst($name);
        return new $className;
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
     * Return an instance of manager
     * @param string $name
     * 
     * @return Manager
     */
    public static function getManager(string $name): AbstractManager
    {
        if (is_null(self::$db)) {
            self::getdB();
        }
        $className = 'Editiel98\\Manager\\' . ucFirst($name);
        return new $className(self::$db);
    }

    /**
     * Return an instance of smarty
     * @return SmartyMKD
     */
    public static function getSmarty(): SmartyMKD
    {
        if (is_null(self::$smarty)) {
            self::$smarty = new SmartyMKD();
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
