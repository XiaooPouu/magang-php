<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';

class PIC {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function getPIC(){
        return $this->db->select("pic", "*", []);
    }

    public function getEmail($email, $id){
        return $this->db->has("pic", [
            "email" => $email,
            "id[!]" => $id
        ]);
    }

    public function getById($id){
        return $this->db->get("pic", "*", [
            "id" => $id
        ]);
    }

    public function insert($nama, $jabatan, $email, $nomer){
        return $this->db->insert("pic", [
           "name" => $nama,
           "jabatan" => $jabatan,
           "email" => $email,
           "nomer" => $nomer 
        ]);
    }

    public function update($id, $nama, $jabatan, $email, $nomer){
        return $this->db->update("pic", [
            "name" => $nama,
            "jabatan" => $jabatan,
            "email" => $email,
            "nomer" => $nomer 
         ], [
            "id" => $id
         ]);
    }

    public function delete($id){
        return $this->db->delete("pic", [
            "id" => $id
         ]);
    }

    public function search($keyword, $status) {
    $where = [];

    if(!empty($keyword)){
        $where['OR'] = [
            'name[~]' => "%$keyword%",
            'jabatan[~]' => "%$keyword%",
            'email[~]' => "%$keyword%",
            'nomer[~]' => "%$keyword%"
        ];
    }

    // Perbaikan utama: konsistensi nilai status
    if(!empty($status)){
        // Normalisasi nilai status dari form ke nilai database
        $dbStatus = ($status === 'no use') ? 'no_use' : 'use';
        $where['status'] = $dbStatus;
    }

    return $this->db->select("pic", "*", $where);
}

    // 🔁 Tambahan: Toggle Status (use / not_use)
    public function toggleStatus($id) {
    $pic = $this->getById($id);
    if (!$pic) return false;

    // Normalisasi status untuk konsistensi
    $currentStatus = $pic['status'];
    
    // Jika ingin mengubah ke 'use'
    if ($currentStatus === 'no_use') {
        // Cek apakah sudah ada PIC lain yang 'use'
        $existingUse = $this->db->select("pic", "*", [
            "status" => "use",
            "id[!]" => $id
        ]);
        
        if (!empty($existingUse)) {
            return 'already_in_use';
        }
    }

    $newStatus = ($currentStatus === 'use') ? 'no_use' : 'use';
    
    return $this->db->update("pic", [
        "status" => $newStatus
    ], [
        "id" => $id
    ]);
}


    public function setAllToNotUse() {
    return $this->db->update("pic", [
        "status" => "not_use"
    ]);
}

}

$picModel = new PIC($db);