<?php

class dbclass
{
    var $Query;
    var $Connection;
    var $debug = true;
    
    public function getConnection() {
        return $this->Connection;
    }

    function mysql($server, $username, $password, $database)
    {
        $this->Connection = new mysqli($server, $username, $password, $database);

        if ($this->Connection->connect_error) {
            die('Error connecting: ' . $this->Connection->connect_error);
        }
        
        // اعمال تنظیمات کاراکترست بلافاصله بعد از اتصال
        $this->init_charset();
    }

    function init_charset() 
    {
        $this->Query("SET NAMES utf8mb4");
        $this->Query("SET CHARACTER SET utf8mb4");
        $this->Query("SET collation_connection = utf8mb4_unicode_ci");
    }

    function close()
    {
        if ($this->Connection) {
            $this->Connection->close();
        }
    }

    function Query($sql)
    {
        $Q = $this->Connection->query($sql);
        if (!$Q) {
            return $this->error($sql);
        }
        return $Q;
    }

    function error($sql)
    {
        if ($this->debug) {
            die('Error: ' . $this->Connection->error . '<br /> ' . $sql);
        } else {
            return $this->Connection->error;
        }
    }

    function iquery($table, $data)
    {
        $field = '';
        $value = '';
        foreach ($data as $filds => $val) {
            $field .= "`$filds`, ";
            $value .= "'" . $this->Connection->real_escape_string($val) . "', ";
        }
        $insert = $this->Query("INSERT INTO `$table` (" . rtrim($field, ', ') . ") VALUES (" . rtrim($value, ', ') . ");");
        return $insert;
    }

    function uquery($table, $data, $id = '')
    {
        $foreach = '';
        foreach ($data as $filds => $value) {
            $foreach .= "`$filds`='" . $this->Connection->real_escape_string($value) . "', ";
        }
        $id = ($id == '') ? '' : ' WHERE ' . $id;
        $update = $this->Query("UPDATE `$table` SET " . rtrim($foreach, ', ') . $id . ' ;');
        return $update;
    }

    function fetch($query)
    {
        $re = $query->fetch_assoc();
        return $re;
    }

    function GetRowValue($field, $Q, $raw = false)
    {
        $Q = ($raw) ? $this->Query($Q) : $Q;
        $row = $Q->fetch_assoc();
        $row = $row[$field];
        return $row;
    }

    function getrows($query, $raw = false)
    {
        $query = ($raw) ? $this->Query($query) : $query;
        $rows = $query->num_rows;
        $rows = empty($rows) ? 0 : $rows;
        return $rows;
    }

    function getmax($field, $table)
    {
        $id = $this->Query("SELECT MAX(`$field`) as id FROM `$table`");
        $id = $this->fetch($id);
        $id = $id['id'];
        return $id;
    }

    function affected()
    {
        return $this->Connection->affected_rows;
    }

    function last()
    {
        return $this->Connection->insert_id;
    }
}