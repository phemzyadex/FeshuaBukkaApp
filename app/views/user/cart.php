<?php require __DIR__ . '/../partials/header.php'; ?>

<h3>🛒 Your Cart</h3>

<?php if (!empty($cartItems)): ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Food</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($cartItems as $item): ?>
        <tr>
            <td><?= $item['name'] ?></td>
            <td><?= $item['qty'] ?></td>
            <td>₦<?= $item['price'] ?></td>
            <td>₦<?= $item['price'] * $item['qty'] ?></td>
            <td><a href="/FastFood_MVC_Phase1_Auth/public/cart/remove/<?= $item['id'] ?>" class="btn btn-sm btn-danger">Remove</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="container mt-4">
  <div class="row">
    <div class="col-md-9">
      <a href="/FastFood_MVC_Phase1_Auth/public/food/menu"
        class="btn btn-info">
        Continue Shopping
      </a>
    </div>
    <div class="col-md-3">
      <a href="/FastFood_MVC_Phase1_Auth/public/checkout"
        class="btn btn-success">
        Checkout
      </a>
    </div>
  </div>
</div>
<?php else: ?>
<p class="text-center">Your cart is empty.</p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<?php if (isset($item_added) && $item_added === true): ?>
<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Item added to cart!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<script>
var toastEl = document.getElementById('cartToast');
var toast = new bootstrap.Toast(toastEl, { delay: 2000 });
toast.show();
</script>
<?php endif; ?>
