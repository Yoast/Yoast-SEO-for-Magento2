<?php

namespace MaxServ\YoastSeo\Model;

class EntityConfigurationPool
{
    /**
     * @var array|EntityConfigurationInterface[]
     */
    protected $entityConfigurations;

    /**
     * @param EntityConfigurationInterface[] $entityConfigurations
     */
    public function __construct(
        array $entityConfigurations = []
    ) {
        $this->entityConfigurations = $entityConfigurations;
    }

    /**
     * @param string $entity
     * @return bool|EntityConfigurationInterface
     */
    public function getEntityConfiguration($entity)
    {
        $entity = str_replace('_form', '', $entity);
        if (!isset($this->entityConfigurations[$entity])) {
            return false;
        }

        return $this->entityConfigurations[$entity];
    }
}
