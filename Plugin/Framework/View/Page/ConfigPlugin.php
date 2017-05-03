<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    Maxserv: MaxServ_YoastSeo
 * @package     Maxserv: MaxServ_YoastSeo
 * @author      Vincent Hornikx <vincent.hornikx@maxser.com>
 * @copyright   Copyright (c) 2016 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Plugin\Framework\View\Page;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Page\Config;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Cms\Model\Page;

class ConfigPlugin
{
    /** @var RequestInterface */
    protected $request;

    /** @var Registry */
    protected $registry;

    /** @var Page */
    protected $page;

    public function __construct(
        RequestInterface $request,
        Registry $registry
    ) {
        $this->request = $request;
        $this->registry = $registry;
    }

    /**
     * @param Config $subject
     * @param string $robots
     * @return string
     */
    public function afterGetRobots(Config $subject, $robots)
    {
        /** @var Page $page */
        $page = $this->registry->registry('current_page');

        /** @var Product $product */
        $product = $this->registry->registry('product');

        /** @var Category $category */
        $category = $this->registry->registry('category');

        if ($page && $page->getId() && $page->getYoastRobotsInstructions()) {
            $robots = $page->getYoastRobotsInstructions();
        } elseif ($product && $product->getId() && $product->getYoastRobotsInstructions()) {
            $robots = $product->getYoastRobotsInstructions();
        } elseif ($category && $category->getId() && $category->getYoastRobotsInstructions()) {
            $robots = $category->getYoastRobotsInstructions();
        }

        if (($robots == 'INDEX,FOLLOW' && $this->isPaged()) || $this->isSearch()) {
            return 'NOINDEX,FOLLOW';
        }

        return $robots;
    }

    /**
     * @return bool
     */
    protected function isPaged()
    {
        $page = $this->request->getParam('p', 1);

        return $page > 1;
    }

    /**
     * @return bool
     */
    protected function isSearch()
    {
        return $this->request->getModuleName() === 'catalogsearch';
    }
}
