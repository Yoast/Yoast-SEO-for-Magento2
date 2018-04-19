<?php

namespace MaxServ\YoastSeo\Controller\Render;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;

class Wysiwyg extends Action
{

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider
    ) {
        parent::__construct($context);

        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $postValue = $this->getRequest()->getPostValue();
        if (!isset($postValue['content'])) {
            return $result->setData([
                'html' => ''
            ]);
        }

        $content = $postValue['content'];

        $filter = $this->filterProvider->getBlockFilter();
        $filter->setStoreId($storeId);

        $html = $filter->filter($content);

        return $result->setData([
            'html' => $html
        ]);
    }
}
