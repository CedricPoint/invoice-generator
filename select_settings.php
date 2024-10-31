<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// Récupérer la liste des entreprises pour sélection
$entreprises = $pdo->query("SELECT * FROM Entreprises")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entreprise_id = $_POST['entreprise_id'];

    // Rediriger vers la page de paramètres de l'entreprise sélectionnée
    header("Location: settings.php?entreprise_id=" . $entreprise_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sélectionner une entreprise</title>
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <h2>Sélectionner une entreprise</h2>
    <form method="post" action="">
        <label for="entreprise_id">Entreprise :</label>
        <select id="entreprise_id" name="entreprise_id">
            <?php foreach ($entreprises as $entreprise): ?>
                <option value="<?= $entreprise['entreprise_id'] ?>"><?= htmlspecialchars($entreprise['nom_entreprise']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Gérer cette entreprise</button>
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
