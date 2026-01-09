<?php require __DIR__ . '/../partials/header.php'; ?>

<h3>📝 Order Status</h3>

<?php if (!empty($orders)): ?>
<table class="table table-striped">
<thead>
<tr>
    <th>Order ID</th>
    <th>Total</th>
    <th>Status</th>
    <th>Placed At</th>
</tr>
</thead>
<tbody>
<?php foreach($orders as $o): ?>
<tr>
    <td><?= $o['id'] ?></td>
    <td>₦<?= $o['total'] ?></td>
    <td><?= $o['status'] ?></td>
    <td><?= $o['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-center">No orders yet.</p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<?php if (isset($order_placed) && $order_placed === true): ?>
<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:11">
  <div id="orderToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Order placed successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<script>
var toastEl = document.getElementById('orderToast');
var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
toast.show();
</script>
<?php endif; ?>
