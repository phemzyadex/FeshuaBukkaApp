<?php
class CategoryController extends Controller {

    public function __construct() {
        $this->requireAdmin();
    }

    public function index() {
        $categories = $this->model('Category')->all();
        $this->view('admin/categories', compact('categories'));
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $model = $this->model('Category');

            if ($model->existsByName($name)) {
                $_SESSION['error'] = "Category already exists!";
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/categories');
                exit;
            }

            $model->create($name);
            $_SESSION['success'] = "Category added!";
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/categories');
            exit;
        }
    }

    public function edit($id) {
        $model = $this->model('Category');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);

            if ($model->existsByName($name, $id)) {
                $_SESSION['error'] = "Another category with this name exists!";
                header('Location: /FastFood_MVC_Phase1_Auth/public/admin/categories');
                exit;
            }

            $model->update($id, $name);
            $_SESSION['success'] = "Category updated!";
            header('Location: /FastFood_MVC_Phase1_Auth/public/admin/categories');
            exit;
        }

        $category = $model->find($id);
        $this->view('admin/edit_category', compact('category'));
    }

    public function delete($id) {
        $this->model('Category')->delete($id);
        $_SESSION['success'] = "Category deleted!";
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/categories');
        exit;
    }

    public function updateCategory($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $categoryModel = $this->model('Category');

        if ($categoryModel->existsByName($name, $id)) {
            $_SESSION['category_error'] = "Category already exists!";
        } else {
            $categoryModel->update($id, $name);
            $_SESSION['category_success'] = "Category updated successfully!";
        }
    }
        header('Location: /FastFood_MVC_Phase1_Auth/public/admin/edit_category/' . $id);
        exit;
    }
}
