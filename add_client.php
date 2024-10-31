<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $siret = !empty($_POST['siret']) ? $_POST['siret'] : null; // SIRET optionnel
    $user_id = $_SESSION['user_id']; // Récupération de l'user_id de la session

    $sql = "INSERT INTO Clients (nom, adresse, telephone, email, siret, user_id) VALUES (:nom, :adresse, :telephone, :email, :siret, :user_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':adresse' => $adresse,
        ':telephone' => $telephone,
        ':email' => $email,
        ':siret' => $siret,
        ':user_id' => $user_id
    ]);

    echo "<div class='success-message'>Client ajouté avec succès !</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un client</title>
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que le fichier CSS pointe vers le bon chemin -->
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <div class="form-container">
        <h2>Ajouter un nouveau client</h2>
        
        <form method="post" action="">
            <div class="input-group">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" required>
            </div>
            
            <div class="input-group">
                <label for="adresse">Adresse :</label>
                <input type="text" name="adresse" required>
            </div>
            
            <div class="input-group">
                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" required>
            </div>
            
            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="input-group">
                <label for="siret">Numéro de SIRET (facultatif) :</label>
                <input type="text" name="siret">
            </div>
            
            <button type="submit" class="orange-button">Ajouter le client</button>
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
