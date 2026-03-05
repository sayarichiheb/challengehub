<?php
require_once(__DIR__ . "/../../config/configuration.php");
class User
{
    private string $name;
    private string $email;
    private string $password; 
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        // Only hash if it looks like a real password (string, not numeric ID)
        if (!empty($password) && !is_numeric($password)) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = '';
        }
    }
    public function register(): bool 
    {
        $connexion = connect_bd();
        $sql = "INSERT INTO users (name, email, password) 
                VALUES (:name, :email, :password)" ;
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new Exception("Cet email est déjà utilisé. Veuillez en choisir un autre.");
            }
            throw $e;
        }
    }
    public function login(string $email, string $password): bool
    {
        $connexion = connect_bd();
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) { session_start(); }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                return true;
            }
        }
        return false;
    }
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
    public function update(array $data): bool
    {
        $connexion = connect_bd();
        $sql = "SELECT password FROM users WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($data['current_password'], $user['password'])) {
            return false;
        }
        $sql = "UPDATE users SET name = :name, email = :email";
        if(!empty($data['password'])) {
            $sql .= ", password = :password";
        }
        $sql .= " WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        if(!empty($data['password'])){
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashed);
        }
        return $stmt->execute();
    }
    public function delete(): bool
    {
        $connexion = connect_bd();
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        if($stmt->execute()){
            $this->logout();
            return true;
        }
        return false;
    }
}
?>