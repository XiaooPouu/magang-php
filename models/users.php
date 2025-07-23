<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
Class User {
    private $db;

    public function __construct($db){
        $this->db =$db;
    }

    // function mengambil data user by username
    public function getByUsername($username){
        return $this->db->get("users", "*", [
            "username" => $username
        ]);
    }

    public function getByEmail($email){
        return $this->db->get("users", "*",[
            "email" => $email
        ]);
    }

    public function getByValidToken($token){
        return $this->db->get("users", "*",[
            "reset_token" => $token,
            "reset_token_expiry[>]" => date('Y-m-d H:i:s')
        ]);
    }

    public function setResetToken($token, $expiry, $email){
        return $this->db->update("users", [
            "reset_token" => $token,
            "reset_token_expiry" => $expiry
        ], 
        [
            "email" => $email
        ]);
    }

    public function updatePasswordByToken($token, $newPassword){
        return $this->db->update("users", [
            "password" => $newPassword,
            "reset_token" => null,
            "reset_token_expiry" => null
        ], [
            "reset_token" => $token
        ]);
    }
}

$usersModel = new User($db);