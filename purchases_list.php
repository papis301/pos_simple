<?php
require 'db.php';

// Récupération des dates du filtre
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// Construction dynamique de la requête
$sql = "
    SELECT 
        p.name AS product_name,
        pu.quantity,
        pu.price AS price,
        (pu.quantity * pu.price) AS total,
        pu.created_at
    FROM purchases pu
    JOIN products p ON p.id = pu.product_id
    WHERE 1
";

$params = [];

if (!empty($start_date)) {
    $sql .= " AND DATE(pu.created_at) >= ?";
    $params[] = $start_date;
}

if (!empty($end_date)) {
    $sql .= " AND DATE(pu.created_at) <= ?";
    $params[] = $end_date;
}

$sql .= " ORDER BY pu.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$purchases = $stmt->fetchAll();

// Calcul du total filtré
$total_amount = 0;
foreach ($purchases as $p) {
    $total_amount += $p['total'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des achats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">

    <h3 class="mb-3">📥 Historique des achats</h3>

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

    <!-- Total des achats filtrés -->
    <div class="alert alert-info">
        💰 Total achats : <strong><?= number_format($total_amount, 0, ',', ' ') ?> FCFA</strong>
    </div>

    <!-- Tableau des achats -->
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix achat</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>

        <?php if (count($purchases) === 0): ?>
            <tr>
                <td colspan="5" class="text-center">Aucun achat enregistré</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($purchases as $a): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></td>
                <td><?= htmlspecialchars($a['product_name']) ?></td>
                <td><?= $a['quantity'] ?></td>
                <td><?= number_format($a['price'], 0, ',', ' ') ?> FCFA</td>
                <td><strong><?= number_format($a['total'], 0, ',', ' ') ?> FCFA</strong></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</div>

</body>
</html>
