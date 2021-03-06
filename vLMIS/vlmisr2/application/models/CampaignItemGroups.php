<?php

/**
 * Model_CampaignReadinessUnionCouncil
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmaleyetii@gmail.com>
 * @version    2
 */
class Model_CampaignItemGroups extends Model_Base {

    private $_table;

    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('CampaignItemGroups');
    }

    public function getCampaignItemGroups() {
        $form_values = $this->form_values;

        $str_sql = $this->_em->createQueryBuilder()
                ->select("c.pkId,ips.itemName,c.ageGroup1Min,c.ageGroup1Max,c.ageGroup2Min,c.ageGroup2Max")
                ->from('CampaignItemGroups', 'c')
                ->join('c.itemPackSize', 'ips');
        if (!empty($form_values['item_id'])) {
            $str_sql->where("ips.pkId= '" . $form_values['item_id'] . "' ");
        }
        // echo $str_sql->getQuery()->getSql();exit;
        $rs = $str_sql->getQuery()->getResult();
        return $rs;
    }

}
