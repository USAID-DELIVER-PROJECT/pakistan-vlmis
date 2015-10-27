<?php

/**
 * Model_ItemPackSizes
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmaleyetii@gmail.com>
 * @version    2
 */
class Model_NonCcmLocations extends Model_Base {

    private $_table;

    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('NonCcmLocations');
    }

    public function placement() {
        $warehouse_id = $this->_identity->getWarehouseId();
        $form_values = $this->form_values;

        $non_ccm_location = new NonCcmLocations();
        $list_detail = new ListDetail();
        $list_detail1 = new Model_ListDetail();
        $area = $this->_em->find("ListDetail", $form_values['area']);
        $row = $this->_em->find("ListDetail", $form_values['row']);
        $rack = $this->_em->find("ListDetail", $form_values['rack']);
        //$rack_information_id = $this->_em->find("RackInformation", $form_values['rack_information_id']);
        $pallet = $this->_em->find("ListDetail", $form_values['pallet']);
        $level = $this->_em->find("ListDetail", $form_values['level']);

        $non_ccm_location->setArea($area);
        $non_ccm_location->setRow($row);
        $non_ccm_location->setRack($rack);
        //$non_ccm_location->setRackInformation($rack_information_id);
        $non_ccm_location->setPallet($pallet);
        $non_ccm_location->setLevel($level);

        $locationName = $area->getListValue() . $row->getListValue() . $rack->getListValue() .  $level->getListValue(). $pallet->getListValue();

        $str_sql = $this->_em->createQueryBuilder()
                ->select('ncl.locationName')
                ->from("NonCcmLocations", "ncl")
                ->where("ncl.locationName = '$locationName'")
                ->andwhere("ncl.warehouse = '$warehouse_id'");
        $result = $str_sql->getQuery()->getResult();
        if (count($result) > 0) {
            return 0;
        } else {
            $non_ccm_location->setLocationName($locationName);
            $warehouse_id = $this->_identity->getWarehouseId();
            $warehouse = $this->_em->find("Warehouses", $warehouse_id);
            $non_ccm_location->setWarehouse($warehouse);
            $this->_em->persist($non_ccm_location);
            $this->_em->flush();
            $location_id = $non_ccm_location->getPkId();
            $placement_location = new PlacementLocations();
            $placement_location->setLocationId($location_id);
            $placement_location->setLocationBarcode($locationName);
            $loctype_id = $this->_em->find("ListDetail", Model_PlacementLocations::LOCATIONTYPE_NONCCM);
            $placement_location->setLocationType($loctype_id);
            $this->_em->persist($placement_location);
            $this->_em->flush();
            // $this->_em->persist($non_ccm_location);
            return 1;
        }
    }

    public function checkLocation($locationName) {
        $str_sql = $this->_em->createQueryBuilder()
                ->select('ncl.locationName')
                ->from("NonCcmLocations", "ncl")
                ->where("ncl.locationName = '$locationName'");


        //echo $str_sql->getQuery()->getSql();
        // die();
        $result = $str_sql->getQuery()->getResult();
        if (count($result) <= 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function getLocationsName() {
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                    non_ccm_locations.location_name as locationName,
                    non_ccm_locations.pk_id as pkId,
                    non_ccm_locations.warehouse_id
                    FROM
                    non_ccm_locations
                    WHERE
                    non_ccm_locations.warehouse_id = $warehouse_id";

        $rec = $this->_em->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        //return $result;
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getBins() {
        return $this->_table->findBy($this->form_values);
    }

    public function getNonCcmLocations() {
        $str_sql = $this->_em->createQueryBuilder()
                ->select('ncl.pkId, ncl.locationName')
                ->from("NonCcmLocations", "ncl");
        // echo $str_sql->getQuery()->getSql();
        // die();
        $result = $str_sql->getQuery()->getResult();
        return $result;
    }

    public function updatePlacement() {

        $form_values = $this->form_values;
        $non_ccm_location = $this->_table->find($form_values['placement_id']);

        $area = $this->_em->find("ListDetail", $form_values['area']);
        $row = $this->_em->find("ListDetail", $form_values['row']);
        $rack = $this->_em->find("ListDetail", $form_values['rack']);
        $rack_information_id = $this->_em->find("RackInformation", $form_values['rack_information_id']);
        $pallet = $this->_em->find("ListDetail", $form_values['pallet']);
        $level = $this->_em->find("ListDetail", $form_values['level']);

        $non_ccm_location->setArea($area);
        $non_ccm_location->setRow($row);
        $non_ccm_location->setRack($rack);
        $non_ccm_location->setRackInformation($rack_information_id);
        $non_ccm_location->setPallet($pallet);
        $non_ccm_location->setLevel($level);

        $locationName = $area->getListValue() . $row->getListValue() . $rack->getListValue() . $pallet->getListValue() . $level->getListValue();
        $non_ccm_location->setLocationName($locationName);

        $str_sql = $this->_em->createQueryBuilder()
                ->select('ncl.locationName')
                ->from("NonCcmLocations", "ncl")
                ->where("ncl.locationName = '$locationName'");
        $result = $str_sql->getQuery()->getResult();
        if (count($result) > 0) {
            return 0;
        } else {
            $non_ccm_location->setLocationName($locationName);
            $warehouse_id = $this->_identity->getWarehouseId();
            $warehouse_id = $this->_em->find("Warehouses", $warehouse_id);
            $non_ccm_location->setWarehouse($warehouse_id);
            $this->_em->persist($non_ccm_location);
            $this->_em->flush();
            $location_id = $non_ccm_location->getPkId();
            $placement_location = new PlacementLocations();
            $placement_location->setLocationId($location_id);
            $placement_location->setLocationBarcode($locationName);
            $loctype_id = $this->_em->find("ListDetail", Model_PlacementLocations::LOCATIONTYPE_NONCCM);
            $placement_location->setLocationType($loctype_id);
            $this->_em->persist($non_ccm_location);
            $this->_em->flush();
            return 1;
        }
    }

    public function locationStatus($area, $lvl = "") {
        $warehouse_id = $this->_identity->getWarehouseId();
//       $str_sql1 = "SELECT
//        non_ccm_locations.location_name,
//        placement_locations.pk_id as placement_locationsid,
//        placement_locations.location_type,
//        placement_locations.pk_id,
//        non_ccm_locations.pk_id
//        FROM
//        non_ccm_locations
//        INNER JOIN placement_locations ON placement_locations.location_id = non_ccm_locations.pk_id
//        WHERE
//        placement_locations.location_type =" . Model_ListDetail::Non_CCM . " AND
//        non_ccm_locations.area =" . $area." AND
//        non_ccm_locations.warehouse_id =" . $warehouse_id;
//        if (!empty($lvl)) {
//            $str_sql1 .= " AND non_ccm_locations.row=" . $lvl;
//        }
        $str_sql1 = "SELECT
	non_ccm_locations.location_name,
	placement_locations.pk_id AS placement_locationsid,
	placement_locations.location_type,
	placement_locations.pk_id,
	non_ccm_locations.pk_id,
  rows.rank AS myrow,
  rows.list_value AS myrowvalue,
	Pallets.list_value AS mypallet,
	racks.list_value AS myrack
FROM
	non_ccm_locations
INNER JOIN placement_locations ON placement_locations.location_id = non_ccm_locations.pk_id
INNER JOIN list_detail AS rows ON non_ccm_locations.`row` = rows.pk_id
INNER JOIN list_detail AS racks ON non_ccm_locations.rack = racks.pk_id
INNER JOIN list_detail AS Pallets ON non_ccm_locations.pallet = Pallets.pk_id
WHERE
	placement_locations.location_type =" . Model_ListDetail::Non_CCM . "
AND non_ccm_locations.area = " . $area . "
AND non_ccm_locations.`level` = " . $lvl . "
AND non_ccm_locations.warehouse_id = " . $warehouse_id . "
ORDER BY
	myrow,
	myrack,
	mypallet";
        // echo $str_sql1;exit;
        $rec = $this->_em->getConnection()->prepare($str_sql1);

        $rec->execute();
        $result = $rec->fetchAll();
        //return $result;
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getMaxRow($area, $lvl = "") {
        $warehouse_id = $this->_identity->getWarehouseId();

        $SQL = "SELECT ifnull(max(rows.rank),0) AS rows
                                FROM
                                non_ccm_locations
                                INNER JOIN list_detail AS rows ON non_ccm_locations.`row` = rows.pk_id
				WHERE
					area=" . $area . " AND level=" . $lvl . " AND warehouse_id =" . $warehouse_id . "
				GROUP BY
					non_ccm_locations.warehouse_id";

        $str_sql1 = $this->_em->getConnection()->prepare($SQL);



//        $str_sql1 = $this->_em->getConnection()->prepare("SELECT
//max(list_detail.list_value) as maxVal
//FROM
//non_ccm_locations
//INNER JOIN list_detail ON non_ccm_locations.`row` = list_detail.pk_id
//WHERE
//list_detail.list_master_id =" . Model_ListMaster::ROW);

        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {
            //return $result[0]['maxVal'];
            return $result[0]['rows'];
            //  return $result;
        } else {
            return false;
        }
    }

    public function getMinRow() {
        $str_sql = $this->_em->getConnection()->prepare("SELECT
min(list_detail.list_value)
FROM
non_ccm_locations
INNER JOIN list_detail ON non_ccm_locations.`row` = list_detail.pk_id
WHERE
list_detail.list_master_id =" . Model_ListMaster::ROW);

        $str_sql->execute();
        $result = $str_sql->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getMaxRack($area, $lvl = "") {
        $warehouse_id = $this->_identity->getWarehouseId();

        $sql = "SELECT ifnull(count(rack.list_value),0) AS racks
                                FROM
                                non_ccm_locations
                                INNER JOIN list_detail AS rack ON non_ccm_locations.`rack` = rack.pk_id
				WHERE
					area=" . $area . " AND level=" . $lvl . " AND warehouse_id =" . $warehouse_id . "
				GROUP BY
					non_ccm_locations.warehouse_id";


        $str_sql1 = $this->_em->getConnection()->prepare($sql);

        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {
            //return $result;
            return $result[0]['racks'];
        } else {
            return false;
        }
    }

    public function getMinRack() {
        $str_sql = $this->_em->getConnection()->prepare("SELECT
min(list_detail.list_value)
FROM
non_ccm_locations
INNER JOIN list_detail ON non_ccm_locations.`rack` = list_detail.pk_id
WHERE
list_detail.list_master_id =" . Model_ListMaster::RACK);

        $str_sql->execute();
        $result = $str_sql->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getMaxPallet() {

        $str_sql1 = $this->_em->getConnection()->prepare("SELECT
max(list_detail.list_value) as maxVal
FROM
non_ccm_locations
INNER JOIN list_detail ON non_ccm_locations.`pallet` = list_detail.pk_id
WHERE
list_detail.list_master_id =" . Model_ListMaster::PALLET);

        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {
            return $result[0]['maxVal'];
        } else {
            return false;
        }
    }

    public function getMinPallet() {
        $str_sql = $this->_em->getConnection()->prepare("SELECT
min(list_detail.list_value)
FROM
non_ccm_locations
INNER JOIN list_detail ON non_ccm_locations.`pallet` = list_detail.pk_id
WHERE
list_detail.list_master_id =" . Model_ListMaster::PALLET);

        $str_sql->execute();
        $result = $str_sql->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getNonCcmLocationId($bin_id) {
        $str_sql = "SELECT
placement_locations.location_id
FROM
placement_locations
WHERE
placement_locations.pk_id =" . $bin_id;

        $str_sql1 = $this->_em->getConnection()->prepare($str_sql);

        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {
            //return $result;
            return $result[0]['location_id'];
        } else {
            return false;
        }
    }

}
