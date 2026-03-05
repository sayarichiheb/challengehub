<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .rank-gold   { color: #f59e0b; font-size:1.5rem; }
        .rank-silver { color: #9ca3af; font-size:1.5rem; }
        .rank-bronze { color: #b45309; font-size:1.5rem; }
        .rank-num    { color: #6c757d; font-weight:bold; font-size:1.1rem; }
        .thumb { width:65px; height:65px; object-fit:cover; border-radius:.5rem; }
        .thumb-placeholder { width:65px; height:65px; background:linear-gradient(135deg,#6610f2,#0d6efd); border-radius:.5rem; display:flex; align-items:center; justify-content:center; font-size:1.8rem; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?action=list_challenges"><i class="bi bi-trophy-fill"></i> ChallengeHub</a>
            <div class="d-flex gap-2">
                <a href="index.php?action=list_challenges" class="btn btn-outline-light btn-sm">Challenges</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=profile" class="btn btn-outline-light btn-sm">Mon Profil</a>
                    <a href="index.php?action=logout" class="btn btn-outline-light btn-sm">Déconnexion</a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-warning btn-sm fw-bold">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width:900px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="fw-bold mb-1"><i class="bi bi-trophy-fill text-warning"></i> Top 10 Contributions</h1>
                <p class="text-muted mb-4">Les participations les mieux notées par la communauté</p>

                <?php if (empty($rankings)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size:3rem;"></i>
                        <p class="mt-2">Aucune participation notée pour l'instant.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Aperçu</th>
                                    <th>Détails</th>
                                    <th>Votes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankings as $index => $r): ?>
                                    <tr>
                                        <td>
                                            <?php if ($index === 0): ?>
                                                <span class="rank-gold"><i class="bi bi-trophy-fill"></i></span>
                                            <?php elseif ($index === 1): ?>
                                                <span class="rank-silver"><i class="bi bi-trophy-fill"></i></span>
                                            <?php elseif ($index === 2): ?>
                                                <span class="rank-bronze"><i class="bi bi-trophy-fill"></i></span>
                                            <?php else: ?>
                                                <span class="rank-num"><?= $index + 1 ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($r['participation']->getImage()): ?>
                                                <img src="<?= e($r['participation']->getImage()) ?>" class="thumb" alt="Participation">
                                            <?php else: ?>
                                                <div class="thumb-placeholder">🎯</div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary mb-1"><?= e($r['challenge_title']) ?></span>
                                            <div class="small text-muted">Utilisateur #<?= $r['participation']->getUserId() ?></div>
                                            <div class="small text-secondary"><?= e(mb_strimwidth($r['participation']->getDescription() ?? '', 0, 100, "...")) ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                                <i class="bi bi-star-fill"></i> <?= $r['vote_count'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="index.php?action=list_challenges" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Retour aux challenges</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
