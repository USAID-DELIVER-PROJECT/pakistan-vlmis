<?php

class IndexController extends App_Controller_Base {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('front-home');
        $referrer = $this->_getParam('referrer', '');
        $form = new Form_Login();
        $error = false;

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();

            if ($form->isValid($formData)) {
                try {
                    if (!$this->_identity->login($form->login_id->getValue(), base64_encode($form->password->getValue()))) {
                        $error = true;
                        throw new Exception();
                    }
                    $role = $this->_identity->getRoleId();

                    if ($role == 1 || $role == 2 || $role == 22) {
                        parent::_redirect('/iadmin/');
                    }
                    if (in_array($role, array(14, 15))) {

                        parent::_redirect('/campaign/manage-campaigns/');
                    }
                    if (in_array($role, array(24))) {
                        parent::_redirect('/reports/dashlet/cold-chain-capacity');
                    }
                    if (in_array($role, array(25))) {
                        parent::_redirect('/dashboard/?office=1');
                    }


                    //$this->_populateSession();
                    if (isset($referrer) && !empty($referrer)) {
                        parent::_redirect(base64_decode($referrer));
                    } else {
                        parent::_redirect('/dashboard/index');
                    }
                } catch (Exception $e) {
                    App_FileLogger::info($e);
                    $form->populate($formData);
                    $error = "Username Or Password is incorrect!";
                }
            } else {
                $form->populate($formData);
            }
        }

        $this->view->headTitle('Log In');
        $this->view->form = $form;
        $this->view->error = $error;
    }

    /**
     * Logout
     *
     * @return NULL
     */
    public function logoutAction() {
        $auth = App_Auth::getInstance();
        $auth->logout();
        $this->redirect("index");
    }

    function Update() {
        $str_sql = $this->_em->createQueryBuilder()
                ->update('Model_Users u')
                ->set('u.password', '?', $this->m_strPass)
                ->where('u.pk_id = ?', $this->m_login);
        $row = $str_sql->execute();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    public function checkOldPasswordAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $old_password = base64_encode($this->_request->old_pass);
        $str_sql = $this->_em->createQueryBuilder()
                ->select("u.password")
                ->from('Users', 'u')
                ->where("u.password ='" . $old_password . "'")
                ->AndWhere("u.pkId='" . $this->_userid . "'");


        $result = $str_sql->getQuery()->getResult();

        if (!empty($result) && count($result) > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function changePasswordAction() {

        if ($this->_request->isPost()) {
            $this->m_strPass = base64_encode($this->_request->new_pass);
            $this->m_login = $this->_userid;


            $users = $this->_em->getRepository('Users')->find($this->_userid);
            $users->setPassword($this->m_strPass);
            $this->_em->persist($users);
            $this->_em->flush();

            // $rs = $this->Update();
            // $strMsg = "Password  changed sucessfully";
            $this->redirect("/index/change-password?e=1");
        }
    }

    public function faqsAction() {
        $this->_helper->layout->setLayout('front-inner');
    }

    public function contactUsAction() {
        $this->_helper->layout->setLayout('front-inner');
    }

    public function trainingManualsAction() {

        //  $base_url = Zend_Registry::get('baseurl');
        // $this->view->headLink()->appendStylesheet($base_url . '/css/default/dashboard/style_tab.css');
    }

    public function technicalDocumentsAction() {

        //  $base_url = Zend_Registry::get('baseurl');
        // $this->view->headLink()->appendStylesheet($base_url . '/css/default/dashboard/style_tab.css');
    }

    public function acronymsAction() {

        //  $base_url = Zend_Registry::get('baseurl');
        // $this->view->headLink()->appendStylesheet($base_url . '/css/default/dashboard/style_tab.css');
    }

    public function allLevelCombosOneAction() {
        $this->_helper->layout->disableLayout();
        // $province_id = $this->_identity->getProvinceId();
        //  $stakeholder_id = $this->_identity->getStakeholderId();

        if (isset($this->_request->office) && !empty($this->_request->office)) {
            $office = $this->_request->office;

            $warehouse = new Model_Warehouses();

            if ($office == '1') {
                $province_id = $this->_identity->getProvinceId();
                $stakeholder_id = $this->_identity->getStakeholderId();

                $warehouse->form_values = array('stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getFederalWarehouses();
            } else if ($office == '60') {
                $province_id = $this->_identity->getProvinceId();
                $stakeholder_id = $this->_identity->getStakeholderId();
                $this->view->result = $warehouse->getIHRWarehouses(60);
            } else {
                $locations = new Model_Locations();
                $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2);
                $this->view->result = $locations->getLocationsByLevel();
            }
        }
    }

    public function allLevelCombosTwoAction() {
        $this->_helper->layout->disableLayout();



        if (isset($this->_request->combo1) && !empty($this->_request->combo1)) {
            $office = $this->_request->office;
            $province_id = $this->_request->combo1;
            $location = new Model_Locations();
            $location->form_values = array('province_id' => $province_id, 'geo_level_id' => 4);

            $warehouse = new Model_Warehouses();

            switch ($office) {
                case 2:
                    $stakeholder_id = $this->_identity->getStakeholderId();

                    $warehouse->form_values = array('stakeholder_id' => $stakeholder_id, 'province_id' => $province_id);

                    $this->view->result = $warehouse->getProvincialWarehouses();
                    break;
                case 3:
                    $stakeholder_id = $this->_identity->getStakeholderId();

                    $warehouse->form_values = array('stakeholder_id' => $stakeholder_id, 'province_id' => $province_id);

                    $this->view->result = $warehouse->getDivsionalWarehousesofProvince();
                    break;
                case 4:
                    $stakeholder_id = $this->_identity->getStakeholderId();

                    $warehouse->form_values = array('stakeholder_id' => $stakeholder_id, 'province_id' => $province_id);

                    $this->view->result = $warehouse->getDistrictWarehousesofProvince();
                    break;
                case 5:
                    $this->view->result = $location->getLocationsByLevelByProvince();
                    break;
                case 6:
                    $this->view->result = $location->getLocationsByLevelByProvince();
                    break;
                case 8:
                    $this->view->result = $location->getLocationsByLevelByProvince();
                    break;
                case 9:
                    $this->view->result = $location->getLocationsByLevelByProvince();
                    break;
            }
        }
    }

    public function allLevelCombosThreeAction() {
        $this->_helper->layout->disableLayout();

        $stakeholder_id = $this->_identity->getStakeholderId();

        if (isset($this->_request->combo2) && !empty($this->_request->combo2)) {
            $district_id = $this->_request->combo2;
            $office = $this->_request->office;

            $warehouse = new Model_Warehouses();
            $warehouse->form_values = array('district_id' => $district_id, 'stakeholder_id' => $stakeholder_id);

            switch ($office) {
                case 5:
                    $this->view->result = $warehouse->getTehsilWarehousesofDistrict();
                    break;
                case 6:
                    $this->view->result = $warehouse->getUCWarehousesofDistrict();
                    break;
                case 8:
                    $this->view->result = $warehouse->getTehsilWarehousesofDistrict();
                    break;
                case 9:
                    $this->view->result = $warehouse->getUCWarehousesofDistrict();
                    break;
            }
        }
    }

    public function levelCombosOneAction() {
        $this->_helper->layout->disableLayout();
        $office = $this->_request->office;
        $province_id = $this->_identity->getProvinceId();
        $district_id = $this->_identity->getDistrictId($this->_userid);
        $stakeholder_id = $this->_identity->getStakeholderId();
        $role_id = $this->_identity->getRoleId();
        if ($role_id == 7) {
            $tehsil_id = $this->_identity->getTehsilId($this->_userid);
        } else {
            $tehsil_id = '';
        }

        $location = new Model_Locations();
        $location->form_values = array('parent_id' => $province_id, 'geo_level_id' => $office);

        $warehouse = new Model_Warehouses();

        switch ($office) {
            case 1:
                break;
            case 2:
            case 3:
            case 4:
                $this->view->result = $location->getLocationsByLevel();
                break;
            case 5:
                $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getProvincialWarehouses();
                break;
            case 6:
                $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getDivsionalWarehousesofProvince();
                break;
            case 7:
                $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getDistrictWarehousesofProvince();
                break;
            case 8:
                $warehouse->form_values = array('district_id' => $district_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getTehsilWarehousesofDistrict();
                break;
            case 9:
                $warehouse->form_values = array('district_id' => $district_id, 'stakeholder_id' => $stakeholder_id, 'role_id' => $role_id, 'tehsil_id' => $tehsil_id);
                $this->view->result = $warehouse->getUCWarehousesofDistrict();
                break;
            case 60:
                $this->view->result = $warehouse->getIHRWarehouses($office);
                break;
        }
    }

    public function levelCombosTwoAction() {
        $this->_helper->layout->disableLayout();

        $office = $this->_request->office;
        $province_id = $this->_request->combo1;
        $stakeholder_id = $this->_identity->getStakeholderId();

        $location = new Model_Locations();
        $location->form_values = array('parent_id' => 10, 'geo_level_id' => 2);

        $warehouse = new Model_Warehouses();
        $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);

        switch ($office) {
            case 1:
                break;
            case 2:
                $this->view->result = $warehouse->getProvincialWarehouses();
                break;
            case 3:
                $this->view->result = $warehouse->getDivsionalWarehousesofProvince();
                break;
            case 4:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
            case 5:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
            case 6:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
            case 7:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
            case 8:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
            case 9:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
        }
    }

    public function levelCombosThreeAction() {
        $this->_helper->layout->disableLayout();
        $office = $this->_request->office;
        $location = new Model_Locations();

        switch ($office) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
        }
    }

    public function locationsCombosOneAction() {
        $this->_helper->layout->disableLayout();

        //    $province_id = $this->_identity->getProvinceId();
        //    $stakeholder_id = $this->_identity->getStakeholderId();

        if (isset($this->_request->office) && !empty($this->_request->office)) {

            $locations = new Model_Locations();
            $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2);

            $this->view->result = $locations->getLocationsByLevel();
            if (!empty($this->_request->province_id)) {

                $this->view->province_id = $this->_request->province_id;
            }
        }
    }

    public function locationsCombosTwoAction() {
        $this->_helper->layout->disableLayout();

        //$stakeholder_id = $this->_identity->getStakeholderId();

        if (isset($this->_request->combo1) && !empty($this->_request->combo1)) {
            // $office = $this->_request->office;
            $province_id = $this->_request->combo1;

            $location = new Model_Locations();
            $location->form_values = array('province_id' => $province_id, 'geo_level_id' => 4);

            $this->view->result = $location->districtLocations();

            if (!empty($this->_request->district_id)) {
                $this->view->district_id = $this->_request->district_id;
            }
        }
    }

    public function locationsCombosThreeAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->combo2) && !empty($this->_request->combo2)) {
            $district_id = $this->_request->combo2;

            $location = new Model_Locations();
            $location->form_values = array('parent_id' => $district_id, 'geo_level_id' => 5);
            $this->view->result = $location->getLocationsByLevelByTehsil();

            if (!empty($this->_request->tehsil_id)) {

                $this->view->tehsil_id = $this->_request->tehsil_id;
            }
        }
    }

    public function locationsCombosFourAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->combo3) && !empty($this->_request->combo3)) {
            $tehsil_id = $this->_request->combo3;

            $location = new Model_Locations();
            $location->form_values = array('parent_id' => $tehsil_id, 'geo_level_id' => 6);
            $this->view->result = $location->getLocationsByLevelByTehsil();

            if (!empty($this->_request->uc_id)) {
                $this->view->uc_id = $this->_request->uc_id;
            }
        }
    }

}
