<?php
require 'db.php';

$name = $_POST['name'];
$purchase_price = $_POST['purchase_price'];
$sale_price = $_POST['sale_price'];
$quantity = $_POST['quantity'];

$pdo->beginTransaction();

$stmt = $pdo->prepare("SELECT id, stock FROM products WHERE name=?");
$stmt->execute([$name]);
$product = $stmt->fetch();

if ($product) {
    $pdo->prepare("
        UPDATE products SET 
        stock = stock + ?, 
        purchase_price=?, 
        sale_price=? 
        WHERE id=?
    ")->execute([$quantity, $purchase_price, $sale_price, $product['id']]);

    $product_id = $product['id'];
} else {
    $pdo->prepare("
        INSERT INTO products (name,purchase_price,sale_price,stock)
        VALUES (?,?,?,?)
    ")->execute([$name,$purchase_price,$sale_price,$quantity]);

    $product_id = $pdo->lastInsertId();
}

$pdo->prepare("
    INSERT INTO purchases (product_id,quantity,price)
    VALUES (?,?,?)
")->execute([$product_id,$quantity,$purchase_price]);

$pdo->commit();

header("Location: stock.php");
