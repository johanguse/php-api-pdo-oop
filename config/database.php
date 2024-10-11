<?php

declare(strict_types=1);

class Database
{
  private string $host = 'localhost';
  private string $dbname = 'php_api_moveup';
  private string $username = 'root';
  private string $password = '';
  private ?PDO $conn = null;

  public function getConnection(): PDO
  {
    if ($this->conn === null) {
      try {
        $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
      }
    }
    return $this->conn;
  }
}
