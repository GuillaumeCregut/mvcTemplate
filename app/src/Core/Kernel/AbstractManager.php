<?php

namespace Editiel98\Kernel;

use Editiel98\Kernel\Database;
use Editiel98\Kernel\Exception\DbException;
use Editiel98\Flash;
use Exception;

/**
 * Manage informations from DB to Entity
 */
abstract class AbstractManager
{
    /**
     * Name of the table represent entity
     *
     * @var string
     */
    protected string $table;

    /**
     * Instance of the DB connection
     *
     * @var Database
     */
    protected Database $db;

    /**
     * Class name of the entity
     *
     * @var string
     */
    protected string $className;


    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Magic function for getters
     *
     * @param [type] $name
     * @return void
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        return $this->$method();
    }

    /**
     * Exec the query
     *
     * @param string $query : Query to execute
     * @param array $vars : vars for the query
     * @return mixed
     */
    protected function execSQL(string $query, array $vars): mixed
    {
        try {
            $result = $this->db->exec($query, $vars);
            return $result;
        } catch (DbException $e) {
            if ($e->getDbCode() === 23000) {
                $flash = new Flash();
                $flash->setFlash('Modification impossible', 'error');
                return false;
            }
            $message = 'SQL : ' . $query . 'a poser problème';
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, $message);
            throw new Exception('Une erreur est survenue');
        }
    }

    /**
     * Execute a prepared query
     *
     * @param string $query : query to execute
     * @param array $vars : vars for the query
     * @param boolean $single : return one result or not
     * @return mixed
     */
    protected function prepareSQL(string $query, array $vars, bool $single): mixed
    {
        try {
            $result = $this->db->prepare($query, $this->className, $vars, $single);
            return $result;
        } catch (DbException $e) {
            $message = 'SQL : ' . $query . 'a poser problème';
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, $message);
            throw new Exception('Une erreur est survenue');
        }
    }

}
