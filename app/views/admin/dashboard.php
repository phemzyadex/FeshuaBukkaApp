<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h3>Admin Dashboard</h3>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary p-3">
                <h5>Total Orders</h5>
                <p><?= isset($stats['orders']) ? $stats['orders'] : 0 ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success p-3">
                <h5>Total Sales</h5>
                <p>₦<?= isset($stats['sales']) ? $stats['sales'] : 0 ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-dark p-3">
                <h5>Total Users</h5>
                <p><?= isset($stats['users']) ? $stats['users'] : 0 ?></p>
            </div>
        </div>
    </div>

    <h4>Orders</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($orders)): ?>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= $o['name'] ?></td>
                    <td>₦<?= $o['total'] ?></td>
                    <td><?= $o['status'] ?></td>
                    <td>
                        <form method="post" action="/admin/updateStatus/<?= $o['id'] ?>">
                            <select name="status" class="form-select form-select-sm mb-1">
                                <option <?= $o['status']=='Pending'?'selected':'' ?>>Pending</option>
                                <option <?= $o['status']=='Preparing'?'selected':'' ?>>Preparing</option>
                                <option <?= $o['status']=='Delivered'?'selected':'' ?>>Delivered</option>
                            </select>
                            <button class="btn btn-sm btn-primary w-100">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No orders yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
