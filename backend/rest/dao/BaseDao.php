<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/Database.php");

class BaseDao {
   protected $table;
   protected $connection;

   public function __construct($table) {
       $this->table = $table;
       $this->connection = Database::connect();
   }

   public function getAll() {
       $stmt = $this->connection->prepare("SELECT * FROM " . $this->table);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getById($id) {
       $stmt = $this->connection->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
       $stmt->bindParam(':id', $id);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function insert($data) {
       $columns = implode(", ", array_keys($data));
       $placeholders = ":" . implode(", :", array_keys($data));
       $sql = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
       $stmt = $this->connection->prepare($sql);
       $stmt->execute($data);
       $id = $this->connection->lastInsertId();  // get newly inserted ID
       return $this->getById($id);               // fetch and return the inserted record
   }

   public function update($id, $data) {

       $fields = [];
       $params = [];
       foreach ($data as $key => $value) {
        // skip empty keys or reserved ones
        if ($key === 'id' || trim($key) === '') continue;

           $fields[] = "$key = :$key";
           $params[$key] = $value;
       }
    
       $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
       $params['id'] = $id; 

        // Debug check: log or print the query and params
     //error_log("SQL: $sql");
     //error_log(" PARAMS: " . print_r($params, true));

       $stmt = $this->connection->prepare($sql);
       return $stmt->execute($params);

       return true;
   }

   public function delete($id) {
       $stmt = $this->connection->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
       $stmt->bindParam(':id', $id);
       return $stmt->execute();
   }
}
?>