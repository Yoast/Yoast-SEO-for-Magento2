<?php

namespace MaxServ\YoastSeo\Block\Schema;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Website extends Template
{
    /**
     * @return string
     */
    public function getSchema()
    {
        $schema = [
            '@context' => 'http://schema.org',
            '@type' => 'WebSite',
            'url' => $this->getBaseUrl()
        ];
        if ($this->isSitelinkSearchboxAvailable()) {
            $searchUrl = $this->getUrl('catalogsearch/result');
            $searchUrl .= '?q={search_term_string}';

            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => $searchUrl,
                'query-input' => 'required name=search_term_string'
            ];
        }

        return json_encode($schema);
    }

    /**
     * @return bool
     */
    public function isSitelinkSearchboxAvailable()
    {
        return (bool)$this->_scopeConfig->getValue(
            'yoastseo/general/enable_sitelink_searchbox',
            ScopeInterface::SCOPE_STORES
        );
    }
}
