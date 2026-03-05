<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once(__DIR__ . "/config/configuration.php");
require_once(__DIR__ . "/app/models/User.php");
$action = isset($_GET['action']) ? $_GET['action'] : 'list_challenges';
switch ($action) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user = new User($_POST['name'], $_POST['email'], $_POST['password']);
                if ($user->register()) {
                    header("Location: index.php?action=login&success=1");
                    exit();
                } else {
                    $error = "Erreur d'inscription. Veuillez réessayer.";
                    require_once(__DIR__ . "/app/views/auth/register.php");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once(__DIR__ . "/app/views/auth/register.php");
            }
        } else {
            require_once(__DIR__ . "/app/views/auth/register.php");
        }
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User("", "", "");
            if ($user->login($_POST['email'], $_POST['password'])) {
                header("Location: index.php?action=profile");
                exit();
            } else {
                $error = "Identifiants incorrects.";
                require_once(__DIR__ . "/app/views/auth/login.php");
            }
        } else {
            require_once(__DIR__ . "/app/views/auth/login.php");
        }
        break;
    case 'profile':
        require_once(__DIR__ . "/app/controllers/ProfileController.php");
        (new ProfileController())->index();
        break;
    case 'list_challenges':
        require_once(__DIR__ . "/app/controllers/ChallengeController.php");
        (new ChallengeController())->list();
        break;
    case 'create_challenge':
        require_once(__DIR__ . "/app/controllers/ChallengeController.php");
        (new ChallengeController())->create();
        break;
    case 'show_challenge':
        require_once(__DIR__ . "/app/controllers/ChallengeController.php");
        (new ChallengeController())->show($_GET['id'] ?? 0);
        break;
    case 'edit_challenge':
        require_once(__DIR__ . "/app/controllers/ChallengeController.php");
        (new ChallengeController())->edit($_GET['id'] ?? 0);
        break;
    case 'delete_challenge':
        require_once(__DIR__ . "/app/controllers/ChallengeController.php");
        (new ChallengeController())->delete($_GET['id'] ?? 0);
        break;
    case 'submit_participation':
        require_once(__DIR__ . "/app/controllers/ParticipationController.php");
        (new ParticipationController())->create($_GET['challenge_id'] ?? 0);
        break;
    case 'edit_participation':
        require_once(__DIR__ . "/app/controllers/ParticipationController.php");
        (new ParticipationController())->edit($_GET['id'] ?? 0);
        break;
    case 'delete_participation':
        require_once(__DIR__ . "/app/controllers/ParticipationController.php");
        (new ParticipationController())->delete($_GET['id'] ?? 0);
        break;
    case 'add_comment':
        require_once(__DIR__ . "/app/controllers/CommentController.php");
        (new CommentController())->create();
        break;
    case 'delete_comment':
        require_once(__DIR__ . "/app/controllers/CommentController.php");
        (new CommentController())->delete($_GET['id'] ?? 0);
        break;
    case 'vote_submission':
        require_once(__DIR__ . "/app/controllers/VoteController.php");
        (new VoteController())->vote();
        break;
    case 'ranking':
        require_once(__DIR__ . "/app/controllers/RankingController.php");
        (new RankingController())->index();
        break;
    case 'logout':
        $user = new User("", "", "");
        $user->logout();
        break;
    default:
        header("Location: index.php?action=list_challenges");
        break;
}