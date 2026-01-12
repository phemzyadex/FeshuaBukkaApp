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
    <div class="row mt-4">
        <div class="col-md-9">
            <a href="/FastFood_MVC_Phase1_Auth/public/food/menu"
            class="btn btn-info">
                Continue Shopping
            </a>
        </div>

        <div class="col-md-3 text-end">
            <button class="btn btn-success btn-lg"
                    onclick="payWithPaystack()">
                Pay ₦<?= number_format($total, 2) ?>
            </button>
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

<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
function payWithPaystack() {
    let handler = PaystackPop.setup({
        key: 'pk_test_2e36e7a8b8d9c36735a9cfea786f7ce202891a40', // YOUR PUBLIC KEY
        email: 'ultraprocess.solutions@email.com',   // replace with logged-in user email
        amount: <?= intval($total * 100) ?>, // kobo
        currency: "NGN",
        ref: 'FF_' + Math.floor((Math.random() * 1000000000) + 1),

        callback: function(response) {
            window.location.href =
                "/FastFood_MVC_Phase1_Auth/public/checkout/verify/" 
                + response.reference;
        },

        onClose: function() {
            alert('Payment cancelled');
        }
    });

    handler.openIframe();
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
