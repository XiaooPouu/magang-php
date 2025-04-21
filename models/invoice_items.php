<?php
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . 'config/database.php';

class InvoiceItems {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // ✅ Ambil semua item dari 1 invoice berdasarkan invoice_id
    public function getByInvoiceId($invoiceId) {
        $stmt = $this->conn->prepare("
            SELECT 
                ii.id, 
                i.ref_no AS kode_item,
                i.name AS nama_item,
                ii.qty, 
                ii.price, 
                ii.total
            FROM inv_items ii
            JOIN items i ON ii.items_id = i.id
            WHERE ii.invoice_id = ?
        ");
        $stmt->bind_param("i", $invoiceId);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $data = [];
    
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    
        return $data;
    }
    

    // ✅ Insert item baru ke invoice
    public function insert($invoiceId, $itemsId, $qty, $price = 0) {
        $invoiceId = (int)$invoiceId;
        $itemsId   = (int)$itemsId;
        $qty       = (int)$qty;
        $price     = floatval($price);

        // Ambil harga default dari item master kalau kosong
        if ($price <= 0) {
            $stmt = $this->conn->prepare("SELECT price FROM items WHERE id = ?");
            $stmt->bind_param("i", $itemsId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $price = $row ? floatval($row['price']) : 0;
        }

        $total = $qty * $price;

        $stmt = $this->conn->prepare("
            INSERT INTO inv_items (invoice_id, items_id, qty, price, total)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiidd", $invoiceId, $itemsId, $qty, $price, $total);
        return $stmt->execute();
    }

    // ✅ Update item dalam invoice
    public function update($id, $invoice_id, $items_id, $qty, $price = 0) {
        if ($price == 0) {
            // Ambil harga dari master item jika kosong
            $itemStmt = $this->conn->prepare("SELECT price FROM items WHERE id = ?");
            $itemStmt->bind_param("i", $items_id);
            $itemStmt->execute();
            $itemResult = $itemStmt->get_result()->fetch_assoc();
            $price = $itemResult['price'] ?? 0;
        }
    
        $total = $qty * $price;
    
        $stmt = $this->conn->prepare("UPDATE inv_items SET invoice_id = ?, items_id = ?, qty = ?, price = ?, total = ? WHERE id = ?");
        $stmt->bind_param("iiiidd", $invoice_id, $items_id, $qty, $price, $total, $id);
        return $stmt->execute();
    }
    

    // ✅ Ambil semua item di semua invoice
    public function getAll() {
        $query = "
            SELECT ii.id, ii.invoice_id, i.name AS item_name, ii.qty, ii.price, ii.total
            FROM inv_items ii
            JOIN items i ON ii.items_id = i.id
            ORDER BY ii.id DESC
        ";
        $result = $this->conn->query($query);
        $data = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // ✅ Ambil 1 data berdasarkan ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM inv_items WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // ✅ Hapus item
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM inv_items WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
