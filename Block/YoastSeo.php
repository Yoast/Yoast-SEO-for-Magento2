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

namespace MaxServ\YoastSeo\Block;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use MaxServ\YoastSeo\Model\EntityConfiguration;
use MaxServ\YoastSeo\Model\EntityConfiguration\MetaProviderInterface;
use MaxServ\YoastSeo\Model\EntityConfigurationPool;

/**
 * Class YoastSeo
 * @package MaxServ\YoastSeo\Block
 * @method string getPageType()
 * @method $this setPageType(string $pageType)
 */
class YoastSeo extends Template
{

    /**
     * @var EntityConfiguration[]
     */
    protected $entityConfigurationPool;

    /**
     * @var MetaProviderInterface
     */
    protected $metaProvider;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param Context $context
     * @param EntityConfigurationPool $entityConfigurationPool
     * @param RequestInterface $request
     * @param array $data
     */
    public function __construct(
        Context $context,
        EntityConfigurationPool $entityConfigurationPool,
        RequestInterface $request,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->entityConfigurationPool = $entityConfigurationPool;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getCanonicalUrl()
    {
        $action = $this->request->getFullActionName();
        $canonical = null;
        switch ($action) {
            case "cms_index_index":
            case "cms_page_view":
                $canonical = $this->getUrl('', ['_current' => true, '_use_rewrite' => true]);
                break;
        }

        if ($canonical && substr($canonical, -5) !== '.html' && substr($canonical, -1) !== '/') {
            $canonical .= '/';
        }

        return $canonical;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->_scopeConfig->getValue('general/locale/code', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->_scopeConfig->getValue('general/store_information/name', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return MetaProviderInterface
     * @throws \ErrorException
     */
    public function getMeta()
    {
        if (empty($this->metaProvider)) {
            $pageType = $this->getPageType();
            if (empty($pageType)) {
                throw new \ErrorException('No pageType registered.');
            }

            /** @var EntityConfiguration $entityConfiguration */
            $entityConfiguration = $this->entityConfigurationPool->getEntityConfiguration($pageType);
            $metaProvider = $entityConfiguration->getMetaProvider();
            $metaProvider->setLayout($this->getLayout());

            $this->metaProvider = $metaProvider;
        }

        return $this->metaProvider;
    }

    /**
     * @return string
     */
    public function getFacebookAppId()
    {
        return $this->_scopeConfig->getValue('yoastseo/facebook/facebook_app_id', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getFacebookAdmins()
    {
        return $this->_scopeConfig->getValue('yoastseo/facebook/facebook_admins', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getFacebookPages()
    {
        return $this->_scopeConfig->getValue('yoastseo/facebook/facebook_pages', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getTwitterSite()
    {
        $twitterSite = $this->_scopeConfig->getValue('yoastseo/twitter/twitter_company', ScopeInterface::SCOPE_STORE);
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
        $twitterUser = $this->_scopeConfig->getValue('yoastseo/twitter/twitter_user', ScopeInterface::SCOPE_STORE);
        if (substr($twitterUser, 0, 1) !== '@') {
            $twitterUser = '@' . $twitterUser;
        }

        if ($twitterUser === '@') {
            $twitterUser = '';
        }

        return $twitterUser;
    }
}
