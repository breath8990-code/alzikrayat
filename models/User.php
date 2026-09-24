<?php
class User
{
    private PDO $db;
    public function __construct() { $this->db = Database::connect(); }
    public function findByEmail(string $email): ?array { $s=$this->db->prepare('SELECT * FROM users WHERE email = ?'); $s->execute([$email]); return $s->fetch() ?: null; }
    public function find(int $id): ?array { $s=$this->db->prepare('SELECT * FROM users WHERE id = ?'); $s->execute([$id]); return $s->fetch() ?: null; }
    public function create(array $data): int { $s=$this->db->prepare('INSERT INTO users (first_name,last_name,email,password,location,description,occupation) VALUES (?,?,?,?,?,?,?)'); $s->execute([$data['first_name'],$data['last_name'],$data['email'],$data['password'],$data['location'] ?: null,$data['description'] ?: null,$data['occupation'] ?: null]); return (int)$this->db->lastInsertId(); }
}
?>
