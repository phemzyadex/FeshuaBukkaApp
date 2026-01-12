<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5 text-center">
    <h2 class="text-success">✅ Order Successful</h2>
    <p>Thank you for your order!</p>

    <a href="/FastFood_MVC_Phase1_Auth/public/food/menu"
       class="btn btn-danger mt-3">
        Continue Ordering
    </a>

    <?php if (!empty($order)): ?>
        <p>Order #<?= htmlspecialchars($order['id']) ?> completed successfully!</p>
        <a href="/download/<?= htmlspecialchars($order['id']) ?>" class="btn btn-sm btn-primary">Download Receipt</a>
    <?php else: ?>
        <p>No order details found.</p>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
