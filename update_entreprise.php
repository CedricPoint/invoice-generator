<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom_entreprise = $_POST['nom_entreprise'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $siret = $_POST['siret'] ?? null; // SIRET facultatif
    $tva = $_POST['tva'];
    $marge = $_POST['marge'];

    $stmt = $pdo->prepare("UPDATE Entreprises SET nom_entreprise = :nom, adresse = :adresse, telephone = :telephone, email = :email, siret = :siret, taux_tva = :tva, marge = :marge WHERE entreprise_id = :id");
    $stmt->execute([
        ':nom' => $nom_entreprise,
        ':adresse' => $adresse,
        ':telephone' => $telephone,
        ':email' => $email,
        ':siret' => $siret,
        ':tva' => $tva,
        ':marge' => $marge,
        ':id' => $id
    ]);
    echo "Entreprise mise à jour avec succès.";
}

$id = $_GET['id'];
$entreprise = $pdo->query("SELECT * FROM Entreprises WHERE entreprise_id = $id")->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Entreprise</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que le CSS pointe vers le bon chemin -->
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <h2>Modifier les informations de l'entreprise</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= $entreprise['entreprise_id'] ?>">
        
        <label>Nom de l'entreprise :</label>
        <input type="text" name="nom_entreprise" value="<?= htmlspecialchars($entreprise['nom_entreprise']) ?>" required>
        
        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($entreprise['adresse']) ?>" required>
        
        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?= $entreprise['telephone'] ?>" required>
        
        <label>Email :</label>
        <input type="email" name="email" value="<?= $entreprise['email'] ?>" required>
        
        <label>SIRET (facultatif) :</label>
        <input type="text" name="siret" value="<?= $entreprise['siret'] ?? '' ?>">

        <label>TVA (%) :</label>
        <input type="number" name="tva" step="0.01" min="0" value="<?= $entreprise['taux_tva'] ?? 0 ?>" required>
        
        <label>Marge (%) :</label>
        <input type="number" name="marge" step="0.01" min="0" value="<?= $entreprise['marge'] ?? 0 ?>" required>
        
        <button type="submit">Mettre à jour</button>
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
