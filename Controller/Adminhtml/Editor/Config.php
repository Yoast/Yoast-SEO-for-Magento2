<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Editor;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use MaxServ\YoastSeo\Model\EntityConfigurationManagement;

class Config extends Action
{
    const ADMIN_RESOURCE = 'MaxServ_YoastSeo::admin';

    /**
     * @var EntityConfigurationManagement
     */
    protected $entityConfigurationManagement;

    /**
     * @param Context $context
     * @param EntityConfigurationManagement $entityConfigurationManagement
     */
    public function __construct(
        Context $context,
        EntityConfigurationManagement $entityConfigurationManagement
    ) {
        parent::__construct($context);
        $this->entityConfigurationManagement = $entityConfigurationManagement;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $dataSource = $this->getRequest()->getParam('dataSource');
        $configuration = $this->entityConfigurationManagement
            ->getByDataSource($dataSource);

        $this->getResponse()->representJson(json_encode($configuration));
    }
}
