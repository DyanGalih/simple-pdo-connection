<?php
/**
 * Simple class for database connection using PDO
 * Author : @DyanGalih
 * Date : 2018-05-02
 */

namespace WebAppId\SimplePDO;

use PDO;

class Database
{
    private $conn;
    
    public function __construct($configuration)
    {
        $this->conn = new PDO('mysql:host=' . $configuration['database']['host'] . ';dbname=' . $configuration['database']['name'], $configuration['database']['user'], $configuration['database']['password']);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function open($sql, $params = null)
    {
        $stmt = $this->conn->prepare($sql);
        $arr_row = array();
        if ($params != null) {
            $paramBind = array();
            $params = get_object_vars($params);
            foreach ($params as $key => $value) {
                $stmt->bindParam(":" . $key, $paramBind[$key]);
                $paramBind[$key] = $params[$key];
            }
            $arr_row = array();
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {
                    $arr_row[] = $row;
                }
            }
        }
        return $arr_row;
    }
    
    private function __execute($sql, $params)
    {
        $stmt = $this->conn->prepare($sql);
        if ($params != null) {
            $paramBind = array();
            $params = get_object_vars($params);
            foreach ($params as $key => $value) {
                $stmt->bindParam(":" . $key, $paramBind[$key]);
                $paramBind[$key] = $value;
            }
        }
        
        return $stmt->execute();
    }
    
    public function execute($sql, $params = null)
    {
        if ($params != null && count($params) !== count($params, COUNT_RECURSIVE)) {
            $result = true;
            $this->conn->beginTransaction();
            
            for ($i = 0; $i < count($params); $i++) {
                if (!$this->__execute($sql, $params[$i])) {
                    $result = false;
                    break;
                }
            }
            
            if ($result) {
                $this->conn->commit();
            } else {
                $this->conn->rollBack();
            }
        } else {
            $result = $this->__execute($sql, $params);
        }
        
        return $result;
    }
    
    public function getLastId()
    {
        return $this->conn->lastInsertId();
    }
    
    public function __destruct()
    {
        $this->conn = null;
    }
}