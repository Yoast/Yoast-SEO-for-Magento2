<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Editor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use MaxServ\YoastSeo\Model\EntityConfigurationPool;

class Config extends Action
{
    const ADMIN_RESOURCE = 'MaxServ_YoastSeo::admin';

    /**
     * @var EntityConfigurationPool
     */
    protected $entityConfigurationPool;

    /**
     * @param Context $context
     * @param EntityConfigurationPool $entityConfigurationPool
     */
    public function __construct(
        Context $context,
        EntityConfigurationPool $entityConfigurationPool
    ) {
        parent::__construct($context);
        $this->entityConfigurationPool = $entityConfigurationPool;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $namespace = $this->getRequest()->getParam('namespace');
        $configuration = $this->entityConfigurationPool
            ->getEntityConfiguration($namespace);

        if ($configuration) {
            $result = $configuration->toArray();
        } else {
            $result = [
                'error' => 'no configuration found for: ' . $namespace
            ];
        }
        $this->getResponse()->representJson(json_encode($result));
    }
}
