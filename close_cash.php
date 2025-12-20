<?php
require 'db.php';

// récupérer caisse ouverte
$stmt = $pdo->query("SELECT * FROM cash_registers WHERE status = 'open' LIMIT 1");
$cash = $stmt->fetch();

if (!$cash) {
    die("❌ Aucune caisse ouverte");
}

$closing_amount = $cash['opening_amount'] + $cash['total_sales'];

$stmt = $pdo->prepare("
    UPDATE cash_registers
    SET closing_amount = ?, status = 'closed', closed_at = NOW()
    WHERE id = ?
");
$stmt->execute([$closing_amount, $cash['id']]);

echo "✅ Caisse clôturée<br>
Montant ouverture : {$cash['opening_amount']}<br>
Total ventes : {$cash['total_sales']}<br>
Montant fermeture : {$closing_amount}";
