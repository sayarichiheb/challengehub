<?php
require_once(__DIR__ . "/../models/Participation.php");
require_once(__DIR__ . "/../models/Challenge.php");
class ParticipationController {
    private function checkLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }
    public function create($challenge_id) {
        $this->checkLogin();
        $challenge = Challenge::getById($challenge_id);
        if (!$challenge) {
            header("Location: index.php?action=list_challenges");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $participation = new Participation(
                $challenge_id,
                $_SESSION['user_id'],
                $_POST['description'],
                $_POST['image'] ?? null
            );
            if ($participation->create()) {
                header("Location: index.php?action=show_challenge&id=$challenge_id&success=submitted");
                exit();
            } else {
                $error = "Erreur lors de la soumission.";
                require_once(__DIR__ . "/../views/participation/create.php");
            }
        } else {
            require_once(__DIR__ . "/../views/participation/create.php");
        }
    }
    public function edit($id) {
        $this->checkLogin();
        $participation = Participation::getById($id);
        if (!$participation || $participation->getUserId() !== $_SESSION['user_id']) {
            header("Location: index.php?action=list_challenges");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'description' => $_POST['description'],
                'image' => $_POST['image'] ?? $participation->getImage()
            ];
            if ($participation->update($data)) {
                header("Location: index.php?action=show_challenge&id=" . $participation->getChallengeId() . "&success=updated");
                exit();
            } else {
                $error = "Erreur lors de la modification.";
                require_once(__DIR__ . "/../views/participation/edit.php");
            }
        } else {
            require_once(__DIR__ . "/../views/participation/edit.php");
        }
    }
    public function delete($id) {
        $this->checkLogin();
        $participation = Participation::getById($id);
        if ($participation && $participation->getUserId() === $_SESSION['user_id']) {
            $challenge_id = $participation->getChallengeId();
            $participation->delete();
            header("Location: index.php?action=show_challenge&id=$challenge_id&success=deleted");
            exit();
        }
        header("Location: index.php?action=list_challenges");
        exit();
    }
}