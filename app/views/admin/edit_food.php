<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h3>Edit Food</h3>

    <form method="post" enctype="multipart/form-data"
          action="/FastFood_MVC_Phase1_Auth/public/food/edit/<?= $food['id'] ?>">
        
        <!-- Category -->
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select name="category_id" id="category" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" 
                        <?= $food['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Food Name</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?= htmlspecialchars($food['name']) ?>" required>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price"
                   name="price" value="<?= $food['price'] ?>" required>
        </div>

        <!-- Current Image -->
        <?php if (!empty($food['image'])): ?>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="/FastFood_MVC_Phase1_Auth/public/uploads/<?= htmlspecialchars($food['image']) ?>"
                     width="100">
            </div>
        <?php endif; ?>

        <!-- New Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Replace Image (optional)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>

        <button class="btn btn-primary">Save Changes</button>
        <a href="/FastFood_MVC_Phase1_Auth/public/food/add" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<!-- Toast notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
<?php if (isset($_SESSION['food_success'])): ?>
    <div class="toast text-bg-success border-0 show">
        <div class="d-flex">
            <div class="toast-body"><?= $_SESSION['food_success'] ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <?php unset($_SESSION['food_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['food_error'])): ?>
    <div class="toast text-bg-danger border-0 show">
        <div class="d-flex">
            <div class="toast-body"><?= $_SESSION['food_error'] ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <?php unset($_SESSION['food_error']); ?>
<?php endif; ?>
</div>
