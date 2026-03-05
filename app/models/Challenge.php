<?php
require_once(__DIR__ . "/../../config/configuration.php");
class Challenge {
    private ?int $id = null;
    private int $user_id;
    private string $title;
    private string $description;
    private string $category;
    private ?string $image;
    private ?string $deadline;
    private ?string $created_at = null;
    public function __construct(int $user_id, string $title, string $description, string $category, ?string $deadline, ?string $image = null, ?int $id = null) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->deadline = $deadline;
        $this->image = $image;
    }
    public function getId(): ?int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getCategory(): string { return $this->category; }
    public function getImage(): ?string { return $this->image; }
    public function getDeadline(): ?string { return $this->deadline; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function create(): bool {
        $connexion = connect_bd();
        $sql = "INSERT INTO challenges (user_id, title, description, category, image, deadline) 
                VALUES (:user_id, :title, :description, :category, :image, :deadline)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':deadline', $this->deadline);
        if ($stmt->execute()) {
            $this->id = $connexion->lastInsertId();
            return true;
        }
        return false;
    }
    public function update(array $data): bool {
        $connexion = connect_bd();
        $sql = "UPDATE challenges SET title = :title, description = :description, 
                category = :category, image = :image, deadline = :deadline 
                WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':deadline', $data['deadline']);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    public function delete(): bool {
        $connexion = connect_bd();
        $sql = "DELETE FROM challenges WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    public static function getById(int $id): ?Challenge {
        $connexion = connect_bd();
        $sql = "SELECT * FROM challenges WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $challenge = new Challenge(
                $data['user_id'],
                $data['title'],
                $data['description'],
                $data['category'],
                $data['deadline'],
                $data['image'],
                $data['id']
            );
            $challenge->created_at = $data['created_at'];
            return $challenge;
        }
        return null;
    }
    public static function getAll(): array {
        $connexion = connect_bd();
        $sql = "SELECT * FROM challenges ORDER BY created_at DESC";
        $stmt = $connexion->query($sql);
        $challenges = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $challenge = new Challenge(
                $data['user_id'],
                $data['title'],
                $data['description'],
                $data['category'],
                $data['deadline'],
                $data['image'],
                $data['id']
            );
            $challenge->created_at = $data['created_at'];
            $challenges[] = $challenge;
        }
        return $challenges;
    }
    public static function getByUser(int $user_id): array {
        $connexion = connect_bd();
        $sql = "SELECT * FROM challenges WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $challenges = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $challenge = new Challenge(
                $data['user_id'],
                $data['title'],
                $data['description'],
                $data['category'],
                $data['deadline'],
                $data['image'],
                $data['id']
            );
            $challenge->created_at = $data['created_at'];
            $challenges[] = $challenge;
        }
        return $challenges;
    }
    public static function search(string $keyword = '', string $category = ''): array {
        $connexion = connect_bd();
        $sql = "SELECT * FROM challenges WHERE 1=1";
        $params = [];
        if (!empty($keyword)) {
            $sql .= " AND (title LIKE :keyword OR description LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        if (!empty($category)) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $connexion->prepare($sql);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        $challenges = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $challenge = new Challenge(
                $data['user_id'],
                $data['title'],
                $data['description'],
                $data['category'],
                $data['deadline'],
                $data['image'],
                $data['id']
            );
            $challenge->created_at = $data['created_at'];
            $challenges[] = $challenge;
        }
        return $challenges;
    }
}
?>