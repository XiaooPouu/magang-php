<?php
require_once __DIR__ . '/../config/env.php';

class Baseurl
{
    public static function getUrlDataItems() {
        return BASE_URL . 'pages/dataItems.php';
    }

    public static function getUrlFormItems($id = null) {
    if (!empty($id)) {
        return BASE_URL . 'pages/formItems.php?id=' . $id;
    } else {
        return BASE_URL . 'pages/formItems.php';
    }
}

    public function getUrlControllerItems(){
        return BASE_URL . 'controllers/itemsController.php';
    }

    public function getUrlControllerDelete($id){
        return BASE_URL . 'controllers/itemsController.php?delete_item=' . $id;
    }

    public function getUrlDataCostumer() {
        return BASE_URL . 'pages/dataCostumer.php';
    }

    public static function getUrlFormCostumer($id = null){
        if(!empty($id)){
            return BASE_URL . 'pages/formCostumer.php?id=' . $id;
        } else {
            return BASE_URL . 'pages/formCostumer.php';
        }
    }

    public function getUrlControllerCostumer(){
        return BASE_URL . 'controllers/costumersController.php';
    }

    public function getUrlControllerDeleteCostumer($id){
        return BASE_URL . 'controllers/costumersController.php?delete_costumer=' . $id;
    }

    public function getUrlDataSupplier() {
        return BASE_URL . 'pages/dataSuppliers.php';
    }

    public static function getUrlFormSupplier($id = null){
        if(!empty($id)){
            return BASE_URL . 'pages/formSuppliers.php?id=' . $id;
        } else {
            return BASE_URL . 'pages/formSuppliers.php';
        }
    }

    public function getUrlControllerSupplier(){
        return BASE_URL . 'controllers/suppliersController.php';
    }

    public function getUrlControllerDeleteSupplier($id){
        return BASE_URL . 'controllers/suppliersController.php?delete_supplier=' . $id;
    }

    public function getUrlDataItemsCostumer() {
        return BASE_URL . 'pages/dataItems_Costumer.php';
    }

    public function getUrlFormItemsCostumer($id = null){
       If(!empty($id)){
           return BASE_URL . 'pages/formItemsCostumer.php?id_ic=' . $id;
       } else {
           return BASE_URL . 'pages/formItemsCostumer.php';
       }
    }


    public function getUrlControllerItemsCostumer(){
        return BASE_URL . 'controllers/items_costumersController.php';
    }

    public function getUrlControllerDeleteItemsCostumer($id){
        return BASE_URL . 'controllers/items_costumersController.php?delete_ic=' . $id;
    }

    public function getUrlDataItemsCostumerReset() {
        return BASE_URL . 'pages/dataItems_Costumer.php?reset=true';
    }

    public function getUrlDataInvoice() {
        return BASE_URL . 'pages/dataInvoice.php';
    }

    public function getUrlFormInvoice($id = null){
        if(!empty($id)){
            return BASE_URL . 'pages/formInvoice.php?id_inv=' . $id;
        } else {
            return BASE_URL . 'pages/formInvoice.php';
        }
    }

    public function getUrlEditInvoice($id){
        return BASE_URL . 'pages/editInvoice.php?id_inv=' . $id;
    }

    public function getUrlControllerInvoice(){
        return BASE_URL . 'controllers/invoiceController.php';
    }

    public function getUrlControllerDeleteInvoice($id){
        return BASE_URL . 'controllers/invoiceController.php?delete_invoice=' . $id;
    }

    public function getUrlDataInvoiceReset() {
        return BASE_URL . 'pages/dataInvoice.php?reset=true';
    }

    public function getUrlDetailInvoice($id){
        return BASE_URL . 'pages/dataInvoiceItems.php?id_inv=' . $id;
    }


    public function getUrlFormInvoiceItems($id_inv, $id = null) {
    $url = BASE_URL . 'pages/formInvoiceItems.php';

    if (!empty($id)) {
        $url .= '?id=' . $id . '&id_inv=' . $id_inv;
    } else {
        $url .= '?id_inv=' . $id_inv;
    }

    return $url;
}

    public function getUrlControllerInvoiceItems(){
        return BASE_URL . 'controllers/invoice_itemsController.php';
    }

    public function getUrlControllerDeleteInvoiceItems($id){
        return BASE_URL . 'controllers/invoice_itemsController.php?action=delete&id=' . $id;
    }

    public function getUrlDataBestSeller(){
        return BASE_URL . 'pages/dataBestSeller.php';
    }
    
    public function getUrlControllerBestSeller(){
        return BASE_URL . 'controllers/bestsellerController.php';
    }

    public function getUrlDataBestSellerReset(){
        return BASE_URL . 'pages/dataBestSeller.php?reset=true';
    }

    public function getUrlDataPayments(){
        return BASE_URL . 'pages/dataPayments.php';
    }

    public function getUrlFormPayments($id = null){
        if(!empty($id)){
            return BASE_URL . 'pages/formPayments.php?id_payments=' . $id;
        } else {
            return BASE_URL . 'pages/formPayments.php';
        }
    }

    public function getUrlFormPaymentsInvoice($id){
        return BASE_URL . 'pages/formPayments.php?invoice_id=' . $id;
    }

    public function getUrlControllerPayments(){
        return BASE_URL . 'controllers/paymentsController.php';
    }

    public function getUrlControllerDeletePayments($id){
        return BASE_URL . 'controllers/paymentsController.php?delete_payment=' . $id;
    }

    public function getUrlDataPaymentsReset() {
        return BASE_URL . 'pages/dataPayments.php?reset=true';
    }

    public function getUrlDataTunggakan(){
        return BASE_URL . 'pages/dataTunggakan.php';
    }

    public function getUrlControllerTunggakan(){
        return BASE_URL . 'controllers/tunggakanController.php';
    }

    public function getUrlDataDetailTunggakan($customer_id){
        return BASE_URL . 'pages/detailTunggakan.php?customer_id=' . $customer_id;
    }

    public function getUrlDataPIC(){
        return BASE_URL . 'pages/dataPIC.php';
    }

    public function getUrlDataPICReset(){
        return BASE_URL . 'pages/dataPIC.php?reset=true';
    }

    public function getURLFormPIC($id = null){
        if(!empty($id)){
            return BASE_URL . 'pages/formPIC.php?id=' . $id;
        } else {
            return BASE_URL . 'pages/formPIC.php';
        }
    }

    public function getUrlControllerPIC(){
        return BASE_URL . 'controllers/picController.php';
    }

    public function getUrlControllerDeletePIC($id){
        return BASE_URL . 'controllers/picController.php?delete_pic=' . $id;
    }

    public function getUrlControllerToggleStatusPIC($id) {
    return BASE_URL . "controllers/picController.php?toggle_status=" . $id;
    }

    public function getInformasiPerusahaan(){
        return BASE_URL . 'pages/informasiPerusahaan.php';
    }

    public function getUrlFormPerusahaan($id){
        return BASE_URL . 'pages/formPerusahaan.php?id=' . $id;
    }

    public function getUrlControllerPerusahaan(){
        return BASE_URL . 'controllers/perusahaanController.php';
    }

    public function getUrlCSS () {
        return BASE_URL . 'src/css/adminlte.css';
    }

    public function getIndex (){
        return BASE_URL . 'index.php';
    }

    public function getLogo(){
        return BASE_URL . 'src/img/AdminLTELogo.png';
    }

    public function getPrint($id){
        return BASE_URL . 'pages/invoice_print.php?id=' . $id;
    }

    public function getPrintKwitansi($id){
        return BASE_URL . 'pages/printKwitansi.php?id_payments=' . $id;
    }
}



$BaseUrl = new Baseurl();