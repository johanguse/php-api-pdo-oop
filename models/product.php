<?php

declare(strict_types=1);

class Product
{
  private PDO $conn;
  private string $table = 'products';

  public function __construct(PDO $db)
  {
    $this->conn = $db;
  }

  public function getAll(): array
  {
    $stmt = $this->conn->query("SELECT * FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getById(int $id): ?array
  {
    $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function create(array $data): bool
  {
    $stmt = $this->conn->prepare("INSERT INTO products (name, description, price, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    return $stmt->execute([$data['name'], $data['description'], $data['price']]);
  }

  public function update(int $id, array $data): bool
  {
    $stmt = $this->conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, updated_at = NOW() WHERE id = ?");
    return $stmt->execute([$data['name'], $data['description'], $data['price'], $id]);
  }

  public function delete(int $id): bool
  {
    $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
  }

  public function readPaginated($page = 1, $perPage = 10)
  {
    $offset = ($page - 1) * $perPage;

    $query = "SELECT id, name, description, price 
              FROM {$this->table}
              LIMIT :offset, :perPage";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTotalCount()
  {
    $query = "SELECT COUNT(*) as total FROM {$this->table}";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$row['total'];
  }
}
