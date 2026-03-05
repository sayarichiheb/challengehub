<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - ChallengeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-sm" style="width:100%;max-width:420px;">
        <div class="card-body p-5">
            <h2 class="text-center fw-bold mb-4 text-primary"><i class="bi bi-trophy-fill"></i> Créer un compte</h2>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>
            <form action="index.php?action=register" method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nom d'utilisateur</label>
                    <input type="text" name="name" class="form-control" required placeholder="Votre nom">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="votre@email.com">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimum 6 caractères">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">S'inscrire</button>
            </form>
            <p class="text-center mt-3 text-muted small">Déjà un compte ? <a href="index.php?action=login" class="text-primary fw-bold">Se connecter</a></p>
        </div>
    </div>
</body>
</html>
