<?php
require 'db.php';

$opening_amount = $_POST['opening_amount'];

// Vérifier s'il y a déjà une caisse ouverte
$stmt = $pdo->query("SELECT id FROM cash_registers WHERE status = 'open'");
if ($stmt->rowCount() > 0) {
    die("❌ Une caisse est déjà ouverte");
}

// Ouvrir la caisse
$stmt = $pdo->prepare("
    INSERT INTO cash_registers (opening_amount)
    VALUES (?)
");
$stmt->execute([$opening_amount]);

echo "✅ Caisse ouverte avec succès";
