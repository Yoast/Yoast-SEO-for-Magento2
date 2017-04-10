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

namespace MaxServ\YoastSeo\Block\Adminhtml\YoastBox;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\ScopeInterface;
use MaxServ\YoastSeo\Helper\Analysis\TemplatesHelper;

/**
 * Class Config
 * @package MaxServ\YoastSeo\Block\Adminhtml\YoastBox
 * @method string getEntityType()
 * @method $this setEntityType(string $entityType)
 * @method array getConfig()
 * @method $this setConfig(array $config)
 */
class Config extends Template
{

    /**
     * @var TemplatesHelper
     */
    protected $templatesHelper;

    public function __construct(
        Context $context,
        TemplatesHelper $templatesHelper,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->templatesHelper = $templatesHelper;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('MaxServ_YoastSeo::yoastbox/config.phtml');
        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getJsConfig()
    {
        $config = [
            'entityType' => $this->getEntityType(),
            'contentTemplate' => $this->getContentTemplate(),
            'images' => $this->getImages(),
            'isKeywordsEnabled' => $this->getIsKeywordsEnabled()
        ];

        if ($this->getConfig() && is_array($this->getConfig())) {
            $config = array_merge($config, $this->getConfig());
        }

        return json_encode($config);
    }

    /**
     * @return string
     */
    public function getContentTemplate()
    {
        return $this->templatesHelper->getTemplate($this->getEntityType());
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->templatesHelper->getImages($this->getEntityType());
    }

    /**
     * @return bool
     */
    public function getIsKeywordsEnabled()
    {
        return (bool) $this->_scopeConfig->getValue('yoastseo/fields/enable_keywords', ScopeInterface::SCOPE_STORE);
    }
}
