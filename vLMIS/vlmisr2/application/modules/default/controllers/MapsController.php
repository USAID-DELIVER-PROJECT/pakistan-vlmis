<?php

class MapsController extends App_Controller_Base {

    public function init() {
        parent::init();
        $base_url = Zend_Registry::get('baseurl');
        $this->view->headScript()->appendFile($base_url . '/js/OpenLayers-2.13/OpenLayers.js');
    }

    public function indexAction() {
        
    }

    public function mosAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

    public function amcAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/IntervalLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

    public function reportingRateAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

    public function wastagesAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

    public function wastagesReportingAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/wastagesReportingLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download2Frame.js');
    }

    public function expiryAlertAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

    public function vaccineCoverageAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

    public function coldChainCapacityAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->coldchain_type->setValue('1');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/html2canvas.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/IntervalLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

}
