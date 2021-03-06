<?php

namespace Doctrine\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class CcmStatusHistoryProxy extends \CcmStatusHistory implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function getPkId()
    {
        $this->__load();
        return parent::getPkId();
    }

    public function setTemperatureAlarm($temperatureAlarm)
    {
        $this->__load();
        return parent::setTemperatureAlarm($temperatureAlarm);
    }

    public function getTemperatureAlarm()
    {
        $this->__load();
        return parent::getTemperatureAlarm();
    }

    public function setWorkingQuantity($workingQuantity)
    {
        $this->__load();
        return parent::setWorkingQuantity($workingQuantity);
    }

    public function getWorkingQuantity()
    {
        $this->__load();
        return parent::getWorkingQuantity();
    }

    public function setComments($comments)
    {
        $this->__load();
        return parent::setComments($comments);
    }

    public function getComments()
    {
        $this->__load();
        return parent::getComments();
    }

    public function setStatusDate($statusDate)
    {
        $this->__load();
        return parent::setStatusDate($statusDate);
    }

    public function getStatusDate()
    {
        $this->__load();
        return parent::getStatusDate();
    }

    public function setUtilization(\CcmStatusList $utilization)
    {
        $this->__load();
        return parent::setUtilization($utilization);
    }

    public function getUtilization()
    {
        $this->__load();
        return parent::getUtilization();
    }

    public function setCcmAssetType(\CcmAssetTypes $ccmAssetType)
    {
        $this->__load();
        return parent::setCcmAssetType($ccmAssetType);
    }

    public function getCcmAssetType()
    {
        $this->__load();
        return parent::getCcmAssetType();
    }

    public function setCcm(\ColdChain $ccm)
    {
        $this->__load();
        return parent::setCcm($ccm);
    }

    public function getCcm()
    {
        $this->__load();
        return parent::getCcm();
    }

    public function setCcmStatusList(\CcmStatusList $ccmStatusList)
    {
        $this->__load();
        return parent::setCcmStatusList($ccmStatusList);
    }

    public function getCcmStatusList()
    {
        $this->__load();
        return parent::getCcmStatusList();
    }

    public function setReason(\CcmStatusList $reason)
    {
        $this->__load();
        return parent::setReason($reason);
    }

    public function getReason()
    {
        $this->__load();
        return parent::getReason();
    }

    public function setWarehouse(\Warehouses $warehouse)
    {
        $this->__load();
        return parent::setWarehouse($warehouse);
    }

    public function getWarehouse()
    {
        $this->__load();
        return parent::getWarehouse();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'pkId', 'temperatureAlarm', 'workingQuantity', 'comments', 'statusDate', 'utilization', 'ccmAssetType', 'ccm', 'ccmStatusList', 'reason', 'warehouse');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}