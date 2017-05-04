<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    Maxserv: MaxServ_YoastSeo
 * @package     Maxserv: MaxServ_YoastSeo
 * @author      Vincent Hornikx <vincent.hornikx@maxserv.com>
 * @copyright   Copyright (c) 2017 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Model;

class EntityConfigurationPool
{

    /**
     * @var EntityConfiguration[]
     */
    protected $entityConfigurations;

    /**
     * EntityConfigurationPool constructor.
     * @param EntityConfiguration[] $entityConfigurations
     */
    public function __construct($entityConfigurations)
    {
        $this->entityConfigurations = $entityConfigurations;
    }

    /**
     * @param $entityType
     * @return EntityConfiguration
     */
    public function getEntityConfiguration($entityType)
    {
        if (!isset($this->entityConfigurations[$entityType])) {
            return false;
        }

        return $this->entityConfigurations[$entityType];
    }

    /**
     * @return array
     */
    public function getRequiredEntities()
    {
        $required = [];

        foreach ($this->entityConfigurations as $entityConfiguration) {
            if ($entityConfiguration->getIsRequired()) {
                $required[] = $entityConfiguration->getEntityName();
            }
        }

        return $required;
    }

    public function getEntityTypesMap()
    {
        $entityTypesMap = [];

        foreach ($this->entityConfigurations as $entityConfiguration) {
            $entityTypesMap[$entityConfiguration->getEntityName()] = $entityConfiguration->getEntityLabel();
        }

        return $entityTypesMap;
    }
}
