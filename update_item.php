<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    if (isset($_POST['delete'])) {
        // Suppression de l'item
        $stmt = $pdo->prepare("DELETE FROM Items WHERE item_id = :id");
        $stmt->execute([':id' => $id]);
        echo "Item supprimé avec succès.";
        header("Location: dashboard.php"); // Redirection après suppression
        exit;
    } else {
        // Mise à jour de l'item
        $description = $_POST['description'];
        $prix_unitaire = $_POST['prix_unitaire'];

        $stmt = $pdo->prepare("UPDATE Items SET description = :description, prix_unitaire = :prix_unitaire WHERE item_id = :id");
        $stmt->execute([':description' => $description, ':prix_unitaire' => $prix_unitaire, ':id' => $id]);
        echo "Item mis à jour avec succès.";
    }
}

$id = $_GET['id'];
$item = $pdo->query("SELECT * FROM Items WHERE item_id = $id")->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Item</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que le CSS pointe vers le bon chemin -->
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <h2>Modifier l'item</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= $item['item_id'] ?>">
        
        <label>Description :</label>
        <input type="text" name="description" value="<?= htmlspecialchars($item['description']) ?>" required>
        <br>
        
        <label>Prix Unitaire :</label>
        <input type="number" name="prix_unitaire" step="0.01" value="<?= $item['prix_unitaire'] ?>" required>
        <br>
        
        <button type="submit">Mettre à jour</button>
        <button type="submit" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">Supprimer</button>
    </form>
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
