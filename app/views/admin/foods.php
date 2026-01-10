<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">

    <h3 class="mb-4">Food Management</h3>

    <!-- ADD FOOD FORM -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3">Add New Food</h5>

            <form method="post"
                  enctype="multipart/form-data"
                  action="/FastFood_MVC_Phase1_Auth/public/food/add">

                <div class="mb-2">
                    <input name="name"
                           class="form-control"
                           placeholder="Food name"
                           required>
                </div>

                <div class="mb-2">
                    <input name="price"
                           type="number"
                           step="0.01"
                           class="form-control"
                           placeholder="Price"
                           required>
                </div>

                <div class="mb-2">
                    <input type="file"
                           name="image"
                           class="form-control"
                           accept="image/*"
                           required>
                </div>

                <button class="btn btn-danger">
                    Add Food
                </button>
            </form>
        </div>
    </div>

    <!-- FOOD LIST -->
    <div class="card shadow">
        <div class="card-body">
            <h5 class="mb-3">All Foods</h5>

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (!empty($foods)): ?>
                    <?php foreach ($foods as $i => $food): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>

                            <td>
                                <img src="/FastFood_MVC_Phase1_Auth/public/uploads/<?= htmlspecialchars($food['image']) ?>"
                                     width="50">
                            </td>

                            <td><?= htmlspecialchars($food['name']) ?></td>

                            <td>₦<?= number_format($food['price'], 2) ?></td>

                            <td>
                                <a href="/FastFood_MVC_Phase1_Auth/public/food/edit/<?= $food['id'] ?>"
                                   class="btn btn-sm btn-primary">
                                   Edit
                                </a>

                                <a href="/FastFood_MVC_Phase1_Auth/public/food/delete/<?= $food['id'] ?>"
                                   onclick="return confirm('Delete this food?')"
                                   class="btn btn-sm btn-danger">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            No food added yet.
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
            <?php if (!empty($pagination)): ?>
                <nav aria-label="Food pagination">
                    <ul class="pagination justify-content-center mt-4">

                        <!-- Previous -->
                        <li class="page-item <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="?page=<?= $pagination['current'] - 1 ?>">
                            Previous
                            </a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $pagination['current'] >= $pagination['pages'] ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="?page=<?= $pagination['current'] + 1 ?>">
                            Next
                            </a>
                        </li>

                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<!-- TOASTS -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">

<?php if (isset($_SESSION['food_success'])): ?>
    <div class="toast text-bg-success border-0 show">
        <div class="d-flex">
            <div class="toast-body">
                <?= $_SESSION['food_success']; ?>
            </div>
            <button class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
<?php unset($_SESSION['food_success']); endif; ?>

<?php if (isset($_SESSION['food_error'])): ?>
    <div class="toast text-bg-danger border-0 show">
        <div class="d-flex">
            <div class="toast-body">
                <?= $_SESSION['food_error']; ?>
            </div>
            <button class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
<?php unset($_SESSION['food_error']); endif; ?>

</div>
