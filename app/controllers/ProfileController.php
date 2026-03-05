<?php
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/Challenge.php");
require_once(__DIR__ . "/../models/Participation.php");
class ProfileController {
    private function checkLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }
    public function index() {
        $this->checkLogin();
        $user = new User($_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['user_id']);
        $message = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update'])) {
                if ($user->update($_POST)) {
                    $message = "Profil mis à jour ✅";
                    $_SESSION['user_name'] = $_POST['name'];
                    $_SESSION['user_email'] = $_POST['email'];
                } else {
                    $message = "Erreur lors de la mise à jour (vérifiez votre mot de passe) ❌";
                }
            } elseif (isset($_POST['delete'])) {
                $user->delete();
                exit();
            } elseif (isset($_POST['logout'])) {
                $user->logout();
                exit();
            }
        }
        $myChallenges = Challenge::getByUser($_SESSION['user_id']);
        $myParticipations = Participation::getByUser($_SESSION['user_id']);
        require_once(__DIR__ . "/../views/user/profile.php");
    }
}
?>