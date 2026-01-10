<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h3>Edit Category</h3>

    <?php if (isset($_SESSION['category_success'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:11;">
        <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"><?= $_SESSION['category_success'] ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['category_success']); endif; ?>

    <?php if (isset($_SESSION['category_error'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:11;">
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"><?= $_SESSION['category_error'] ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['category_error']); endif; ?>

    <form method="post" action="/FastFood_MVC_Phase1_Auth/public/admin/updateCategory/<?= $category['id'] ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        </div>
        <button class="btn btn-primary">Save Changes</button>
        <a href="/FastFood_MVC_Phase1_Auth/public/admin/categories" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastElList = [].slice.call(document.querySelectorAll('.toast'))
    toastElList.forEach(function(toastEl) {
        new bootstrap.Toast(toastEl, { delay: 3000 }).show()
    })
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
