<?php require 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
<h3>💰 Vente</h3>

<form method="POST" class="card p-4 shadow">
<select name="product_id" class="form-control mb-2" required>
<option value="">-- Produit --</option>
<?php
$products = $pdo->query("SELECT * FROM products WHERE stock>0")->fetchAll();
foreach($products as $p){
  echo "<option value='{$p['id']}'>{$p['name']} (Stock {$p['stock']})</option>";
}
?>
</select>

<input type="number" name="quantity" class="form-control mb-2" placeholder="Quantité" required>
<button class="btn btn-primary w-100">Valider Vente</button>
</form>

<?php
if ($_POST) {
    $stmt = $pdo->prepare("SELECT stock,sale_price FROM products WHERE id=?");
    $stmt->execute([$_POST['product_id']]);
    $p = $stmt->fetch();

    if ($p && $p['stock'] >= $_POST['quantity']) {
        $pdo->prepare("UPDATE products SET stock=stock-? WHERE id=?")
            ->execute([$_POST['quantity'],$_POST['product_id']]);

        $pdo->prepare("INSERT INTO sales(product_id,quantity,price)
                       VALUES(?,?,?)")
            ->execute([$_POST['product_id'],$_POST['quantity'],$p['sale_price']]);

        echo "<div class='alert alert-success mt-3'>Vente réussie</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Stock insuffisant</div>";
    }
}
?>
</div>
</body>
</html>
