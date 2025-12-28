<?php
require 'db.php';

/*
  Récupération des produits existants
*/
$products = $pdo->query("
  SELECT id, name 
  FROM products 
  ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Achat produit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <h3>➕ Achat Produit</h3>

  <form method="POST" action="add_purchase.php" class="card p-4 shadow">

    <!-- Produit existant -->
    <label class="fw-bold">Produit existant</label>
    <select class="form-control mb-2" name="product_id">
      <option value="">-- Sélectionner un produit --</option>
      <?php foreach ($products as $p): ?>
        <option value="<?= $p['id'] ?>">
          <?= htmlspecialchars($p['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <div class="text-center my-2 fw-bold">OU</div>

    <!-- Nouveau produit -->
    <label class="fw-bold">Nouveau produit</label>
    <input 
      class="form-control mb-2" 
      name="new_product_name" 
      placeholder="Nom du nouveau produit">

    <!-- Prix et quantité -->
    <input 
      class="form-control mb-2" 
      type="number" 
      step="0.01" 
      name="purchase_price" 
      placeholder="Prix d'achat" 
      required>

    <input 
      class="form-control mb-2" 
      type="number" 
      step="0.01" 
      name="sale_price" 
      placeholder="Prix de vente" 
      required>

    <input 
      class="form-control mb-2" 
      type="number" 
      name="quantity" 
      placeholder="Quantité" 
      required>

    <button class="btn btn-success w-100">
      💾 Enregistrer Achat
    </button>
  </form>
</div>

</body>
</html>
