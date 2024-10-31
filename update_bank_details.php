<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];

// Si le formulaire est soumis, mettre à jour les informations bancaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bank_name = $_POST['bank_name'];
    $iban = $_POST['iban'];
    $swift_bic = $_POST['swift_bic'];

    // Vérifier si l'utilisateur a déjà des informations bancaires
    $check = $pdo->prepare("SELECT * FROM BankDetails WHERE user_id = :user_id");
    $check->execute([':user_id' => $user_id]);

    if ($check->rowCount() > 0) {
        // Mettre à jour les informations existantes
        $stmt = $pdo->prepare("UPDATE BankDetails SET bank_name = :bank_name, iban = :iban, swift_bic = :swift_bic WHERE user_id = :user_id");
        $stmt->execute([':bank_name' => $bank_name, ':iban' => $iban, ':swift_bic' => $swift_bic, ':user_id' => $user_id]);
    } else {
        // Insérer de nouvelles informations bancaires
        $stmt = $pdo->prepare("INSERT INTO BankDetails (user_id, bank_name, iban, swift_bic) VALUES (:user_id, :bank_name, :iban, :swift_bic)");
        $stmt->execute([':user_id' => $user_id, ':bank_name' => $bank_name, ':iban' => $iban, ':swift_bic' => $swift_bic]);
    }

    echo "Informations bancaires mises à jour avec succès !";
}

// Récupérer les informations bancaires de l'utilisateur pour pré-remplir le formulaire
$bank_details = $pdo->prepare("SELECT * FROM BankDetails WHERE user_id = :user_id");
$bank_details->execute([':user_id' => $user_id]);
$bank_details = $bank_details->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier les Informations Bancaires</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <h2>Modifier les Informations Bancaires</h2>
    <form method="post" action="">
        <label>Nom de la Banque :</label>
        <input type="text" name="bank_name" value="<?= htmlspecialchars($bank_details['bank_name'] ?? '') ?>" required><br>

        <label>IBAN :</label>
        <input type="text" name="iban" value="<?= htmlspecialchars($bank_details['iban'] ?? '') ?>" required><br>

        <label>SWIFT/BIC :</label>
        <input type="text" name="swift_bic" value="<?= htmlspecialchars($bank_details['swift_bic'] ?? '') ?>" required><br>

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
