<?php

namespace MaxServ\YoastSeo\Controller\Render\Cms;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;

class Block extends Action
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
     * @var BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        BlockRepositoryInterface $blockRepository
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
        $this->blockRepository = $blockRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $store = $this->storeManager->getStore();
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $filter = $this->filterProvider->getBlockFilter();
        $filter->setStoreId($store->getId());

        $blockId = $this->getRequest()->getParam('cms_block_id');
        if (!$blockId) {
            return $result->setData([
                'html' => ''
            ]);
        }

        $block = $this->blockRepository->getById($blockId);
        $html = $filter->filter($block->getContent());

        return $result->setData([
            'html' => $html
        ]);
    }
}
