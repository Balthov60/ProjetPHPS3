<?php

// TODO: Fill PHPDoc
class SQLServices
{
    private $db;

    function __construct($host, $dbname, $user, $password) {
        try {
            $this->db =  new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $user, $password);
        } catch (Exception $e) {
            die('Error : ' . $e->getMessage());
        }
    }

    /*********************/
    /* Utilities Methods */
    /*********************/

    /**
     * @param $array
     * @return mixed
     */
    function extractValueFromArray($array)
    {
        foreach ($array as $key => $value)
        {
            foreach ($value as $key_value => $value_value)
                return $value_value;
        }
    }

    /**
     * @param $array
     * @return string with ', ' separation
     */
    static private function getSQLStringFromArray($array) {
        if (!is_array($array))
            return $array . " ";

        $sqlString = "";
        foreach ($array as $value)
            $sqlString .= $value . ", ";

        return substr($sqlString, 0, -2) . " ";
    }

    /**
     * @param $array
     * @return string with formatted for SQL insert
     */
    static private function formatDataForValueInsertion($array) {
        $sqlString = "(";
        foreach ($array as $value) {
            if (is_string($value))
                $sqlString .= "'$value'";
            else
                $sqlString .= $value;
            $sqlString .= ", ";
        }

        return substr($sqlString, 0, -2) . ")";
    }

    /**
     * @param $array
     * @return string with formatted for SQL insert
     */
    static private function formatDataForKeyInsertion($array) {
        $sqlString = "";
        foreach ($array as $key => $value)
            $sqlString .= $key . ", ";

        return substr($sqlString, 0, -2) . ") ";
    }

    /*********************/
    /* Data Manipulation */
    /*********************/

    /**
     * @param $table
     * @param $select
     * @param null $options : where, group_by, limit, order_by
     *
     * @return array of data matching the request
     */
    function getData($table, $select, $options = null)
    {
        $query = "SELECT " . SQLServices::getSQLStringFromArray($select);
        $query .= "FROM $table ";

        if (isset($options)) {
            if (array_key_exists("where", $options)) {
                $query .= "WHERE " . $options["where"] . " ";
            }
            if (array_key_exists("group_by", $options)) {
                $query .= "GROUP BY " . SQLServices::getSQLStringFromArray($options["group_by"]);
            }
            if (array_key_exists("limit", $options)) {
                $query .= "LIMIT " . $options["limit"] . " ";
            }
            if (array_key_exists("order_by", $options)) {
                $query .= "ORDER BY " . SQLServices::getSQLStringFromArray($options["order_by"]);
            }
        }

        $cursor = $this->db->query($query);
        if ($cursor == false)
            return null;

        $result = $cursor->fetchAll(PDO::FETCH_BOTH);
        $cursor->closeCursor();
        return $result;
    }

    /**
     * @param $table
     * @param $values
     */
    function insertData($table, $values)
    {
        foreach($values as $value) {

            if (!is_array($value))
                continue;

            $query = "INSERT INTO $table(";

            $query .= self::formatDataForKeyInsertion($value);
            $query .= "VALUES";

            $query .= self::formatDataForValueInsertion($value);

            $this->db->exec($query) or die(print_r($this->db->errorInfo()));
        }
    }

    /**
     * @param $table
     * @param $optionWhere
     * @param $limit
     */
    function removeData($table, $optionWhere, $limit) {
        $query = "DELETE FROM $table ";

        if (isset($optionWhere)) {
            $query .= "WHERE $optionWhere";
        }
        if (isset($limit)) {
            $query .= "LIMIT $limit";
        }

        $this->db->exec($query);
    }

    /**
     * @param $table
     * @param $optionWhere
     * @param $value
     */
    function updateData($table, $optionWhere, $value) {
        $query = "UPDATE $table ";
        $query .= "SET $value ";

        if (isset($optionWhere)) {
            $query .= "WHERE $optionWhere";
        }

        $this->db->exec($query);
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    function isAdmin($username, $password)
    {
        $statement = "SELECT count(*) FROM user ";
        $statement .= "WHERE username = '$username' ";
        $statement .= "AND password = '" . md5($password) . "' ";
        $statement .= "AND admin = 1";

        $query = $this->db->query($statement);

        if ($query->fetchColumn() == 0)
            return false;

        return true;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    function isRegistered($username, $password)
    {
        $statement = "SELECT count(*) FROM user ";
        $statement .= "WHERE username = '$username' ";
        $statement .= "AND password = '".md5($password)."' ";
        $statement .= "AND admin = 0";

        $query = $this->db->query($statement);

        if ($query->fetchColumn() == 0)
            return false;

        return true;
    }

    /**
     * @param $username
     * @return string
     */
    /*function getUserId($username)
    {
        $statement = "SELECT username FROM user ";
        $statement .= "WHERE username = '$username' ";
        $result = $this->db->query($statement);

        return $result->fetchColumn();
    }*/

    /**
     *
     */
    function displayKeywordList()
    {
        $keyword_list = $this->getData('keyword', 'name_keyword');

        if (isset($keyword_list)) {
            foreach ($keyword_list as $value) {
                    echo "<li><a>" . htmlspecialchars($value[0]) . "</a></li>";
            }
        }
        else {
            echo "<li>No Keyword Found</li>";
        }
    }
}