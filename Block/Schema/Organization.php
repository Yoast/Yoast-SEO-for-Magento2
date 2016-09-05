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
use Magento\Theme\Block\Html\Header\Logo;

class Organization extends Template
{

    /**
     * @var array
     */
    protected $storeInformation = [];

    /**
     * @return string
     */
    public function getStoreLogo()
    {
        /** @var Logo $logoBlock */
        $logoBlock = $this->getLayout()->getBlock('logo');

        return $logoBlock->getLogoSrc();
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        $schema = [
            '@context' => 'http://schema.org',
            '@type' => 'Organization',
            'url' => $this->getBaseUrl(),
            'name' => $this->getStoreInformation('general/store_information/company_name'),
            'logo' => $this->getStoreLogo()
        ];

        $additional = [
            'telephone' => $this->getStoreInformation('general/store_information/phone'),
            'address' => $this->getStoreAddress(),
            'sameAs' => $this->getStoreSameAs(),
            'taxId' => $this->getStoreInformation('general/store_information/merchant_vat_number')
        ];

        $schema = array_merge($schema, array_filter($additional));

        return json_encode($schema);
    }

    /**
     * @return array
     */
    public function getStoreAddress()
    {
        $street = [
            $this->getStoreInformation('general/store_information/street_line1'),
            $this->getStoreInformation('general/store_information/street_line2')
        ];
        $address = [
            'streetAddress' => implode("\n", array_filter($street)),
            'postalCode' => $this->getStoreInformation('general/store_information/postcode'),
            'addressLocality' => $this->getStoreInformation('general/store_information/city'),
            'addressRegion' => $this->getStoreInformation('general/store_information/region'),
            'addressCountry' => $this->getStoreInformation('general/store_information/country_id')
        ];

        return array_filter($address);
    }

    /**
     * @return array
     */
    public function getStoreSameAs()
    {
        $sameAs = $this->getStoreInformation('general/store_information/schema_sameas');
        $sameAs = str_replace("\n", "", $sameAs);
        $sameAs = explode("\r", $sameAs);

        return $sameAs;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getStoreInformation($key)
    {
        if (!isset($this->storeInformation[$key])) {
            $this->storeInformation[$key] = $this->_scopeConfig->getValue($key, ScopeInterface::SCOPE_STORE);
        }
        return $this->storeInformation[$key];
    }
}
