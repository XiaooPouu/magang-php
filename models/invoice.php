<?php
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . 'config/database.php';

class Invoice {
    private $conn;

    public function __construct($db){
        $this->conn = $db;   
    }

    public function createInvoice($kode_inv, $tgl_inv, $customers_id){
        $query = "INSERT INTO invoice (kode_inv, tgl_inv, customers_id) 
                  VALUES ('$kode_inv', '$tgl_inv', '$customers_id')";
        return mysqli_query($this->conn, $query);
    }

    public function getByAll(){
        $query = "SELECT invoice.*, customers.name as nama_customer
                  FROM invoice
                  JOIN customers ON invoice.customers_id = customers.id
                  ORDER BY invoice.id_inv DESC";

        $result = mysqli_query($this->conn, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    public function getById($id_inv){
        $sql = "SELECT * FROM invoice WHERE id_inv = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_inv);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id_inv, $kode_inv, $tgl_inv, $customers_id){
        $query = "UPDATE invoice SET kode_inv = ?, tgl_inv = ?, customers_id = ? WHERE id_inv = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$kode_inv, $tgl_inv, $customers_id, $id_inv]);
    }

    public function delete($id_inv) {
        $sql = "DELETE FROM invoice WHERE id_inv = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_inv);
        return $stmt->execute();
    }
}
