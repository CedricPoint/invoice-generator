<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];

// Récupérer les devis de l'utilisateur connecté
$devis_stmt = $pdo->prepare("SELECT Devis.*, Clients.nom AS client_nom, Entreprises.nom_entreprise AS entreprise_nom 
                             FROM Devis 
                             JOIN Clients ON Devis.client_id = Clients.client_id 
                             JOIN Entreprises ON Devis.entreprise_id = Entreprises.entreprise_id 
                             WHERE Devis.user_id = :user_id");
$devis_stmt->execute([':user_id' => $user_id]);
$devis_list = $devis_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Devis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Mes Devis</h1>
        <a href="dashboard.php">Retour au tableau de bord</a>
    </header>

    <div class="devis-container">
        <?php if (count($devis_list) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Date de création</th>
                    <th>Client</th>
                    <th>Entreprise</th>
                    <th>Total (€)</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($devis_list as $devis): ?>
                    <tr>
                        <td><?= $devis['devis_id'] ?></td>
                        <td><?= $devis['date_creation'] ?></td>
                        <td><?= htmlspecialchars($devis['client_nom']) ?></td>
                        <td><?= htmlspecialchars($devis['entreprise_nom']) ?></td>
                        <td><?= number_format($devis['total'], 2, ',', ' ') ?></td>
                        <td>
                            <a href="generate_pdf.php?devis_id=<?= $devis['devis_id'] ?>" target="_blank">Voir PDF</a>
                            <a href="edit_devis.php?devis_id=<?= $devis['devis_id'] ?>">Modifier</a>
                            <a href="delete_devis.php?devis_id=<?= $devis['devis_id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Vous n'avez créé aucun devis pour le moment.</p>
        <?php endif; ?>
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
