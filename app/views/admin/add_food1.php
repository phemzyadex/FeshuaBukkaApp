<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">

    <h3 class="mb-4">Food Management</h3>

    <!-- ADD FOOD FORM -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3">Add New Food</h5>

            <form method="post" enctype="multipart/form-data" action="/FastFood_MVC_Phase1_Auth/public/admin/addFood">
                <div class="mb-2">
                    <input name="name" class="form-control" placeholder="Food name" required onblur="this.value=this.value.trim()">
                </div>

                <div class="mb-2">
                    <input name="price" type="number" step="0.01" class="form-control" placeholder="Price" required>
                </div>

                <div class="mb-2">
                    <input type="file" name="image" class="form-control" required>
                </div>

                <button class="btn btn-danger">Add Food</button>
            </form>
        </div>
    </div>

    <!-- FOOD LIST TABLE -->
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
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($foods)): ?>
                    <?php foreach ($foods as $i => $food): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <img src="/FastFood_MVC_Phase1_Auth/public/uploads/<?= htmlspecialchars($food['image']) ?>" width="50">
                        </td>
                        <td><?= htmlspecialchars($food['name']) ?></td>
                        <td>₦<?= number_format($food['price'], 2) ?></td>
                        <td>
                            <a href="/FastFood_MVC_Phase1_Auth/public/admin/editFood/<?= $food['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="/FastFood_MVC_Phase1_Auth/public/admin/deleteFood/<?= $food['id'] ?>"
                               onclick="return confirm('Delete this food?')"
                               class="btn btn-sm btn-danger">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No food added yet.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
                        <!-- EDIT FOOD MODAL -->
            <div class="modal fade" id="editFoodModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="editFoodForm" class="modal-content" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Food</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-2">
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                    <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                    </div>

                    <div class="mb-2">
                    <input type="file" name="image" class="form-control" onchange="previewImage(this)">
                    <img id="preview" class="mt-2" width="80">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save Changes</button>
                </div>
                </form>
            </div>
            </div>                                           
        </div>
    </div>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<!-- TOASTS -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">

<?php if (isset($_SESSION['food_success'])): ?>
<div id="successToast" class="toast text-bg-success border-0">
    <div class="d-flex">
        <div class="toast-body"><?= $_SESSION['food_success'] ?></div>
        <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
</div>
<?php unset($_SESSION['food_success']); endif; ?>

<?php if (isset($_SESSION['food_error'])): ?>
<div id="errorToast" class="toast text-bg-danger border-0">
    <div class="d-flex">
        <div class="toast-body"><?= $_SESSION['food_error'] ?></div>
        <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
</div>
<?php unset($_SESSION['food_error']); endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let toastEl = document.querySelector('.toast');
    if (toastEl) {
        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    }
});
</script>
<script>
    function openEdit(food) {
        document.getElementById('edit_id').value = food.id;
        document.getElementById('edit_name').value = food.name;
        document.getElementById('edit_price').value = food.price;
        document.getElementById('preview').src =
            '/FastFood_MVC_Phase1_Auth/public/uploads/' + food.image;

        new bootstrap.Modal(document.getElementById('editFoodModal')).show();
    }

    function previewImage(input) {
        document.getElementById('preview').src = URL.createObjectURL(input.files[0]);
    }

    // AJAX submit
    document.getElementById('editFoodForm').addEventListener('submit', function(e){
        e.preventDefault();
        fetch('/FastFood_MVC_Phase1_Auth/public/admin/updateFood', {
            method: 'POST',
            body: new FormData(this)
        }).then(() => location.reload());
    });
</script>
<script>
function deleteFood(id) {
    if (!confirm('Delete this food?')) return;
    fetch('/FastFood_MVC_Phase1_Auth/public/admin/deleteFood/' + id)
        .then(() => location.reload());
}
</script>