<?php
require_once(__DIR__ . "/../../config/configuration.php");
class Vote {
    private ?int $id = null;
    private int $submission_id;
    private int $user_id;
    private ?string $created_at = null;
    public function __construct(int $submission_id, int $user_id, ?int $id = null) {
        $this->id = $id;
        $this->submission_id = $submission_id;
        $this->user_id = $user_id;
    }
    public function getId(): ?int { return $this->id; }
    public function getSubmissionId(): int { return $this->submission_id; }
    public function getUserId(): int { return $this->user_id; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function create(): bool {
        $connexion = connect_bd();
        $sql = "INSERT INTO votes (submission_id, user_id) VALUES (:submission_id, :user_id)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':submission_id', $this->submission_id);
        $stmt->bindParam(':user_id', $this->user_id);
        try {
            if ($stmt->execute()) {
                $this->id = $connexion->lastInsertId();
                return true;
            }
        } catch (PDOException $e) {
            return false;
        }
        return false;
    }
    public function delete(): bool {
        $connexion = connect_bd();
        $sql = "DELETE FROM votes WHERE submission_id = :submission_id AND user_id = :user_id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':submission_id', $this->submission_id);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }
    public static function countBySubmission(int $submission_id): int {
        $connexion = connect_bd();
        $sql = "SELECT COUNT(*) FROM votes WHERE submission_id = :submission_id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':submission_id', $submission_id);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    public static function hasVoted(int $submission_id, int $user_id): bool {
        $connexion = connect_bd();
        $sql = "SELECT 1 FROM votes WHERE submission_id = :submission_id AND user_id = :user_id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':submission_id', $submission_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
}
?>