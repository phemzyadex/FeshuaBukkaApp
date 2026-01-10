<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h4>Edit Food</h4>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-2">
            <input type="text" name="name"
                   class="form-control"
                   value="<?= htmlspecialchars($food['name']) ?>"
                   required>
        </div>

        <div class="mb-2">
            <input type="number" step="0.01"
                   name="price"
                   class="form-control"
                   value="<?= $food['price'] ?>"
                   required>
        </div>

        <div class="mb-2">
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Leave blank to keep existing image</small>
        </div>

        <img src="/FastFood_MVC_Phase1_Auth/public/uploads/<?= $food['image'] ?>"
             width="80" class="mb-2">

        <button class="btn btn-primary w-100">Update Food</button>
    </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
