<h3>Finance Report</h3>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card p-3 shadow">
            <h6>Total Revenue</h6>
            <h4>₦<?= number_format($data['summary']->total_revenue ?? 0, 2) ?></h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow">
            <h6>Total Transactions</h6>
            <h4><?= $data['summary']->total_transactions ?? 0 ?></h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow">
            <h6>Today’s Revenue</h6>
            <h4>₦<?= number_format($data['today']->revenue_today ?? 0, 2) ?></h4>
        </div>
    </div>
</div>

<form method="post" class="row mb-4">
    <div class="col-md-4">
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="col-md-4">
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <div class="col-md-4">
        <button class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Reference</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['records'] as $row): ?>
        <tr>
            <td><?= $row->reference ?></td>
            <td>₦<?= number_format($row->amount, 2) ?></td>
            <td><?= $row->created_at ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
