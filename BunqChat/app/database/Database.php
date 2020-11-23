<?php


class Database {
 
    // specify your own database credentials
    /*private $host = "localhost";
    private $db_name = "maintenance";
    private $username = "root";
    private $password = "";*/
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            //make db connection
            $this->conn = new PDO('sqlite:app/database/db/database.sqlite3');
            // Set errormode to exceptions
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, 
                                    PDO::ERRMODE_EXCEPTION);
            //initailize db if not exists;
            $this->initalizeDatabase();
            return $this->conn;
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
    }

    private function initalizeDatabase() {

        $query = "CREATE TABLE IF NOT EXISTS user ( 'id' INTEGER PRIMARY KEY, 'name' VARCHAR(20), 'password' VARCHAR(20) )";
        $this->conn->exec($query);

        $query = "CREATE TABLE IF NOT EXISTS 'message' ( id INTEGER PRIMARY KEY, 'message' TEXT, 
        from_user_id INTEGER, to_user_id INTEGER, 
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(from_user_id) REFERENCES users(id),
        FOREIGN KEY(to_user_id) REFERENCES users(id))";
        $this->conn->exec($query);
    }

}



