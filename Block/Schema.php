<?php

namespace MaxServ\YoastSeo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use MaxServ\YoastSeo\Block\Schema\Product;

class Schema extends Template
{
   /**
    * Delete this class for test purpose only
    */

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        Product $product,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->product = $product; 
    }
/**
 * Get product Schema
 *
 * @return string
 */
   public function getProductSchema(){
       return $this->product->getSchema();

   }
   public function test(){
       return "test";
   }
}
