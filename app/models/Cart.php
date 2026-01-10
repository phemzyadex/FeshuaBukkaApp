<?php
class Cart {

    public function __construct() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Add item to cart
    public function addItem($foodId, $qty = 1) {
        if (isset($_SESSION['cart'][$foodId])) {
            $_SESSION['cart'][$foodId] += $qty;
        } else {
            $_SESSION['cart'][$foodId] = $qty;
        }
    }

    // Get all cart items with food data
    public function all() {
        if (empty($_SESSION['cart'])) return [];

        $config = require '../config/database.php';
        $db = new PDO(
            "mysql:host={$config['host']};dbname={$config['db']}",
            $config['user'],
            $config['pass']
        );

        $items = [];
        foreach ($_SESSION['cart'] as $foodId => $qty) {
            $stmt = $db->prepare("SELECT * FROM foods WHERE id = ?");
            $stmt->execute([$foodId]);
            $food = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($food) {
                $food['qty'] = $qty;
                $food['subtotal'] = $qty * $food['price'];
                $items[] = $food;
            }
        }
        return $items;
    }

    // Cart total
    public function total() {
        $total = 0;
        foreach ($this->all() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    // Remove item
    public function remove($foodId) {
        unset($_SESSION['cart'][$foodId]);
    }

    // Clear cart
    public function clear() {
        $_SESSION['cart'] = [];
    }

    // Increase quantity
    public function increase($foodId) {
        if (isset($_SESSION['cart'][$foodId])) {
            $_SESSION['cart'][$foodId]++;
        }
    }

    // Decrease quantity
    public function decrease($foodId) {
        if (isset($_SESSION['cart'][$foodId])) {
            $_SESSION['cart'][$foodId]--;
            if ($_SESSION['cart'][$foodId] <= 0) {
                unset($_SESSION['cart'][$foodId]);
            }
        }
    }

}
