<?php
include 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css"> <!-- Le CSS actuel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <div class="login-container">
        <h2>Connexion</h2>

        <?php if (!empty($error)) : ?>
            <div class="error-message show-error" id="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Entrer votre email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Entrer votre mot de passe" required>
            </div>
            <button type="submit">Se Connecter</button>
            <div class="switch-link">
                <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></p>
            </div>
        </form>
    </div>

    <footer class="dashboard-footer">
        <div class="footer-content">
            <!-- Section À propos -->
            <div class="footer-section about">
                <h3>À propos de Informaclique</h3>
                <p>Informaclique est votre partenaire pour la création de sites web, le support informatique, et la cybersécurité. Nous assurons des solutions personnalisées pour répondre à chaque besoin de nos clients avec sécurité et efficacité.</p>
            </div>

            <!-- Section de contact -->
            <div class="footer-section contact-info">
                <h3>Contact</h3>
                <p><strong>Adresse :</strong> 478 chemin de Fromentin, 69380 CHASSELAY, France</p>
                <p><strong>Téléphone :</strong> +33 7 82 91 93 59</p>
                <p><strong>Email :</strong> <a href="mailto:contact@informaclique.fr">contact@informaclique.fr</a></p>
            </div>
        </div>

        <!-- Section des droits et crédits -->
        <div class="footer-bottom">
            <p>&copy; 2024 Informaclique - Tous droits réservés.</p>
            <p>Développé par <a href="https://informaclique.fr" target="_blank" rel="noopener noreferrer">Informaclique</a> | Cédric FRANK</p>
        </div>
    </footer>
</body>
</html>
