<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Prevent browser caching of protected pages
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $isAdmin = $user && $user['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FastFood App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
  <div class="container">
    <a class="navbar-brand" href="/FastFood_MVC_Phase1_Auth/public/food/menu">FastFood</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <?php if(isset($_SESSION['user'])): ?>
            <?php if($_SESSION['user']['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/admin/dashboard">Dashboard</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/admin/add_food">Add Food</a>foods
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/admin/foods">Add Food</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown">
                    Manage
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item"
                            href="/FastFood_MVC_Phase1_Auth/public/admin/foods">
                            Foods
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                            href="/FastFood_MVC_Phase1_Auth/public/admin/categories">
                            Categories
                            </a>
                        </li>
                    </ul>
                </li>

            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/food/menu">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/cart">Cart
                        
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/order/track">Track Order</a>
                </li> -->
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/auth/logout">Logout (<?= $_SESSION['user']['name'] ?>)</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/auth/login">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/FastFood_MVC_Phase1_Auth/public/auth/register">Register</a>
            </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
