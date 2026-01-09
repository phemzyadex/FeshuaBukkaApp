<?php require __DIR__ . '/../partials/header.php'; ?>

<h3 class="mb-4">🍽️ Our Menu</h3>

<div class="row">
<?php foreach ($foods as $food): ?>
<div class="col-md-3 mb-4">
<div class="card shadow-sm h-100">
<img src="/public/uploads/<?= $food['image'] ?>" class="card-img-top">

<div class="card-body text-center">
<h6><?= $food['name'] ?></h6>
<p class="text-danger fw-bold">₦<?= $food['price'] ?></p>

<a href="/cart/add/<?= $food['id'] ?>" class="btn btn-sm btn-danger">
Add to Cart
</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
