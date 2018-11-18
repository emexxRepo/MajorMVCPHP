<?php

class DB
{
    private static $_instance = null;
    private $_pdo, $_query, $_error = false, $_result, $_count = 0, $_lastInsertID = null;


    public function __construct()
    {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
            ];

            $this->_pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, $options);

        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    //SINGLETON

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }

    public function query(string $sql, $params = null)
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {

            $counter = 1;
            if (is_array($params)) {
                if (count($params) > 0) {

                    foreach ($params as $param) {

                        $this->_query->bindValue($counter, $param);
                        $counter++;
                    }
                }
            }


            if ($this->_query->execute()) {
                $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
                $this->_lastInsertID = $this->_pdo->lastInsertId();
            } else {
                $this->_error = true;
            }
        }

        return $this;
    }

    public function insert($table, array $fields): bool
    {
        $fieldString = '';
        $valueString = '';
        $values = [];

        // ` karakteri için altgr + virgül

        foreach ($fields as $field => $value) {

            $fieldString .= '`' . $field . '`,';
            $valueString .= '?,';
            $values[] = $value;

        }

        $fieldString = rtrim($fieldString, ',');
        $valueString = rtrim($valueString, ',');
        // exampleInsertsql = "INSERT INTO contacts(`fname`,`lname`,`email`) VALUES('john','doe','johndoe@example.com')";
        $sql = "INSERT INTO {$table} ({$fieldString}) VALUES({$valueString})";
        if (!$this->query($sql, $values)->error()) {
            return true;
        } else {
            return false;
        }
    }

    public function update(string $table, array $fields, int $id): bool
    {
        $fieldString = '';
        $values = [];

        foreach ($fields as $field => $value) {

            $fieldString .= ' ' . $field . '=' . '?,';
            $values[] = $value;
        }

        $fieldString = trim($fieldString);
        $fieldString = rtrim($fieldString, ',');
        $sql = "UPDATE {$table} SET {$fieldString} WHERE id = {$id}";

        if (!$this->query($sql, $values)->error()) {
            return true;
        }

        return false;

    }

    public function delete(string $table, int $id): bool
    {
        $sql = "DELETE FROM {$table} WHERE id = {$id}";

        if (!$this->query($sql)->error()) {
            return true;
        }

        return false;
    }

    public function find(string $table, array $params)
    {
        if ($this->read($table, $params)) {
            return $this->results();
        }

        return false;
    }

    public function findFirst(string $table, array $params)
    {
        if ($this->read($table, $params)) {
            return $this->first();
        }
        return false;
    }

    protected function read(string $table, array $params): bool
    {
        $conditionString = '';
        $bind = array();
        $order = '';
        $limit = '';

        //conditions
        if (isset($params['conditions'])) {
            if (is_array($params['conditions'])) {
                foreach ($params['conditions'] as $condition) {
                    $conditionString .= ' ' . $condition . ' AND';
                }

                $conditionString = trim($conditionString);
                $conditionString = rtrim($conditionString, ' AND');

            } else {
                $conditionString = $params['conditions'];
            }

            if (!empty($conditionString)) {
                $conditionString = ' WHERE ' . $conditionString;
            }
        }

        //bind

        if (array_key_exists('bind', $params)) {
            $bind = $params['bind'];
        }
        //order
        if (array_key_exists('order', $params)) {
            $order = ' ORDER BY ' . $params['order'];
        }

        //limit

        if (array_key_exists('limit', $params)) {
            $limit = ' LIMIT ' . $params['limit'];
        }

        $sql = "SELECT * FROM {$table}{$conditionString}{$order}{$limit}";

        if ($this->query($sql, $bind)) {
            if (count($this->_result)) {

                return true;
            }
            return false;
        }

        return false;

    }

    public function results(): array
    {
        return $this->_result;
    }

    public function first(): object
    {
        return (!empty($this)) ? $this->_result[0] : (object)[];
    }

    public function count(): int
    {
        return $this->_count;
    }

    public function lastID()
    {
        return $this->_lastInsertID;
    }

    public function getColumns(string $table)
    {
        return $this->query("SHOW COLUMNS FROM {$table}")->results();
    }

    public function error()
    {
        return $this->_error;
    }
}