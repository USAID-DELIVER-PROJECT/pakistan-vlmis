<?php

/**
 * Model_CcmAssetTypes
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmaleyetii@gmail.com>
 * @version    2
 */
class Model_CcmAssetTypes extends Model_Base {

    private $_table;

    const REFRIGERATOR = 1;
    const VACCINECARRIER = 2;
    const COLDROOM = 3;
    const ICEPACKS = 4;
    const VOLTAGEREGULATOR = 5;
    const GENERATOR = 6;
    const TRANSPORT = 7;

    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('CcmAssetTypes');
    }

    public function getAssetSubTypes() {
        $str_sql = $this->_em->createQueryBuilder()
                ->select("a.pkId,a.assetTypeName")
                ->from('CcmAssetTypes', 'a');
        if ($this->form_values['parent_id'] == 'childs') {
            $str_sql->where('a.parent <> 0');
        } else if (!empty($this->form_values['parent_id'])) {
            $str_sql->where('a.parent = ' . $this->form_values['parent_id']);
        } else {
            $str_sql->where('a.parent =0');
        }
        //echo $str_sql->getQuery()->getSql();
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function getAllAssetSubTypes($order = null, $sort = null) {
        $str_sql = $this->_em->createQueryBuilder()
                ->select("a.pkId,a.status,a.assetTypeName,u.userName,p.assetTypeName as assetType")
                ->from('CcmAssetTypes', 'a')
                ->join('a.createdBy', 'u')
                ->join('a.parent', 'p')
                ->where('p.pkId <> 0 ');


        if (!empty($this->form_values['assetSubType'])) {
            //$str_sql->andWhere('a.assetTypeName LIKE "%' .  str_replace("'","\'",$this->form_values['assetSubType']) . '%"');
            //$str_sql->andWhere('a.assetTypeName LIKE "%' .  $this->form_values['assetSubType'] . '%"');
            //$str_sql->andWhere('a.assetTypeName ' . $this->form_values['assetSubType']) . '\');
        }

        if ($order == 'asset_sub_type') {
            $str_sql->orderBy("a.assetTypeName", $sort);
        }

        if ($order == 'created_by') {
            $str_sql->orderBy("a.createdBy", $sort);
        }

        $str_qry = "SELECT
	c0_.pk_id AS pkId,
	c0_. STATUS AS status,
	c0_.asset_type_name AS assetTypeName,
	u1_.user_name AS userName,
	c2_.asset_type_name AS assetType
        FROM
                ccm_asset_types c0_
        INNER JOIN users u1_ ON c0_.created_by = u1_.pk_id
        INNER JOIN ccm_asset_types c2_ ON c0_.parent_id = c2_.pk_id
        WHERE
                c2_.pk_id <> 0";
        if (!empty($this->form_values['assetSubType'])) {
            $str_qry .= ' AND c0_.asset_type_name LIKE "%' . $this->form_values['assetSubType'] . '%"';
        }

        $str_qry .= " ORDER BY
                c0_.asset_type_name ASC";
        $row = $this->_em->getConnection()->prepare($str_qry);

        //echo $str_qry; exit;
        $row->execute();
        return $row->fetchAll();

        //return $str_sql->getQuery()->getResult();
    }

}
