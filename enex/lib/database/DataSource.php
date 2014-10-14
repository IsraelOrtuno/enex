<?php

class DataSource {

    /**
     * Database config
     *
     * @var array
     */
    protected $_dbConfig;

    /**
     * Link to the database
     *
     * @var PDO
     */
    protected $_pdo;

    /**
     * Datasource constructor
     */
    public function __construct() {
        $this->_dbConfig = Config::get('database');
        
        $dbName     = $this->_dbConfig['name'];
        $host       = $this->_dbConfig['host'];

        $pdoScript  = "mysql:host=$host;dbname=$dbName";

        try
        {
             @$this->_pdo = new PDO($pdoScript, $this->_dbConfig['user'], $this->_dbConfig['pass']);  // @ -> Do not show any error message, it'll be handled by the catch statement
        }
        catch (PDOException $e)
        {
            //echo "$e <br/>";
            echo "Database connection failed.";
            exit();
        }
        $this->_pdo->query("SET NAMES 'utf8'");
    }

    /**
     * Executes a query
     *
     * @param   SQL     $sql    SQL query
     *
     * @return  PDOStatement    Result of the query
     */
    public function query($sql)
    {
        return $this->_pdo->query($sql);
    }

    /**
     * Executes an action
     *
     * @param   SQL     $sql    SQL action
     *
     * @return  mixed           Action result
     */
    public function exec($sql)
    {
        return $this->_pdo->exec($sql);
    }

    /**
     * Returns the id of the last insertion
     * 
     * @return  mixed
     */
    public function getLastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }
}

?>