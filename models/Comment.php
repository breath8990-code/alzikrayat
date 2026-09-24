<?php
class Comment
{
    private PDO $db;
    public function __construct() { $this->db = Database::connect(); }
    public function forPhoto(int $photoId): array { $s=$this->db->prepare("SELECT c.*, CONCAT(u.first_name,' ',u.last_name) author FROM comments c JOIN users u ON u.id=c.user_id WHERE c.photo_id=? ORDER BY c.date_time ASC"); $s->execute([$photoId]); return $s->fetchAll(); }
    public function create(int $photoId, int $userId, string $text): void { $s=$this->db->prepare('INSERT INTO comments(photo_id,user_id,comment) VALUES(?,?,?)'); $s->execute([$photoId,$userId,$text]); }
}
?>
