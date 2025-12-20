<?php
require 'db.php';

$cash = $pdo->query("
    SELECT * FROM cash_registers
    ORDER BY opened_at DESC
    LIMIT 1
")->fetch();
?>

<h3>🧾 Caisse Journalière</h3>

<ul>
    <li>Ouverture : <?= $cash['opening_amount'] ?> FCFA</li>
    <li>Total ventes : <?= $cash['total_sales'] ?> FCFA</li>
    <li>Fermeture : <?= $cash['closing_amount'] ?> FCFA</li>
    <li>Statut : <?= strtoupper($cash['status']) ?></li>
</ul>
