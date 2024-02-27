<?php

namespace Editiel98\Kernel\Database;

use Editiel98\Kernel\Exception\DbException;
use Editiel98\Flash;
use Editiel98\Interfaces\DatabaseInterface;
use Editiel98\Kernel\Emitter;
use Editiel98\Kernel\Entity\AbstractEntity;
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
     * @var DatabaseInterface
     */
    protected DatabaseInterface $db;

    /**
     * Class name of the entity
     *
     * @var string
     */
    protected string $className;


    public function __construct(DatabaseInterface $db, string $classname)
    {
        $this->db = $db;
        $this->className = $classname;
    }

    /**
     * @return mixed[]
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM " . $this->table;
        return $this->prepareSQL($query, [], false);
    }

    public function getOneById(int $id): AbstractEntity | false
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id=:id";
        $values = [
            ':id' => $id
        ];
        return $this->prepareSQL($query, $values, true);
    }

    /**
     * @param string $fieldName
     * @param mixed $value
     *
     * @return mixed[]
     */
    public function getBy(string $fieldName, mixed $value): array | false
    {
        $query = "SELECT * FROM " . $this->table . " WHERE " . $fieldName . '= :value';
        $values = [
            ':value' => $value
        ];
        return $this->prepareSQL($query, $values, false);
    }

    /**
     * Exec the query
     *
     * @param string $query : Query to execute
     * @param array<mixed> $vars : vars for the query
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
     * @param array<mixed> $vars : vars for the query
     * @param boolean $single : return one result or not
     * @return mixed
     */
    protected function prepareSQL(string $query, array $vars, bool $single): mixed
    {
        try {
            $result = $this->db->preparedQuery($query, $this->className, $vars, $single);
            return $result;
        } catch (DbException $e) {
            $message = 'SQL : ' . $query . 'a poser problème';
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, $message);
            throw new Exception('Une erreur est survenue');
        }
    }
}
