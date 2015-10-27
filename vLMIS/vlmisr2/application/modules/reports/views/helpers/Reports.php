<?php

class Zend_View_Helper_Reports extends Zend_View_Helper_Abstract {

    public function reports() {
        return $this;
    }

    public function reportData($in_col, $in_rg, $in_type, $month, $year, $in_item, $in_stk, $in_prov, $in_dist) {
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare("SELECT REPgetData('$in_col','$in_rg','$in_type'," . $month . "," . $year . "," . $in_item . ",'$in_stk','$in_prov','$in_dist') AS Value from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    public function reportsMos($v_mos, $sel_item, $id, $sel_lvl) {
        // echo "SELECT getMosColor('" . $v_mos . "'," . $sel_item . ",1," . $sel_lvl . ") as col from Dual";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare("SELECT getMosColor('" . $v_mos . "'," . $sel_item . ",1," . $sel_lvl . ") as col from Dual");
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportingRateStr($in_type, $in_month, $in_year, $in_item, $in_WF, $in_skt, $in_prov, $in_dist) {

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare("SELECT REPgetReportingRateStr('" . $in_type . "'," . $in_month . "," . $in_year . ",'" . $in_item . "','" . $in_WF . "'," . $in_skt . "," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    public function getAvailabilityRateStr($in_type, $in_month, $in_year, $in_item, $in_WF, $in_skt, $in_prov, $in_dist) {
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare("SELECT REPgetAvailabilityRateStr('" . $in_type . "'," . $in_month . "," . $in_year . "," . $in_item . ",'" . $in_WF . "'," . $in_skt . "," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    public function getConsumptionAVG($t, $sel_month, $sel_year, $sel_item, $row_item, $in_prov, $in_dist) {
        //  echo "SELECT REPgetConsumptionAVG('" . $t . "'," . $sel_month . "," . $sel_year . ",'" . $sel_item . "','" . $row_item . "'," . $in_prov . "," . $in_dist . ") ";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare("SELECT REPgetConsumptionAVG('" . $t . "'," . $sel_month . "," . $sel_year . ",'" . $sel_item . "','" . $row_item . "'," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    public function getMOS($t, $sel_month, $sel_year, $sel_item, $row_item, $in_prov, $in_dist) {
        // echo "SELECT REPgetMOS('" . $t . "'," . $sel_month . "," . $sel_year . ",'" . $sel_item . "','" . $row_item . "'," . $in_prov . "," . $in_dist . ")";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare("SELECT REPgetMOS('" . $t . "'," . $sel_month . "," . $sel_year . ",'" . $sel_item . "','" . $row_item . "'," . $in_prov . "," . $in_dist . ") As Rate from DUAL");

        $row->execute();
        return $row->fetchAll();
    }

    public function getCB($t, $sel_month, $sel_year, $sel_item, $row_item, $in_prov, $in_dist) {
        // echo "SELECT REPgetCB('" . $t . "'," . $sel_month . "," . $sel_year . ",'" . $sel_item . "','" . $row_item . "'," . $in_prov . "," . $in_dist . ")";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare("SELECT REPgetCB('" . $t . "'," . $sel_month . "," . $sel_year . ",'" . $sel_item . "','" . $row_item . "'," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    public function getProvincialYearlyReport($report_indicator, $str_date, $in_prov, $in_dist) {
        $col_name = "";
        $level = "";
        $prov_clause = "";
        $dist_clause = "";

        if ($in_prov == 'all') {
            $prov_clause = "";
        } else {
            $prov_clause = "AND warehouses.province_id = '" . $in_prov . "'";
        }

        if ($in_dist == "") {
            $dist_clause = "";
        } else {
            $dist_clause = "AND warehouses.district_id = '" . $in_dist . "'";
        }

        if ($report_indicator == 1) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=6';
        } else if ($report_indicator == 2) {
            $col_name = 'SUM(warehouses_data.closing_balance) AS total';
            $level = '>= 2';
        } else if ($report_indicator == 3) {
            $col_name = 'SUM(warehouses_data.received_balance) AS total';
            $level = '=2';
        } else if ($report_indicator == 4) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=2';
        }

        $str_qry = "SELECT
                    $col_name,
                    warehouses_data.item_pack_size_id
                    FROM
                    item_pack_sizes
                    INNER JOIN warehouses_data ON item_pack_sizes.pk_id = warehouses_data.item_pack_size_id
                    INNER JOIN warehouses ON warehouses_data.warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    item_pack_sizes.item_category_id <> 3
                    AND warehouses.status = 1
                    AND stakeholders.geo_level_id $level
                    $prov_clause
                    $dist_clause    
                    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
                    GROUP BY warehouses_data.item_pack_size_id
                    ORDER BY item_pack_sizes.list_rank ASC";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getProvincialYearlyReportProduct($report_indicator, $str_date, $in_prov, $in_district) {

        $col_name = "";
        $level = "";
        $prov_clause = "";

        if ($in_prov == 'all') {
            $prov_clause = "";
        } else {
            $prov_clause = "AND warehouses.province_id = '" . $in_prov . "'";
        }

        if ($in_district == '') {
            $district_clause = "";
        } else {
            $district_clause = "AND warehouses.district_id = '" . $in_district . "'";
        }
        if ($report_indicator == 1) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=6';
        } else if ($report_indicator == 2) {
            $col_name = 'SUM(warehouses_data.closing_balance) AS total';
            $level = '>= 2';
        } else if ($report_indicator == 3) {
            $col_name = 'SUM(warehouses_data.received_balance) AS total';
            $level = '=2';
        } else if ($report_indicator == 4) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=2';
        }

        $str_qry = "SELECT
                    $col_name,
                    warehouses_data.item_pack_size_id
                    FROM
                    item_pack_sizes
                    INNER JOIN warehouses_data ON item_pack_sizes.pk_id = warehouses_data.item_pack_size_id
                    INNER JOIN warehouses ON warehouses_data.warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    item_pack_sizes.item_category_id <> 3
                    AND warehouses.status = 1
                    AND stakeholders.geo_level_id $level
                    $prov_clause
                    $district_clause
                    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
                    GROUP BY warehouses_data.item_pack_size_id
                    ORDER BY item_pack_sizes.list_rank ASC";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getDistrictsYearlyReport($report_indicator, $str_date, $in_prov, $sel_prod) {
        $col_name = "";
        $level = "";
        $prov_clause = "";

        if (isset($sel_prod) && !empty($sel_prod)) {
            $sel_item = $sel_prod;
        }


        if ($in_prov == 'all') {
            $prov_clause = "";
        } else {
            $prov_clause = "AND warehouses.province_id = '" . $in_prov . "'";
        }

        if ($report_indicator == 1) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=6';
        } else if ($report_indicator == 2) {
            $col_name = 'SUM(warehouses_data.closing_balance) AS total';
            $level = '>= 2';
        } else if ($report_indicator == 3) {
            $col_name = 'SUM(warehouses_data.received_balance) AS total';
            $level = '=4';
        } else if ($report_indicator == 4) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=4';
        }

        $str_qry = "SELECT

                    $col_name,
                    warehouses.district_id
                    FROM
                    item_pack_sizes
                    INNER JOIN warehouses_data ON item_pack_sizes.pk_id = warehouses_data.item_pack_size_id
                    INNER JOIN warehouses ON warehouses_data.warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    item_pack_sizes.item_category_id <> 3
                    AND warehouses.status = 1
                    AND stakeholders.geo_level_id  $level

                    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
                    AND warehouses_data.item_pack_size_id = $sel_item
                    $prov_clause
                    GROUP BY warehouses.district_id
                    ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getWastagesReport($str_date, $in_prov, $sel_prod, $sel_level) {
        $col_name = "";
        $level = "";
        $prov_clause = "";

        if (isset($sel_prod) && !empty($sel_prod) && $sel_prod != 'all') {
            $itemId = $sel_prod;

            $itemFilter = " AND warehouses_data.item_pack_size_id = $itemId";
        } else {
            $itemFilter = " ";
        }



        if ($in_prov == 'all') {
            $prov_clause = "";
        } else if ($in_prov == '') {
            $prov_clause = "";
        } else {
            $prov_clause = "AND warehouses.province_id = '" . $in_prov . "'";
        }
        if ($in_prov == 'all') {
            $uc_clause = "";
        } else if ($in_prov == '') {
            $prov_clause = "";
            $uc_clause = "";
        } else {
            $uc_clause = "AND UC.province_id = '" . $in_prov . "'";
        }
        if ($sel_level == '1') {
            $uc_where = "UC.geo_level_id = 2";
        } else {
            $uc_where = "UC.geo_level_id = 6";
        }


        $str_qry = "SELECT
								districtId,
								districtName,
								reported,
								TotalWH,
								ROUND(((reported / TotalWH) * 100), 1) AS RptPer,
								wastagePer
							FROM
								(
									SELECT
										District.pk_id AS districtId,
										District.location_name AS districtName,
										COUNT(DISTINCT UC.pk_id) AS reported,
										ROUND(IFNULL(
											(
												sum(warehouses_data.wastages) / (
													sum(warehouses_data.issue_balance) + sum(warehouses_data.wastages)
												)
											) * 100,
											0
										), 1) AS wastagePer
									FROM
										locations AS District
									INNER JOIN locations AS UC ON District.pk_id = UC.district_id
									INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
									INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                                                                    
									WHERE
										$uc_where
										AND warehouses.stakeholder_id = 1
                                                                                AND warehouses.status = 1
									AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
									AND warehouses_data.issue_balance IS NOT NULL
                                                                        AND warehouses_data.issue_balance != 0
									$prov_clause
									$itemFilter
									
									GROUP BY
										District.pk_id
								) AS A
							JOIN (
								SELECT
									COUNT(DISTINCT UC.pk_id) AS TotalWH,
									warehouses.district_id
								FROM
									locations AS UC
								INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
								WHERE
									$uc_where
									AND warehouses.stakeholder_id = 1
                                                                        AND warehouses.status = 1
									$uc_clause
								GROUP BY
									warehouses.district_id
							) AS B ON A.districtId = B.district_id
							GROUP BY
								A.districtId
						ORDER BY
							A.districtId ASC";
        //  echo $str_qry;
        //   exit;

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedProvinces1() {


        $str_qry = "SELECT
                            Province.pk_id AS provinceId,
                            Province.location_name AS provinceName,
                            COUNT(DISTINCT UC.pk_id) AS TotalUCs
                    FROM
                            locations AS Province
                    INNER JOIN locations AS UC ON Province.pk_id = UC.province_id
                    INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
				      	stakeholders.geo_level_id = 6
      					AND warehouses.stakeholder_id = 1
                                        AND warehouses.status = 1
                    GROUP BY
                            Province.pk_id
                    ORDER BY
                            Province.pk_id ASC";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedProvinces($str_date) {


        $str_qry = "SELECT
                                Province.location_name AS provinceName,
                                Province.pk_id AS provinceId,
                                COUNT(DISTINCT UC.pk_id) AS reported
                        FROM
                                locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        INNER JOIN locations AS Province ON Province.pk_id = District.province_id
                        WHERE
                        stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses.status = 1
                        AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
						AND  warehouses_data.issue_balance IS NOT NULL
                                                AND warehouses_data.issue_balance != 0
                        GROUP BY
                                Province.pk_id
                        ORDER BY
                                provinceId ASC
                    ";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

//SELECT DISTINCT
//                                        l0_.pk_id,
//                                        l0_.location_name
//                                        FROM
//                                        locations AS l0_
//                                        INNER JOIN locations AS dist ON dist.province_id = l0_.pk_id
//                                        INNER JOIN pilot_districts ON pilot_districts.district_id = dist.pk_id
//                                        WHERE
//                                        l0_.geo_level_id = 2 AND
//                                        l0_.province_id IS NOT NULL
//                                        ORDER BY l0_.pk_id
    public function getStockOnHand1() {
        $str_qry = "SELECT DISTINCT
                                        l0_.pk_id,
                                        l0_.location_name
                                        FROM
                                        locations AS l0_
                                        INNER JOIN locations AS dist ON dist.province_id = l0_.pk_id
                                        INNER JOIN pilot_districts ON pilot_districts.district_id = dist.pk_id
                                        WHERE
                                        l0_.geo_level_id = 2 AND
                                        l0_.province_id IS NOT NULL
                                        ORDER BY l0_.pk_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStockOnHand2($str_date, $item_id) {
        $str_qry = "SELECT
                Sum(warehouses_data.closing_balance) AS CB,
                 warehouses.province_id
            FROM
                warehouses
            INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
            WHERE
              DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
            AND stakeholders.geo_level_id >= 2
            AND warehouses.status = 1
            AND warehouses_data.item_pack_size_id = " . $item_id . "
            GROUP BY
                warehouses.province_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getMonthOfStock($item, $province, $data_in) {
        $str_qry = "SELECT AVG(csum) AS AMC, prov_id FROM (
                        SELECT RptDate, csum, prov_id FROM (
                                SELECT  reporting_start_date as RptDate,sum(warehouses_data.issue_balance) As csum,warehouses.province_id as prov_id
                                    FROM warehouses
                                           Inner Join warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                                           Inner Join stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                           WHERE warehouses_data.item_pack_size_id = " . $item . " AND warehouses.province_id = " . $province . " and stakeholders.geo_level_id=6
                                           AND warehouses.status = 1     GROUP BY RptDate
                                ) As A
                         WHERE csum > 0
                         AND  DATE_FORMAT(RptDate, '%Y-%m') <= '$data_in'
                         ORDER BY RptDate DESC
                         LIMIT 3
                        ) As B";
        $str_qry .= ' UNION ALL';



        $str_qry = substr($str_qry, 0, -10);
        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getConsumption($str_date, $item_id) {
        $str_qry = "SELECT
                Sum(warehouses_data.issue_balance) AS TC,
                warehouses.province_id
            FROM
                warehouses
            INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
            WHERE
              DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
            AND stakeholders.geo_level_id = 6
            AND warehouses.status = 1
            AND warehouses.stakeholder_id = 1
            AND warehouses_data.item_pack_size_id = " . $item_id . "
            GROUP BY
                warehouses.province_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedDistrictsByUc($provFilter) {
        $str_qry = "SELECT
						District.pk_id AS districtId,
						District.location_name AS districtName,
						COUNT(DISTINCT UC.pk_id) AS totalWH
					FROM
						locations AS District
					INNER JOIN locations AS UC ON District.pk_id = UC.district_id
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					WHERE
						stakeholders.geo_level_id = 6
						AND warehouses.stakeholder_id = 1
                                                AND warehouses.status = 1
						AND District.province_id= '" . $provFilter . "'
					GROUP BY
						District.pk_id
					ORDER BY
						districtId ASC";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedDistrictsByUc1($provFilter, $str_date) {
        $str_qry = "SELECT
							District.pk_id AS districtId,
							District.location_name AS districtName,
							COUNT(DISTINCT UC.pk_id) AS reported
						FROM
							locations AS District
						INNER JOIN  locations AS UC ON District.pk_id = UC.district_id
						INNER JOIN  warehouses ON UC.pk_id =  warehouses.location_id
						INNER JOIN  warehouses_data ON  warehouses.pk_id = warehouses_data.warehouse_id
						INNER JOIN stakeholders ON  warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id = 6
							AND warehouses.stakeholder_id = 1
                                                        AND warehouses.status = 1
							AND District.province_id= '" . $provFilter . "'
							AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
							AND  warehouses_data.issue_balance IS NOT NULL AND warehouses_data.issue_balance != 0
						GROUP BY
							District.pk_id
						ORDER BY
							districtId ASC";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedDistrictsByUser($provFilter) {
        $str_qry = "SELECT
						locations.pk_id AS districtId,
						locations.location_name AS districtName,
						count(
								DISTINCT warehouse_users.user_id
							) AS totalWH
					FROM
						warehouses
					        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
						INNER JOIN locations ON warehouses.district_id = locations.pk_id
						WHERE
							stakeholders.geo_level_id = 6
                                                        AND warehouses.status = 1
							AND warehouses.stakeholder_id = 1
							AND locations.province_id= '" . $provFilter . "'
						GROUP BY
							locations.pk_id";




        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedDistrictsByUser1($provFilter, $str_date) {
        $str_qry = "SELECT
							locations.location_name as districtName,
							Count(DISTINCT warehouses_data.created_by) AS reported,
							locations.district_id as districtId
						FROM
							warehouses
						INNER JOIN stakeholders ON  warehouses.stakeholder_office_id = stakeholders.pk_id
						INNER JOIN warehouse_users ON  warehouses.pk_id = warehouse_users.warehouse_id
						INNER JOIN locations ON  warehouses.district_id = locations.pk_id
						INNER JOIN warehouses_data ON warehouses_data.warehouse_id = warehouse_users.warehouse_id
						WHERE
							stakeholders.geo_level_id = 6
							AND warehouses.stakeholder_id = 1
                                                        AND warehouses.status = 1
							AND warehouses.province_id = '" . $provFilter . "'
							AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
							AND warehouses_data.created_by != 2
							AND  warehouses_data.issue_balance IS NOT NULL AND warehouses_data.issue_balance != 0
                        GROUP BY
                            warehouses_data.reporting_start_date,locations.location_name
                        ORDER BY
                            locations.location_name ASC";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedDistrictsByFacility($provFilter) {
        $str_qry = "SELECT
						locations.pk_id AS districtId,
						locations.location_name AS districtName,
						count(
								DISTINCT warehouse_users.warehouse_id
							) AS totalWH
					FROM
						locations
					    INNER JOIN warehouses ON locations.pk_id = warehouses.district_id
					    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					    INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id

						WHERE
							stakeholders.geo_level_id = 6
							AND warehouses.stakeholder_id = 1
                                                        AND warehouses.status = 1
							AND locations.province_id= '" . $provFilter . "'
						GROUP BY
							warehouses.district_id";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedDistrictsByFacility1($provFilter, $str_date) {
        $str_qry = "SELECT
                            locations.location_name AS districtName,
                            COUNT(DISTINCT warehouses_data.warehouse_id) AS reported,
                            locations.pk_id AS districtId
                        FROM
                            warehouses_data
                        INNER JOIN warehouses ON warehouses.pk_id = warehouses_data.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        INNER JOIN locations ON warehouses.district_id = locations.pk_id
						INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                        WHERE
							stakeholders.geo_level_id = 6
							AND warehouses.stakeholder_id = 1
                                                        AND warehouses.status = 1
							AND warehouses.province_id = '" . $provFilter . "'
							AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
							AND warehouses_data.issue_balance IS NOT NULL AND warehouses_data.issue_balance != 0
                        GROUP BY
                            warehouses.district_id
                        ORDER BY
                            locations.location_name ASC";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNonReportedTotalByFacility($str_date, $sel_prov, $sel_dist) {
        if (!empty($sel_dist)) {
            $qry_dist = "AND warehouses.district_id = '" . $sel_dist . "' ";
        }
        $str_qry = " SELECT 
			count(DISTINCT warehouses_data.warehouse_id) as abc
		FROM
			warehouses
		INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
		WHERE
		warehouses_data.issue_balance IS NOT NULL
                AND warehouses.status = 1
                AND warehouses_data.issue_balance != 0
		AND stakeholders.geo_level_id = 6 AND warehouses.stakeholder_id = 1
		AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "' 
		AND warehouses.province_id = '" . $sel_prov . "'
		$qry_dist		
		";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getTotalByFacility($str_date, $sel_prov, $sel_dist) {
        if (!empty($sel_dist)) {
            $qry_dist = "AND warehouses.district_id = '" . $sel_dist . "' ";
        }

        $str_qry = "SELECT
                    COUNT(DISTINCT warehouse_users.warehouse_id) as abc
		FROM
                    warehouses
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
		WHERE
                warehouses.location_id <> 0
                AND warehouses.status = 1                
                AND stakeholders.geo_level_id = 6 
                AND warehouses.stakeholder_id = 1 
		AND warehouses.province_id = '" . $sel_prov . "'
		$qry_dist		
		";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNonReportedDistrictsByFacility($str_date, $sel_prov, $sel_dist) {
        $str_qry = " SELECT DISTINCT
                    warehouses_data.warehouse_id
		FROM
                    warehouses
		INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
		WHERE
                    warehouses_data.issue_balance IS NOT NULL
                    AND warehouses_data.issue_balance != 0
                    AND warehouses.status = 1
		    AND stakeholders.geo_level_id = 6 AND warehouses.stakeholder_id = 1
		    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "' ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNonReportedDistrictsByFacility1($str_date, $sel_prov, $sel_dist, $qwhlist) {
        if (!empty($sel_dist)) {
            $qry_dist = "AND warehouses.district_id = '" . $sel_dist . "' ";
        }
        $str_qry = "SELECT DISTINCT
                        warehouses.pk_id
                FROM
                    warehouses				
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN locations ON warehouses.district_id = locations.pk_id
                    INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                WHERE
                    warehouses.location_id <> 0 
                    AND warehouses.status = 1                    
                    AND stakeholders.geo_level_id = 6 AND  warehouses.stakeholder_id = 1                                
                    $qry_dist	
                    AND warehouses.province_id = '" . $sel_prov . "' 	
                    $qwhlist
                ORDER BY
                    locations.location_name ASC ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNonReportedDistrictsByUc($str_date, $sel_prov, $sel_dist) {
        $str_qry = " 
		SELECT
                            DISTINCT UC.pk_id AS UCID
			FROM
                            locations AS District
			INNER JOIN locations AS UC ON District.pk_id = UC.district_id
			INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
			INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
                            warehouses_data.issue_balance IS NOT NULL
                            AND warehouses.status = 1
                            AND warehouses_data.issue_balance != 0
		            AND stakeholders.geo_level_id = 6 AND warehouses.stakeholder_id = 1
			    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "' ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNonReportedTotalByUc($str_date, $sel_prov, $sel_dist) {
        $qry_dist = "";
        if (!empty($sel_dist)) {
            $qry_dist = " AND warehouses.district_id = '" . $sel_dist . "' ";
        }
        $str_qry = " SELECT 
                    count(DISTINCT UC.pk_id) as abc
		FROM
                    locations as District
		INNER JOIN locations AS UC ON District.pk_id = UC.district_id
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		WHERE
                    warehouses_data.issue_balance IS NOT NULL
                    AND warehouses.status = 1
                    AND warehouses_data.issue_balance != 0
		    AND stakeholders.geo_level_id = 6 AND warehouses.stakeholder_id = 1
		    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "' 
		    AND warehouses.province_id = '" . $sel_prov . "'
		$qry_dist
		";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getTotalByUc($str_date, $sel_prov, $sel_dist) {
        $qry_dist = "";
        if (!empty($sel_dist)) {
            $qry_dist = " AND warehouses.district_id = '" . $sel_dist . "' ";
        }
        $str_qry = "SELECT
                    COUNT(DISTINCT UC.pk_id) as abc
		FROM
                    locations AS District
		INNER JOIN locations AS UC ON District.pk_id = UC.district_id
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		WHERE
	        stakeholders.geo_level_id = 6 
                AND warehouses.status = 1                
                AND warehouses.stakeholder_id = 1 
		AND warehouses.province_id = '" . $sel_prov . "'
		$qry_dist
		";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNonReportedDistrictsByUc1($str_date, $sel_prov, $sel_dist, $qwhlist) {
        $qry_dist = "";
        if (!empty($sel_dist)) {
            $qry_dist = " AND warehouses.district_id = '" . $sel_dist . "' ";
        }
        $str_qry = "SELECT DISTINCT
                    Province.location_name AS Province,
                    District.location_name AS District,
                    UC.location_name AS UCName,
                    users.user_name AS User,
                    users.cell_number AS Phone
                    FROM
                            warehouses
                    INNER JOIN stakeholders ON  warehouses.stakeholder_office_id = stakeholders.pk_id
                    LEFT JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                    INNER JOIN locations AS UC ON warehouses.location_id = UC.pk_id
                    LEFT JOIN users ON warehouse_users.user_id = users.pk_id
                    INNER JOIN locations AS District ON UC.pk_id = District.pk_id
                    INNER JOIN locations AS Province ON District.province_id = Province.pk_id
                    WHERE
                    stakeholders.geo_level_id = 6 
                    AND warehouses.status = 1                    
                    AND warehouses.stakeholder_id = 1
                    AND warehouses.province_id = '" . $sel_prov . "' 	
                    $qry_dist
                    $qwhlist";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNonReportedDistrictsByFacility2($warehouse_id) {
        $str_qry = "SELECT
		office.stakeholder_name,
		stakeholders.stakeholder_name AS wh_type_id,
		province.location_name AS prov_tittle,
		Districts.location_name AS d_name,
		UCs.location_name As UC,
		warehouses.warehouse_name,
		users.user_name AS FullName,
		users.cell_number AS Phone
		FROM
		warehouses
		Inner Join locations AS province ON  warehouses.province_id = province.pk_id
		Inner Join locations AS Districts ON warehouses.district_id = Districts.pk_id
		Inner Join locations AS UCs ON  warehouses.location_id = UCs.pk_id
		Inner Join stakeholders AS office ON  warehouses.stakeholder_id = office.pk_id
		Inner Join stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
		INNER JOIN users ON warehouse_users.user_id = users.pk_id
		WHERE warehouses.pk_id=" . $warehouse_id . " 
                AND warehouses.status = 1                    
                ORDER BY province.location_name ,Districts.location_name
                LIMIT 0,1";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getItemPackSizesWastagesRateAllowed($item_id) {
        if ($item_id == 'all') {
            $str_qry = "SELECT
                    item_pack_sizes.item_name,
                    Sum(item_pack_sizes.wastage_rate_allowed) wastage_rate_allowed 
                   FROM
                    item_pack_sizes
                   Group By item_pack_sizes.item_name";
        } else {

            $str_qry = "SELECT
                    item_pack_sizes.item_name,
                    item_pack_sizes.wastage_rate_allowed
                   FROM
                    item_pack_sizes
                    WHERE
                    item_pack_sizes.pk_id = $item_id";
        }


        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentCentral() {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                    WHERE
                        stakeholders.geo_level_id = 1
                        AND stock_master.draft = 0
                        AND warehouses.status = 1
                        AND stakeholders.stakeholder_type_id = 1 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
//echo $str_qry;

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentProvincial($provFilter) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                    WHERE
                        stakeholders.geo_level_id = 2
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                       $provFilter
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentDistrict($provFilter, $distFilter) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                    WHERE
                        stakeholders.geo_level_id = 4
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
						$provFilter
						$distFilter 
                       
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentTehsil($provFilter, $distFilter, $tehsilFilter) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                         stock_master.transaction_number
                    FROM
                        locations
                    INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
WHERE
                        stakeholders.geo_level_id = 5
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter
                        $tehsilFilter
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentCentral1($date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                           
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                                  
                    WHERE
                        stakeholders.geo_level_id = 1
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        AND stakeholders.stakeholder_type_id = 1 
			AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentProvincial1($provFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id                    
                    WHERE
                        stakeholders.geo_level_id = 2
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentDistrict1($provFilter, $distFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id   
                    WHERE
                        stakeholders.geo_level_id = 4
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter 
                       AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentTehsil1($provFilter, $distFilter, $tehsilFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                         stock_master.transaction_number
                    FROM
                        locations
                    INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id                    
                    WHERE
                        stakeholders.geo_level_id = 5
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter
                        $tehsilFilter
			AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusCentral() {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                    WHERE
                        stakeholders.geo_level_id = 1
                        AND stock_master.draft = 0
                        AND warehouses.status = 1
                        AND stakeholders.stakeholder_type_id = 1 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
//echo $str_qry;

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusProvincial($provFilter) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                    WHERE
                        stakeholders.geo_level_id = 2
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                       $provFilter
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusDistrict($provFilter, $distFilter) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                    INNER JOIN pilot_districts ON pilot_districts.district_id = towh.district_id
                    WHERE
                        stakeholders.geo_level_id = 4
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
						$provFilter
						$distFilter 
                       
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
        //echo $str_qry;
        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusTehsil($provFilter, $distFilter, $tehsilFilter) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                         stock_master.transaction_number
                    FROM
                        locations
                    INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
WHERE
                        stakeholders.geo_level_id = 5
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter
                        $tehsilFilter
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusCentral1($date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv,
                    SUM(pendingStatus) AS pending
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2 && stock_detail.is_received = 0 , 1,0) ) AS pendingStatus,                        
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id
                    INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id                    
                    WHERE
                        stakeholders.geo_level_id = 1
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        AND stakeholders.stakeholder_type_id = 1 
			AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusProvincial1($provFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id                    
                    WHERE
                        stakeholders.geo_level_id = 2
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusDistrict1($provFilter, $distFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                        stock_master.transaction_number
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id  
                    INNER JOIN pilot_districts ON pilot_districts.district_id = towh.district_id
                    WHERE
                        stakeholders.geo_level_id = 4
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter 
                       AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusTehsil1($provFilter, $distFilter, $tehsilFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        SUM(IF(stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2, 1, 0)) AS stockIssue,
                        SUM(IF(stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 1, 1, 0)) AS stockRcv,
                         stock_master.transaction_number
                    FROM
                        locations
                    INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN stock_master ON warehouses.pk_id = stock_master.from_warehouse_id OR warehouses.pk_id = stock_master.to_warehouse_id
                    INNER JOIN warehouses AS towh ON stock_master.to_warehouse_id = towh.pk_id                    
                    WHERE
                        stakeholders.geo_level_id = 5
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter
                        $tehsilFilter
			AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusPendingProvincial1($provFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
	            SUM(stockPend) AS pending
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        (IF (stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2 && stock_detail.is_received=0 ,
				COUNT(DISTINCT stock_master.pk_id),
				0
			)
			) AS stockPend,
                        stock_master.transaction_number
                    FROM
                        warehouses
                   INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		   INNER JOIN stock_master ON  warehouses.pk_id = stock_master.to_warehouse_id
		   INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id                   
                    WHERE
                        stakeholders.geo_level_id = 2
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusPendingDistrict1($provFilter, $distFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
	            SUM(stockPend) AS pending
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        (IF (stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2 && stock_detail.is_received=0 ,
				COUNT(DISTINCT stock_master.pk_id),
				0
			)
			) AS stockPend,
                        stock_master.transaction_number
                    FROM
                        warehouses
                   INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		   INNER JOIN stock_master ON  warehouses.pk_id = stock_master.to_warehouse_id
		   INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id 
                   INNER JOIN pilot_districts ON pilot_districts.district_id = warehouses.district_id
                    WHERE
                        stakeholders.geo_level_id = 4
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter 
                       AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
        //echo $str_qry."<br>";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getStatusPendingTehsil1($provFilter, $distFilter, $tehsilFilter, $date) {
        $str_qry = "SELECT 
                    wh_id,
                    wh_name,
	            SUM(stockPend) AS pending
                FROM (
                    SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name,
                        (IF (stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2 && stock_detail.is_received=0 ,
				COUNT(DISTINCT stock_master.pk_id),
				0
			)
			) AS stockPend,
                         stock_master.transaction_number
                    FROM
                        locations
                    INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		    INNER JOIN stock_master ON  warehouses.pk_id = stock_master.to_warehouse_id
		    INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id                    
                    WHERE
                        stakeholders.geo_level_id = 5
                        AND warehouses.status = 1
                        AND stock_master.draft = 0
                        $provFilter
                        $distFilter
                        $tehsilFilter
			AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                        warehouses.pk_id,
                        stock_master.transaction_number
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getCentralProvincialReport($colName, $where, $date) {
        $str_qry = "SELECT
                    warehouses_data.item_pack_size_id as item_id,
                    $colName					
                    FROM
                    warehouses
                    INNER JOIN warehouses_data ON warehouses_data.warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders AS Office ON Office.pk_id = warehouses.stakeholder_office_id
                    INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = warehouses_data.item_pack_size_id
                    WHERE
                    $where
                    AND item_pack_sizes.item_category_id <> 3
                    AND warehouses.status = 1
                    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $date . "' 
                    GROUP BY
                    warehouses_data.item_pack_size_id ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getWarehouseId($wh_type, $and) {
        $querypro = "SELECT pk_id as wh_id,warehouse_name as wh_name,stakeholder_office_id FROM warehouses WHERE stakeholder_office_id=" . $wh_type . " $and";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getWarehouseName($warehouse_id) {

        $query = "SELECT
			warehouses.warehouse_name from warehouses where												
			warehouses.pk_id = '" . $warehouse_id . "'  and  warehouses.status = 1  ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($query);

        $row->execute();
        return $row->fetchAll();
    }

    public function getProvinceName() {
        $querypro = "SELECT DISTINCT
                        l.pk_id AS PkLocID,
                        l.location_name AS LocName
                        FROM
                        locations AS l
                        INNER JOIN locations AS dist ON dist.province_id = l.pk_id
                        INNER JOIN pilot_districts ON pilot_districts.district_id = dist.pk_id
                        WHERE
                        l.geo_level_id = 2 AND
                        l.province_id IS NOT NULL
                        ORDER BY l.pk_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getProvinceName1($provId) {
        $querypro = "SELECT
			Districts.pk_id as PkLocID,
			Districts.location_name as LocName
			FROM
			locations AS Districts
                        INNER JOIN pilot_districts ON pilot_districts.district_id = Districts.pk_id
			WHERE
			Districts.province_id = " . $provId . "
			AND Districts.geo_level_id = 4
                        
			GROUP BY
				Districts.pk_id
			ORDER BY
				Districts.location_name ASC";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getProvinceName2($distId) {
        $querypro = "SELECT
			Tehsil.pk_id as PkLocID,
			Tehsil.location_name as LocName
			FROM
				locations AS Tehsil
			WHERE
				Tehsil.geo_level_id = 5
			AND Tehsil.parent_id = $distId
			GROUP BY
				Tehsil.pk_id
			ORDER BY
				Tehsil.location_name ASC";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentTransactionIssue($whId, $date) {
        $querypro = "SELECT
                        stock_master.pk_id as PkStockID,
                        stock_master.transaction_number as TranNo,
                        warehouses.warehouse_name as wh_name,
                        DATE_FORMAT(stock_master.transaction_date, '%d/%m/%y') AS TranDate,
                        (SELECT	IF(SUM(IF(stock_detail.is_received = 0, 1, 0)) > 0, 0, 1) FROM stock_detail WHERE stock_detail.stock_master_id = stock_master.pk_id) AS IsReceived,
                        stock_detail.adjustment_type as adjustmentType
			FROM
				stock_master
			INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
			INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			WHERE
                            stock_master.from_warehouse_id = '$whId'
                            AND stock_master.draft = 0
                            AND warehouses.status = 1
                            AND DATE_FORMAT(stock_master.transaction_date, '%m-%Y') = '" . $date . "'
                            AND stock_master.transaction_type_id = 2
			GROUP BY
				stock_master.transaction_number
			ORDER BY
				stock_master.transaction_number ASC";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentTransactionReceive($whId, $date) {
        $querypro = "SELECT
                        stock_master.pk_id as PkStockID,
                        stock_master.transaction_number as TranNo,
                        warehouses.warehouse_name as wh_name,
                        DATE_FORMAT(stock_master.transaction_date, '%d/%m/%y') AS TranDate
			FROM
				stock_master
			INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
			WHERE
                            stock_master.to_warehouse_id = '$whId'
                            AND stock_master.draft = 0
                            AND warehouses.status = 1
                            AND DATE_FORMAT(stock_master.transaction_date, '%m-%Y') = '" . $date . "'
                            AND stock_master.transaction_type_id = 1
			GROUP BY
                                
				stock_master.transaction_number
			ORDER BY
				stock_master.transaction_number ASC";
        //echo $querypro . "<hr>";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShipmentTransactionPending($whId, $date) {
        $querypro = "SELECT
                        stock_master.pk_id as PkStockID,
                        stock_master.transaction_number as TranNo,
                        warehouses.warehouse_name as wh_name,
                        (SELECT	IF(SUM(IF(stock_detail.is_received = 0, 1, 0)) > 0, 0, 1) FROM stock_detail WHERE stock_detail.stock_master_id = stock_master.pk_id) AS IsReceived,
                        DATE_FORMAT(stock_master.transaction_date, '%d/%m/%y') AS TranDate
			FROM
				stock_master
			INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
                        INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
			WHERE
                            stock_master.to_warehouse_id = '$whId'
                            AND stock_master.draft = 0
                            AND stock_detail.is_received = 0
                            AND warehouses.status = 1
                            AND DATE_FORMAT(stock_master.transaction_date, '%m-%Y') = '" . $date . "'
                            AND stock_master.transaction_type_id = 2
			GROUP BY
                                
				stock_master.transaction_number
			ORDER BY
				stock_master.transaction_number ASC";
        // echo $querypro . "<hr>";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedUc($district_id) {
        $qry_dist = "";
        if (!empty($district_id)) {
            $qry_dist = " AND warehouses.district_id = '" . $district_id . "' ";
        }
        $querypro = "SELECT
                        warehouses.warehouse_name AS WHName,
                        District.location_name AS District,
                        users.user_name AS FullName,
                        users.email AS Email,
                        users.address AS Address,
                        users.phone_number AS Phone,
                        warehouses.pk_id AS WHId,
                        UC.location_name AS UCName,
                        UC.pk_id as PkLocID
                    FROM
                            warehouses
                    INNER JOIN stakeholders ON stakeholders.pk_id = warehouses.stakeholder_office_id
                    INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                    LEFT JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                    LEFT JOIN users ON warehouse_users.user_id = users.pk_id
                    INNER JOIN locations AS UC ON warehouses.location_id = UC.pk_id
                    WHERE
                            stakeholders.geo_level_id = 6
                            AND warehouses.status = 1
                            AND warehouses.stakeholder_id = 1
                            $qry_dist
                    GROUP BY
                            UC.pk_Id
                    ORDER BY UCName";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedUc1($district_id, $month, $year) {
        $querypro = "SELECT
                        UC.pk_id as PkLocID
                    FROM
                        locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                        warehouses.district_id = $district_id
                        AND warehouses.status = 1    
                        AND DATE_FORMAT(warehouses_data.reporting_start_date, '%m-%Y') = '" . $month . "-" . $year . "  '
                        AND warehouses_data.issue_balance IS NOT NULL AND warehouses_data.issue_balance != 0
                        AND stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                    GROUP BY
                        UC.pk_id";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getPopupDataEntry($district_id) {
        $querypro = "SELECT
                        warehouses.pk_id as wh_id,
                        warehouses.warehouse_name as wh_name
                    FROM
                        warehouses
                    INNER JOIN stakeholders ON stakeholders.pk_id = warehouses.stakeholder_office_id
                    WHERE
                        warehouses.location_id = $district_id
                        AND warehouses.status = 1    
                        AND warehouses.stakeholder_id = 1
                        AND stakeholders.geo_level_id = 6";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getReportetUcUser($district_id) {
        $querypro = "SELECT distinct
                        District.location_name AS District,
                        users.pk_id as UserID,
                        users.user_name AS FullName,
                        users.address AS Address,
                        users.phone_number AS Phone
                    FROM
                        warehouses
                        INNER JOIN stakeholders ON stakeholders.pk_id = warehouses.stakeholder_office_id
                        INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                        INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                        INNER JOIN users ON warehouse_users.user_id = users.pk_id
                        INNER JOIN locations AS UC ON  warehouses.location_id = UC.pk_id
                    WHERE
                        warehouses.district_id = $district_id
                        AND warehouses.status = 1
                        AND stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                    ORDER BY
                        FullName";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getReportetUcUser1($districtId, $month, $year) {
        $querypro = "SELECT distinct
                        warehouses_data.created_by as UserID
                    FROM
                        warehouses
                        INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                        warehouses.district_id = $districtId
                        AND warehouses.status = 1    
                        AND DATE_FORMAT(warehouses_data.reporting_start_date, '%m-%Y') = '" . $month . "-" . $year . "  '
                        AND stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses_data.created_by != 2
                        AND warehouses_data.issue_balance IS NOT NULL AND warehouses_data.issue_balance != 0
                    GROUP BY
                        warehouses_data.created_by";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getReportetUcUser2($districtId, $month, $year) {
        $querypro = "SELECT
                        UC.pk_id as PkLocID
                    FROM
                        locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                        warehouses.district_id = $districtId
                        AND warehouses.status = 1    
                        AND DATE_FORMAT(warehouses_data.reporting_start_date, '%m-%Y') = '" . $month . "-" . $year . "  '
                        AND warehouses_data.issue_balance IS NOT NULL AND warehouses_data.issue_balance != 0
                        AND stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                    GROUP BY
                        UC.pk_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getReportetUcUser3($user_id) {
        $querypro = "SELECT DISTINCT
                        locations.pk_id AS UCId,
                        locations.location_name as UCs
                    FROM
                        warehouse_users
                        INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
                        INNER JOIN locations ON warehouses.location_id = locations.pk_id
                    WHERE
                       warehouses.status = 1 and warehouse_users.user_id = " . $user_id;

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedWarehouseList($districtId) {
        $querypro = "SELECT
                        warehouses.warehouse_name AS WHName,
                        District.location_name AS District,
                        users.user_name AS FullName,
                        users.email AS Email,
                        users.address AS Address,
                        users.phone_number AS Phone,
                        warehouses.pk_id AS WHId,
                        UC.location_name AS UCName,
                        warehouses.location_id as locid
                    FROM
                        warehouses
                        INNER JOIN stakeholders ON stakeholders.pk_id = warehouses.stakeholder_office_id
                        INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                        INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                        INNER JOIN users ON warehouse_users.user_id = users.pk_id
                        LEFT JOIN locations AS UC ON warehouses.location_id = UC.pk_id
                    WHERE
                        warehouses.district_id = $districtId
                        AND warehouses.status = 1    
                        AND stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                    GROUP BY
                        warehouses.pk_id
                    ORDER BY
                        warehouses.warehouse_name ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getReportedWarehouseList1($districtId, $month, $year) {
        $querypro = "SELECT
                        warehouses.pk_id as wh_id
                    FROM
                        warehouses
                        INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                        warehouses.district_id = $districtId
                        AND DATE_FORMAT(warehouses_data.reporting_start_date, '%m-%Y') = '" . $month . "-" . $year . "  '
                        AND stakeholders.geo_level_id = 6
                        AND warehouses.status = 1  
                        AND warehouses.stakeholder_id = 1
                        AND warehouses_data.issue_balance IS NOT NULL
                        AND warehouses_data.issue_balance != 0
                    GROUP BY
                        warehouses.pk_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getStakeholderByWarehouseId($wh_id) {
        $querypro = "SELECT
                        warehouses.stakeholder_id
                    FROM
                        warehouses
                    WHERE
                        pk_id=" . $wh_id;

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function GetWarehouseLevelById($id) {
        $querypro = "SELECT
			stakeholders.geo_level_id
			FROM
			warehouses
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			WHERE
			warehouses.pk_id = $id AND warehouses.status = 1 ";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getPreviousMonthReportDate($thismonth) {
        $new_date_temp = $this->addDate($thismonth, - 1);
        return $new_date_temp->format('Y-m-d');
    }

    public function getMonthYearByWHID($wh_id) {
        $querypro = " SELECT DISTINCT
                    MONTH(warehouses_data.reporting_start_date) as report_month,
                    YEAR(warehouses_data.reporting_start_date) as report_year,
                    warehouses.location_id as locid
                    FROM
                    warehouses_data
                    INNER JOIN warehouses ON warehouses_data.warehouse_id = warehouses.pk_id
                    WHERE
                    warehouses_data.warehouse_id = $wh_id AND warehouses.status = 1  
                    ORDER BY
                    warehouses_data.reporting_start_date DESC";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getMonthlyReceiveQuantity($mm, $yy, $wh_id, $stkid) {
        $querypro = "SELECT getMonthlyRcvQtyWH($mm,$yy,1,$wh_id) as rcv,item_pack_sizes.item_name as itm_name,item_pack_sizes.pk_id as itm_id,item_pack_sizes.number_of_doses as doses_per_unit"
                . " FROM `item_pack_sizes` WHERE `status`='1' AND item_category_id <> 3 AND `pk_id` IN "
                . "(SELECT `item_pack_size_id` FROM `stakeholder_item_pack_sizes` WHERE `stakeholder_id` = $stkid) ORDER BY
                 item_pack_sizes.list_rank ASC";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getPopUpData($wh_id, $PrevMonthDate, $rsRow1) {
        $querypro = "SELECT * FROM warehouses_data WHERE `warehouse_id`='" . $wh_id . "' AND reporting_start_date='" . $PrevMonthDate . "' AND `item_pack_size_id`='$rsRow1'";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function reportedDistrictsByUcGraphs($distId) {

        $querypro = "SELECT
			COUNT(DISTINCT UC.pk_id) AS reported
		FROM
			locations AS District
		INNER JOIN locations AS UC ON District.pk_id = UC.district_id
		INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
		INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
		WHERE
			UC.geo_level_id = 6
                        AND warehouses.status = 1  
                        AND  warehouses_data.issue_balance IS NOT NULL
                        AND warehouses_data.issue_balance != 0
			AND District.pk_id = '" . $distId . "'";
        return $querypro;

//        $this->_em = Zend_Registry::get('doctrine');
//        $row = $this->_em->getConnection()->prepare($querypro);
//
//        $row->execute();
//        return $row->fetchAll();
    }

    public function reportedDistrictsByUserGraphs($distId) {

        $querypro = "SELECT
				COUNT(DISTINCT warehouses_data.created_by) AS reported
			FROM
				warehouses
			INNER JOIN stakeholders ON  warehouses.stakeholder_office_id = stakeholders.pk_id
			INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
			INNER JOIN locations ON warehouses.district_id = locations.pk_id
			INNER JOIN warehouses_data ON warehouses_data.warehouse_id = warehouse_users.warehouse_id
			WHERE
				stakeholders.geo_level_id = 6
                                AND warehouses.status = 1  
				AND warehouses_data.created_by != 2
                                AND warehouses_data.issue_balance IS NOT NULL
                                AND warehouses_data.issue_balance != 0
				AND locations.pk_id = '" . $distId . "'";

        return $querypro;
//        $this->_em = Zend_Registry::get('doctrine');
//        $row = $this->_em->getConnection()->prepare($querypro);
//
//        $row->execute();
//        return $row->fetchAll();
    }

    public function reportedDistrictsByFacilityGraphs($distId) {

        $querypro = "SELECT
				COUNT(DISTINCT warehouses_data.warehouse_id) AS reported
			FROM
				warehouses_data
			INNER JOIN warehouses ON warehouses.pk_id = warehouses_data.warehouse_id
			INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
			INNER JOIN locations ON warehouses.district_id = locations.pk_id
                        INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
			WHERE
				stakeholders.geo_level_id = 6
                                AND warehouses.status = 1  
                                AND warehouses.stakeholder_id = 1
                                AND warehouses_data.issue_balance IS NOT NULL
                                AND warehouses_data.issue_balance != 0
				AND locations.pk_id = '" . $distId . "'";

        return $querypro;
//        $this->_em = Zend_Registry::get('doctrine');
//        $row = $this->_em->getConnection()->prepare($querypro);
//
//        $row->execute();
//        return $row->fetchAll();
    }

    public function reportedDistrictsGraphs($qry, $month, $year) {
        $querypro = "$qry AND DATE_FORMAT(warehouses_data.reporting_start_date, '%m-%Y') = '" . $month . "-" . $year . "  '";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getProductWiseDistrictsYearlyReport($report_indicator, $str_date, $in_prov) {
        $col_name = "";
        $level = "";
        $prov_clause = "";

        if ($in_prov == 'all') {
            $prov_clause = "";
        } else {
            $prov_clause = "AND warehouses.province_id = '" . $in_prov . "'";
        }

        if ($report_indicator == 1) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=6';
        } else if ($report_indicator == 2) {
            $col_name = 'SUM(warehouses_data.closing_balance) AS total';
            $level = '>= 2';
        } else if ($report_indicator == 3) {
            $col_name = 'SUM(warehouses_data.received_balance) AS total';
            $level = '=2';
        } else if ($report_indicator == 4) {
            $col_name = 'SUM(warehouses_data.issue_balance) AS total';
            $level = '=2';
        }

        $str_qry = "SELECT
                    $col_name,
                    warehouses_data.item_pack_size_id
                    FROM
                    item_pack_sizes
                    INNER JOIN warehouses_data ON item_pack_sizes.pk_id = warehouses_data.item_pack_size_id
                    INNER JOIN warehouses ON warehouses_data.warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    item_pack_sizes.item_category_id <> 3
                    AND warehouses.status = 1  
                    AND stakeholders.geo_level_id $level
                    $prov_clause
                    AND DATE_FORMAT(warehouses_data.reporting_start_date, '%Y-%m') = '" . $str_date . "'
                    GROUP BY warehouses_data.item_pack_size_id
                    ORDER BY item_pack_sizes.list_rank ASC";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getItemPackSizesDoses($wh_id) {
        if (empty($wh_id)) {
            $wh_id = '159';
        }

        $str_qry = "SELECT
                        item_pack_sizes.pk_id,
                        item_pack_sizes.item_name,
                        item_pack_sizes.description,
                        Sum(stock_batch.quantity) * item_pack_sizes.number_of_doses AS quantity
                FROM
                        stock_batch
                INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = stock_batch.item_pack_size_id
                WHERE
                item_pack_sizes.item_category_id = 1 AND
                stock_batch.warehouse_id = $wh_id
                GROUP BY
                        item_name";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getWeightedAvg($wh_id, $item_id) {
        if (empty($wh_id)) {
            $wh_id = '159';
        }

        $str_qry = "SELECT 
        ROUND(SUM(A.total) / SUM(A.Qty)) AS val
        FROM (SELECT
                stock_batch.quantity * item_pack_sizes.number_of_doses AS Qty,
                TIMESTAMPDIFF(MONTH, stock_master.transaction_date, stock_batch.expiry_date)*
                (stock_batch.quantity * item_pack_sizes.number_of_doses) AS total
        FROM
                stock_master
        INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_detail.stock_batch_id
        INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = stock_batch.item_pack_size_id
        WHERE
                item_pack_sizes.pk_id = $item_id and
                stock_batch.warehouse_id = $wh_id
        GROUP BY
                stock_batch.pk_id
) A";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShelfLife6($wh_id, $item_id, $time_period1) {
        //$time = strtotime($time_period1);
        $time_period = App_Controller_Functions::dateToDbFormat($time_period1);

        // echo $time_period = date_format($time, Y-m-d);

        if (empty($wh_id)) {
            $wh_id = '159';
        }
        $str_qry = "SELECT

          A.item_pack_size_id,
          A.item_name,
          A.totalQty * A.number_of_doses as totalQuantity,
          A.Expire6Months * A.number_of_doses as 6months,
          A.Expire12Months  * A.number_of_doses as 12months,
          ROUND(((A.Expire6Months / A.totalQty) * 100), 1) * A.number_of_doses AS Expire6Months,
          ROUND(((A.Expire12Months / A.totalQty) * 100), 1) * A.number_of_doses AS Expire12Months,
					A.min6Month,
					A.max6Month,
					A.min12Month,
					A.max12Month
         FROM (SELECT
          stock_batch.item_pack_size_id,
          item_pack_sizes.item_name,
          SUM(stock_batch.quantity) AS totalQty,
					item_pack_sizes.number_of_doses,
					(stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH)),
					MIN(stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH)) AS min6Month,
					MAX(stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH)) AS max6Month,
					MIN((stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH))) AS min12Month,
					MAX((stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH))) AS max12Month,
          SUM(IF (stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH), stock_batch.quantity, 0)) AS Expire6Months,
          SUM(IF (stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH), stock_batch.quantity, 0)) AS Expire12Months
         FROM
          stock_batch
         INNER JOIN item_pack_sizes ON stock_batch.item_pack_size_id = item_pack_sizes.pk_id
         WHERE
         stock_batch.item_pack_size_id IS NOT NULL
         AND stock_batch.quantity > 0
         AND stock_batch.item_pack_size_id = $item_id
         AND stock_batch.warehouse_id = $wh_id
         ) A";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShelfLifeMonths6($wh_id, $item_id, $time_period1) {

        $time_period = App_Controller_Functions::dateToDbFormat($time_period1);
        if (empty($wh_id)) {
            $wh_id = '159';
        }
        $str_qry = "SELECT
                        sum(stock_batch.quantity * item_pack_sizes.number_of_doses) AS Qty,
                        Max(TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date)) as Max6months,
                        Min(TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date)) as Min6months,
                        Min(DATE_FORMAT(stock_batch.expiry_date, '%Y-%m-%d')) as minExpiryDate
                FROM
                        stock_master
                INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_detail.stock_batch_id
                INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = stock_batch.item_pack_size_id
                WHERE
                
                item_pack_sizes.pk_id = $item_id and
                stock_batch.warehouse_id = $wh_id 
                    AND stock_batch.expiry_date > CURDATE()
                and TIMESTAMPDIFF(MONTH,  '$time_period', stock_batch.expiry_date) <= 6 ";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShelfLife12($wh_id, $item_id, $time_period1) {
        $time_period = App_Controller_Functions::dateToDbFormat($time_period1);
        if (empty($wh_id)) {
            $wh_id = '159';
        }
        $str_qry = "SELECT

          A.item_pack_size_id,
          A.item_name,
          A.totalQty * A.number_of_doses as totalQuantity,
          A.Expire6Months * A.number_of_doses as 6months,
          A.Expire12Months  * A.number_of_doses as 12months,
          ROUND(((A.Expire6Months / A.totalQty) * 100), 1) * A.number_of_doses AS Expire6Months,
          ROUND(((A.Expire12Months / A.totalQty) * 100), 1) * A.number_of_doses AS Expire12Months,
					A.min6Month,
					A.max6Month,
					A.min12Month,
					A.max12Month
         FROM (SELECT
          stock_batch.item_pack_size_id,
          item_pack_sizes.item_name,
          SUM(stock_batch.quantity) AS totalQty,
					item_pack_sizes.number_of_doses,
					(stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH)),
					MIN(stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH)) AS min6Month,
					MAX(stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH)) AS max6Month,
					MIN((stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH))) AS min12Month,
					MAX((stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH))) AS max12Month,
          SUM(IF (stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH), stock_batch.quantity, 0)) AS Expire6Months,
          SUM(IF (stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH), stock_batch.quantity, 0)) AS Expire12Months
         FROM
          stock_batch
         INNER JOIN item_pack_sizes ON stock_batch.item_pack_size_id = item_pack_sizes.pk_id
         WHERE
         stock_batch.item_pack_size_id IS NOT NULL
         AND stock_batch.quantity > 0
         AND stock_batch.item_pack_size_id = $item_id
         AND stock_batch.warehouse_id = $wh_id
         ) A";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShelfLife24($wh_id, $item_id, $time_period1) {
        $time_period = App_Controller_Functions::dateToDbFormat($time_period1);
        if (empty($wh_id)) {
            $wh_id = '159';
        }
        $str_qry = "SELECT

          A.item_pack_size_id,
          A.item_name,
          A.totalQty * A.number_of_doses as totalQuantity,
          A.Expire6Months * A.number_of_doses as 6months,
          A.Expire12Months  * A.number_of_doses as 12months,
          A.Expire24Months  * A.number_of_doses as 24months,
          ROUND(((A.Expire6Months / A.totalQty) * 100), 1) * A.number_of_doses AS Expire6Months,
          ROUND(((A.Expire12Months / A.totalQty) * 100), 1) * A.number_of_doses AS Expire12Months,
          ROUND(((A.Expire24Months / A.totalQty) * 100), 1) * A.number_of_doses AS Expire24Months,
					A.min6Month,
					A.max6Month,
					A.min12Month,
					A.max12Month
         FROM (SELECT
          stock_batch.item_pack_size_id,
          item_pack_sizes.item_name,
          SUM(stock_batch.quantity) AS totalQty,
					item_pack_sizes.number_of_doses,
					(stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH)),
					MIN(stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH)) AS min6Month,
					MAX(stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH)) AS max6Month,
					MIN((stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH))) AS min12Month,
					MAX((stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH))) AS max12Month,
                                        SUM(IF (stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 6 MONTH), stock_batch.quantity, 0)) AS Expire6Months,
                                        SUM(IF (stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE('$time_period', INTERVAL 12 MONTH), stock_batch.quantity, 0)) AS Expire12Months,
                                        SUM(IF (stock_batch.expiry_date > ADDDATE('$time_period', INTERVAL 12 MONTH) , stock_batch.quantity, 0)) AS Expire24Months
                  
FROM
          stock_batch
         INNER JOIN item_pack_sizes ON stock_batch.item_pack_size_id = item_pack_sizes.pk_id
         WHERE
         stock_batch.item_pack_size_id IS NOT NULL
         AND stock_batch.quantity > 0
         AND stock_batch.item_pack_size_id = $item_id
         AND stock_batch.warehouse_id = $wh_id
         ) A";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShelfLifeMonths12($wh_id, $item_id, $time_period1) {


        $time_period = App_Controller_Functions::dateToDbFormat($time_period1);
        if (empty($wh_id)) {
            $wh_id = '159';
        }
        $str_qry = "SELECT
              sum(stock_batch.quantity * item_pack_sizes.number_of_doses) AS Qty,
              Max(TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date)) as Max12months,
              Min(TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date)) as Min12months

                FROM
                    stock_master
            INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_detail.stock_batch_id
            INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = stock_batch.item_pack_size_id
            WHERE
                    item_pack_sizes.pk_id = $item_id and
            stock_batch.warehouse_id = $wh_id 
                AND stock_batch.expiry_date > CURDATE()
            and TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date) > 6  
            and TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date) <= 12";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getShelfLifeMonths24($wh_id, $item_id, $time_period1) {


        $time_period = App_Controller_Functions::dateToDbFormat($time_period1);
        if (empty($wh_id)) {
            $wh_id = '159';
        }
        $str_qry = "SELECT
              sum(stock_batch.quantity * item_pack_sizes.number_of_doses) AS Qty,
              Max(TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date)) as Max24months,
              Min(TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date)) as Min24months

                FROM
                    stock_master
            INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_detail.stock_batch_id
            INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = stock_batch.item_pack_size_id
            WHERE
                    item_pack_sizes.pk_id = $item_id and
            stock_batch.warehouse_id = $wh_id 
                AND stock_batch.expiry_date > CURDATE()
            and TIMESTAMPDIFF(MONTH, '$time_period', stock_batch.expiry_date) > 12  
            ";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function ucWiseReport1($wh_id, $item_id, $age_id, $report_year, $report_month, $i) {
        if (!empty($i)) {
            $where = "AND hf_data_detail.vaccine_schedule_id = $i";
        } else {

            $where = "";
        }

        $report_date1 = $report_year . "-" . $report_month;
        $report_date = date('Y-m', strtotime($report_date1));


        $str_qry = "SELECT
        locations.location_name,
	locations.pk_id,
	(
		hf_data_detail.fixed_inside_uc_male + hf_data_detail.fixed_inside_uc_female
	) inside_uc,
	(
		hf_data_detail.fixed_outside_uc_male + hf_data_detail.fixed_outside_uc_female
	) outside_uc,
	(
		hf_data_detail.referal_male + hf_data_detail.referal_female
	) referal_uc,
	(
		hf_data_detail.fixed_inside_uc_male + hf_data_detail.fixed_inside_uc_female + hf_data_detail.fixed_outside_uc_male + hf_data_detail.fixed_outside_uc_female + hf_data_detail.referal_male + hf_data_detail.referal_female
	) AS total
            FROM
            warehouses
            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
            INNER JOIN hf_data_detail ON hf_data_master.pk_id = hf_data_detail.hf_data_master_id
            INNER JOIN locations ON warehouses.location_id = locations.pk_id
            WHERE
            warehouses.location_id  = $wh_id
            AND hf_data_master.item_pack_size_id = $item_id
            AND hf_data_detail.age_group_id = $age_id
            AND DATE_FORMAT(
                    hf_data_master.reporting_start_date,
                    '%Y-%m'
            ) = '$report_date'
            Group BY locations.pk_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $row = $row->fetchAll();
        return $row[0];
    }

    public function ucWiseReport11($wh_id, $item_id, $report_year, $report_month) {

        $report_date1 = $report_year . "-" . $report_month;
        $report_date = date('Y-m', strtotime($report_date1));
        $str_qry = "SELECT
            hf_data_master.opening_balance,
            hf_data_master.received_balance,
            (  hf_data_master.opening_balance +
            hf_data_master.received_balance) total_doses,
            hf_data_master.issue_balance,
            hf_data_master.closing_balance,
            hf_data_master.wastages 
            
           FROM
                warehouses
                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
               
                WHERE
               	warehouses.location_id = $wh_id
                    AND
                hf_data_master.item_pack_size_id = $item_id    
                
                AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m') = '" . $report_date . "'   
                GROUP BY location_id";



        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $row = $row->fetchAll();
        return $row[0];
    }

    public function ucWiseReport2($wh_id, $item_id, $age_id, $report_year, $report_month, $i) {
        if (!empty($i)) {
            $where = "AND hf_data_detail.vaccine_schedule_id = $i";
        } else {

            $where = "";
        }

        $report_date1 = $report_year . "-" . $report_month;
        $report_date = date('Y-m', strtotime($report_date1));


        $str_qry = "SELECT
        locations.location_name,
	locations.pk_id,
	SUM(
		hf_data_detail.fixed_inside_uc_male + hf_data_detail.fixed_inside_uc_female
	) inside_uc,
	SUM(
		hf_data_detail.fixed_outside_uc_male + hf_data_detail.fixed_outside_uc_female
	) outside_uc,
	SUM(
		hf_data_detail.referal_male + hf_data_detail.referal_female
	) referal_uc,
	SUM(
		hf_data_detail.fixed_inside_uc_male + hf_data_detail.fixed_inside_uc_female + hf_data_detail.fixed_outside_uc_male + hf_data_detail.fixed_outside_uc_female + hf_data_detail.referal_male + hf_data_detail.referal_female
	) AS total
            FROM
            warehouses
            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
            INNER JOIN hf_data_detail ON hf_data_master.pk_id = hf_data_detail.hf_data_master_id
            INNER JOIN locations ON warehouses.location_id = locations.pk_id
            WHERE
            warehouses.location_id  = $wh_id
            AND hf_data_master.item_pack_size_id = $item_id
            
            AND DATE_FORMAT(
                    hf_data_master.reporting_start_date,
                    '%Y-%m'
            ) = '$report_date'
            Group BY locations.pk_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $row = $row->fetchAll();
        return $row[0];
    }

    public function ucWiseReport3($wh_id, $item_id, $age_id, $report_year, $report_month, $i) {
        if (!empty($i)) {
            $where = "AND hf_data_detail.vaccine_schedule_id = $i";
        } else {

            $where = "";
        }

        $report_date1 = $report_year . "-" . $report_month;
        $report_date = date('Y-m', strtotime($report_date1));


        $str_qry = "SELECT
        locations.location_name,
	locations.pk_id,
	SUM(hf_data_detail.fixed_inside_uc_male) inside_uc_male,
        SUM(hf_data_detail.fixed_inside_uc_female) inside_uc_female,
	SUM(hf_data_detail.fixed_outside_uc_male) outside_uc_male,
        SUM(hf_data_detail.fixed_outside_uc_female) outside_uc_female,
	SUM(
	 hf_data_detail.fixed_inside_uc_male + hf_data_detail.fixed_inside_uc_female + hf_data_detail.fixed_outside_uc_male + hf_data_detail.fixed_outside_uc_female
	) AS total
            FROM
            warehouses
            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
            INNER JOIN hf_data_detail ON hf_data_master.pk_id = hf_data_detail.hf_data_master_id
            INNER JOIN locations ON warehouses.location_id = locations.pk_id
            WHERE
            warehouses.location_id  = $wh_id
            AND hf_data_master.item_pack_size_id = $item_id
            
            AND DATE_FORMAT(
                    hf_data_master.reporting_start_date,
                    '%Y-%m'
            ) = '$report_date'
            Group BY locations.pk_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $row = $row->fetchAll();
        return $row[0];
    }

}

?>