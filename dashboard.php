<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// Obtenez l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupérer uniquement les clients, items et entreprises créés par l'utilisateur connecté
$clients = $pdo->prepare("SELECT * FROM Clients WHERE user_id = :user_id");
$clients->execute([':user_id' => $user_id]);
$clients = $clients->fetchAll(PDO::FETCH_ASSOC);

$items = $pdo->prepare("SELECT * FROM Items WHERE user_id = :user_id");
$items->execute([':user_id' => $user_id]);
$items = $items->fetchAll(PDO::FETCH_ASSOC);

$entreprises = $pdo->prepare("SELECT * FROM Entreprises WHERE user_id = :user_id");
$entreprises->execute([':user_id' => $user_id]);
$entreprises = $entreprises->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - Informaclique</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="dashboard-header">
    <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Tableau de Bord - Informaclique</a></h2>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="logout-button">Déconnexion</a>
    <?php endif; ?>
</header>

<div class="dashboard-container">
    <nav class="competences-container">
        <button class="competence" onclick="showSection('devis')">Créer un devis</button>
        <button class="competence" onclick="showSection('client')">Ajouter un client</button>
        <button class="competence" onclick="showSection('entreprise')">Créer une entreprise</button>
        <button class="competence" onclick="showSection('item')">Ajouter un item</button>
        <button class="competence" onclick="showSection('view_items')">Voir les items</button>
        <button class="competence" onclick="showSection('view_entreprises')">Voir les entreprises</button>
        <button class="competence" onclick="showSection('view_clients')">Voir les clients</button>
        <button class="competence" onclick="showSection('bank_info')">Mettre à jour les informations bancaires</button>
        <button class="competence" onclick="showSection('view_devis')">Voir les devis</button>
    </nav>

    <!-- Sections du dashboard -->
    <!-- Section Créer un devis -->
    <section id="devis" class="dashboard-section">
            <h2>Créer un devis</h2>
            <form method="post" action="create_devis.php" enctype="multipart/form-data">
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
                <button type="submit" class="orange-button">Créer le devis</button>
            </form>

            <script>
                function addItem() {
                    var container = document.getElementById('items-container');
                    var itemDiv = container.children[0].cloneNode(true);
                    container.appendChild(itemDiv);
                }
            </script>
        </section>


        <!-- Section Ajouter un client -->
        <section id="client" class="dashboard-section" style="display: none;">
            <h2>Ajouter un client</h2>
            <form method="post" action="add_client.php">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" required><br>

                <label for="adresse">Adresse :</label>
                <input type="text" name="adresse" required><br>

                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" required><br>

                <label for="email">Email :</label>
                <input type="email" name="email" required><br>

                <button type="submit" class="cta-button">Ajouter le client</button>
            </form>
        </section>

        <!-- Section Créer une entreprise -->
        <section id="entreprise" class="dashboard-section" style="display: none;">
            <h2>Créer une entreprise</h2>
            <form method="post" action="add_entreprise.php" enctype="multipart/form-data">
                <label for="nom_entreprise">Nom de l'entreprise :</label>
                <input type="text" name="nom_entreprise" required><br>

                <label for="adresse">Adresse :</label>
                <input type="text" name="adresse" required><br>

                <label for="telephone">Téléphone :</label>
                <input type="text" name="telephone" required><br>

                <label for="email">Email :</label>
                <input type="email" name="email" required><br>

                <label for="logo">Logo :</label>
                <input type="file" name="logo" accept="image/*"><br>

                <button type="submit" class="orange-button">Créer l'entreprise</button>
            </form>
        </section>

        <!-- Section Ajouter un item -->
        <section id="item" class="dashboard-section" style="display: none;">
            <h2>Ajouter un item</h2>
            <form method="post" action="add_item.php">
                <label for="description">Description de l'item :</label>
                <input type="text" name="description" required><br>

                <label for="prix_unitaire">Prix unitaire (€) :</label>
                <input type="number" name="prix_unitaire" step="0.01" required><br>

                <button type="submit" class="orange-button">Ajouter l'item</button>
            </form>
        </section>

         <!-- Section Voir les items -->
         <section id="view_items" class="dashboard-section" style="display: none;">
            <h2>Liste des items</h2>
            <table>
                <tr><th>ID</th><th>Description</th><th>Prix Unitaire (€)</th><th>Actions</th></tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['item_id'] ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                        <td><?= $item['prix_unitaire'] ?></td>
                        <td><a href="update_item.php?id=<?= $item['item_id'] ?>">Modifier</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <!-- Section Voir les entreprises -->
        <section id="view_entreprises" class="dashboard-section" style="display: none;">
            <h2>Liste des entreprises</h2>
            <table>
                <tr><th>ID</th><th>Nom</th><th>Adresse</th><th>Téléphone</th><th>Email</th><th>Actions</th></tr>
                <?php foreach ($entreprises as $entreprise): ?>
                    <tr>
                        <td><?= $entreprise['entreprise_id'] ?></td>
                        <td><?= htmlspecialchars($entreprise['nom_entreprise']) ?></td>
                        <td><?= htmlspecialchars($entreprise['adresse']) ?></td>
                        <td><?= $entreprise['telephone'] ?></td>
                        <td><?= $entreprise['email'] ?></td>
                        <td><a href="update_entreprise.php?id=<?= $entreprise['entreprise_id'] ?>">Modifier</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <!-- Section Voir les clients -->
        <section id="view_clients" class="dashboard-section" style="display: none;">
            <h2>Liste des clients</h2>
            <table>
                <tr><th>ID</th><th>Nom</th><th>Adresse</th><th>Téléphone</th><th>Email</th><th>Actions</th></tr>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['client_id'] ?></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['adresse']) ?></td>
                        <td><?= $client['telephone'] ?></td>
                        <td><?= $client['email'] ?></td>
                        <td><a href="update_client.php?id=<?= $client['client_id'] ?>">Modifier</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </div>

    <!-- Section Mettre à jour les informations bancaires -->
<section id="bank_info" class="dashboard-section" style="display: none;">
    <h2>Mettre à jour les informations bancaires</h2>
    <?php include 'update_bank_details.php'; ?>
</section>

    <!-- Section Voir les devis -->
<section id="view_devis" class="dashboard-section" style="display: none;">
    <h2>Liste des devis</h2>
    <table>
        <tr><th>ID</th><th>Date</th><th>Client</th><th>Total HT (€)</th><th>Actions</th></tr>
        <?php
        // Requête pour récupérer les devis créés par l'utilisateur avec marge et tva
        $devis_stmt = $pdo->prepare("
            SELECT Devis.*, Clients.nom AS client_nom 
            FROM Devis 
            JOIN Clients ON Devis.client_id = Clients.client_id 
            WHERE Devis.user_id = :user_id
        ");
        $devis_stmt->execute([':user_id' => $_SESSION['user_id']]);
        $devis_list = $devis_stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($devis_list as $devis): 
            // Calcul du total avec marge et TVA
            $total_ht = $devis['total'];
            $marge_multiplier = 1 + ($devis['marge'] / 100);
            $tva_multiplier = 1 + ($devis['tva'] / 100);
            $total_ttc = $total_ht * $marge_multiplier * $tva_multiplier;
        ?>
            <tr>
                <td><?= $devis['devis_id'] ?></td>
                <td><?= $devis['date_creation'] ?></td>
                <td><?= htmlspecialchars($devis['client_nom']) ?></td>
                <td><?= number_format($total_ttc, 2, ',', ' ') ?></td>
                <td><a href="generate_pdf.php?devis_id=<?= $devis['devis_id'] ?>" target="_blank">Voir le devis</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>


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



</div>

    <script>
        function showSection(section) {
            document.querySelectorAll('.dashboard-section').forEach(s => s.style.display = 'none');
            document.getElementById(section).style.display = 'block';
        }

        function addItem() {
            var container = document.getElementById('items-container');
            var itemDiv = container.children[0].cloneNode(true);
            container.appendChild(itemDiv);
        }
    </script>

</body>
</html>
