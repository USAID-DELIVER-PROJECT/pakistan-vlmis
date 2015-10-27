<?php

class Api_GeoController extends App_Controller_Base {

    public function init() {
        parent::init();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $this->_helper->layout()->disableLayout();
    }

    public function indexAction() {
        echo "Hello";
    }

    public function getMosMapDataAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $product = $this->_request->getParam('product', '');
        $level = $this->_request->getParam('level', '');

        $geoModel = new Model_Geo();
        $result = $geoModel->getMosMapData($year, $month,$province,$product,$level);

        echo Zend_Json::encode($result);
    }

    public function getAmcMapDataAction() {


        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $product = $this->_request->getParam('product', '');
        $province = $this->_request->getParam('province', '');
        $amctype = $this->_request->getParam('type', '');

        $geoModel = new Model_Geo();
        $result = $geoModel->getAmcMapData($year, $month, $product, $province, $amctype);
       
        echo Zend_Json::encode($result);
    }

    public function getReportingRateAction() {


        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');

        $geoModel = new Model_Geo();
        $return = $geoModel->getReportingRate($year, $month, $province);

        echo Zend_Json::encode($return);
    }

    public function getWastageMapDataAction() {

        $return = array();     
        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $product = $this->_request->getParam('product', '');

        $geoModel = new Model_Geo();
        $return[0] = $geoModel->getAcceptableWastages($product);
        $return[1] = $geoModel->getWastageMapData($year, $month,$province,$product);
        
        echo Zend_Json::encode($return);
    }

    public function getWastagesVsReportingAction() {


        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $product = $this->_request->getParam('product', '');

        $geoModel = new Model_Geo();
        $result = $geoModel->getWastagesVsReporting($year, $month,$province,$product);


        echo Zend_Json::encode($result);
    }

    public function getExpiryAlertAction() {
        
        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $product = $this->_request->getParam('product', '');

        $geoModel = new Model_Geo();
        $return = $geoModel->getExpiryAlert($year,$month,$province,$product);

       
        
        echo Zend_Json::encode($return);
    }

    public function getVaccineCoverageAction() {


        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $product = $this->_request->getParam('product', '');

        $geoModel = new Model_Geo();
        $return = $geoModel->getVaccineCoverage($year, $month, $province,$product);
       
        echo Zend_Json::encode($return);
    }

    public function getColdchainCapacityAction() {

        $province = $this->_request->getParam('province', '');
        $type = $this->_request->getParam('type', '');

        $geoModel = new Model_Geo();
        $return = $geoModel->getColdchainCapacity($province, $type);   
        echo Zend_Json::encode($return);
    }

    public function getColorClassesAction() {
        $id = $this->_request->getParam('id', '');

        $geoModel = new Model_Geo();
        $result = $geoModel->getColorClasses($id);

        echo Zend_Json::encode($result);
    }

    public function getMonthRangeAction() {
        $id = $this->_request->getParam('month','');
        $geoModel = new Model_Geo();
        $result = $geoModel->getMonthRange($id);
        echo Zend_Json::encode($result);
    }
    
     public function getReportingRateTrendAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        
        $geoModel = new Model_Geo();
        $return = $geoModel->getReportingRateTrend($year, $month,$district);
        
        echo Zend_Json::encode($return);
    }
       
     public function getWastagesUcsListAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $product = $this->_request->getParam('product', '');
        $district = $this->_request->getParam('district', '');
        $limit = $this->_request->getParam('limit', '');
        
        $geoModel = new Model_Geo();
        $return = $geoModel->getWastagesUcsList($year, $month,$district,$product,$limit);
        
        echo Zend_Json::encode($return);
    }
    
     public function getStockBatchDetailAction() {

        $product = $this->_request->getParam('product', '');
        $district = $this->_request->getParam('district', '');
        
        $geoModel = new Model_Geo();
        $return = $geoModel->getStockBatchDetail($district,$product);
        
        echo Zend_Json::encode($return);
    }
    
     public function getColdChainAssetDetailAction() {

        $district = $this->_request->getParam('district', '');
        $type = $this->_request->getParam('type', '');
        
        $geoModel = new Model_Geo();
        $return = $geoModel->getColdChainAssetDetail($district,$type);
        
        echo Zend_Json::encode($return);
    }
}
