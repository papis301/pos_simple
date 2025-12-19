<?php require 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Achat produit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
<h3>➕ Achat Produit</h3>

<form method="POST" action="add_purchase.php" class="card p-4 shadow">
  <input class="form-control mb-2" name="name" placeholder="Nom du produit" required>
  <input class="form-control mb-2" type="number" step="0.01" name="purchase_price" placeholder="Prix d'achat" required>
  <input class="form-control mb-2" type="number" step="0.01" name="sale_price" placeholder="Prix de vente" required>
  <input class="form-control mb-2" type="number" name="quantity" placeholder="Quantité" required>
  <button class="btn btn-success w-100">Enregistrer Achat</button>
</form>
</div>

</body>
</html>
