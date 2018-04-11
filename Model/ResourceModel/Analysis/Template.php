<?php

namespace MaxServ\YoastSeo\Model\ResourceModel\Analysis;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;

class Template extends AbstractDb
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @inheritDoc
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    protected function _construct() // phpcs:ignore
    {
        $this->_init(
            AnalysisTemplateInterface::TABLE,
            AnalysisTemplateInterface::KEY_TEMPLATE_ID
        );
    }

    /**
     * @inheritDoc
     */
    public function load(
        AbstractModel $object,
        $value,
        $field = null
    ) {
        if ($field == AnalysisTemplateInterface::KEY_ENTITY_TYPE) {
            $value = $this->getIdByEntityType($value);
        }
        $this->entityManager->load($object, $value);

        return $this;
    }

    /**
     * @param string $entityType
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIdByEntityType($entityType)
    {
        $select = $this->getConnection()->select();
        $select
            ->from($this->getMainTable())
            ->columns(AnalysisTemplateInterface::KEY_TEMPLATE_ID)
            ->where(
                AnalysisTemplateInterface::KEY_ENTITY_TYPE . ' = ?',
                $entityType
            );

        return (int)$this->getConnection()->fetchOne($select);
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);

        return $this;
    }
}
