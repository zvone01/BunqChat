<?php

declare(strict_types=1);

class User
{

    private $conn;
 
    public $id;
    public $name;
    public $password;
 
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function read()
    {
        $query = "SELECT 
                    *  
                FROM 
                    user";

        $stmt = $this->conn->prepare($query);
  
        $stmt->execute();
    
        return $stmt;
    }

function create()
{

    $query = "INSERT INTO user 
    (name, password) 
    VALUES  (:n, :p);";

    $stmt = $this->conn->prepare($query);
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->password=htmlspecialchars(strip_tags($this->password));

    $stmt->bindParam(":n", $this->name);
    $stmt->bindParam(":p", $this->password);

    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}

function delete()
{
    $query = "DELETE FROM user WHERE id = ?";
 
    $stmt = $this->conn->prepare($query);
 
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    $stmt->bindParam(1, $this->id);

    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}



function get_user()
{
   // delete query
   $query = "SELECT * FROM user WHERE name = :name";
   // prepare query
   $stmt = $this->conn->prepare($query);
   // sanitize
   $this->name=htmlspecialchars(strip_tags($this->name));
   // bind id of record to delete
   $stmt->bindParam(":name", $this->name);
   
   try
   {
       $stmt->execute();
   }
   catch(PDOException $e)
   {   
       $mshg = $e->getMessage();
       //$response->getBody()->write("Statement failed:,$mshg " );
       
   }
   return $stmt;
}

function get_user_by_id()
{
   // delete query
   $query = "SELECT * FROM user WHERE id = :id";
   // prepare query
   $stmt = $this->conn->prepare($query);
   // sanitize
   $this->id=htmlspecialchars(strip_tags($this->id));
   // bind id of record to delete
   $stmt->bindParam(":id", $this->id);
   
   try
   {
       $stmt->execute();
   }
   catch(PDOException $e)
   {   
       $mshg = $e->getMessage();
       //$response->getBody()->write("Statement failed:,$mshg " );
       
   }
   return $stmt;
}

function updatePass(){
 
    // update query
    $query = "UPDATE
                user
            SET
                password = :password            
            WHERE
                id = :id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->id=htmlspecialchars(strip_tags($this->id));

 
    // bind new values
    $stmt->bindParam(':password', $this->password);
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }

}
}