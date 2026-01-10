<?php require __DIR__.'/../partials/header.php'; ?>

<div class="container mt-4">
    <h3>Categories</h3>

    <!-- Add Category -->
    <form method="post" action="/FastFood_MVC_Phase1_Auth/public/category/create" class="mb-4">
        <input type="text" name="name" class="form-control" placeholder="Category name" required>
        <button class="btn btn-success mt-2">Add Category</button>
    </form>

    <!-- List Categories -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $i => $cat): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td>
                    <a href="/FastFood_MVC_Phase1_Auth/public/category/edit/<?= $cat['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="/FastFood_MVC_Phase1_Auth/public/category/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__.'/../partials/footer.php'; ?>
