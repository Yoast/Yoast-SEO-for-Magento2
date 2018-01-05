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
 * @author      Vincent Hornikx <vincent.hornikx@maxserv.com>
 * @copyright   Copyright (c) 2017 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Store\Model\ScopeInterface;

class YoastBox extends Template
{

    protected function _construct()
    {
        $this->setTemplate('MaxServ_YoastSeo::yoastbox.phtml');

        parent::_construct();
    }

    /**
     * @return bool
     */
    public function getIsKeywordsEnabled()
    {
        return (bool) $this->_scopeConfig->getValue('yoastseo/fields/enable_keywords', ScopeInterface::SCOPE_STORE);
    }
}
