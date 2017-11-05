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
     *
     */
    function displayKeywordList()
    {
        $keyword_list = $this->getData('keyword', 'name_keyword');

        if (isset($keyword_list)) {
            foreach ($keyword_list as $value) {
                echo "<li><input class='tags' type='checkbox' name='".htmlspecialchars($value[0])."_tag'><p id='tag-text'>".htmlspecialchars($value[0])."</p></li>";
            }
        }
        else {
            echo "<li>No Keyword Found</li>";
        }
    }

    /*******************/
    /* Account Methods */
    /*******************/

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

    /*********************/
    /* Utilities Methods */
    /*********************/

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