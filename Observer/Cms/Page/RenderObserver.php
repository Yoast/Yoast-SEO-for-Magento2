<?php

namespace MaxServ\YoastSeo\Observer\Cms\Page;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;

class RenderObserver implements ObserverInterface
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $page = $observer->getEvent()->getPage();

        $this->registry->register('current_page', $page);
    }
}
