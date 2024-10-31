<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $prix_unitaire = $_POST['prix_unitaire'];
    $user_id = $_SESSION['user_id']; // Récupération de l'ID utilisateur

    // Insertion dans la base de données avec user_id
    $stmt = $pdo->prepare("INSERT INTO Items (description, prix_unitaire, user_id) VALUES (:description, :prix_unitaire, :user_id)");
    $stmt->execute([
        ':description' => $description,
        ':prix_unitaire' => $prix_unitaire,
        ':user_id' => $user_id
    ]);

    echo "Item ajouté avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un item</title>
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
        <h2>Ajouter un nouvel item</h2>

        <?php if (isset($message)) : ?>
            <div class="success-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" action="add_item.php">
            <div class="input-group">
                <i class="fas fa-pencil-alt"></i>
                <input type="text" name="description" placeholder="Description de l'item" required>
            </div>

            <div class="input-group">
                <i class="fas fa-euro-sign"></i>
                <input type="number" name="prix_unitaire" step="0.01" placeholder="Prix unitaire (€)" required>
            </div>

            <button type="submit" class="orange-button">Ajouter l'item</button>
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
