<?php
class User {
    private $db;
    public function __construct() {
        $c=require '../config/database.php';
        $this->db=new PDO("mysql:host={$c['host']};dbname={$c['db']}",$c['user'],$c['pass']);
    }
    public function register($name,$email,$pass){
        $h=password_hash($pass,PASSWORD_DEFAULT);
        $stmt=$this->db->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'user')");
        return $stmt->execute([$name,$email,$h]);
    }
    public function login($email,$pass){
        $stmt=$this->db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);
        return $user && password_verify($pass,$user['password'])?$user:false;
    }
    
    public function exists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Count all users
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return (int) $stmt->fetchColumn();
    }

     // Check if phone exists
    public function existsByPhone($phone) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE phone=?");
        $stmt->execute([$phone]);
        return $stmt->fetchColumn();
    }

    // Create new user
    public function create($name, $email, $phone, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'user')"
        );
        $stmt->execute([$name, $email, $phone, $hashed]);
    }
}
