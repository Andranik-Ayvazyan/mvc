<?php
namespace core;
use \PDO;

class QueryBuilder
{

    private $con;
    private $select = '';
    private $optTable = '';
    private $join = '';
    private $where = '';
    private $params = [];
    private $orderBy = '';
    private $groupBy = '';
    private $offset = '';
    private $limit = '';
    private $insert = '';
    private $dataToInsert = '';
    private $update = '';
    private $delete = '';
    private static $instance;
    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const DB = 'mvctask';


    private function __construct()
    {
        try {

            $this->con = new \PDO('mysql:host='.self::HOST.';dbname='.self::DB,self::USER,self::PASSWORD);
            $this->con ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
        catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $this;
    }

    public function select($params)
    {
        $this->select = 'SELECT ';

        if (is_array($params)) {
            $this->select .= implode(',', $params);
        } else {
            $this->select .= $params;
        }

        return $this;
    }


    public function optTable($table)
    {
        if (!empty($this->select) || !empty($this->delete)) {

            $this->optTable = ' FROM ' . $table;

        } else {

            $this->optTable = $table;

        }

        return $this;
    }


    public function where($params = array())
    {
        $this->where = ' WHERE ';
        
           foreach ($params as $key => $item) {

            $this->where .= $item['column'] . ' ';
            $this->where .= $item['expression'];
            $this->where .= ':' . $item['column'] . ' ';
            $this->where .= isset($item['condition']) ? $item['condition'] . ' ' : ' ';
            $this->params[":" . $item['column']] = $item['value'];

             

        }
       

        return $this;
    }


    public function orderBy($orderByParams = array())
    {

        if (!$this->orderBy) {
            $this->orderBy = " ORDER BY ";
        }

        foreach ($orderByParams as $item) {

            if (!isset($item['type'])) {
                $item['type'] = 'ASC';
            }

            $this->orderBy .= $item['col'] . " " . $item['type'];

        }

            return $this;
    }


    public function groupBy($col)
    {
        if (!$this->groupBy) {
            $this->groupBy = ' GROUP BY ';
        }
        $this->groupBy .= $col;

        return $this;
    }


    public function offset($int)
    {
        $this->offset = ' OFFSET ' . $int;

        return $this;
    }


    public function limit($int)
    {

        $this->limit = ' LIMIT ' . $int;

        return $this;
    }

    public function join($joinType, $joinsTable, $tableCol, $expression, $joinsTableCol)
    {
        $this->join = ' ' . $joinType . ' JOIN ' . $joinsTable . " ON " . $tableCol . " " . $expression . " " . $joinsTableCol;

        return $this;
    }


    public function insert($table, $insertParams = array())
    {

        $columns = [];
        $values = [];

        foreach ($insertParams as $key => $value) {

            $columns[] = $key;
            $values[] = ":" . $key;
            $this->params[":" . $key] = $value;
        }

        $columns = implode(',', $columns);
        $values = implode(',', $values);
        $this->dataToInsert = "(" . $columns . ') VALUES (' . $values . ")";
        $this->insert = ' INSERT INTO ' . $table . $this->dataToInsert;


        return $this;
    }


    public function update($table, $params = array())
    {
        $data = [];

        foreach ($params as $key => $value) {

            $data[] = $key . " = :" . $key;
            $this->params[":" . $key] = $value;
        }

        $this->dataToInsert = implode(',', $data);
        $this->update = 'UPDATE ' . $table . ' SET ' . $this->dataToInsert;


        return $this;
    }


    public function delete($table)
    {
        $this->delete = 'DELETE FROM ' . $table;

        return $this;
    }


    private function query($type = 'one')
    {

        try {

            $query = sprintf('%s  %s %s %s %s %s %s %s', $this->select, $this->insert, $this->update, $this->delete, $this->optTable, $this->join, $this->where, $this->orderBy, $this->groupBy, $this->offset, $this->limit);
            $stmt = $this->con->prepare($query);
            $stmt->execute($this->params);
            
            if (!empty($this->select)) {

                $stmt = $type == 'all' ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_OBJ);
            }

            return $stmt;

        } catch (PDOException $e) {

            echo "ERROR: " . $e->getMessage();
        }

    }

    public function get()
    {

        $res = $this->query('all');

        return $res;
    }


    public function first()
    {
        $res = $this->query();

        return $res;
    }


    public function execute()
    {

        $this->query();

    }


    public static function getInstance()
    {
        if (!self::$instance) {

            self::$instance = new QueryBuilder();
        }

        return  self::$instance;
    }
}