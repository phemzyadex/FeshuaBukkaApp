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

                <!-- CATEGORY -->
                <div class="mb-3">
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- NAME -->
                <div class="mb-3">
                    <input name="name"
                           class="form-control"
                           placeholder="Food name"
                           required>
                </div>

                <!-- PRICE -->
                <div class="mb-3">
                    <input name="price"
                           type="number"
                           step="0.01"
                           class="form-control"
                           placeholder="Price"
                           required>
                </div>

                <!-- IMAGE -->
                <div class="mb-3">
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

            <table class="table table-bordered table-hover align-middle">
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

            <!-- PAGINATION -->
            <?php if (!empty($pagination)): ?>
                <nav>
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item <?= $pagination['current'] <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>">Previous</a>
                        </li>

                        <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= $pagination['current'] >= $pagination['pages'] ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
