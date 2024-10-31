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
    $tva_incluse = isset($_POST['tva_incluse']) ? 1 : 0;
    $taux_tva = $_POST['taux_tva'];
    $marge = $_POST['marge'];

    // Mettre à jour les paramètres
    $sql = "UPDATE Settings SET nom_entreprise = :nom_entreprise, adresse = :adresse, telephone = :telephone, email = :email, tva_incluse = :tva_incluse, taux_tva = :taux_tva, marge = :marge WHERE id = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom_entreprise' => $nom_entreprise,
        ':adresse' => $adresse,
        ':telephone' => $telephone,
        ':email' => $email,
        ':tva_incluse' => $tva_incluse,
        ':taux_tva' => $taux_tva,
        ':marge' => $marge
    ]);

    echo "Paramètres mis à jour avec succès !";
}

// Récupérer les paramètres actuels
$settings = $pdo->query("SELECT * FROM Settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres de l'entreprise</title>
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <h2>Paramètres de l'entreprise et options de devis</h2>
    <form method="post" action="">
        <label for="nom_entreprise">Nom de l'entreprise :</label>
        <input type="text" id="nom_entreprise" name="nom_entreprise" value="<?= htmlspecialchars($settings['nom_entreprise']) ?>" required><br>

        <label for="adresse">Adresse :</label>
        <textarea id="adresse" name="adresse" required><?= htmlspecialchars($settings['adresse']) ?></textarea><br>

        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($settings['telephone']) ?>" required><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($settings['email']) ?>" required><br>

        <label for="tva_incluse">Inclure la TVA :</label>
        <input type="checkbox" id="tva_incluse" name="tva_incluse" <?= $settings['tva_incluse'] ? 'checked' : '' ?>><br>

        <label for="taux_tva">Taux de TVA (%) :</label>
        <input type="number" step="0.01" id="taux_tva" name="taux_tva" value="<?= htmlspecialchars($settings['taux_tva']) ?>"><br>

        <label for="marge">Marge (%) :</label>
        <input type="number" step="0.01" id="marge" name="marge" value="<?= htmlspecialchars($settings['marge']) ?>"><br>

        <button type="submit">Mettre à jour les paramètres</button>
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
