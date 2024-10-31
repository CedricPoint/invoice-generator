<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

$user_id = $_SESSION['user_id'];

// Récupérer les clients, items, et entreprises de l'utilisateur
$clients = $pdo->prepare("SELECT * FROM Clients WHERE user_id = :user_id");
$clients->execute([':user_id' => $user_id]);
$clients = $clients->fetchAll(PDO::FETCH_ASSOC);

$items = $pdo->prepare("SELECT * FROM Items WHERE user_id = :user_id");
$items->execute([':user_id' => $user_id]);
$items = $items->fetchAll(PDO::FETCH_ASSOC);

$entreprises = $pdo->prepare("SELECT * FROM Entreprises WHERE user_id = :user_id");
$entreprises->execute([':user_id' => $user_id]);
$entreprises = $entreprises->fetchAll(PDO::FETCH_ASSOC);

$devis_created = false;
$devis_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $entreprise_id = $_POST['entreprise_id'];
    $selected_items = $_POST['item_id'];
    $quantities = $_POST['quantite'];
    
    $marge = isset($_POST['marge']) ? 1 + ($_POST['marge'] / 100) : 1;
    $taux_tva = isset($_POST['tva']) ? $_POST['tva'] / 100 : 0;
    
    $total = 0;

    foreach ($selected_items as $index => $item_id) {
        $quantite = $quantities[$index];
        $item_stmt = $pdo->prepare("SELECT prix_unitaire FROM Items WHERE item_id = :item_id AND user_id = :user_id");
        $item_stmt->execute([':item_id' => $item_id, ':user_id' => $user_id]);
        $prix_unitaire = $item_stmt->fetchColumn() * $marge;
        $prix_total = $prix_unitaire * $quantite * (1 + $taux_tva);
        $total += $prix_total;
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO Devis (client_id, entreprise_id, user_id, date_creation, total, marge, tva) VALUES (:client_id, :entreprise_id, :user_id, NOW(), :total, :marge, :tva)");
        $stmt->execute([
            ':client_id' => $client_id,
            ':entreprise_id' => $entreprise_id,
            ':user_id' => $user_id,
            ':total' => $total,
            ':marge' => $_POST['marge'] ?? 0,
            ':tva' => $_POST['tva'] ?? 0
        ]);
        $devis_id = $pdo->lastInsertId();

        $item_insert_stmt = $pdo->prepare("INSERT INTO Devis_Items (devis_id, item_id, quantite, prix_total) VALUES (:devis_id, :item_id, :quantite, :prix_total)");
        foreach ($selected_items as $index => $item_id) {
            $quantite = $quantities[$index];
            $prix_total = $prix_unitaire * $quantite * (1 + $taux_tva);
            $item_insert_stmt->execute([':devis_id' => $devis_id, ':item_id' => $item_id, ':quantite' => $quantite, ':prix_total' => $prix_total]);
        }

        $pdo->commit();
        $devis_created = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur lors de la création du devis : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un devis</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function openDevisInNewTab() {
            const devisUrl = document.getElementById('devis_url').value;
            if (devisUrl) {
                window.open(devisUrl, '_blank');
            }
            // Redirection vers le dashboard après l'ouverture de l'onglet
            setTimeout(function() {
                window.location.href = "dashboard.php";
            }, 1000); // Ajuster le délai selon les besoins
        }
    </script>
</head>
<body onload="<?= $devis_created && $devis_id ? 'openDevisInNewTab()' : '' ?>">
    <header class="dashboard-header">
        <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="logout-button">Déconnexion</a>
        <?php endif; ?>
    </header>
    <h2>Créer un nouveau devis</h2>

    <?php if ($devis_created && $devis_id): ?>
        <p>Devis créé avec succès !</p>
        <input type="hidden" id="devis_url" value="generate_pdf.php?devis_id=<?= $devis_id ?>">
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data">
        <label for="entreprise_id">Entreprise :</label>
        <select id="entreprise_id" name="entreprise_id" required>
            <?php foreach ($entreprises as $entreprise): ?>
                <option value="<?= $entreprise['entreprise_id'] ?>"><?= htmlspecialchars($entreprise['nom_entreprise']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="client_id">Client :</label>
        <select id="client_id" name="client_id" required>
            <?php foreach ($clients as $client): ?>
                <option value="<?= $client['client_id'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="marge">Marge (%) :</label>
        <input type="number" name="marge" step="0.01" min="0" required placeholder="Ex: 5.00"><br>

        <label for="tva">TVA (%) :</label>
        <input type="number" name="tva" step="0.01" min="0" required placeholder="Ex: 20.00"><br>

        <h3>Items</h3>
        <div id="items-container">
            <div class="item">
                <select name="item_id[]" required>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['item_id'] ?>"><?= htmlspecialchars($item['description']) ?> (<?= $item['prix_unitaire'] ?> €)</option>
                    <?php endforeach; ?>
                </select>
                <label>Quantité :</label>
                <input type="number" name="quantite[]" min="1" value="1" required>
            </div>
        </div>

        <button type="button" onclick="addItem()">Ajouter un autre item</button><br><br>
        <button type="submit">Créer le devis</button>
    </form>

    <script>
        function addItem() {
            var container = document.getElementById('items-container');
            var itemDiv = container.children[0].cloneNode(true);
            container.appendChild(itemDiv);
        }
    </script>

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
