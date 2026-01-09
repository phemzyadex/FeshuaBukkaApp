<?php
class Food {
    private $db;

    public function __construct() {
        $config = require '../config/database.php';
        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['db']}",
            $config['user'],
            $config['pass']
        );
    }

        public function all() {
        return $this->db->query("SELECT * FROM foods ORDER BY id DESC")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

        public function create($data, $file) {
        $image = time() . '_' . $file['image']['name'];
        move_uploaded_file($file['image']['tmp_name'], "../public/assets/uploads/$image");

        $stmt = $this->db->prepare(
            "INSERT INTO foods (name, price, image) VALUES (?, ?, ?)"
        );
        $stmt->execute([$data['name'], $data['price'], $image]);
    }

        public function existsByName($name) {
        $stmt = $this->db->prepare("SELECT id FROM foods WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetchColumn();
    }

    public function update($data, $file) {
    $sql = "UPDATE foods SET name=?, price=?";
    $params = [$data['name'], $data['price']];

    if (!empty($file['image']['name'])) {
        $image = time().'_'.$file['image']['name'];
        move_uploaded_file($file['image']['tmp_name'], "../public/uploads/$image");
        $sql .= ", image=?";
        $params[] = $image;
    }
        $sql .= " WHERE id=?";
        $params[] = $data['id'];

        $this->db->prepare($sql)->execute($params);
    }

    public function delete($id) {
        $this->db->prepare("DELETE FROM foods WHERE id=?")->execute([$id]);
    }

}
