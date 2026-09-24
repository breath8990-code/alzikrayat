<?php
class Photo
{
    private PDO $db;
    public function __construct() { $this->db = Database::connect(); }
    public function all(string $order='latest'): array { $sort=$order==='oldest'?'ASC':'DESC'; return $this->db->query("SELECT p.*, CONCAT(u.first_name,' ',u.last_name) author FROM photos p JOIN users u ON u.id=p.user_id ORDER BY p.date_time {$sort}")->fetchAll(); }
    public function find(int $id): ?array { $s=$this->db->prepare("SELECT p.*, CONCAT(u.first_name,' ',u.last_name) author FROM photos p JOIN users u ON u.id=p.user_id WHERE p.id=?"); $s->execute([$id]); return $s->fetch() ?: null; }
    public function create(array $data): int { $s=$this->db->prepare('INSERT INTO photos(user_id,file_name,title,description) VALUES(?,?,?,?)'); $s->execute([$data['user_id'],$data['file_name'],$data['title'],$data['description'] ?: null]); return (int)$this->db->lastInsertId(); }
    public function deleteOwned(int $id, int $userId): ?string { $s=$this->db->prepare('SELECT file_name FROM photos WHERE id=? AND user_id=?'); $s->execute([$id,$userId]); $photo=$s->fetch(); if (!$photo) return null; $d=$this->db->prepare('DELETE FROM photos WHERE id=? AND user_id=?'); $d->execute([$id,$userId]); return $photo['file_name']; }
    public function count(): int { return (int)$this->db->query('SELECT COUNT(*) FROM photos')->fetchColumn(); }
}
?>
