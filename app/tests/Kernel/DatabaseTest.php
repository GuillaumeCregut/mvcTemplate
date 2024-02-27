<?php

use Editiel98\Kernel\Database\Database;
use Editiel98\Kernel\Exception\DbException;
use PHPUnit\Framework\TestCase;

class TableTestFor
{
    private int $id;
    private string $name;
    private string $toto;
}

class BadTableTestFor 
{
    private int $id;
    private string $name;
}

class DataBaseTest extends TestCase
{
    private Database $database;

    public function setup(): void
    {
        $this->database=Database::getInstance();
    }
    
    public function testInstance(): void
    {
        $this->assertInstanceOf(\Editiel98\Kernel\Database\Database::class,$this->database);
    }

    public function testGetPDO(): void
    {
        $pdo=$this->database->getConnect();
        $this->assertInstanceOf(\PDO::class,$pdo);
    }

    public function testQueryBadTable(): void
    {
        $this->expectException(DbException::class);
        $this->database->query('SELECT * FROM matable');
    }

    public function testQueryOKTable(): void
    {
        $result=$this->database->query('SELECT * FROM table1');
        $this->assertIsArray($result);
    }

    public function testQueryWithBadClass(): void
    {
        $this->expectException(DbException::class);
        $result=$this->database->query('SELECT * FROM table1','BadTableTestFor');
       
    }

    public function testQueryWithClass(): void
    {
        $result=$this->database->query('SELECT * FROM table1','TableTestFor');
        $this->assertIsArray($result);
    }

    public function testExecWithBadValues(): void
    {
        $query="INSERT INTO table1 (name, toto) VALUES (:name,:toto)";
        $values=[];
        $this->expectException(DbException::class);
        $this->database->exec($query, $values);
    }

    public function testExecWithValues(): void
    {
        $query="INSERT INTO table1 (name, toto) VALUES (:name,:toto)";
        $values=[
            ':name'=>'Hello',
            ':toto'=>'World'
        ];
        $result=$this->database->exec($query, $values);
        $this->assertEquals(1,$result);
        $array=$this->database->query("SELECT * FROM table1 WHERE name='Hello'");
        $this->assertIsArray($array);
        $this->assertNotEmpty($array);
    }

    public function testQueryWithCreatedClass(): void
    {
        $result=$this->database->query('SELECT * FROM table1','TableTestFor');
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $class=$result[0];
        $this->assertInstanceOf(TableTestFor::class,$class);
    }

    public function testQueryWithBadClassCreated(): void
    {
        $this->expectException(DbException::class);
        $this->database->query('SELECT * FROM table1','BadTableTestFor');
    }
    
}