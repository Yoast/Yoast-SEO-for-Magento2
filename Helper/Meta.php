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

namespace MaxServ\YoastSeo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;

abstract class Meta extends AbstractHelper
{

    /**
     * @var string
     */
    protected $page_type;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ImageHelper $imageHelper
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->scopeConfig->getValue('general/store_information/name', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getTitleTemplate()
    {
        return '%s | ' . $this->getStoreName();
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getUrl();

    /**
     * @return string
     */
    abstract public function getTitle();

    /**
     * @return string
     */
    abstract public function getDescription();

    /**
     * @return string
     */
    abstract public function getImage();

    /**
     * @return string
     */
    abstract public function getOpenGraphTitle();

    /**
     * @return string
     */
    abstract public function getOpenGraphDescription();

    /**
     * @return string
     */
    abstract public function getOpenGraphImage();

    /**
     * @return string
     */
    abstract public function getTwitterTitle();

    /**
     * @return string
     */
    abstract public function getTwitterDescription();

    /**
     * @return string
     */
    abstract public function getTwitterImage();

    /**
     * @return null|string
     */
    public function getFirstAvailableValue()
    {
        $args = func_get_args();
        $result = null;
        foreach ($args as $arg) {
            if (!empty($arg) && is_string($arg) && $arg !== '') {
                $result = $arg;
                break;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getPageType()
    {
        return $this->page_type;
    }

    /**
     * @param string $pageType
     */
    public function setPageType($pageType)
    {
        $this->page_type = $pageType;
    }
}
