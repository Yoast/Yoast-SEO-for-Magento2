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
        if (!isset($this->entityConfigurations[$entity])) {
            return false;
        }

        return $this->entityConfigurations[$entity];
    }

    /**
     * @param string $entityType
     * @return EntityConfigurationInterface|null
     */
    public function getConfigurationByEntityType($entityType)
    {
        $configuration = null;
        foreach ($this->entityConfigurations as $entityConfiguration) {
            if ($entityConfiguration->getEntityType() === $entityType) {
                $configuration = $entityConfiguration;
                break;
            }
        }

        return $configuration;
    }
}
