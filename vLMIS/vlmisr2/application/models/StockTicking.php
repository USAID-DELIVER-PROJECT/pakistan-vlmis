<?php

/**
 * Model_StockTicking
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmaleyetii@gmail.com>
 * @version    2
 */
class Model_StockTicking extends Model_Base {

    protected $_table;

    public function __construct() {
        parent::__construct();
    }

    public function uploadStockTicking() {
        $params = $this->form_values;
        //scanning_date=&scanner_id=&warehouse_id=&product_id=&batch_no=&expiry_date=&gtin=&serial_no=&barcode=&location_id=&location_name=&qty=&vvm=&inner_location=
        $str_qry = "INSERT INTO stock_ticking(ScanningDate,ScannerID,WarehouseID,ProductID,BatchNo,ExpiryDate,GTIN,SerialNumber,Barcode,LocationID,LocationName,Qty,VVM,InnerLocation) "
                . "VALUES ('".$params['scanning_date']."','".$params['scanner_id']."','".$params['warehouse_id']."','".$params['product_id']."',"
                . "'".$params['batch_no']."','".$params['expiry_date']."','".$params['gtin']."','".$params['serial_no']."','".$params['barcode']."','".$params['location_id']."','".$params['location_name']."','".$params['qty']."','".$params['vvm']."','".$params['inner_location']."')";
        $rec = $this->_em->getConnection()->prepare($str_qry);
        $rec->execute();
        return array("success" => "Data has been added successfully");
    }

}
