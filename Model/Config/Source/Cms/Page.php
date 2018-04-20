<?php

namespace MaxServ\YoastSeo\Model\Config\Source\Cms;

use Magento\Cms\Model\ResourceModel\Page\Collection as PageCollection;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

class Page implements OptionSourceInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var PageCollectionFactory
     */
    protected $pageCollectionFactory;

    /**
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     * @param PageCollectionFactory $pageCollectionFactory
     */
    public function __construct(
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        PageCollectionFactory $pageCollectionFactory
    ) {
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->pageCollectionFactory = $pageCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        /** @var PageCollection $collection */
        $collection = $this->pageCollectionFactory->create();

        $store = $this->request->getParam('store');
        $website = $this->request->getParam('website');

        if ($store) {
            $collection->addStoreFilter($store);
        } else {
            $website = $this->storeManager->getWebsite($website);
            $group = $website->getDefaultGroup();
            $store= $group->getDefaultStoreId();
            $collection->addStoreFilter($store);
        }

        $options = [];

        foreach ($collection as $page) {
            $options[$page->getId()] = $page->getTitle();
        }

        return $options;
    }
}
