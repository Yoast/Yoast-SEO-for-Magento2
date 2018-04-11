<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Templates;

use Magento\Backend\Model\View\Result\Forward;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class NewAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Forward $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $result->forward('edit');

        return $result;
    }
}
