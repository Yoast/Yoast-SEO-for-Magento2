<?php

namespace MaxServ\YoastSeo\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class EntityType implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $entityTypes;

    /**
     * @param array $entityTypes
     */
    public function __construct(
        array $entityTypes = []
    ) {
        $this->entityTypes = $entityTypes;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->entityTypes;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }
}
