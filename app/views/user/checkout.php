<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h3>Checkout</h3>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Food</th>
                <th width="160">Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($cartItems as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>

            <td class="text-center">
                <a href="/FastFood_MVC_Phase1_Auth/public/cart/decrease/<?= $item['id'] ?>"
                   class="btn btn-sm btn-secondary">−</a>

                <span class="mx-2 fw-bold"><?= $item['qty'] ?></span>

                <a href="/FastFood_MVC_Phase1_Auth/public/cart/increase/<?= $item['id'] ?>"
                   class="btn btn-sm btn-secondary">+</a>
            </td>

            <td>₦<?= number_format($item['price'], 2) ?></td>
            <td>₦<?= number_format($item['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h4 class="text-end">
        Total: <strong>₦<?= number_format($total, 2) ?></strong>
    </h4>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-9">
                <a href="/FastFood_MVC_Phase1_Auth/public/food/menu"
                    class="btn btn-info">
                    Continue Shopping
                </a>
            </div>
            <div class="col-md-3">
                <form method="post"
                    action="/FastFood_MVC_Phase1_Auth/public/checkout/placeOrder"
                    class="text-end mt-3">
                    <button class="btn btn-success btn-lg">
                        Place Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
