<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $siret = $_POST['siret'] ?? null; // SIRET facultatif

    $stmt = $pdo->prepare("UPDATE Clients SET nom = :nom, adresse = :adresse, telephone = :telephone, email = :email, siret = :siret WHERE client_id = :id");
    $stmt->execute([
        ':nom' => $nom,
        ':adresse' => $adresse,
        ':telephone' => $telephone,
        ':email' => $email,
        ':siret' => $siret,
        ':id' => $id
    ]);
    echo "Client mis à jour avec succès.";
}

$id = $_GET['id'];
$client = $pdo->query("SELECT * FROM Clients WHERE client_id = $id")->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Client</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que le CSS pointe vers le bon chemin -->
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <h2>Modifier les informations du client</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= $client['client_id'] ?>">
        
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
        
        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($client['adresse']) ?>" required>
        
        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?= $client['telephone'] ?>" required>
        
        <label>Email :</label>
        <input type="email" name="email" value="<?= $client['email'] ?>" required>
        
        <label>SIRET (facultatif) :</label>
        <input type="text" name="siret" value="<?= $client['siret'] ?? '' ?>">
        
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
