<?php
require_once(__DIR__ . "/../models/Challenge.php");
require_once(__DIR__ . "/../models/Participation.php");
class ChallengeController {
    private function checkLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }
    public function list() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $keyword = $_GET['q'] ?? '';
        $category = $_GET['cat'] ?? '';
        if (!empty($keyword) || !empty($category)) {
            $challenges = Challenge::search($keyword, $category);
        } else {
            $challenges = Challenge::getAll();
        }
        require_once(__DIR__ . "/../views/challenge/list.php");
    }
    public function create() {
        $this->checkLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $challenge = new Challenge(
                $_SESSION['user_id'],
                $_POST['title'],
                $_POST['description'],
                $_POST['category'],
                $_POST['deadline'],
                $_POST['image'] ?? null
            );
            if ($challenge->create()) {
                header("Location: index.php?action=list_challenges&success=created");
                exit();
            } else {
                $error = "Erreur lors de la création du challenge.";
                require_once(__DIR__ . "/../views/challenge/create.php");
            }
        } else {
            require_once(__DIR__ . "/../views/challenge/create.php");
        }
    }
    public function show($id) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $challenge = Challenge::getById($id);
        if (!$challenge) {
            header("Location: index.php?action=list_challenges");
            exit();
        }
        $participations = Participation::getByChallenge($id);
        require_once(__DIR__ . "/../views/challenge/show.php");
    }
    public function edit($id) {
        $this->checkLogin();
        $challenge = Challenge::getById($id);
        if (!$challenge || $challenge->getUserId() !== $_SESSION['user_id']) {
            header("Location: index.php?action=list_challenges");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'category' => $_POST['category'],
                'deadline' => $_POST['deadline'],
                'image' => $_POST['image'] ?? $challenge->getImage()
            ];
            if ($challenge->update($data)) {
                header("Location: index.php?action=show_challenge&id=$id&success=updated");
                exit();
            } else {
                $error = "Erreur lors de la mise à jour.";
                require_once(__DIR__ . "/../views/challenge/edit.php");
            }
        } else {
            require_once(__DIR__ . "/../views/challenge/edit.php");
        }
    }
    public function delete($id) {
        $this->checkLogin();
        $challenge = Challenge::getById($id);
        if ($challenge && $challenge->getUserId() === $_SESSION['user_id']) {
            $challenge->delete();
        }
        header("Location: index.php?action=list_challenges&success=deleted");
        exit();
    }
}