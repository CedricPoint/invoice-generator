<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_entreprise = $_POST['nom_entreprise'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $logo_path = null;
    $user_id = $_SESSION['user_id']; // Récupération de l'ID utilisateur

    // Gestion de l'upload du logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/logos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $logo_name = uniqid() . '_' . basename($_FILES['logo']['name']);
        $logo_path = $upload_dir . $logo_name;
        move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path);
    }

    // Insertion dans la base de données avec user_id
    $stmt = $pdo->prepare("INSERT INTO Entreprises (nom_entreprise, adresse, telephone, email, logo, user_id) VALUES (:nom, :adresse, :tel, :email, :logo, :user_id)");
    $stmt->execute([
        ':nom' => $nom_entreprise,
        ':adresse' => $adresse,
        ':tel' => $telephone,
        ':email' => $email,
        ':logo' => $logo_path,
        ':user_id' => $user_id
    ]);

    echo "Entreprise ajoutée avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une entreprise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <div class="signup-container">
        <h2>Ajouter une nouvelle entreprise</h2>

        <?php if (isset($message)) : ?>
            <div class="success-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" action="add_entreprise.php" enctype="multipart/form-data">
            <div class="input-group">
                <i class="fas fa-building"></i>
                <input type="text" name="nom_entreprise" placeholder="Nom de l'entreprise" required>
            </div>

            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="adresse" placeholder="Adresse" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telephone" placeholder="Téléphone" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <i class="fas fa-image"></i>
                <input type="file" name="logo" accept="image/*">
            </div>

            <button type="submit" class="orange-button">Ajouter l'entreprise</button>
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
