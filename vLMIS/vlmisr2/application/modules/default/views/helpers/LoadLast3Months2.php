<?php

class Zend_View_Helper_LoadLast3Months2 extends Zend_View_Helper_Abstract {

    public function loadLast3Months2() {
        return $this;
    }

    public function loadReportedMonths($wh_id, $loc_id, $update = '') {

        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id, 'loc_id' => $loc_id);

        if (isset($update) && !empty($update)) {
            $result = $reports->last3monthsUpdate2();
        } else {
            $result = $reports->last3months2();
        }

        return $result;
    }

    public function loadLastReportedDate($wh_id) {
        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id);

        $max_date = $reports->getLastCreatedDate2();
        //echo $max_date;
        if (!empty($max_date)) {
            return date("d/m/Y", strtotime($max_date));
        } else {
            return '-';
        }
    }

}

?>