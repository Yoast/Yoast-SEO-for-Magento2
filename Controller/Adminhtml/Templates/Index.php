<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Templates;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Index extends AbstractAction
{

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu('MaxServ_YoastSeo::templates');
        $page->getConfig()->getTitle()->prepend('Yoast SEO');
        $page->getConfig()->getTitle()->prepend('Analysis Templates');

        return $page;
    }
}
