<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumettre une participation - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?action=list_challenges"><i class="bi bi-trophy-fill"></i> ChallengeHub</a>
            <a href="index.php?action=show_challenge&id=<?= $challenge_id ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Annuler</a>
        </div>
    </nav>
    <div class="container" style="max-width:640px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="fw-bold mb-1"><i class="bi bi-send-fill text-success"></i> Ma participation</h2>
                <p class="text-muted mb-4">Challenge : <strong><?= e($challenge->getTitle()) ?></strong></p>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                <form action="index.php?action=submit_participation&challenge_id=<?= $challenge_id ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Présentation</label>
                        <textarea name="description" class="form-control" rows="6" required placeholder="Expliquez votre travail, démarche créative..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Lien de l'image ou du projet <span class="text-muted fw-normal">(optionnel)</span></label>
                        <input type="url" name="image" class="form-control" placeholder="https://example.com/mon-travail.jpg">
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-check-lg"></i> Envoyer ma participation</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
