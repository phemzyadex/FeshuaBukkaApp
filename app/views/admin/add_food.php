<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="mb-3 text-center">Add New Food</h4>

                    <form method="post"
                          enctype="multipart/form-data"
                          action="/FastFood_MVC_Phase1_Auth/public/food/add">

                        <div class="mb-3">
                            <label class="form-label">Food Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file"
                                   name="image"
                                   class="form-control"
                                   accept="image/*"
                                   required>
                        </div>

                        <button class="btn btn-danger w-100">
                            Save Food
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
