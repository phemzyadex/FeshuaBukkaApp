<?php
class Cart {

    public function add($food) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$food['id']])) {
            $_SESSION['cart'][$food['id']]['qty']++;
        } else {
            $_SESSION['cart'][$food['id']] = [
                'id' => $food['id'],
                'name' => $food['name'],
                'price' => $food['price'],
                'qty' => 1
            ];
        }
    }

    public function all() { 
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : []; 
    }

    public function total() {
        $total = 0;
        foreach ($this->all() as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }

    public function clear() {
        unset($_SESSION['cart']);
    }
}
