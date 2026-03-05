<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier ma participation - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?action=list_challenges"><i class="bi bi-trophy-fill"></i> ChallengeHub</a>
            <a href="index.php?action=show_challenge&id=<?= $participation->getChallengeId() ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Annuler</a>
        </div>
    </nav>
    <div class="container" style="max-width:640px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="fw-bold mb-4"><i class="bi bi-pencil-fill text-primary"></i> Modifier ma participation</h2>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                <form action="index.php?action=edit_participation&id=<?= $participation->getId() ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="6" required><?= e($participation->getDescription() ?? '') ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Lien de l'image <span class="text-muted fw-normal">(optionnel)</span></label>
                        <input type="url" name="image" class="form-control" value="<?= e($participation->getImage() ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-check-lg"></i> Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
