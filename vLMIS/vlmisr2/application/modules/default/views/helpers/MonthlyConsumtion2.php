<?php

class Zend_View_Helper_MonthlyConsumtion2 extends Zend_View_Helper_Abstract {

    public function monthlyConsumtion2() {
        return $this;
    }

    public function monthlyConsumtion2Vaccines($wh_id, $prev_month_date, $pk_id, $age_group_id) {

        $this->_em = Zend_Registry::get('doctrine');
        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) > 0) {
            $querypro = " SELECT w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate
                       
                       FROM
                               hf_data_master_draft w0_
                       WHERE
                               w0_.warehouse_id = $wh_id
           AND 
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.created_date AS createdDate,
                        hf_data_detail.pk_id,
                        hf_data_detail.fixed_inside_uc_male,
                        hf_data_detail.fixed_inside_uc_female,
                        hf_data_detail.fixed_outside_uc_male,
                        hf_data_detail.fixed_outside_uc_female,
                        hf_data_detail.referal_male,
                        hf_data_detail.referal_female,
                        hf_data_detail.outreach_male,
                        hf_data_detail.outreach_female,
                        hf_data_detail.age_group_id,
                        hf_data_detail.vaccine_schedule_id
                        FROM
                        hf_data_master AS w0_
                        INNER JOIN hf_data_detail ON w0_.pk_id = hf_data_detail.hf_data_master_id
                        WHERE
                               w0_.warehouse_id = '$wh_id'
                                   AND hf_data_detail.age_group_id = '$age_group_id'
           AND 
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = '$pk_id' ";
        }

        // echo $querypro;


        $row = $this->_em->getConnection()->prepare($querypro);

        $rs = $row->execute();
        $result = $row->fetchAll();

        return $result;
    }

    public function monthlyConsumtion2VaccinesTt($wh_id, $prev_month_date, $pk_id) {

        $this->_em = Zend_Registry::get('doctrine');
        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) < 0) {
            $querypro = " SELECT w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate
                       
                       FROM
                               hf_data_master_draft w0_
                       WHERE
                               w0_.warehouse_id = $wh_id
           AND 
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.created_date AS createdDate,
                        hf_data_detail.pk_id,
                        hf_data_detail.pregnant_women,
                        hf_data_detail.non_pregnant_women,
                        hf_data_detail.age_group_id,
                        hf_data_detail.vaccine_schedule_id
                        FROM
                        hf_data_master AS w0_
                        INNER JOIN hf_data_detail ON w0_.pk_id = hf_data_detail.hf_data_master_id
                        WHERE
                               w0_.warehouse_id = '$wh_id'
                                  
           AND 
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = '$pk_id' ";
        }



        $row = $this->_em->getConnection()->prepare($querypro);

        $rs = $row->execute();
        $result = $row->fetchAll();

        return $result;
    }

    public function monthlyConsumptionNonVaccinces($wh_id, $prev_month_date, $pk_id, $age_group_id) {

        $this->_em = Zend_Registry::get('doctrine');
        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) < 0) {
            $querypro = " SELECT w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate
                       
                       FROM
                               hf_data_master_draft w0_
                       WHERE
                               w0_.warehouse_id = $wh_id
           AND 
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.created_date AS createdDate
                        FROM
                        hf_data_master AS w0_
                        
                        WHERE
                               w0_.warehouse_id = '$wh_id'
                                
           AND 
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = '$pk_id' ";
        }

        // echo $querypro;


        $row = $this->_em->getConnection()->prepare($querypro);

        $rs = $row->execute();
        $result = $row->fetchAll();

        return $result[0];
    }

    public function monthlyConsumtion2Targets($wh_id, $prev_month_date) {

        $pov = explode('-', $prev_month_date);

        $this->_em = Zend_Registry::get('doctrine');

        $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.children_live_birth,
                        w0_.surviving_children_0_11,
                        w0_.children_aged_12_23,
                        w0_.pregnant_women
                        
                        FROM
                        hf_data_master AS w0_
                  
                        WHERE
                        w0_.warehouse_id = '$wh_id'
                                  
           AND 
           DATE_FORMAT(w0_.reporting_start_date,'%Y') = '$pov[0]' LIMIT 1
                 ";


//
//        $querypro = "SELECT
//                    warehouse_population.pregnant_women_per_year,
//                    warehouse_population.live_births_per_year
//                    FROM
//                    warehouse_population 
//                    where
//                    warehouse_population.warehouse_id = '$wh_id' ";
        //echo $querypro;


        $row = $this->_em->getConnection()->prepare($querypro);

        $rs = $row->execute();
        $result = $row->fetchAll();

        return $result[0];
    }

    public function logBook($data_id) {

        $this->_em = Zend_Registry::get('doctrine');

        $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.doses
                      FROM
                        log_book_item_doses AS w0_
             
                        WHERE
                         w0_.log_book_id =$data_id ";
//         $querypro = " SELECT
//log_book_item_doses.doses,
//log_book_item_doses.pk_id as pkId
//FROM
//log_book
//LEFT JOIN log_book_item_doses ON log_book.pk_id = log_book_item_doses.log_book_id
//where  log_book_item_doses.log_book_id = '$data_id'
//";



        $row = $this->_em->getConnection()->prepare($querypro);

        $rs = $row->execute();
        $result = $row->fetchAll();

        return $result;
    }

}

?>