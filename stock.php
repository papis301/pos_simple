<?php require 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
<h3>📦 Stock</h3>

<table class="table table-bordered table-striped">
<tr>
<th>Produit</th><th>Prix Achat</th><th>Prix Vente</th><th>Stock</th>
</tr>
<?php
$products = $pdo->query("SELECT * FROM products")->fetchAll();
foreach($products as $p){
echo "<tr>
<td>{$p['name']}</td>
<td>{$p['purchase_price']}</td>
<td>{$p['sale_price']}</td>
<td>{$p['stock']}</td>
</tr>";
}
?>
</table>
</div>

</body>
</html>
