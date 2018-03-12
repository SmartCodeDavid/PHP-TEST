<?php
    Class DatabaseConnector {
        //Please change the servername, username, password to connecte your database. 
        private $servername = "localhost";
        private $username = "root";
        private $password = "root";         
        private $dbname = "php_test";       
        
        
        private $conn;
        
        public function getConn(){
            return $this.conn;
        }
        
        public function connectDatabase() {
            //Create connection
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            
            if ($this->conn->connect_error) {
                echo "ERROR! Please visit later..";
            } else {     // connect successfully
                return $this->conn;
            }           
        }
        
        public function closeDatabase() {
            mysqli_close($this->conn);
        }
    }
?>