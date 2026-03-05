<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .card-img-top { height: 180px; object-fit: cover; }
        .card-placeholder { height: 180px; background: linear-gradient(135deg, #6610f2, #0d6efd); display:flex; align-items:center; justify-content:center; font-size:3rem; }
        .card { transition: transform .2s, box-shadow .2s; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12)!important; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?action=list_challenges"><i class="bi bi-trophy-fill"></i> ChallengeHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link active" href="index.php?action=list_challenges"><i class="bi bi-grid"></i> Challenges</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=ranking"><i class="bi bi-bar-chart"></i> Classement</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=profile"><i class="bi bi-person-circle"></i> Mon Profil</a></li>
                        <li class="nav-item"><a class="btn btn-warning btn-sm fw-bold" href="index.php?action=create_challenge"><i class="bi bi-plus-lg"></i> Créer</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=logout"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="btn btn-light btn-sm fw-bold" href="index.php?action=login">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h1 class="fw-bold mb-4">Challenges Disponibles</h1>

        <form action="index.php" method="get" class="card shadow-sm p-3 mb-4">
            <input type="hidden" name="action" value="list_challenges">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <input type="text" name="q" class="form-control" placeholder="🔍 Rechercher par mot-clé..." value="<?= e($_GET['q'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <select name="cat" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <option value="Design" <?= ($_GET['cat'] ?? '') == 'Design' ? 'selected' : '' ?>>Design</option>
                        <option value="Développement" <?= ($_GET['cat'] ?? '') == 'Développement' ? 'selected' : '' ?>>Développement</option>
                        <option value="Photographie" <?= ($_GET['cat'] ?? '') == 'Photographie' ? 'selected' : '' ?>>Photographie</option>
                        <option value="Musique" <?= ($_GET['cat'] ?? '') == 'Musique' ? 'selected' : '' ?>>Musique</option>
                        <option value="Autre" <?= ($_GET['cat'] ?? '') == 'Autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </div>
        </form>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> Opération réussie !</div>
        <?php endif; ?>

        <?php if (empty($challenges)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:3rem;"></i>
                <p class="mt-2">Aucun challenge trouvé. Soyez le premier à en créer un !</p>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=create_challenge" class="btn btn-primary">+ Créer un challenge</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($challenges as $c): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <?php if ($c->getImage()): ?>
                                <img src="<?= e($c->getImage()) ?>" class="card-img-top" alt="<?= e($c->getTitle()) ?>">
                            <?php else: ?>
                                <div class="card-placeholder rounded-top">🎯</div>
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= e($c->getCategory()) ?></span>
                                <h5 class="card-title fw-bold"><?= e($c->getTitle()) ?></h5>
                                <p class="text-danger small"><i class="bi bi-clock"></i> Expire le: <?= e($c->getDeadline() ?? 'N/A') ?></p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="index.php?action=show_challenge&id=<?= $c->getId() ?>" class="btn btn-primary btn-sm w-100">Voir / Participer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
