<?php


class Message {
 
    // database connection and table name
    private $conn;
    private $table_name = "message";
 
    // object properties
    public $id;
    public $from_user_id;
    public $to_user_id;
    public $message;
    public $created_at;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read users
    function read(){
    
        // select all query
        $query = "SELECT *  FROM " . $this->table_name;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create user
function create(){
 
    if($this->created_at == null) {
        $this->created_at = date('Y-m-d H:i:s');
    }

    // query to insert record
    $query = "INSERT INTO
               " . $this->table_name . " (from_user_id, to_user_id, message, created_at) VALUES (:from_user_id, :to_user_id, :message, :created_at)";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->from_user_id=htmlspecialchars(strip_tags($this->from_user_id));
    $this->to_user_id=htmlspecialchars(strip_tags($this->to_user_id));
    $this->message=htmlspecialchars(strip_tags($this->message));
    $this->created_at=htmlspecialchars(strip_tags($this->created_at));
 
    // bind values
    $stmt->bindParam(":from_user_id", $this->from_user_id);
    $stmt->bindParam(":to_user_id", $this->to_user_id);
    $stmt->bindParam(":message", $this->message);
    $stmt->bindParam(":created_at", $this->created_at);
    
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}

// delete the user
function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE ID = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->ID=htmlspecialchars(strip_tags($this->ID));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->ID);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}


// returns last message of each conversation
function get_all_chats() {
    $query = "SELECT  b.id, u.name  FROM user u 
                JOIN
                (SELECT DISTINCT 
                CASE WHEN to_user_id = :from_user_id THEN from_user_id ELSE to_user_id END as id
            FROM message 
            WHERE to_user_id = :from_user_id or from_user_id= :from_user_id) as b 
                ON u.id = b.id";
        // prepare query
    $stmt = $this->conn->prepare($query);

    //sanitize
    $this->from_user_id=htmlspecialchars(strip_tags($this->from_user_id));
    
    // bind values
    $stmt->bindParam(":from_user_id", $this->from_user_id);
    
    // execute query
    try
    {
        $stmt->execute();
    }
    catch(PDOException $e)
    {   
        $mshg = $e->getMessage();
        
    }
    return $stmt;

}
//returns last 50 messages of the conversation with from_user_id and to_user_id starting from created_at date
function get_messages() {

    if($this->created_at == null) {
        $this->created_at = date('Y-m-d H:i:s');
    }
    $query =" SELECT  * FROM " . $this->table_name . " WHERE 
        ((from_user_id = :from_user_id and to_user_id = :to_user_id ) OR (from_user_id = :to_user_id and to_user_id = :from_user_id)) 
        AND created_at <  :created_at  
        ORDER by created_at ASC  LIMIT 50";

        // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->from_user_id=htmlspecialchars(strip_tags($this->from_user_id));
    $this->to_user_id=htmlspecialchars(strip_tags($this->to_user_id));
    $this->created_at=htmlspecialchars(strip_tags($this->created_at));
 
    // bind values
    $stmt->bindParam(":from_user_id", $this->from_user_id);
    $stmt->bindParam(":to_user_id", $this->to_user_id);
    $stmt->bindParam(":created_at", $this->created_at);
 
    // execute query
   
    try
    {
        $stmt->execute();
    }
    catch(PDOException $e)
    {   
        $mshg = $e->getMessage();
        
    }
    return $stmt;
}

function readOne(){
 
    $this->ID=htmlspecialchars(strip_tags($this->ID));

    // query to read single record
    $query = "SELECT *  FROM " . $this->table_name . " WHERE ID = ".$this->ID;

    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    
    // execute query
    $stmt->execute();
 
    if($stmt->rowCount() < 1)
        return false;
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // set values to object properties
    $this->Name = $row['Name'];
    $this->Description = $row['Description'];
    return true;
}




}