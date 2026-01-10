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
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Get all foods
    public function all() {
        return $this->db->query("SELECT * FROM foods ORDER BY id DESC")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }
    // Check duplicate by name
    public function existsByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM foods WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // returns the row if exists, false otherwise
    }

    // Check duplicate by name
    public function findByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM foods WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add new food
    public function create($name, $price, $file) {
        // Handle upload
        $image = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $image = uniqid('food_', true) . '.' . $ext;

            $uploadDir = realpath(__DIR__ . '/../../public/uploads');
            if ($uploadDir === false) mkdir(__DIR__ . '/../../public/uploads', 0755, true);
            $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $image;

            move_uploaded_file($file['tmp_name'], $uploadPath);
        }

        $stmt = $this->db->prepare("INSERT INTO foods (name, price, image) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $image]);
    }

    // Update food
    public function update($id, $name, $price, $file = null) {
        $sql = "UPDATE foods SET name=?, price=?";
        $params = [$name, $price];

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $image = uniqid('food_', true) . '.' . $ext;

            $uploadDir = realpath(__DIR__ . '/../../public/uploads');
            if ($uploadDir === false) mkdir(__DIR__ . '/../../public/uploads', 0755, true);
            $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $image;

            move_uploaded_file($file['tmp_name'], $uploadPath);

            $sql .= ", image=?";
            $params[] = $image;
        }

        $sql .= " WHERE id=?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    // Delete food
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM foods WHERE id=?");
        $stmt->execute([$id]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM foods WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        public function paginate($page, $limit)
    {
        $offset = ($page - 1) * $limit;

        $total = $this->db
            ->query("SELECT COUNT(*) FROM foods")
            ->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT *
            FROM foods
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'pagination' => [
                'current' => $page,
                'pages'   => ceil($total / $limit)
            ]
        ];
    }

}
