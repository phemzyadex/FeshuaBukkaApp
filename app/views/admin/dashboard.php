<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">Admin Dashboard</h3>
    <a href="/FastFood_MVC_Phase1_Auth/public/admin/exportOrders" class="btn btn-outline-success mb-3">
        ⬇ Export Orders (CSV)
    </a>
    
    <!-- STATS TODAY-->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-info text-white p-3 shadow">
                <h6>Today's Orders</h6>
                <h3><?= $analytics['today_orders'] ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white p-3 shadow">
                <h6>Today's Sales</h6>
                <h3>₦<?= number_format($analytics['today_sales'],2) ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning p-3 shadow">
                <h6>This Month Sales</h6>
                <h3>₦<?= number_format($analytics['month_sales'],2) ?></h3>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary p-3 shadow">
                <h5>Total Orders</h5>
                <h3><?= isset($stats['orders']) ? $stats['orders'] : 0 ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success p-3 shadow">
                <h5>Total Sales</h5>
                <h3>₦<?= isset($stats['sales']) ? number_format($stats['sales'], 2) : '0.00' ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-dark p-3 shadow">
                <h5>Total Users</h5>
                <h3><?= isset($stats['users']) ? $stats['users'] : 0 ?></h3>
            </div>
        </div>
    </div>

    <!-- <div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="card-text">₦<?= number_format($totalRevenue, 2) ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p class="card-text"><?= $totalOrders ?></p>
            </div>
        </div>
    </div> -->
</div>

<!-- <h3>Recent Payments</h3>
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
        <?php foreach($recentPayments as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['reference']) ?></td>
            <td><?= $p['user_id'] ?></td>
            <td>₦<?= number_format($p['amount'], 2) ?></td>
            <td><?= $p['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> -->
<!-- FILTER FORM -->
    <form method="get" class="row g-2 mb-4 align-items-end">
        <div class="col-md-3">
            <label for="filterDate" class="form-label">Filter by Date</label>
            <input type="date" name="filterDate" id="filterDate" class="form-control"
                   value="<?= isset($_GET['filterDate']) && $_GET['filterDate'] !== '' ? htmlspecialchars($_GET['filterDate']) : date('Y-m-d') ?>">
        </div>
        <div class="col-md-3">
            <label for="filterStatus" class="form-label">Filter by Status</label>
            <select name="filterStatus" id="filterStatus" class="form-select">
                <option value="">All</option>
                <?php foreach (['Pending', 'Preparing', 'Delivered'] as $status): ?>
                    <option value="<?= $status ?>" <?= (isset($_GET['filterStatus']) && $_GET['filterStatus'] === $status) ? 'selected' : '' ?>>
                        <?= $status ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filterName" class="form-label">Filter by Customer</label>
            <input type="text" name="filterName" id="filterName" class="form-control"
                   placeholder="Customer name"
                   value="<?= isset($_GET['filterName']) ? htmlspecialchars($_GET['filterName']) : '' ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- ORDERS TABLE -->
    <table class="table table-bordered align-middle" id="ordersTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th width="220">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $o): ?>

                <?php
                // Apply filters
                $show = true;
                if (isset($_GET['filterStatus']) && $_GET['filterStatus'] !== '' && $_GET['filterStatus'] !== $o['status']) {
                    $show = false;
                }
                if (isset($_GET['filterName']) && $_GET['filterName'] !== '' && stripos($o['name'], $_GET['filterName']) === false) {
                    $show = false;
                }
                if (isset($_GET['filterDate']) && $_GET['filterDate'] !== '' &&
                    date('Y-m-d', strtotime($o['created_at'])) !== $_GET['filterDate']) {
                    $show = false;
                }
                if (!$show) continue;

                switch ($o['status']) {
                    case 'Pending':   $statusClass = 'bg-warning text-dark'; break;
                    case 'Preparing': $statusClass = 'bg-info'; break;
                    case 'Delivered': $statusClass = 'bg-success'; break;
                    default:          $statusClass = 'bg-secondary';
                }
                ?>

                <tr
                    data-name="<?= strtolower($o['name']) ?>"
                    data-status="<?= $o['status'] ?>"
                    data-date="<?= isset($o['created_at']) ? date('Y-m-d', strtotime($o['created_at'])) : '' ?>"
                >
                    <td>#<?= $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['name']) ?></td>
                    <td>₦<?= number_format($o['total'], 2) ?></td>
                    <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($o['status']) ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-secondary w-100 mb-1"
                                data-bs-toggle="collapse"
                                data-bs-target="#items<?= $o['id'] ?>">
                            View Items
                        </button>
                        <form method="post" action="/FastFood_MVC_Phase1_Auth/public/admin/updateStatus/<?= $o['id'] ?>">
                            <select name="status" class="form-select form-select-sm my-1">
                                <?php foreach (['Pending','Preparing','Delivered'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $o['status']===$s ? 'selected' : '' ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-sm btn-primary w-100">Save</button>
                        </form>
                    </td>
                </tr>

                <!-- ITEMS -->
                <tr class="collapse bg-light" id="items<?= $o['id'] ?>">
                    <td colspan="5">
                        <?php if (!empty($o['items'])): ?>
                            <table class="table table-sm table-striped mt-2">
                                <thead>
                                    <tr>
                                        <th>Food</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($o['items'] as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= (int)$item['qty'] ?></td>
                                        <td>₦<?= number_format($item['price'], 2) ?></td>
                                        <td>₦<?= number_format($item['qty'] * $item['price'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <em class="text-muted">No items</em>
                        <?php endif; ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-muted">No orders yet.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?php if (!empty($pagination)): ?>
        <nav aria-label="Orders pagination">
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                    <li class="page-item <?= $i == $pagination['current'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $pagination['current'] >= $pagination['pages'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
