<?php

namespace MaxServ\YoastSeo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use MaxServ\YoastSeo\Model\Entity\MetaProviderInterface;
use MaxServ\YoastSeo\Model\EntityConfigurationPool;

class YoastSeo extends Template
{
    /**
     * @var EntityConfigurationPool
     */
    protected $entityConfigurationPool;

    /**
     * @param Context $context
     * @param EntityConfigurationPool $entityConfigurationPool
     * @param array $data
     */
    public function __construct(
        Context $context,
        EntityConfigurationPool $entityConfigurationPool,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->entityConfigurationPool = $entityConfigurationPool;
    }

    /**
     * @return MetaProviderInterface
     */
    public function getMetaProvider()
    {
        $configuration = $this->entityConfigurationPool
            ->getConfigurationByEntityType($this->getData('entity_type'));

        if (!$configuration || !$configuration->getMetaProvider()) {
            return null;
        }

        return $configuration->getMetaProvider();
    }

    /**
     * @return string
     */
    public function getCanonicalUrl()
    {
        $canonical = $this->getUrl('', [
            '_current' => true,
            '_use_rewrite' => true
        ]);
        if ($canonical
            && substr($canonical, -5) !== '.html'
            && substr($canonical, -1) !== '/') {
            $canonical .= '/';
        }

        return $canonical;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->_scopeConfig->getValue(
            'general/locale/code',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->_scopeConfig->getValue(
            'general/store_information/name',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getFacebookAppId()
    {
        return $this->_scopeConfig->getValue(
            'yoastseo/facebook/app_id',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getFacebookAdmins()
    {
        return $this->_scopeConfig->getValue(
            'yoastseo/facebook/admin_ids',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getFacebookPages()
    {
        return $this->_scopeConfig->getValue(
            'yoastseo/facebook/facebook_pages',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getTwitterSite()
    {
        $twitterSite = $this->_scopeConfig->getValue(
            'yoastseo/twitter/company_account',
            ScopeInterface::SCOPE_STORES
        );
        if (substr($twitterSite, 0, 1) !== '@') {
            $twitterSite = '@' . $twitterSite;
        }
        if ($twitterSite === '@') {
            $twitterSite = '';
        }

        return $twitterSite;
    }

    /**
     * @return string
     */
    public function getTwitterCreator()
    {
        $twitterUser = $this->_scopeConfig->getValue(
            'yoastseo/twitter/manager_account',
            ScopeInterface::SCOPE_STORE
        );
        if (substr($twitterUser, 0, 1) !== '@') {
            $twitterUser = '@' . $twitterUser;
        }
        if ($twitterUser === '@') {
            $twitterUser = '';
        }

        return $twitterUser;
    }
}
