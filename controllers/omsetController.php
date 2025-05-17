<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/omset.php';

class OmsetController
{
    private $omsetModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->omsetModel = new Omset($db);
    }

    public function getOmsetByPeriode($periode = 'harian')
    {
        switch ($periode) {
            case 'bulanan':
                return $this->omsetModel->getOmsetBulanan();
            case 'mingguan':
                return $this->omsetModel->getOmsetMingguan();
            case 'harian':
            default:
                return $this->omsetModel->getOmsetHarian();
        }
    }
}
