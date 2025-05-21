<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php'; // Pastikan database.php sudah berisi pengaturan Medoo


class InvoiceItems {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil semua item dari 1 invoice berdasarkan invoice_id
    public function getByInvoiceId($invoice_id, $limit = null, $offset = null) {
        $where = [
            "invoice_id" => $invoice_id
        ];
    
        if ($limit !== null && $offset !== null) {
            $where["LIMIT"] = [$offset, $limit];
        }
    
        return $this->db->select("inv_items", [
            "[>]items" => ["items_id" => "id"]
        ], [
            "inv_items.id",
            "inv_items.invoice_id",
            "inv_items.qty",
            "inv_items.price",
            "inv_items.total",
            "items.name",
            "items.ref_no"
        ], $where);
    }
    

    // Insert item baru ke invoice
    public function insert($invoiceId, $itemsId, $qty, $price = 0) {
        // Ambil harga default dari item master kalau kosong
        if ($price <= 0) {
            $itemPrice = $this->db->get('items', 'price', ['id' => $itemsId]);
            $price = $itemPrice ? floatval($itemPrice) : 0;
        }

        $total = $qty * $price;

        return $this->db->insert('inv_items', [
            'invoice_id' => $invoiceId,
            'items_id' => $itemsId,
            'qty' => $qty,
            'price' => $price,
            'total' => $total
        ]);
    }

    // Update item dalam invoice
    public function update($id, $invoice_id, $items_id, $qty, $price = 0) {
        // Ambil harga dari master item jika kosong
        if ($price == 0) {
            $itemPrice = $this->db->get('items', 'price', ['id' => $items_id]);
            $price = $itemPrice ? floatval($itemPrice) : 0;
        }

        $total = $qty * $price;

        return $this->db->update('inv_items', [
            'invoice_id' => $invoice_id,
            'items_id' => $items_id,
            'qty' => $qty,
            'price' => $price,
            'total' => $total
        ], [
            'id' => $id
        ]);
    }

    // Ambil semua item di semua invoice
    public function getAll() {
        return $this->db->select('inv_items', [
            '[>]items' => ['items_id' => 'id']
        ], [
            'inv_items.id',
            'inv_items.invoice_id',
            'items.name AS item_name',
            'inv_items.qty',
            'inv_items.price',
            'inv_items.total'
        ], [
            'ORDER' => ['inv_items.id' => 'DESC']
        ]);
    }

    public function getCountByInvoiceId($invoice_id) {
        return $this->db->count("inv_items", [
            "invoice_id" => $invoice_id
        ]);
    }
    

    public function getWithLimit($limit, $offset, $invoice_id) {
        return $this->db->select('inv_items', [
            '[>]items' => ['items_id' => 'id']// Join tabel items berdasarkan items_id
        ], [
            'inv_items.id',
            'inv_items.invoice_id',
            'items.name AS item_name',
            'items.ref_no',
            'inv_items.qty',
            'inv_items.price',
            'inv_items.total'
        ], [
            'inv_items.invoice_id' => $invoice_id,  // Menyaring berdasarkan invoice_id
            'LIMIT' => [$offset, $limit],  // Menentukan limit dan offset
            'ORDER' => ['inv_items.id' => 'DESC']  // Mengurutkan berdasarkan id inv_items secara descending
        ]);
    }

    public function search($keyword, $invoice_id) {
        return $this->db->select('inv_items', [
            '[>]items' => ['items_id' => 'id']
        ], [
            'inv_items.id',
            'inv_items.invoice_id',
            'items.name',
            'inv_items.qty',
            'inv_items.price',
            'inv_items.total'
        ], [
            'AND' => [
                'inv_items.invoice_id' => $invoice_id,
                'items.name[~]' => $keyword  // Gunakan operator LIKE
            ],
            'ORDER' => ['inv_items.id' => 'DESC']
        ]);
    }
    

    // Ambil 1 data berdasarkan ID
    public function getById($id) {
        return $this->db->get('inv_items', [
            '[>]items' => ['items_id' => 'id']
        ], [
            'inv_items.id',
            'inv_items.invoice_id',
            'inv_items.items_id',
            'inv_items.qty',
            'inv_items.price',
            'inv_items.total',
            'items.name AS item_name'
        ], [
            'inv_items.id' => $id
        ]);
    }

    // Hapus item
    public function delete($id) {
        return $this->db->delete('inv_items', [
            'id' => $id
        ]);
    }
}

$invoiceItems = new InvoiceItems($db);
?>
