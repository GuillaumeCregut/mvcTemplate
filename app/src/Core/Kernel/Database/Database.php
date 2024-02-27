<?php

namespace Editiel98\Kernel\Database;

use Editiel98\Kernel\Emitter;
use Editiel98\Kernel\Exception\DbException;
use Editiel98\Kernel\GetEnv;
use Editiel98\Interfaces\DatabaseInterface;
use Error;
use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;

/**
 * Database
 * Manage connection with database
 */

class Database implements DatabaseInterface
{
    private string $user;
    private string $host;
    private string $name;
    private string $pass;
    private string $port;
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var Database
     */
    private static $instance;

    public function __construct()
    {
        $this->loadCredentials();
    }

    /**
     * getInstance
     * create singleton for database connection
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * getConnect
     * create pdo Connection
     *
     * @return pdo
     */
    public function getConnect()
    {
        if (is_null($this->pdo)) {
            try {
                $options = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "set lc_time_names = 'fr_FR'"
                );
                $this->pdo = new PDO(
                    'mysql:dbname=' . $this->name . ';host=' . $this->host . '; port=' . $this->port . 'charset=UTF8',
                    $this->user,
                    $this->pass,
                    $options
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $errCode = $e->getCode();
                $errMessage = $e->getMessage();
                $emitter = Emitter::getInstance();
                $emitter->emit(Emitter::DATABASE_ERROR, 'database : ' . $errMessage);
                throw new DbException('Erreur de connexion à la base', $errCode, $errMessage);
            }
        }
        return $this->pdo;
    }

    /**
     * loadCredentials
     * Load credentials from config file
     *
     * @return void
     */
    private function loadCredentials()
    {
        try {
            $this->user = GetEnv::getEnvValue('dblogin');
            $this->pass = GetEnv::getEnvValue('dbpass');
            $this->name = GetEnv::getEnvValue('dbname');
            $this->host = GetEnv::getEnvValue('dbhost');
            $this->port = GetEnv::getEnvValue('dbport');
        } catch (Exception $e) {
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, 'database : Impossible de lire les credentials');
            throw new Exception('Impossible de lire les credentials');
        }
    }

    private function handleDeprecated(int $errno, string $errstr, string $errfile, string $errline): void
    {
        throw new InvalidArgumentException('Class should not be created.');
    }

    /**
     * query
     * Make a PDO query
     *
     * @param string $statement : SQL query
     * @param string|null $className : class type of result if exists
     * @return array<mixed>
     */
    public function query(string $statement, ?string $className = null): array
    {
        $old_error_handler = set_error_handler([$this, 'handleDeprecated']);
        try {
            $req = $this->getConnect()->query($statement);
            if (!$req) {
                throw new Exception('Database connection error');
            }
            if (is_null($className)) {
                $datas = $req->fetchAll(PDO::FETCH_OBJ);
            } else {
                $datas = $req->fetchAll(PDO::FETCH_CLASS, $className);
            }
            return $datas;
        } catch (PDOException $e) {
            $errCode = intVal($e->getCode());
            $errMessage = $e->getMessage();
            throw new DbException('Erreur Query', $errCode, $errMessage);
        } catch (Exception $e) {
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, 'database : ' . $e->getMessage());
            throw new DbException('Erreur DB:  ' . $e->getMessage(), 0, 'Erreur DB:  Inconnue');
        } catch (Error $e) {
            throw new DbException('Deprecated', $e->getCode(), $e->getMessage());
        } finally {
            set_error_handler($old_error_handler);
        }
    }

    /**
     * prepare
     *
     * prepare a PDO request and execute it
     *
     * @param string $statement : SQL query
     * @param string|null $className : class of result if exist
     * @param array<mixed>|null $values : array of bind values
     * @param boolean|null $single : return unique data or set of datas
     * @return mixed : array or object
     */
    public function preparedQuery(
        string $statement,
        ?string $className,
        ?array $values = [],
        ?bool $single = false
    ): mixed {
        $old_error_handler = set_error_handler([$this, 'handleDeprecated']);
        try {
            $req = $this->getConnect()->prepare($statement);
            $req->execute($values);
            if (is_null($className)) {
                $req->setFetchMode(PDO::FETCH_OBJ);
            } else {
                $req->setFetchMode(PDO::FETCH_CLASS, $className);
            }
            if ($single) {
                $data = $req->fetch();
            } else {
                $data = $req->fetchAll();
            }
            return $data;
        } catch (PDOException $e) {
            $errCode = intVal($e->getCode());
            $errMessage = $e->getMessage();
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, 'database : ' . $e->getMessage());
            throw new DbException('Erreur Prepare', $errCode, $errMessage);
        } finally {
            set_error_handler($old_error_handler);
        }
    }

    /**
     * execStraight
     * Execute query directly without binding or result needed
     *
     * @param string $query : Query to execute
     * @return boolean : result of query
     */
    public function execStraight(string $query): bool
    {
        try {
            $result = $this->getConnect()->exec($query);
            if ($result === false) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            $errCode = intVal($e->getCode());
            $errMessage = $e->getMessage();
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, 'database : ' . $e->getMessage());
            throw new DbException('Erreur Exec', $errCode, $errMessage);
        }
    }

    /**
     * exec
     * execute a prepared PDO request
     *
     * @param string $statement : SQL query
     * @param array<mixed> $values : binding values
     * @return bool|int : bool or int
     */
    public function exec(string $statement, array $values): bool | int
    {
        try {
            $req = $this->getConnect()->prepare($statement);
            $result = $req->execute($values);
            if ($result) {
                $count = $req->rowCount();
                return $count;
            } else {
                return $result;
            }
        } catch (Exception $e) {
            $errCode = intVal($e->getCode());
            $errMessage = $e->getMessage();
            $emitter = Emitter::getInstance();
            $emitter->emit(Emitter::DATABASE_ERROR, 'database : ' . $e->getMessage());
            throw new DbException('Erreur Exec', $errCode, $errMessage);
        }
    }

    /**
     * startTransction
     * init a SQL transaction
     *
     * @return void
     */
    public function startTransac(): void
    {
        $this->getConnect()->beginTransaction();
    }

    /**
     * commitTransac
     * commit the current transaction
     *
     * @return void
     */
    public function commitTransc(): void
    {
        $this->getConnect()->commit();
    }

    /**
     * rollBack
     * rollback the current transaction
     *
     * @return void
     */
    public function rollBack(): void
    {
        $this->getConnect()->rollBack();
    }
}
