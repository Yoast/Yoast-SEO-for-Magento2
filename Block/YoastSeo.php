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
use MaxServ\YoastSeo\Helper\Meta;

/**
 * Class YoastSeo
 * @package MaxServ\YoastSeo\Block
 * @method string getPageType()
 * @method $this setPageType(string $pageType)
 */
class YoastSeo extends Template
{

    /**
     * @var Meta[]
     */
    protected $metaPool;

    /**
     * @param Context $context
     * @param array $metaPool
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $metaPool,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->metaPool = $metaPool;
    }

    /**
     * @return Meta
     */
    public function getMeta()
    {
        $pageType = $this->getPageType();
        if (empty($pageType)) {
            exit('no pagetype');
            // todo: exception please
        }

        if (!isset($this->metaPool[$pageType])) {
            exit('no meta object for pagetype');
            // todo: exception please
        }

        return $this->metaPool[$pageType];
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->_scopeConfig->getValue('web/seo/facebook_app_id', ScopeInterface::SCOPE_STORE);
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
