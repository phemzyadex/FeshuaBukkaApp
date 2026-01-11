<?php require __DIR__ . '/../partials/header.php'; ?>

<h3 class="mb-4">🍽️ Our Menu</h3>

<?php foreach ($groupedFoods as $category => $foods): ?>

    <h4 class="mt-4 mb-3 text-danger"><?= htmlspecialchars($category) ?></h4>

    <div class="row">
        <?php foreach ($foods as $food): ?>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="/FastFood_MVC_Phase1_Auth/public/uploads/<?= htmlspecialchars($food['image']) ?>"
                         class="card-img-top">

                    <div class="card-body text-center">
                        <h6><?= htmlspecialchars($food['name']) ?></h6>
                        <p class="text-danger fw-bold">
                            ₦<?= number_format($food['price'], 2) ?>
                        </p>

                        <a href="/FastFood_MVC_Phase1_Auth/public/cart/add/<?= $food['id'] ?>"
                           class="btn btn-sm btn-danger">
                            Add to Cart
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endforeach; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>
