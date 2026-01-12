<h1>Finance Report</h1>

<form method="get" class="row g-3 mb-4">
    <div class="col-md-3">
        <label>Start Date</label>
        <input type="date" name="start" class="form-control" value="<?= $startDate ?>">
    </div>
    <div class="col-md-3">
        <label>End Date</label>
        <input type="date" name="end" class="form-control" value="<?= $endDate ?>">
    </div>
    <div class="col-md-2 align-self-end">
        <button class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-2 align-self-end">
        <a class="btn btn-success" 
           href="/FastFood_MVC_Phase1_Auth/public/admin/financeReportPDF?start=<?= $startDate ?>&end=<?= $endDate ?>" 
           target="_blank">
           Download PDF
        </a>
    </div>
</form>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white p-3">
            Total Revenue: ₦<?= number_format($totalRevenue,2) ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-primary text-white p-3">
            Total Orders: <?= $totalOrders ?>
        </div>
    </div>
</div>

<h3>Payments</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Reference</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($payments as $p): ?>
        <tr>
            <td><?= $p['reference'] ?></td>
            <td><?= $p['user_id'] ?></td>
            <td>₦<?= number_format($p['amount'],2) ?></td>
            <td><?= $p['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
