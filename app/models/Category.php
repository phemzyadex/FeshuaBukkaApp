<?php
class Category {
    private $db;

    public function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['db']}",
            $config['user'],
            $config['pass']
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Get all categories
    public function all() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find by ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add new category
    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
    }


    // Delete category
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id=?");
        $stmt->execute([$id]);
    }

    


    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check duplicate name
    public function existsByName($name, $excludeId = null) {
    if ($excludeId) {
        $stmt = $this->db->prepare("SELECT id FROM categories WHERE name = ? AND id != ?");
        $stmt->execute([$name, $excludeId]);
    } else {
        $stmt = $this->db->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$name]);
    }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
    }

}
