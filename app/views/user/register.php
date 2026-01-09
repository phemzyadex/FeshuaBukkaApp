<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="text-center mb-3">Register</h4>

                <form method="post" action="">
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Full Name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                    </div>
                    <div class="mb-2">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button class="btn btn-danger w-100 mt-2" type="submit">Register</button>
                </form>

                <p class="text-center mt-3">
                    Already have an account? <a href="/FastFood_MVC_Phase1_Auth/public/auth/login">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">

<?php if (isset($error)): ?>
  <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <?= htmlspecialchars($error) ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['register_success']) && $_SESSION['register_success'] === true): ?>
  <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Registration successful! Redirecting to login...
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
  <?php unset($_SESSION['register_success']); ?>
<?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show error toast if it exists
    var errorEl = document.getElementById('errorToast');
    if (errorEl) {
        var toast = new bootstrap.Toast(errorEl, { delay: 3000 });
        toast.show();
    }

    // Show success toast if it exists and redirect
    var successEl = document.getElementById('successToast');
    if (successEl) {
        var toast = new bootstrap.Toast(successEl, { delay: 3000 });
        toast.show();
        setTimeout(function() {
            window.location.href = '/FastFood_MVC_Phase1_Auth/public/auth/login';
        }, 3000);
    }
});
</script>
