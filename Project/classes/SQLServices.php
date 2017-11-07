<?php

class SQLServices
{
    private $db;

    /**
     * SQLServices constructor.
     * 
     * @param $host
     * @param $dbname
     * @param $user
     * @param $password
     */
    function __construct($host, $dbname, $user, $password) {
        try {
            $this->db =  new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $user, $password);
        } catch (Exception $e) {
            die('Error : ' . $e->getMessage());
        }
    }

    /*********************/
    /* Data Manipulation */
    /*********************/

    /**
     * SQL SELECT on data.
     *
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
     * SQL INSERT on data.
     *
     * @param $table
     * @param $values array of array (one array is one row insertion)
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
     * SQL REMOVE on data.
     *
     * @param $table
     * @param $optionWhere : WHERE and LIMIT implemented.
     * @param $limit
     */
    function removeData($table, $optionWhere, $limit = null) {
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
     * SQL UPDATE on data.
     *
     * @param $table
     * @param $optionWhere : ONLY WHERE implement yet.
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

    /*************************/
    /* Specified SQL Methods */
    /*************************/

    /**
     * Check If User exist, if his password is Good and if he is not an admin.
     *
     * @param $username
     * @param $password
     * @return bool
     */
    public function isUser($username, $password)
    {
        $query  = "SELECT count(*) FROM user ";
        $query .= "WHERE username = '$username' ";
        $query .= "AND password = '".md5($password)."' ";
        $query .= "AND admin = 0";

        return $this->queryReturnData($query);
    }

    /**
     * Check if User exist, if his password is Good and if he is an admin.
     *
     * @param $username
     * @param $password
     * @return bool
     */
    public function isAdmin($username, $password)
    {
        $query  = "SELECT count(*) FROM user ";
        $query .= "WHERE username = '$username' ";
        $query .= "AND password = '" . md5($password) . "' ";
        $query .= "AND admin = 1";

        return $this->queryReturnData($query);
    }

    /**
     * Check if username is already used.
     *
     * @param $username
     * @return bool
     */
    public function usernameExist($username) {
        $query  = "SELECT count(*) FROM user ";
        $query .= "WHERE username = '$username' ";

        return $this->queryReturnData($query);
    }

    /**
     * Check if mail is already used.
     *
     * @param $mail
     * @return bool
     */
    public function mailExist($mail)
    {
        $query = "SELECT count(*) FROM user ";
        $query .= "WHERE mail = '$mail' ";

        return $this->queryReturnData($query);
    }

    /**
     *  Remove .jpg/.jpeg/.png from db image name.
     *
     * @param $imageName
     * @return bool|string
     */
    public function removeExtensionFromImageName($imageName) {
        $idPos = strpos($imageName,'.jpg');
        if ($idPos == false) {
            $idPos = strpos($imageName,'.jpeg');

            if ($idPos == false) {
                $idPos = strPos($imageName, '.png');
            }
        }

        return substr($imageName, 0, $idPos);
    }

    /**
     * Check if keyword exist.
     *
     * @param $keyword
     * @return bool
     */
    public function keywordExist($keyword) {
        $query = "SELECT count(*) FROM keyword ";
        $query .= "WHERE name_keyword = '$keyword' ";

        return $this->queryReturnData($query);
    }

    /**
     * check if the couple image username exist in cart db.
     *
     * @param $image
     * @param $username
     * @return bool
     */
    public function cartEntryExist($image, $username) {
        $query = "SELECT count(*) FROM cart ";
        $query .= "WHERE username = '$username' AND image_name = '$image' ";

        return $this->queryReturnData($query);
    }

    /*********************/
    /* Utilities Methods */
    /*********************/

    /**
     * extract single value from array.
     *
     * @param $array
     * @return mixed
     */
    function extractValueFromArray($array)
    {
        return $array[0][0];
    }

    /**
     * Get SQL formatted strings with ', ' separation from an array of string.
     *
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
     *  Get SQL formatted strings with ', ' separation from an array of string
     *  With security to prevent SQL Injection.
     *
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
     *  Get SQL formatted strings with ', ' separation from an array of string
     *  For Key insertion.
     *
     * @param $array
     * @return string with formatted for SQL insert
     */
    static private function formatDataForKeyInsertion($array) {
        $sqlString = "";
        foreach ($array as $key => $value)
            $sqlString .= $key . ", ";

        return substr($sqlString, 0, -2) . ") ";
    }

    /**
     * Check If Query Return Anything.
     *
     * @param $query
     * @return bool
     */
    public function queryReturnData($query) {
        $result = $this->db->query($query);

        if ($result->fetchColumn() == 0)
            return false;

        return true;
    }
}