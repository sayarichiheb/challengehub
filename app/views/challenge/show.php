<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($challenge->getTitle()) ?> - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .challenge-img { width:100%; max-height:360px; object-fit:cover; border-radius:.5rem; }
        .participation-card { border-left: 4px solid #0d6efd; }
        .comment-item { border-left: 3px solid #6c757d; background:#f8f9fa; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?action=list_challenges"><i class="bi bi-trophy-fill"></i> ChallengeHub</a>
            <div class="d-flex gap-2 align-items-center">
                <a href="index.php?action=list_challenges" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Retour</a>
                <a href="index.php?action=ranking" class="btn btn-outline-light btn-sm"><i class="bi bi-bar-chart"></i> Classement</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=profile" class="btn btn-outline-light btn-sm"><i class="bi bi-person"></i> Profil</a>
                    <a href="index.php?action=logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-warning btn-sm fw-bold">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width:920px;">
        <!-- Challenge Details -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <span class="badge bg-primary mb-2 fs-6"><?= e($challenge->getCategory()) ?></span>
                <h1 class="fw-bold"><?= e($challenge->getTitle()) ?></h1>
                <p class="text-muted small">Par l'utilisateur #<?= $challenge->getUserId() ?> &nbsp;|&nbsp; <span class="text-danger"><i class="bi bi-clock"></i> Expire le: <?= e($challenge->getDeadline() ?? 'N/A') ?></span></p>
                <?php if ($challenge->getImage()): ?>
                    <img src="<?= e($challenge->getImage()) ?>" class="challenge-img mb-3" alt="<?= e($challenge->getTitle()) ?>">
                <?php endif; ?>
                <p class="text-secondary lh-lg"><?= e($challenge->getDescription()) ?></p>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=submit_participation&challenge_id=<?= $challenge->getId() ?>" class="btn btn-success fw-bold mt-2">
                        <i class="bi bi-plus-circle"></i> Soumettre ma participation
                    </a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-outline-primary mt-2">Connectez-vous pour participer</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Creator Options -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $challenge->getUserId()): ?>
            <div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-gear-fill fs-5"></i>
                <span><strong>Options créateur :</strong></span>
                <a href="index.php?action=edit_challenge&id=<?= $challenge->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Modifier</a>
                <a href="index.php?action=delete_challenge&id=<?= $challenge->getId() ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce challenge ?')"><i class="bi bi-trash"></i> Supprimer</a>
            </div>
        <?php endif; ?>

        <!-- Contributions -->
        <h3 class="fw-bold mb-3"><i class="bi bi-people-fill text-primary"></i> Contributions (<?= count($participations) ?>)</h3>

        <?php if (empty($participations)): ?>
            <div class="text-center text-muted py-4 bg-white rounded shadow-sm">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">Aucune participation encore. Soyez le premier !</p>
            </div>
        <?php else: ?>
            <?php
                require_once(__DIR__ . "/../../models/Vote.php");
                require_once(__DIR__ . "/../../models/Comment.php");
            ?>
            <?php foreach ($participations as $p): ?>
                <?php
                    $voteCount = Vote::countBySubmission($p->getId());
                    $hasVoted  = isset($_SESSION['user_id']) ? Vote::hasVoted($p->getId(), $_SESSION['user_id']) : false;
                    $comments  = Comment::getBySubmission($p->getId());
                ?>
                <div class="card shadow-sm border-0 participation-card mb-4">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="fw-bold"><i class="bi bi-person-circle"></i> Utilisateur #<?= $p->getUserId() ?></span>
                                <div class="text-muted small"><?= e($p->getCreatedAt() ?? '') ?></div>
                            </div>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $p->getUserId()): ?>
                                <div class="d-flex gap-2">
                                    <a href="index.php?action=edit_participation&id=<?= $p->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="index.php?action=delete_participation&id=<?= $p->getId() ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')"><i class="bi bi-trash"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($p->getImage()): ?>
                            <img src="<?= e($p->getImage()) ?>" class="img-fluid rounded mb-3" style="max-height:300px;object-fit:contain;background:#f8f9fa;width:100%;" alt="Participation">
                        <?php endif; ?>
                        <p class="text-secondary" style="white-space:pre-wrap;"><?= e($p->getDescription() ?? '') ?></p>

                        <!-- Vote -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fw-bold text-warning fs-5"><i class="bi bi-star-fill"></i> <?= $voteCount ?></span>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="index.php?action=vote_submission&submission_id=<?= $p->getId() ?>"
                                   class="btn btn-sm <?= $hasVoted ? 'btn-warning' : 'btn-outline-warning' ?>">
                                    <?= $hasVoted ? '<i class="bi bi-star-fill"></i> Retirer mon vote' : '<i class="bi bi-star"></i> Voter' ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Comments -->
                        <div class="border-top pt-3">
                            <h6 class="fw-bold mb-3"><i class="bi bi-chat-dots"></i> Commentaires (<?= count($comments) ?>)</h6>
                            <?php foreach ($comments as $com): ?>
                                <div class="comment-item rounded p-2 mb-2 ps-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="fw-semibold small">Utilisateur #<?= $com->getUserId() ?></span>
                                            <p class="mb-0 small text-secondary"><?= e($com->getContent()) ?></p>
                                            <span class="text-muted" style="font-size:.75em;"><?= e($com->getCreatedAt() ?? '') ?></span>
                                        </div>
                                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $com->getUserId()): ?>
                                            <a href="index.php?action=delete_comment&id=<?= $com->getId() ?>" class="btn btn-sm btn-outline-danger py-0 px-1" style="font-size:.75em;"><i class="bi bi-trash"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <form action="index.php?action=add_comment" method="post" class="d-flex gap-2 mt-2">
                                    <input type="hidden" name="submission_id" value="<?= $p->getId() ?>">
                                    <input type="hidden" name="challenge_id" value="<?= $challenge->getId() ?>">
                                    <textarea name="content" class="form-control form-control-sm" rows="1" required placeholder="Ajouter un commentaire..."></textarea>
                                    <button type="submit" class="btn btn-sm btn-primary">Publier</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
