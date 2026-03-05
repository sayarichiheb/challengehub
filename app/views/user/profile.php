<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?action=list_challenges"><i class="bi bi-trophy-fill"></i> ChallengeHub</a>
            <div class="d-flex gap-2">
                <a href="index.php?action=list_challenges" class="btn btn-outline-light btn-sm">Challenges</a>
                <a href="index.php?action=ranking" class="btn btn-outline-light btn-sm">Classement</a>
                <a href="index.php?action=logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4"><i class="bi bi-person-circle text-primary"></i> Mon Compte</h4>

                        <?php if(!empty($message)): ?>
                            <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger' ?> py-2">
                                <?= e($message) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nom complet</label>
                                <input type="text" name="name" class="form-control" value="<?= e($_SESSION['user_name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= e($_SESSION['user_email']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mot de passe actuel <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required placeholder="Requis pour confirmer">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Nouveau mot de passe <span class="text-muted fw-normal">(optionnel)</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Laissez vide pour conserver">
                            </div>
                            <button type="submit" name="update" class="btn btn-primary w-100 fw-bold"><i class="bi bi-check-lg"></i> Mettre à jour</button>
                        </form>

                        <hr class="my-4">

                        <form method="post" onsubmit="return confirm('Attention: action irréversible. Supprimer votre compte ?');">
                            <button type="submit" name="delete" class="btn btn-danger w-100"><i class="bi bi-trash"></i> Supprimer le compte</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- My Challenges -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0"><i class="bi bi-rocket-takeoff-fill text-primary"></i> Mes Challenges Créés
                                <span class="badge bg-primary ms-1"><?= count($myChallenges) ?></span>
                            </h5>
                            <a href="index.php?action=create_challenge" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i> Créer</a>
                        </div>
                        <?php if(empty($myChallenges)): ?>
                            <p class="text-muted text-center py-3">Vous n'avez pas encore créé de challenge.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach($myChallenges as $c): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-semibold"><?= e($c->getTitle()) ?></div>
                                            <small class="text-muted"><span class="badge bg-secondary me-1"><?= e($c->getCategory()) ?></span> Expire le: <?= e($c->getDeadline() ?? 'N/A') ?></small>
                                        </div>
                                        <a href="index.php?action=show_challenge&id=<?= $c->getId() ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- My Participations -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-send-fill text-success"></i> Mes Participations
                            <span class="badge bg-success ms-1"><?= count($myParticipations) ?></span>
                        </h5>
                        <?php if(empty($myParticipations)): ?>
                            <p class="text-muted text-center py-3">Vous n'avez pas encore participé à un challenge.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach($myParticipations as $p): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-semibold">Participation au challenge #<?= $p->getChallengeId() ?></div>
                                            <small class="text-muted"><?= e(mb_strimwidth($p->getDescription() ?? '', 0, 80, "...")) ?></small>
                                        </div>
                                        <a href="index.php?action=show_challenge&id=<?= $p->getChallengeId() ?>" class="btn btn-sm btn-outline-success">Voir</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
