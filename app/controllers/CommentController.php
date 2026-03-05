<?php
require_once(__DIR__ . "/../models/Comment.php");
class CommentController {
    private function checkLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }
    public function create() {
        $this->checkLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submission_id = $_POST['submission_id'] ?? 0;
            $challenge_id = $_POST['challenge_id'] ?? 0;
            $content = $_POST['content'] ?? '';
            if ($submission_id > 0 && !empty($content)) {
                $comment = new Comment($submission_id, $_SESSION['user_id'], $content);
                $comment->create();
            }
            header("Location: index.php?action=show_challenge&id=$challenge_id");
            exit();
        }
    }
    public function delete($id) {
        $this->checkLogin();
        $comment = Comment::getById($id);
        if ($comment && $comment->getUserId() === $_SESSION['user_id']) {
            $comment->delete();
        }
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "index.php"));
        exit();
    }
}