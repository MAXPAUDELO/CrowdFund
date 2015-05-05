<?php
//Database object to associate with the database
class database {

    private static $host = "crowdfund.c0xjytogn3na.us-east-1.rds.amazonaws.com";
    private static $user = "CrowdFundUser";
    private static $pass = "12345678";
    private static $dbname = "CrowdFund";
    private static $con;

    private static function connect(){
        if (self::$con){
            return self::$con;
        }
        self::$con = new mysqli(self::$host,self::$user,self::$pass,self::$dbname);

        if (!self::$con){
            // throw error here using your own error handler
            // or you can use die.
            // But no respectable application would use die to handle errors
        }

        return self::$con;
    }
    
    public static function query($sql){
        $con = self::connect();

        $result = $con->query($sql);

        if (!$result){
            // Then there is probably an error in the query
            // Add a nice error handler here
            echo "He's dead jim... he is dead\nIn this query:\"<i>$sql</i>\"\n$con->error";
            exit;
        }
        if (isset($result->field_count) && $result->field_count == 0){
            return false;
        }

        $group = array();
        while (($r = $result->fetch_object()) != false){
            $group[] = $r;
        }
        return $group;
    }
    
    public static function escape($query) {
        $con = self::connect();
        $query = $con -> real_escape_string($query);
        return $query;
    }
}
?>