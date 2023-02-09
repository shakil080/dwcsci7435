<?php

class Database{
    private $connection = null;
    private $errorText = "";
    private $errorCode = 0;
    private $conn = "";
    private $user = "";
    private $pass = "";
    private $dbName = "";
    public static $_database = null;


    public function __construct($cn, $un, $pw, $db) {
        $this->conn = $cn;
        $this->user = $un;
        $this->pass = $pw;
        $this->dbName = $db;
    }

    public static function getDatabase($cn, $un, $pw, $db){

        if(!isset(static::$_database)){
            static::$_database = new Database($cn, $un, $pw, $db);
        }

        return static::$_database;
    }

    private function openConnection(){
        $this->connection = mysqli_connect($this->conn, $this->user, $this->pass);

        if(!$this->connection){
            $this->setError(mysqli_errno($this->connection), mysqli_error($this->connection));
        }
        else{

            mysqli_select_db($this->connection,$this->dbName);
        }
    }

    private function closeConnection(){
        mysqli_close($this->connection);
    }

    private function setError($errCode, $errText){
        $this->errorText = "" . $errText;
        $this->errorCode = $errCode;
        echo $errText;
    }

    public function getErrorCode(){
        return $this->errorCode;
    }

    public function getErrorText(){
        return $this->errorText;
    }

    private function resetError(){
        $this->errorText = "";
        $this->errorCode = 0;
    }

    public function GetQueryResult($query){

        //echo $query."<br />";
        // exit();
        $this->resetError();
        $this->openConnection();

        $result = null;
        $count = 0;

        if($this->errorCode == 0){
            $result = mysqli_query($this->connection, $query);
            if (!$result) {
                $this->setError(mysqli_errno($this->connection), mysqli_error($this->connection));
            }
            else{
                while ($row = mysqli_fetch_assoc($result)) {
					$count = $row['row'];
                }
            }

            $this->closeConnection();
        }

        return $count;
    }

    public function GetCollegeData(){

        $query = "SELECT * FROM college";

        //echo $query."<br />";
        // exit();
        $this->resetError();
        $this->openConnection();

        $result = null;
        $userid = 0;

        $data = null;

        if($this->errorCode == 0){
            $result = mysqli_query($this->connection, $query);

            if (!$result) {
                $this->setError(mysqli_errno($this->connection), mysqli_error($this->connection));
            }
            else{

                $count = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[$count] = array(
                        'collegeid' => $row['collegeid'],
                        'collegename' => $row['collegename']
                    );
                    
                    $count++;
                }
                mysqli_free_result($result);
                // while ($row = mysqli_fetch_assoc($result)) {
                //     $data['collegeid'] = $row['collegeid'];
                //     $data['collegename'] = $row['collegename'];
                // }
            }
            $this->closeConnection();
        }

        return $data;
    }
    
}
?>
