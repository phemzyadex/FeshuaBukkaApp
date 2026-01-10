<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h3 class="mb-4">Admin Dashboard</h3>
    <a href="/FastFood_MVC_Phase1_Auth/public/admin/exportOrders"
        class="btn btn-outline-success mb-3">
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
                <h3>
                    ₦<?= isset($stats['sales']) ? number_format($stats['sales'], 2) : '0.00' ?>
                </h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-dark p-3 shadow">
                <h5>Total Users</h5>
                <h3><?= isset($stats['users']) ? $stats['users'] : 0 ?></h3>
            </div>
        </div>
    </div>
    <!-- SEARCH & FILTER -->
    <div class="card shadow mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" id="searchName" class="form-control"
                           placeholder="Search by customer name">
                </div>

                <div class="col-md-4">
                    <select id="filterStatus" class="form-select">
                        <option value="">Filter by status</option>
                        <option value="Pending">Pending</option>
                        <option value="Preparing">Preparing</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <input type="date" id="filterDate" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- ORDERS -->
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
                    <td>
                        <span class="badge <?= $statusClass ?>">
                            <?= htmlspecialchars($o['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-secondary w-100 mb-1"
                                data-bs-toggle="collapse"
                                data-bs-target="#items<?= $o['id'] ?>">
                            View Items
                        </button>

                        <form method="post"
                              action="/FastFood_MVC_Phase1_Auth/public/admin/updateStatus/<?= $o['id'] ?>">
                            <select name="status" class="form-select form-select-sm my-1">
                                <?php foreach (['Pending','Preparing','Delivered'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $o['status']===$s ? 'selected' : '' ?>>
                                        <?= $s ?>
                                    </option>
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
                                        <td>
                                            ₦<?= number_format($item['qty'] * $item['price'], 2) ?>
                                        </td>
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
    <?php if (!empty($pagination)): ?>
<nav aria-label="Orders pagination">
    <ul class="pagination justify-content-center mt-4">

        <!-- Previous -->
        <li class="page-item <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
            <a class="page-link"
               href="?page=<?= $pagination['current'] - 1 ?>">
               Previous
            </a>
        </li>

        <!-- Pages -->
        <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
            <li class="page-item <?= $i == $pagination['current'] ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <li class="page-item <?= $pagination['current'] >= $pagination['pages'] ? 'disabled' : '' ?>">
            <a class="page-link"
               href="?page=<?= $pagination['current'] + 1 ?>">
               Next
            </a>
        </li>

    </ul>
</nav>
<?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
