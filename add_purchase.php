<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

/* =====================
   Récupération des données
===================== */
$product_id        = $_POST['product_id'] ?? null;
$new_product_name  = trim($_POST['new_product_name'] ?? '');
$purchase_price    = $_POST['purchase_price'] ?? 0;
$sale_price        = $_POST['sale_price'] ?? 0;
$quantity          = $_POST['quantity'] ?? 0;

/* =====================
   Vérifications
===================== */
if (
    (empty($product_id) && empty($new_product_name)) ||
    $purchase_price <= 0 ||
    $sale_price <= 0 ||
    $quantity <= 0
) {
    die("❌ Données invalides");
}

try {
    $pdo->beginTransaction();

    /* =====================
       CAS 1 : Nouveau produit
    ===================== */
    if (empty($product_id)) {

        // Création du produit
        $stmt = $pdo->prepare("
            INSERT INTO products (name, sale_price, stock)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $new_product_name,
            $sale_price,
            $quantity
        ]);

        $product_id = $pdo->lastInsertId();

    } 
    /* =====================
       CAS 2 : Produit existant
    ===================== */
    else {

        // Mise à jour du stock et du prix de vente
        $stmt = $pdo->prepare("
            UPDATE products 
            SET 
              stock = stock + ?, 
              sale_price = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $quantity,
            $sale_price,
            $product_id
        ]);
    }

    /* =====================
       Enregistrement de l'achat
    ===================== */
    $stmt = $pdo->prepare("
        INSERT INTO purchases (product_id, price, quantity, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([
        $product_id,
        $purchase_price,
        $quantity
    ]);

    $pdo->commit();

    header("Location: purchases_list.php?success=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("❌ Erreur achat : " . $e->getMessage());
}
