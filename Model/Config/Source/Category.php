<?php

namespace MaxServ\YoastSeo\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Api\Data\GroupInterface;
use Magento\Store\Api\Data\WebsiteInterface;
use Magento\Store\Model\StoreManagerInterface;

class Category implements OptionSourceInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $website = $this->request->getParam('website');
        $store = $this->request->getParam('store');

        /** @var CategoryCollection $collection */
        $collection = $this->categoryCollectionFactory->create();
        $collection
            ->addOrder('level', 'asc')
            ->addOrder('path', 'asc')
            ->addAttributeToFilter('level', ['gt' => 0])
            ->addAttributeToSelect(['name', 'level']);
        $options = [];

        if ($store) {
            $collection->setStoreId($store);
        } elseif ($website) {
            /** @var WebsiteInterface $website */
            $website = $this->storeManager->getWebsite($website);

            /** @var GroupInterface $group */
            $group = $website->getDefaultGroup();

            $collection->setStoreId($group->getDefaultStoreId());
        }

        $collection->load();

        foreach ($collection as $category) {
            $name = $category->getName();
            $name = str_repeat('- ', $category->getLevel() - 1) . ' ' . $name;

            $options[$category->getId()] = $name;
        }

        return $options;
    }
}
