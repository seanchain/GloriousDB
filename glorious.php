<?php
/**
 * Created by PhpStorm.
 * User: seanchain
 * Date: 4/21/15
 * Time: 1:57 PM
 */

class GloriousDB{
    private $db;
    private $state;
    private $table;
    private $find_q = "";
    private $host = "127.0.0.1"; //Your host here
    private $username = "root"; //Your username here
    private $passwd = "********"; //Your password here
    public function __construct($database){
        $this->db = new mysqli($this->host, $this->username, $this->passwd, $database);
        if(mysqli_connect_errno())
        {
            $this->state = false;
        }
        $this->state = true;
    }

    public function state(){
        return $this->state;
    }

    public function destroy(){
        $this->db->close();
    }

    private function _execute($query){
        $res = $this->_query($query);
        $columns = $this->getColumnNames($this->table);
        $ret = array();
        for ($i = 0; $i < $res->num_rows; $i ++){
            $row = $res->fetch_assoc();
            $temp_ret = array();
            for($j = 0; $j < count($columns); $j ++) {
                $column = $columns[$j];
                $temp_ret[$column] = $row[$column];
            }
            array_push($ret, $temp_ret);
        }
        return $ret;
    }

    public function getColumnNames($table){
        $q = "show columns from ".$table;
        $res = $this->db->query($q);
        $arr = array();
        while($row = mysqli_fetch_array($res)){
            array_push($arr, $row['Field']);
        }
        return $arr;
    }

    private function _getKeyName($table)
    {
        $r = mysqli_fetch_assoc($this->_query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'"));
        return $r['Column_name'];
    }


    public function findOne($value)
    {
        $pri = $this->_getKeyName($this->table);
        $q = "select * from $this->table where $pri=$value;";
        $res = $this->_execute($q);
        return $res[0];
    }

    public function _wherehelper($ary, $opr="and", $op)
    {
        $keys = array_keys($ary);
        $values = array_values($ary);
        $sql_cond = "(";
        for($k = 0; $k < count($keys); $k ++)
        {
            $str = ""; //string for each condition
            $key = $keys[$k];
            $value = $values[$k];
            $first_letter = $value[0];
            if($first_letter == ">" || $first_letter == "<" || $first_letter ==
                "!")
            {
                $str .= "$key$value $opr ";
            }
            else{
                $str .= "$key='$value' $opr ";
            }
            $sql_cond .= $str;
        }
        if($opr == "or")
            $sql_cond = substr($sql_cond, 0, -4);
        else
            $sql_cond = substr($sql_cond, 0, -5);
        $sql_cond .= ")";
        $this->find_q .= " $op ".$sql_cond;
    }

    public function where($ary, $opr="and")
    {
        $this->_wherehelper($ary, $opr, "and");
        return $this->db;
    }

    public function orWhere($ary, $opr="and")
    {
        $this->_wherehelper($ary, $opr, "or");
        return $this->db;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function sql($query)
    {
        return $this->_execute($query);
    }

    public function find()
    {
        $length = count($this->find_q);
        $q = "select * from $this->table where ".substr($this->find_q, 4).";";
        $ret = $this->_execute($q);
        $this->find_q = "";
        return $ret;
    }

    private function _query($sql){
        return $this->db->query($sql);
    }
}

$db = new GloriousDB("userinfo");
$db->setTable("info");
$db->where([
    'email' => 'seanchain@outlook.com',
    'id' => 'csh'
], "or");

$db->orWhere(['NO' => '1']);

$res = $db->find();

print_r($res[0]);

print_r($db->sql("select * from info"));

$db->destroy();

?>
