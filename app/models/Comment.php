<?php
require_once(__DIR__ . "/../../config/configuration.php");
class Comment {
    private ?int $id = null;
    private int $submission_id;
    private int $user_id;
    private string $content;
    private ?string $created_at = null;
    public function __construct(int $submission_id, int $user_id, string $content, ?int $id = null) {
        $this->id = $id;
        $this->submission_id = $submission_id;
        $this->user_id = $user_id;
        $this->content = $content;
    }
    public function getId(): ?int { return $this->id; }
    public function getSubmissionId(): int { return $this->submission_id; }
    public function getUserId(): int { return $this->user_id; }
    public function getContent(): string { return $this->content; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function create(): bool {
        $connexion = connect_bd();
        $sql = "INSERT INTO comments (submission_id, user_id, content) VALUES (:submission_id, :user_id, :content)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':submission_id', $this->submission_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':content', $this->content);
        if ($stmt->execute()) {
            $this->id = $connexion->lastInsertId();
            return true;
        }
        return false;
    }
    public function delete(): bool {
        $connexion = connect_bd();
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    public static function getBySubmission(int $submission_id): array {
        $connexion = connect_bd();
        $sql = "SELECT * FROM comments WHERE submission_id = :submission_id ORDER BY created_at DESC";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':submission_id', $submission_id);
        $stmt->execute();
        $comments = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($data['submission_id'], $data['user_id'], $data['content'], $data['id']);
            $comment->created_at = $data['created_at'];
            $comments[] = $comment;
        }
        return $comments;
    }
    public static function getById(int $id): ?Comment {
        $connexion = connect_bd();
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($data['submission_id'], $data['user_id'], $data['content'], $data['id']);
            $comment->created_at = $data['created_at'];
            return $comment;
        }
        return null;
    }
}
?>