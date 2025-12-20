<?php
require 'db.php';

// Récupération des dates du filtre
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// Construction dynamique de la requête
$sql = "
    SELECT 
        p.name AS product_name,
        s.quantity,
        s.price,
        (s.quantity * s.price) AS total,
        s.created_at
    FROM sales s
    JOIN products p ON p.id = s.product_id
    WHERE 1
";

$params = [];

if (!empty($start_date)) {
    $sql .= " AND DATE(s.created_at) >= ?";
    $params[] = $start_date;
}

if (!empty($end_date)) {
    $sql .= " AND DATE(s.created_at) <= ?";
    $params[] = $end_date;
}

$sql .= " ORDER BY s.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$sales = $stmt->fetchAll();

// Calcul du total filtré
$total_amount = 0;
foreach ($sales as $sale) {
    $total_amount += $sale['total'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des ventes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">

    <h3 class="mb-3">🛒 Historique des ventes</h3>

    <!-- Formulaire filtre par date -->
    <form method="GET" class="row g-3 mb-3">

        <div class="col-md-4">
            <label>Date début</label>
            <input type="date" name="start_date" class="form-control"
                   value="<?= htmlspecialchars($start_date) ?>">
        </div>

        <div class="col-md-4">
            <label>Date fin</label>
            <input type="date" name="end_date" class="form-control"
                   value="<?= htmlspecialchars($end_date) ?>">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100">🔍 Filtrer</button>
        </div>

    </form>

    <!-- Total des ventes filtrées -->
    <div class="alert alert-success">
        💰 Total ventes : <strong><?= number_format($total_amount, 0, ',', ' ') ?> FCFA</strong>
    </div>

    <!-- Tableau des ventes -->
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix vente</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>

        <?php if (count($sales) === 0): ?>
            <tr>
                <td colspan="5" class="text-center">Aucune vente enregistrée</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($sales as $sale): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($sale['created_at'])) ?></td>
                <td><?= htmlspecialchars($sale['product_name']) ?></td>
                <td><?= $sale['quantity'] ?></td>
                <td><?= number_format($sale['price'], 0, ',', ' ') ?> FCFA</td>
                <td><strong><?= number_format($sale['total'], 0, ',', ' ') ?> FCFA</strong></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</div>

</body>
</html>
