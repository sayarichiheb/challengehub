<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Challenge - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?action=list_challenges"><i class="bi bi-trophy-fill"></i> ChallengeHub</a>
            <a href="index.php?action=show_challenge&id=<?= $challenge->getId() ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Annuler</a>
        </div>
    </nav>
    <div class="container" style="max-width:640px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="fw-bold mb-4"><i class="bi bi-pencil-fill text-primary"></i> Modifier le Challenge</h2>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                <form action="index.php?action=edit_challenge&id=<?= $challenge->getId() ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre</label>
                        <input type="text" name="title" class="form-control" value="<?= e($challenge->getTitle()) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catégorie</label>
                        <select name="category" class="form-select" required>
                            <option value="Design" <?= $challenge->getCategory() == 'Design' ? 'selected' : '' ?>>Design</option>
                            <option value="Développement" <?= $challenge->getCategory() == 'Développement' ? 'selected' : '' ?>>Développement</option>
                            <option value="Photographie" <?= $challenge->getCategory() == 'Photographie' ? 'selected' : '' ?>>Photographie</option>
                            <option value="Musique" <?= $challenge->getCategory() == 'Musique' ? 'selected' : '' ?>>Musique</option>
                            <option value="Autre" <?= $challenge->getCategory() == 'Autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="5" required><?= e($challenge->getDescription()) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL de l'image <span class="text-muted fw-normal">(optionnel)</span></label>
                        <input type="url" name="image" class="form-control" value="<?= e($challenge->getImage() ?? '') ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Date limite</label>
                        <input type="date" name="deadline" class="form-control" value="<?= e($challenge->getDeadline() ?? '') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-check-lg"></i> Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
