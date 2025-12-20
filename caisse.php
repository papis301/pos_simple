<?php
require 'db.php';

// Vérifier s'il y a une caisse ouverte
$stmt = $pdo->query("SELECT * FROM cash_registers WHERE status = 'open' LIMIT 1");
$cash = $stmt->fetch();
?>

<h2>🧾 CAISSE</h2>

<?php if (!$cash): ?>

    <!-- Aucune caisse ouverte -->
    <form action="open_cash.php" method="post">
        <label>Montant d'ouverture</label><br>
        <input type="number" name="opening_amount" required>
        <br><br>
        <button type="submit">Ouvrir la caisse</button>
    </form>

<?php else: ?>

    <!-- Caisse ouverte -->
    <p>💰 Ouverture : <?= $cash['opening_amount'] ?> FCFA</p>
    <p>🛒 Total ventes : <?= $cash['total_sales'] ?> FCFA</p>
    <p>📊 Solde actuel : <?= $cash['opening_amount'] + $cash['total_sales'] ?> FCFA</p>

    <hr>

    <a href="vente.php">➕ Faire une vente</a><br><br>
    <a href="close_cash.php" onclick="return confirm('Fermer la caisse ?')">
        🔒 Fermer la caisse
    </a>

<?php endif; ?>
