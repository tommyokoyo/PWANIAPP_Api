<?php
    
    include_once 'config.php';
    
    class DbConnect{
        
        private $connect_Db1;
        private $connect_Db2;
        
        public function __construct(){
            
            $this->connect_Db1 = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $this->connect_Db2 = mysqli_connect(DB_HOST, DB_USER_2, DB_PASSWORD_2, DB_NAME_2);
            
            if (mysqli_connect_error($this->connect_Db1)){
                echo "Unable to connect to MySQL Database: " . mysqli_connect_error();
            }

            if (mysqli_connect_error($this->connect_Db2)){
                echo "Unable to connect to MySQL Database: " . mysqli_connect_error();
            }

        }
        
        public function getDb(){

            return $this->connect_Db1;

        }

        public function getDb2(){

            return $this->connect_DB2;

        }

    }
    ?>