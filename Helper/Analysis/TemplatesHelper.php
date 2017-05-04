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

namespace MaxServ\YoastSeo\Helper\Analysis;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Math\Random;
use Magento\Store\Model\ScopeInterface;
use MaxServ\YoastSeo\Model\EntityConfiguration;
use MaxServ\YoastSeo\Model\EntityConfigurationPool;

class TemplatesHelper extends AbstractHelper
{

    protected $entityConfigurationPool;

    /**
     * @var Random
     */
    protected $mathRandom;

    /**
     * @var array
     */
    protected $templates = [];

    /**
     * TemplatesHelper constructor.
     * @param Context $context
     * @param Random $mathRandom
     * @param EntityConfigurationPool $entityConfigurationPool
     */
    public function __construct(
        Context $context,
        Random $mathRandom,
        EntityConfigurationPool $entityConfigurationPool
    ) {
        parent::__construct($context);
        $this->entityConfigurationPool = $entityConfigurationPool;
        $this->mathRandom = $mathRandom;
        $this->loadConfigTemplates();
    }

    protected function loadConfigTemplates()
    {
        $templates = $this->scopeConfig->getValue('yoastseo/templates/analysis_templates', ScopeInterface::SCOPE_STORE);

        if (!empty($templates) && is_string($templates)) {
            $unserialized = unserialize($templates);
            if ($unserialized) {
                $templates = $unserialized;
            }
        } else {
            $templates = [];
        }

        $this->templates = $templates;
    }

    /**
     * @param string $entityType
     * @return string
     */
    public function getDefaultTemplate($entityType)
    {
        /** @var EntityConfiguration $entityConfiguration */
        $entityConfiguration = $this->entityConfigurationPool->getEntityConfiguration($entityType);
        return $entityConfiguration->getDefaultTemplate();
    }

    /**
     * @param string $entityType
     * @param bool $processTemplate
     * @return string
     */
    public function getTemplate($entityType, $processTemplate = true)
    {
        $entityConfiguration = $this->entityConfigurationPool->getEntityConfiguration($entityType);

        if (isset($this->templates[$entityType])) {
            $template = $this->templates[$entityType];
        } else {
            /** @var EntityConfiguration $entityConfiguration */
            $template = $entityConfiguration->getDefaultTemplate();
        }
        if ($processTemplate) {
            $templateProcessor = $entityConfiguration->getTemplateProcessor();
            $template = $templateProcessor->processTemplate($template);
        }

        return $template;
    }

    /**
     * @param string $entityType
     * @return array
     */
    public function getImages($entityType)
    {
        /** @var EntityConfiguration $entityConfiguration */
        $entityConfiguration = $this->entityConfigurationPool->getEntityConfiguration($entityType);
        $images = $entityConfiguration->getImageProvider()->getImages();

        return $images;
    }

    /**
     * @return array
     */
    public function getEditorArray()
    {
        $result = [];
        $requiredEntityTypes = $this->entityConfigurationPool->getRequiredEntities();
        foreach ($requiredEntityTypes as $entityType) {
            $resultId = $this->mathRandom->getUniqueHash('_');
            $result[$resultId] = [
                'entity_type' => $entityType,
                'template' => $this->getTemplate($entityType, false)
            ];
        }

        return $result;
    }
}
