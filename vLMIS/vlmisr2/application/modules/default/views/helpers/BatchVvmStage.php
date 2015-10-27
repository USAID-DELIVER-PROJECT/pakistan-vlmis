<?php

class Zend_View_Helper_BatchVvmStage extends Zend_View_Helper_Abstract {

    public function batchVvmStage($batch_id) {

        $em = Zend_Registry::get('doctrine');

        $str_sql = $em->createQueryBuilder()
                ->select("MAX(ps.vvmStage) as stage")
                ->from('PlacementSummary', 'ps')
                ->where("ps.stockBatch = $batch_id");

        $row = $str_sql->getQuery()->getResult();

        if (isset($row) && count($row) > 0) {
            echo $row[0]['stage'];
        } else {
            echo '1';
        }
    }

}
