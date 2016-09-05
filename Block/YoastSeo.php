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

namespace MaxServ\YoastSeo\Block;

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
     * @param Context $context
     * @param EntityConfigurationPool $entityConfigurationPool
     * @param array $data
     */
    public function __construct(
        Context $context,
        EntityConfigurationPool $entityConfigurationPool,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->entityConfigurationPool = $entityConfigurationPool;
    }

    /**
     * @return MetaProviderInterface
     * @throws \ErrorException
     */
    public function getMeta()
    {
        $pageType = $this->getPageType();
        if (empty($pageType)) {
            throw new \ErrorException('No pageType registered.');
        }

        /** @var EntityConfiguration $entityConfiguration */
        $entityConfiguration = $this->entityConfigurationPool->getEntityConfiguration($pageType);

        return $entityConfiguration->getMetaProvider();
    }

    /**
     * @return string
     */
    public function getFacebookAppId()
    {
        return $this->_scopeConfig->getValue('web/seo/facebook_app_id', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getFacebookAdmins()
    {
        return $this->_scopeConfig->getValue('web/seo/facebook_admins', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getTwitterSite()
    {
        $twitterSite = $this->_scopeConfig->getValue('web/seo/twitter_company', ScopeInterface::SCOPE_STORE);
        if (substr($twitterSite, 0, 1) !== '@') {
            $twitterSite = '@' . $twitterSite;
        }

        return $twitterSite;
    }

    /**
     * @return string
     */
    public function getTwitterCreator()
    {
        $twitterUser = $this->_scopeConfig->getValue('web/seo/twitter_user', ScopeInterface::SCOPE_STORE);
        if (substr($twitterUser, 0, 1) !== '@') {
            $twitterUser = '@' . $twitterUser;
        }

        return $twitterUser;
    }
}
