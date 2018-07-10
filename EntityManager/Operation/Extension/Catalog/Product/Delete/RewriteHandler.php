<?php

namespace MaxServ\YoastSeo\EntityManager\Operation\Extension\Catalog\Product\Delete;

use Magento\Catalog\Model\Product;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use MaxServ\YoastSeo\Model\Entity\Catalog\ProductRedirectManager;

class RewriteHandler implements ExtensionInterface
{
    /**
     * @var ProductRedirectManager
     */
    protected $productRedirectManager;

    /**
     * @param ProductRedirectManager $productRedirectManager
     */
    public function __construct(
        ProductRedirectManager $productRedirectManager
    ) {
        $this->productRedirectManager = $productRedirectManager;
    }

    /**
     * @param Product $entity
     * @param array $arguments
     * @return bool|object|void
     */
    public function execute($entity, $arguments = [])
    {
        $this->productRedirectManager->updateRedirects($entity);
    }
}
