<?php

class Reports_InventoryManagementController extends App_Controller_Base {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        // action body
    }

    public function nationalReportAction() {
        $this->_helper->layout->setLayout('reports');
        $item_pack_sizes = new Model_ItemPackSizes();
        $item = $item_pack_sizes->productsReport();

        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $locations = new Model_Locations();
            $lct = $locations->nationalReport();
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SNASUM';
            $this->view->report_title = 'National Report for';
            $this->view->actionpage = '';
            $this->view->parameters = 'T';
            $this->view->parameter_width = '40%';
            $this->view->location = $lct;
            $this->view->in_item = 1;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'N';
            $this->view->in_type = 'N';
            $this->view->stk = $stk;
            $this->view->actionpage = 'national-report';
        } else {
            $warehouse_data = new Model_WarehousesData();
            //$year = $warehouse_data->getMaxYear();
            $year = date("Y");
            //$warehouse_data->getMaxMonth($year);
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }

            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();

            $locations = new Model_Locations();
            $lct = $locations->nationalReport();
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SNASUM';
            $this->view->report_title = 'National Report for';
            $this->view->actionpage = 'national-report';
            $this->view->parameters = 'T';
            $this->view->parameter_width = '40%';
            $this->view->location = $lct;
            $this->view->in_item = 1;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'N';
            $this->view->in_type = 'N';
            $this->view->stk = $stk;
        }
        $this->view->item_id = $item;
        $this->view->geo_level_id = 1;
    }

    public function provincialReportAction() {
        $this->_helper->layout->setLayout('reports');
        $item_pack_sizes = new Model_ItemPackSizes();
        $item = $item_pack_sizes->productsReport();

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $warehouse_data = new Model_WarehousesData();
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SPROVINCEREPORT';
            $this->view->report_title = 'Province/Region Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'TS01I';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'P';
            $this->view->in_type = 'P';
            $this->view->sel_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $this->view->location = $lct;
            $this->view->in_item = $this->_request->prod_sel;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->actionpage = 'provincial-report';
        } else {
            $warehouse_data = new Model_WarehousesData();
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            //$warehouse_data->getMaxMonth($year);
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SPROVINCEREPORT';
            $this->view->report_title = 'Province/Region Report';
            $this->view->actionpage = 'provincial-report';
            $this->view->parameters = 'TS01I';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'P';
            $this->view->in_type = 'P';
            $this->view->sel_item = 28;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $this->view->location = $lct;
            $this->view->in_item = 28;
            $this->view->in_stk = 1;
            $this->view->in_prov = 1;
            $this->view->in_dist = 1;
            $this->view->counter = 1;
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        if (!empty($this->_request->prod_sel)) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {
            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }
        $this->view->item_id = $item;
        $this->view->geo_level_id = 2;
    }

    public function stockAvailabilityReportAction() {
        $this->_helper->layout->setLayout('reports');
        $q_str_pro = '';
        $q_str_prov = '';
        $sel_prov = '';
        $warehouses_data = new Model_WarehousesData();
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();
        $stakeholders = new Model_Stakeholders();
        $request_data = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $this->view->report_id = 'SNASUMSTOCKLOC';
        $this->view->report_title = 'Stock Availability Report';
        $this->view->parameters = 'TS01P01I';
        $this->view->cwh_name = '';
        $this->view->num_item2 = '';
        $this->view->total = 0;
        $this->view->cwhtotal = 0;
        $this->view->ppiutotal = 0;
        $this->view->disttotal = 0;
        $this->view->old1 = '';
        $this->view->actionpage = 'stock-availability-report';
        // $this->view->month_sel = $this->_request->month_sel;
        // $this->view->year_sel = $this->_request->year_sel;
        $this->view->sel_item = $this->_request->prod_sel;
        $this->view->sel_stk = $this->_request->stk_id;
        $this->view->prov_sel = $this->_request->prov_sel;

        $lct = $locations->devisionalReport();
        $this->view->location = $lct;

        $warehouses_data->temp = $request_data;
        $rs_items = $warehouses_data->generateStockAvailabilityReport();
        $this->view->rs_items = $rs_items;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->prod_sel)) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->product_name = $item_pack_sizes->getProductName();
        }

        if ($this->_request->prov_sel == 'all') {
            $this->view->location_name = "All";
        } else if ($this->_request->prov_sel == '') {
            $this->view->location_name = "Punjab";
        } else {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        }

        if ($this->_request->stk_id == 'all' || $this->_request->stk_id == "") {
            $this->view->stakeholder_name = "All";
        } elseif (!empty($this->_request->stk_id)) {
            $stakeholders->form_values['pk_id'] = $this->_request->stk_id;
            $this->view->stakeholder_name = $stakeholders->getStakeholderName();
        }
    }

    public function divisionalReportAction() {
        $this->_helper->layout->setLayout('reports');
        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'Divisional Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'PIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type_1 = 'V';
            $this->view->in_type = 'P';
            $this->view->sel_item = 1;
            $locations = new Model_Locations();
            $this->view->sel_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $lct = $locations->devisionalReport();
            $locations->province_id = $this->_request->prov_sel;
            $lct2 = $locations->devisionalLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 1;
            $this->view->in_stk = 0;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->actionpage = 'divisional-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();

            $this->view->item_id = $item;
            $warehouse_data = new Model_WarehousesData();
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'Divisional Report';
            $this->view->actionpage = 'divisional-report';
            $this->view->parameters = 'PIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'P';
            $this->view->in_type_1 = 'V';
            $this->view->in_type = 'P';
            $this->view->sel_item = 1;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->province_id = 1;
            $lct2 = $locations->devisionalLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 1;
            $this->view->in_stk = 0;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
        }
    }

    public function districtReportAction() {
        $this->_helper->layout->setLayout('reports');
        $sess_prov_id = App_Auth::getInstance()->getProvinceId();
        $sess_dist_id = App_Auth::getInstance()->getDistrictId();
        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'District Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '95%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $locations = new Model_Locations();
            $this->view->sel_item = $this->_request->prod_sel;
            $this->view->in_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $lct2 = $locations->districtLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = $this->_request->prov_sel;

            $this->view->in_stk = 0;
            $this->view->in_prov = $this->_request->prov_sel;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->actionpage = 'district-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();

            $this->view->item_id = $item;
            $warehouse_data = new Model_WarehousesData();
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'District Report';
            $this->view->actionpage = 'district-report';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '95%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $this->view->sel_item = 28;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = '1';
            $lct2 = $locations->districtLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = 1;
            $this->view->in_item = 28;
            $this->view->in_stk = 0;
            $this->view->in_prov = 1;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {
            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }
        $this->view->geo_level_id = 4;
    }

    public function tehsilReportAction() {
        $this->_helper->layout->setLayout('reports');
        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'TEHSILREPORT';
            $this->view->report_title = 'Tehsil Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $this->view->in_type_1 = 'H';
            $this->view->sel_item = 1;
            $locations = new Model_Locations();
            $this->view->sel_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $lct2 = $locations->tehsilLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->sel_item = $this->_request->prod_sel;
            $this->view->sel_dist = $this->_request->dist_id;
            $this->view->dist_sel = $this->_request->dist_id;
            if ($this->_request->dist_id) {
                $this->view->in_dist = $this->_request->dist_id;
                $this->view->dist_sel = $this->_request->dist_id;
            } else {
                $this->view->in_dist = $this->_request->prov_sel;
                $this->view->dist_sel = 0;
            }
            $this->view->in_item = $this->_request->prod_sel;
            $this->view->in_stk = 1;
            $this->view->in_prov = $this->_request->prov_sel;
            //  $this->view->in_dist = $this->_request->dist_id;
            $this->view->counter = 1;
            $this->view->actionpage = 'tehsil-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $warehouse_data = new Model_WarehousesData();
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'TEHSILREPORT';
            $this->view->report_title = 'Tehsil Report';
            $this->view->actionpage = 'tehsil-report';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $this->view->in_type_1 = 'H';
            $this->view->sel_item = 28;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = '1';
            $locations->form_values['district_id'] = '';
            $lct2 = $locations->tehsilLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = '1';
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 28;
            $this->view->in_stk = 1;
            $this->view->in_prov = 1;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
        }

        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {

            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {

            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }
        $this->view->geo_level_id = 5;
    }

    public function ucReportAction() {
        $this->_helper->layout->setLayout('reports');

        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $locations = new Model_Locations();

            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->getStakholder();
            $lct = $locations->devisionalReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'UCREPORT';
            $this->view->report_title = 'Union Council Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '90%';
            $sel_item = $this->_request->prod_sel;
            // $sel_item = $this->_request->item_sel;
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'U';
            $this->view->in_type = 'D';
            $this->view->sel_item = $this->_request->stkid;
            $this->view->in_type_1 = 'U';
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->sel_item = $this->_request->prod_sel;
            //  $this->view->sel_item = $this->_request->item_sel;
            $this->view->stk = $stk;
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $locations->form_values['tehsil_id'] = $this->_request->teh_id;
            $locations->form_values['uc_id'] = $this->_request->uc_id;
            $lct2 = $locations->ucLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;

            $locations->form_values['geo_level_id'] = 5;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $tehsil = $locations->getLocationsByLevelByDistrict();
            $this->view->combo_teh = $tehsil;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['parent_id'] = $this->_request->teh_id;
            $uc = $locations->getLocationsByLevelByTehsil();
            $this->view->combo_uc = $uc;
            $this->view->in_item = $this->_request->prod_sel;
            $this->view->in_stk = 1;
            $this->view->in_prov = $this->_request->prov_sel;
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->counter = 1;
            $this->view->sel_dist = $this->_request->dist_id;
            $this->view->sel_teh = $this->_request->teh_id;
            $this->view->sel_uc = $this->_request->uc_id;
            $item_pack_sizes->form_values['pk_id'] = $sel_item;
            $item_name = $item_pack_sizes->getProductName();
            $this->view->item_name = $item_name;
            $locations->form_values['pk_id'] = $this->_request->dist_id;
            $location_name = $locations->getLocationName();
            $this->view->district_name = $location_name;
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $location_name = $locations->getLocationName();
            $this->view->province_name = $location_name;
            $locations->form_values['pk_id'] = $this->_request->teh_id;
            $location_name = $locations->getLocationName();
            $this->view->tehsil_name = $location_name;
            $this->view->actionpage = 'uc-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();

            $this->view->item_id = $item;
            $warehouse_data = new Model_WarehousesData();
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'UCREPORT';
            $this->view->report_title = 'Union Council Report';
            $this->view->actionpage = 'uc-report';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '90%';
            $this->view->in_col = 'SPIT';
            $this->view->in_rg = 'U';
            $this->view->in_type = 'D';
            $this->view->sel_item = 28;
            $this->view->in_type_1 = 'U';
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->getStakholder();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = "";
            $locations->form_values['district_id'] = "";
            $locations->form_values['tehsil_id'] = "";
            $locations->form_values['uc_id'] = "";
            $lct2 = $locations->ucLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = '1';
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 28;
            $this->view->in_stk = 0;
            $this->view->in_prov = 1;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $item_pack_sizes->form_values['pk_id'] = 28;
            $item_name = $item_pack_sizes->getProductName();
            $this->view->item_name = $item_name;
            $locations->form_values['pk_id'] = 0;
            $location_name = $locations->getLocationName();
            $this->view->location_name = $location_name;
            $locations->form_values['pk_id'] = 1;
            $location_name = $locations->getLocationName();
            $this->view->province_name = $location_name;
            if (empty($this->_request->dist_id)) {
                $this->view->district_name = "All";
            }
            if (empty($this->_request->teh_id)) {
                $this->view->tehsil_name = "All";
            }
            if (empty($this->_request->uc_id)) {
                $this->view->uc_name = "All";
            }
        }
        $this->view->geo_level_id = 6;
    }

    function prov2distAction() {
        $this->_helper->layout->disableLayout();
        $type = $this->_request->combo;
        $locations = new Model_Locations();
        if ($type == '4') {

            if (isset($this->_request->dist_sel) && !empty($this->_request->dist_sel)) {
                $dist_id = $this->_request->dist_sel;
                $locations->form_values['geo_level_id'] = 5;
                $locations->form_values['district_id'] = $dist_id;
                $result = $locations->getLocationsByLevelByDistrict();
                $this->view->result = $result;
            }
        }

        if ($type == '2') {
            if (isset($this->_request->prov_sel) && !empty($this->_request->prov_sel)) {
                $prov_id = $this->_request->prov_sel;
                $locations->form_values['geo_level_id'] = 4;
                $locations->form_values['province_id'] = $prov_id;
                $result = $locations->getLocationsByLevelByProvince();
                $this->view->result = $result;
            }
        }

        if ($type == '5' && !empty($this->_request->teh_sel)) {
            $teh_sel = $this->_request->teh_sel;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['parent_id'] = $teh_sel;
            $result = $locations->getLocationsByLevelByTehsil();
            $this->view->result = $result;
        }

        if ($type == '5' && !empty($this->_request->dist_sel)) {
            $dist_sel = $this->_request->dist_sel;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['district_id'] = $dist_sel;
            $result = $locations->getLocationsByLevelByDistrict();
            $this->view->result = $result;
        }
    }

    public function provincialYearlyReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'PROVINCIALWAREHOUSE';
        $this->view->report_title = 'Provincial Yearly Report';
        $this->view->actionpage = 'provincial-yearly-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $locations = new Model_Locations();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");

            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }


        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        } else {
            $prov_sel = 1;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        }

        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }

        $this->view->prov_sel = $prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }

        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;
        } else {
            $this->view->in_dist = '';
            $this->view->sel_dist = '';
        }


        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;
///echo $year;

        $end_date1 = $year . '-' . ($month) . '-01';
        //   $end_date1 = 2015-1-01;

        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        // App_Controller_Functions::pr($period);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function districtsYearlyReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'DISTRICTWAREHOUSE';
        $this->view->report_title = 'District Yearly Report';
        $this->view->actionpage = 'districts-yearly-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $location = new Model_Locations();
        $warehouses_data = new Model_WarehousesData();
        if ($this->_request->prov_sel) {
            $province = $this->_request->prov_sel;
        } else {
            $province = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->getLocations();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $this->view->location_id = $location_name;
        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        if (!empty($this->_request->prod_sel)) {
            $this->view->sel_prod = $this->_request->prod_sel;
        } else {
            $this->view->sel_prod = 28;
        }
        if (!empty($this->_request->prov_sel)) {
            $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;
        } else {
            $this->view->prov_sel = 1;
        }

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {

            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {

            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }
        $locations = new Model_Locations();
        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function wastagesReportAction() {

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'WASTAGESREPORTING';
        $this->view->report_title = 'Reporting and Wastages Rate';
        $this->view->actionpage = 'wastages-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $location = new Model_Locations();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        if (!empty($this->_request->level)) {
            $this->view->sel_level = $level_sel = $this->_request->level;
        } else {
            $this->view->sel_level = $level_sel = '2';
        }


        if (!empty($this->_request->prov_sel)) {
            $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;
        } else {
            $this->view->prov_sel = $prov_sel = "1";
        }
        $location->form_values['level_id'] = $level_sel;
        $location->form_values['province_id'] = $prov_sel;
        $location_name = $location->getLocationWastages();
        $this->view->item_id = $item;
        $this->view->location_id = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");

            $month = date("m", strtotime("-1 month"));

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->prod_sel)) {
            $this->view->sel_prod = $this->_request->prod_sel;
            $this->view->sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_prod = 6;
            $this->view->sel_item = 6;
        }


        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));
        //$end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($endDate1))));
        //$start_date = date('Y-m-d', strtotime("-5 month", strtotime($endDate1)));
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;

        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function shipmentReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SHIPMENTREPORT';
        $this->view->report_title = 'Warehouse Shipment Report';
        $this->view->actionpage = 'shipment-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
        } else {
            $this->view->province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function centralProvincialWarehouseAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'CENTRALWAREHOUSE';
        $this->view->report_title = 'Federal/Provincial Warehouse Report';
        $this->view->actionpage = 'central-provincial-warehouse';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 159;
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Issue\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function reportedProvincesAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDPROVINCES';
        $this->view->report_title = 'Consumption Data Reporting Status (Province-wise)';
        $this->view->actionpage = 'reported-provinces';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));

        if ($this->_request->prov_sel == 'all') {
            $this->view->location_name = "All";
        } else if ($this->_request->prov_sel == '') {
            $this->view->location_name = "Punjab";
        } else {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        }

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function stockOnHandAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SOH';
        $this->view->report_title = 'Provincial/Regional Store-Stock on Hand';
        $this->view->actionpage = 'stock-on-hand';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;

        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m") - 1;
            } else {
                $month = date("m") - 2;
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function stockAnalysisDistrictReportAction() {
        $form = new Form_Reports_MonthYear();
        $this->_helper->layout->setLayout('reports');
        $this->view->report_title = 'Monthly Stock Analysis Report';

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $warehouse_data = new Model_WarehousesData();
                $warehouse_data->form_values = $form->getValues();
                $this->view->xml_store = $warehouse_data->getStockAnalysisDistrictWiseReport();
            }
        }

        $this->view->form = $form;
    }

    public function monthOfStockAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'MOS';
        $this->view->report_title = 'Provincial/Regional Store-Months of Stock';
        $this->view->actionpage = 'month-of-stock';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;

        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m") - 1;
            } else {
                $month = date("m") - 2;
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function consumptionAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'CONSUMPTION';
        $this->view->report_title = 'Provincial/Regional Store-Consumption';
        $this->view->actionpage = 'consumption';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;

        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function amcAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'AMC';
        $this->view->report_title = 'Provincial/Regional Store-Average Monthly Consumption';
        $this->view->actionpage = 'amc';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function reportedDistrictsByUcAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Consumption Data Reporting Status (By UC)';
        $this->view->actionpage = 'reported-districts-by-uc';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (!empty($this->_request->prov_sel)) {
            $province = $this->_request->prov_sel;
        } else {
            $province = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            // $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }

        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;
        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function reportedDistrictsByUserAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Consumption Data Reporting Status (By User)';
        $this->view->actionpage = 'reported-districts-by-user';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (!empty($this->_request->prov_sel)) {
            $province = $this->_request->prov_sel;
        } else {
            $province = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $province;
        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;
        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function reportedDistrictByFacilityAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Consumption Data Reporting Status (By Facility)';
        $this->view->actionpage = 'reported-district-by-facility';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (!empty($this->_request->prov_sel)) {
            $province = $this->_request->prov_sel;
        } else {
            $province = 1;
        }

        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;
        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function nonReportedDistrictsByFacilityAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->actionpage = 'non-reported-districts-by-facility';
        $this->view->parameters = 'TSP';
        $this->view->parameter_width = '100%';

        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if ($this->_request->prov_sel) {
            $province = $this->_request->prov_sel;
        } else {
            $province = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;
        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;
        $this->view->dist_id = $dist_sel = $this->_request->dist_id;

        if (isset($this->_request->prov_sel) && !empty($this->_request->prov_sel)) {
            $this->view->prov_sel = $prov_sel = $this->_request->prov_sel;
        } else {
            $this->view->prov_sel = $prov_sel = 1;
        }
        if (isset($this->_request->dist_id)) { //&& !empty($this->_request->dist_id)
            $this->view->dist_id_hidden = $this->_request->dist_id;
            $this->view->sel_dist = $dist_id = $this->_request->dist_id;
        } else {
            $this->view->dist_id_hidden = '33';
            $this->view->sel_dist = $dist_id = '33';
        }

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if (!empty($this->_request->prov_sel)) {
            $this->view->location_name = "Punjab";
        } else {
            $location->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $location->getLocationName();
        }

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = '1';
        $district = $locations->getLocationsByLevelByProvince();
        $this->view->district = $district;
    }

    public function nonReportedDistrictsByUcAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'Non-reported EPI Centers Report for';
        $this->view->actionpage = 'non-reported-districts-by-uc';
        $this->view->parameters = 'TSP';
        $this->view->parameter_width = '100%';

        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if ($this->_request->prov_sel) {
            $this->view->prov_sel = $province = $this->_request->prov_sel;
        } else {
            $this->view->prov_sel = $province = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->prov_sel)) {
            $this->view->location_name = "Punjab";
        } else {
            $locations = new Model_Locations();
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        }

        //$this->view->dist_sel = $dist_sel = $this->_request->dist_id;
        if (isset($this->_request->dist_id)) { //&& !empty($this->_request->dist_id)
            $this->view->dist_id_hidden = $this->_request->dist_id;
            $this->view->sel_dist = $dist_sel = $this->_request->dist_id;
        } else {
            $this->view->dist_id_hidden = 33;
            $this->view->sel_dist = $dist_sel = 33;
        }

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = '1';
        $district = $locations->getLocationsByLevelByProvince();
        $this->view->district = $district;
    }

    public function provinceToWarehouseAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $locations = new Model_Locations();
        $locations->form_values = $form_values;
        $result = $locations->getProvinceToWarehouse();
        $this->view->result = $result;
    }

    public function ajaxCombosAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $this->view->form_values = $form_values;
    }

    public function transactionDetailAction() {
        $this->_helper->layout->setLayout('print-report');
        $param = explode('|', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    public function reportedUcAction() {
        $this->_helper->layout->setLayout('print-report');
        $param = explode('-', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    public function reportedUcUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->layout->setLayout('reports');
        $param = explode('-', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    public function reportedWarehouseListAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->layout->setLayout('reports');
        $param = explode('-', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    public function popupDataEntryAction() {
        // $this->_helper->layout->disableLayout();
        $this->_helper->layout->setLayout("print-report");
        $param = explode('|', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    public function popupDataEntryFacilityAction() {
        // $this->_helper->layout->disableLayout();
        $this->_helper->layout->setLayout("print-report");
        $param = $this->_request->getParam('param', '');
        $this->view->param = $param;
    }

    public function productWiseDistrictsYearlyReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'PRODUCTDISTRICTWAREHOUSE';
        $this->view->report_title = 'Product Wise Districts Yearly Report';
        $this->view->actionpage = 'product-wise-districts-yearly-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }


        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        $locations = new Model_Locations();
        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        } else {
            $prov_sel = 1;
            $this->view->location_name = "Punjab";
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        }
        $this->view->prov_sel = $prov_sel;


        $this->view->dist_sel = $this->_request->dist_id;
        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;
        } else {
            $this->view->in_dist = 33;
            $this->view->sel_dist = 33;
        }
        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function dataEntryStatusAction() {
        $this->view->report_id = 'DataEntryStatus';
        $this->view->parameters = 'TS01I';

        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $stock_master = new Model_StockMaster();
        $role_id = $this->_identity->getRoleId();

        if ($this->_request->wh_type) {
            $stock_master->form_values['wh_type'] = $this->_request->wh_type;
        } else {
            $stock_master->form_values['role_id'] = $role_id;
        }
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
            $stock_master->form_values['province'] = $this->_request->province;
        } else {
            $this->view->province = $this->_identity->getProvinceId();
            $stock_master->form_values['province'] = $this->_identity->getProvinceId();
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
            $stock_master->form_values['district'] = $this->_request->district;
        } else {
            $district_id = $this->_identity->getDistrictId($this->_identity->getIdentity());
            $this->view->district = $district_id;
            $stock_master->form_values['district'] = $district_id;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
            $stock_master->form_values['tehsil'] = $this->_request->tehsil;
        } else {
            $tehsil_id = $this->_identity->getTehsilId($this->_userid);

            $this->view->tehsil = $tehsil_id;
            $stock_master->form_values['tehsil'] = $tehsil_id;
        }
        $data_arr = $stock_master->getDataEntryStatusFederal();
        $data_arr1 = $stock_master->getDataEntryStatusProvincial();
        $data_arr2 = $stock_master->getDataEntryStatusDistrict();
        $data_arr3 = $stock_master->getDataEntryStatusTehsil();

        $this->view->data = $data_arr;
        $this->view->data1 = $data_arr1;
        $this->view->data2 = $data_arr2;
        $this->view->data3 = $data_arr3;

        $this->view->role_id = $this->_identity->getRoleId();

        $this->view->main_heading = "Data Entry Status";
        $this->view->report_title = "Data Entry Status";

        $this->view->search_form = $search_form;
        $this->view->form_values = $form_values;


        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            if ($role_id == 3) {
                $wh_type_role = 1;
            } else if ($role_id == 4) {
                $wh_type_role = 2;
            } else if ($role_id == 6) {
                $wh_type_role = 4;
            } else if ($role_id == 7) {
                $wh_type_role = 5;
            } else if ($role_id == 17) {
                $wh_type_role = 1;
            }

            $this->view->wh_type = $wh_type = $wh_type_role;
        }
        $this->view->actionpage = 'data-entry-status';
    }

    public function batchTrackingAction() {
        $form = new Form_BatchTracking();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $product_id = $this->_request->getPost('product');
                $from_wh = $this->_request->getPost('from_wh');
                $to_wh = $this->_request->getPost('to_wh');

                $form->product->setValue($product_id);
                $stock_master = new Model_StockMaster();
                $result1 = $stock_master->getWarehousesByProduct($product_id);
                if ($result1 && count($result1) > 0) {
                    foreach ($result1 as $whs) {
                        $result1set[$whs['pkId']] = $whs['warehouseName'];
                    }
                }
                $form->from_wh->setMultiOptions($result1set);
                $form->from_wh->setValue($from_wh);
                $result2 = $stock_master->getToWarehousesByProduct($from_wh, $product_id);
                if ($result2 && count($result1) > 0) {
                    foreach ($result2 as $whs) {
                        $result2set[$whs['pkId']] = $whs['warehouseName'];
                    }
                }
                $form->to_wh->setMultiOptions($result2set);
                $form->to_wh->setValue($to_wh);

                $stock_master->form_values = $form->getValues();
                $result3 = $stock_master->getAllWarehouseBatches();

                $this->view->result = $result3;
            }
        }

        $this->view->form = $form;
    }

    public function ajaxGetWarehousesByProductAction() {
        $this->_helper->layout->disableLayout();
        $product_id = $this->_request->getParam('product');

        $stock_master = new Model_StockMaster();
        $result = $stock_master->getWarehousesByProduct($product_id);

        $this->view->result = $result;
    }

    public function ajaxGetToWarehousesByProductAction() {
        $this->_helper->layout->disableLayout();
        $product_id = $this->_request->getParam('product');
        $from_wh = $this->_request->getParam('from_wh');

        $stock_master = new Model_StockMaster();
        $result = $stock_master->getToWarehousesByProduct($from_wh, $product_id);

        $this->view->result = $result;
    }

    public function ajaxGetBatchDataAction() {
        $this->_helper->layout->disableLayout();

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $this->_request->getParams();
        $issue_result = $stock_master->getBatchData();
        $batch = $this->_em->getRepository("StockBatch")->find($this->_request->getParam('batch_id'));
        $stock_master->form_values['batch_number'] = $batch->getNumber();
        $receive_result = $stock_master->getReceiveBatchData();

        $this->view->receive_result = $receive_result;
        $this->view->issue_result = $issue_result;
    }

    public function ajaxLinkReceiveWithIssueAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $this->_request->getParams();
        $stock_master->linkReceiveWithIssue();
    }

    public function batchShelfLifeGraphAction() {
        $form = new Form_BatchShelfLife();

        $this->view->form = $form;
        // $this->_helper->layout->setLayout('reports');

        $this->view->report_id = 'REPORTEDPROVINCES';
        $this->view->report_title = 'Vaccine Shelf Life';
        $this->view->actionpage = 'reported-provinces';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;


        if ($this->_request->isPost()) {
            $this->view->combos = $this->_request->getPost();
            $form_values = $this->_request->getPost();
            // App_Controller_Functions::pr($form_values);
            $this->view->office = $form_values['office'];
            $this->view->warehouse_id = $form_values['warehouse'];
            $this->view->warehouse_hidden = $form_values['warehouse'];

            $this->view->combo1_hidden = $form_values['combo1'];
            $this->view->combo2_hidden = $form_values['combo2'];
            $this->view->time_period = $form_values['time_period'];
            $form->time_period->setValue($form_values['time_period']);
        } else {
            $this->view->office = 1;
            $this->view->warehouse_id = $this->_identity->getWarehouseId();
            $this->view->warehouse_hidden = 159;
            $this->view->time_period = date("d/m/Y");
            $form->time_period->setValue(date("d/m/Y"));
        }





        $this->view->role_id = $this->_identity->getRoleId();

        $base_url = Zend_Registry::get('baseurl');
        $this->view->menu_type = 1;
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    public function batchShelfLifeAction() {
        $form = new Form_BatchShelfLife();

        $this->view->form = $form;
        //$this->_helper->layout->setLayout('reports');

        $this->view->report_id = 'REPORTEDPROVINCES';
        $this->view->report_title = 'Vaccine Shelf Life';
        $this->view->actionpage = 'reported-provinces';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;


        if ($this->_request->isPost()) {
            $this->view->combos = $this->_request->getPost();
            $form_values = $this->_request->getPost();
            // App_Controller_Functions::pr($form_values);
            $this->view->office = $form_values['office'];
            $this->view->warehouse_id = $form_values['warehouse'];
            $this->view->warehouse_hidden = $form_values['warehouse'];

            $this->view->combo1_hidden = $form_values['combo1'];
            $this->view->combo2_hidden = $form_values['combo2'];
            $this->view->time_period = $form_values['time_period'];
            $form->time_period->setValue($form_values['time_period']);
        } else {
            $this->view->office = 1;
            $this->view->warehouse_id = $this->_identity->getWarehouseId();
            $this->view->warehouse_hidden = 159;
            $this->view->time_period = date("d/m/Y");
            $form->time_period->setValue(date("d/m/Y"));
        }





        $this->view->role_id = $this->_identity->getRoleId();

        $base_url = Zend_Registry::get('baseurl');
        $this->view->menu_type = 1;
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    public function stockAnalysisSummaryReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'DISTRICTWAREHOUSE';
        $this->view->report_title = 'Stock Analysis Summary Report';
        $this->view->actionpage = 'product-wise-districts-yearly-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }


        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        $locations = new Model_Locations();
        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        } else {
            $prov_sel = 1;
            $this->view->location_name = "Punjab";
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        }
        $this->view->prov_sel = $prov_sel;


        $this->view->dist_sel = $this->_request->dist_id;
        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;
        } else {
            $this->view->in_dist = $this->_request->prov_sel;
            $this->view->sel_dist = 0;
        }
        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    public function sufficientReportAction() {

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->role_id = $this->_identity->getRoleId();

        $stock_master = new Model_StockMaster();
        $result = $stock_master->getSufficientProductReport();

        /* $today_date = new DateTime(date("Y-m-d"));
          $last_year = $today_date->modify("-1 year");
          $from_date = $last_year->format('d F, Y');
          $to_date = date("d F, Y"); */

        $this->view->result = $result;
        $this->view->headTitle("Stock Sufficiency Report - 2014");
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Stock Sufficiency Graph' 
         exportFileName='District wise percentage of UCs having wastage > 50% " . date('Y-m-d H:i:s') . "' yAxisName='MOS (Avg. Issuance)' showValues='1' formatNumberScale='0' theme='fint' numberSuffix=''>";
        foreach ($result as $data) {
            $month6 = ROUND($data['soh'] / $data['avg_issue'], 1);
            $month = ($month6 > 0) ? $month6 . " months" : '';

            $xmlstore .= "<set label='$data[item_name]' value='" . $month . "' />";
        }
        $xmlstore .="</chart>";

        $this->view->xml_store = $xmlstore;
    }

    public function statusReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SHIPMENTREPORT';
        $this->view->report_id1 = 'STATUSREPORT';
        $this->view->report_title = 'IM Status Report';
        $this->view->actionpage = 'status-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
        } else {
            $this->view->province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date1)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $begin->diff($end);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    public function stockIssuanceAction() {
        $stock_master = new Model_StockMaster();

        if ($this->_request->isPost()) {
            $stock_master->form_values = $this->_request->getParams();
            $this->view->month = $this->_request->getParam('month');
            $this->view->year = $this->_request->getParam('year');
        } else {
            $stock_master->form_values = array(
                'month' => date("m"),
                'year' => date("Y")
            );
        }

        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->result = $stock_master->getStockIssuanceByDate();
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    public function printStockIssuanceAction() {
        $this->_helper->layout->setLayout('print');

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $this->_request->getParams();
        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->result = $stock_master->getStockIssuanceByDate();
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    public function stockReportAction() {
        $stock_master = new Model_StockMaster();

        if ($this->_request->isPost()) {
            $stock_master->form_values = $this->_request->getParams();
        } else {
            $stock_master->form_values = array(
                'month' => date("m"),
                'year' => date("Y")
            );
        }

        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->result = $stock_master->getStockReportByDate();
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    public function printStockReportAction() {
        $this->_helper->layout->setLayout('print');

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $this->_request->getParams();
        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->result = $stock_master->getStockReportByDate();
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    public function ucWise1Action() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'UCWISE1';
        $this->view->report_title = 'Provincial Yearly Report';
        $this->view->actionpage = 'uc-wise1';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $locations = new Model_Locations();
        $this->view->item_id = $item;

        if (!empty($this->_request->month_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");

            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }


        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvinceConsumption();
            $this->view->district = $district;
        } else {
            $prov_sel = 2;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvinceConsumption();
            $this->view->district = $district;
        }



        $this->view->prov_sel = $prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }

        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;

            $locations->form_values['dist_id'] = $this->_request->dist_id;
            $res = $locations->getLocationsForConsumptionReport();
            $distrctName = $locations->getLocationForReport();
            // App_Controller_Functions::pr($res);
        } else {
            $this->view->in_dist = '';
            $this->view->sel_dist = '';
        }

        $items = $item_pack_sizes->monthlyConsumtion2Vaccines();
        $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
        $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
        $reportingDate = $year . '-' . $month . '-01';
        $fileName = 'UCWise1_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
        $this->view->file_name = $fileName;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;
        $this->view->result = $res;

        $this->view->items = $items;
        $this->view->items_non_vaccinces = $items_non_vaccines;
        $this->view->items_tt = $items_tt;
    }

    public function ucWise2Action() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'UCWISE2';

        $this->view->actionpage = 'uc-wise2';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $locations = new Model_Locations();
        $this->view->item_id = $item;

        if (!empty($this->_request->month_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");

            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }


        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvinceConsumption();
            $this->view->district = $district;
        } else {
            $prov_sel = 2;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvinceConsumption();

            $this->view->district = $district;
        }



        $this->view->prov_sel = $prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }

        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;
            $locations->form_values['dist_id'] = $this->_request->dist_id;
            $res = $locations->getLocationsForConsumptionReport();
            $distrctName = $locations->getLocationForReport();
            // App_Controller_Functions::pr($res);
        } else {
            $this->view->in_dist = '';
            $this->view->sel_dist = '';
        }

        $items = $item_pack_sizes->monthlyConsumtion2Vaccines();
        $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
        $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
        $reportingDate = $this->_request->year_sel . '-' . $this->_request->month_sel . '-01';
        $fileName = 'UCWise2_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
        $this->view->file_name = $fileName;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;
        $this->view->result = $res;

        $this->view->items = $items;
        $this->view->items_non_vaccinces = $items_non_vaccines;
        $this->view->items_tt = $items_tt;
    }

    public function ucWise3Action() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'UCWISE3';

        $this->view->actionpage = 'uc-wise3';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $warehouses_data = new Model_WarehousesData();
        $item = $item_pack_sizes->productsReport();
        $locations = new Model_Locations();
        $this->view->item_id = $item;

        if (!empty($this->_request->month_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");

            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }


        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvinceConsumption();
            $this->view->district = $district;
        } else {
            $prov_sel = 2;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvinceConsumption();
            $this->view->district = $district;
        }



        $this->view->prov_sel = $prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }

        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;

            $locations->form_values['dist_id'] = $this->_request->dist_id;
            $res = $locations->getLocationsForConsumptionReport();
            $distrctName = $locations->getLocationForReport();
            // App_Controller_Functions::pr($res);
        } else {
            $this->view->in_dist = '';
            $this->view->sel_dist = '';
        }



        $items = $item_pack_sizes->monthlyConsumtion2Vaccines();
        $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
        $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
        $reportingDate = $this->_request->year_sel . '-' . $this->_request->month_sel . '-01';
        $fileName = 'UCWise3_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
        $this->view->file_name = $fileName;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;
        $this->view->result = $res;

        $this->view->items = $items;
        $this->view->items_non_vaccinces = $items_non_vaccines;
        $this->view->items_tt = $items_tt;
    }

}
