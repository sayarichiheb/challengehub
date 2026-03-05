<?php
require_once(__DIR__ . "/../../config/configuration.php");
class Participation {
    private ?int $id = null;
    private int $challenge_id;
    private int $user_id;
    private ?string $description;
    private ?string $image;
    private ?string $created_at = null;
    public function __construct(int $challenge_id, int $user_id, ?string $description = null, ?string $image = null, ?int $id = null) {
        $this->id = $id;
        $this->challenge_id = $challenge_id;
        $this->user_id = $user_id;
        $this->description = $description;
        $this->image = $image;
    }
    public function getId(): ?int { return $this->id; }
    public function getChallengeId(): int { return $this->challenge_id; }
    public function getUserId(): int { return $this->user_id; }
    public function getDescription(): ?string { return $this->description; }
    public function getImage(): ?string { return $this->image; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function create(): bool {
        $connexion = connect_bd();
        $sql = "INSERT INTO submissions (challenge_id, user_id, description, image) 
                VALUES (:challenge_id, :user_id, :description, :image)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':challenge_id', $this->challenge_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        if ($stmt->execute()) {
            $this->id = $connexion->lastInsertId();
            return true;
        }
        return false;
    }
    public function update(array $data): bool {
        $connexion = connect_bd();
        $sql = "UPDATE submissions SET description = :description, image = :image 
                WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    public function delete(): bool {
        $connexion = connect_bd();
        $sql = "DELETE FROM submissions WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    public static function getById(int $id): ?Participation {
        $connexion = connect_bd();
        $sql = "SELECT * FROM submissions WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $participation = new Participation(
                $data['challenge_id'],
                $data['user_id'],
                $data['description'],
                $data['image'],
                $data['id']
            );
            $participation->created_at = $data['created_at'];
            return $participation;
        }
        return null;
    }
    public static function getByChallenge(int $challenge_id): array {
        $connexion = connect_bd();
        $sql = "SELECT * FROM submissions WHERE challenge_id = :challenge_id ORDER BY created_at DESC";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':challenge_id', $challenge_id);
        $stmt->execute();
        $participations = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participation = new Participation(
                $data['challenge_id'],
                $data['user_id'],
                $data['description'],
                $data['image'],
                $data['id']
            );
            $participation->created_at = $data['created_at'];
            $participations[] = $participation;
        }
        return $participations;
    }
    public static function getByUser(int $user_id): array {
        $connexion = connect_bd();
        $sql = "SELECT * FROM submissions WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $participations = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participation = new Participation(
                $data['challenge_id'],
                $data['user_id'],
                $data['description'],
                $data['image'],
                $data['id']
            );
            $participation->created_at = $data['created_at'];
            $participations[] = $participation;
        }
        return $participations;
    }
    public static function getRanking(int $limit = 10): array {
        $connexion = connect_bd();
        $sql = "SELECT s.*, COUNT(v.id) as vote_count 
                FROM submissions s 
                LEFT JOIN votes v ON s.id = v.submission_id 
                GROUP BY s.id 
                ORDER BY vote_count DESC 
                LIMIT :limit";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participation = new Participation(
                $data['challenge_id'],
                $data['user_id'],
                $data['description'],
                $data['image'],
                $data['id']
            );
            $participation->created_at = $data['created_at'];
            $results[] = [
                'participation' => $participation,
                'vote_count' => $data['vote_count']
            ];
        }
        return $results;
    }
}
?>