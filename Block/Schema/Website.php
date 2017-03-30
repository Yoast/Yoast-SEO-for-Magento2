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

namespace MaxServ\YoastSeo\Block\Schema;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Website extends Template
{

    public function getSchema()
    {
        $schema = [
            '@context' => 'http://schema.org',
            '@type' => 'WebSite',
            'url' => $this->getBaseUrl()
        ];

        if ($this->isSitelinkSearchboxAvailable()) {
            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => $this->getUrl('catalogsearch/result') . '?q={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ];
        }

        return json_encode($schema);
    }

    public function isSitelinkSearchboxAvailable()
    {
        return (bool)$this->_scopeConfig->getValue('web/seo/sitelink_searchbox', ScopeInterface::SCOPE_STORE);
    }
}
