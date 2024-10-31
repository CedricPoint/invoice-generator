<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $entreprise = $_POST['entreprise'];

    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Vérification de l'existence de l'email ou du nom d'utilisateur
        $check = $pdo->prepare("SELECT * FROM Users WHERE email = :email OR username = :username");
        $check->execute([':email' => $email, ':username' => $username]);

        if ($check->rowCount() > 0) {
            $error = "L'email ou le nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Insertion avec l’email, l'entreprise et la date de création
            $stmt = $pdo->prepare("INSERT INTO Users (username, email, password, entreprise, created_at) VALUES (:username, :email, :password, :entreprise, NOW())");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $password_hashed,
                ':entreprise' => $entreprise
            ]);
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="signup-container">
        <h2>Inscription</h2>

        <?php if (!empty($error)) : ?>
            <div class="error-message show-error" id="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Entrer votre email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-building"></i>
                <input type="text" name="entreprise" placeholder="Nom de l'entreprise" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Entrer votre mot de passe" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            </div>
            <button type="submit">S'inscrire</button>
            <div class="switch-link">
                <p>Déjà un compte ? <a href="login.php">Connectez-vous</a></p>
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
