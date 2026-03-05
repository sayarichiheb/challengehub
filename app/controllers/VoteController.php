<?php
require_once(__DIR__ . "/../models/Vote.php");
class VoteController {
    private function checkLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }
    public function vote() {
        $this->checkLogin();
        $submission_id = $_GET['submission_id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        if ($submission_id > 0) {
            $vote = new Vote($submission_id, $user_id);
            if (Vote::hasVoted($submission_id, $user_id)) {
                $vote->delete();
            } else {
                $vote->create();
            }
        }
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "index.php"));
        exit();
    }
}