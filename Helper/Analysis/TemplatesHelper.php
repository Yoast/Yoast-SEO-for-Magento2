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

namespace MaxServ\YoastSeo\Helper\Analysis;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Math\Random;
use Magento\Store\Model\ScopeInterface;

class TemplatesHelper extends AbstractHelper
{

    /**
     * @var array
     */
    protected $defaultTemplates;

    /**
     * @var array
     */
    protected $imagesHelpers;

    /**
     * @var array
     */
    protected $templates = [];

    /**
     * @var Random
     */
    protected $mathRandom;

    /**
     * TemplatesHelper constructor.
     * @param Context $context
     * @param Random $mathRandom
     * @param array $defaultTemplates
     * @param array $imagesHelpers
     */
    public function __construct(
        Context $context,
        Random $mathRandom,
        array $defaultTemplates = [],
        array $imagesHelpers = []
    ) {
        parent::__construct($context);
        $this->defaultTemplates = $defaultTemplates;
        $this->imagesHelpers = $imagesHelpers;
        $this->mathRandom = $mathRandom;
        $this->loadConfigTemplates();
    }

    protected function loadConfigTemplates()
    {
        $templates = $this->scopeConfig->getValue('web/seo/analysis_templates', ScopeInterface::SCOPE_STORE);

        if (!empty($templates) && is_string($templates)) {
            $unserialized = unserialize($templates);
            if ($unserialized) {
                $templates = $unserialized;
            }
        }

        $this->templates = $templates;
    }

    /**
     * @param string $entityType
     * @return string
     */
    public function getDefaultTemplate($entityType)
    {
        if (!isset($this->defaultTemplates[$entityType])) {
            return '';
        }

        return $this->defaultTemplates[$entityType];
    }

    /**
     * @param string $entityType
     * @return string
     */
    public function getTemplate($entityType)
    {
        if (!isset($this->templates[$entityType])) {
            return '';
        }

        return $this->templates[$entityType];
    }

    public function getImages($entityType)
    {
        if (isset($this->imagesHelpers[$entityType])) {
            $images = $this->imagesHelpers[$entityType]->getImages();
        } else {
            $images = [];
        }

        return $images;
    }

    public function getEditorArray($templates)
    {
        if (!is_array($templates)) {
            $templates = [];
        }
        $result = [];
        $entityTypes = [];
        foreach ($templates as $entityType => $template) {
            $resultId = $this->mathRandom->getUniqueHash('_');
            $result[$resultId] = [
                'entity_type' => $entityType,
                'template' => $template
            ];
            $entityTypes[] = $entityType;
        }

        $requiredEntityTypes = ['catalog_product', 'catalog_category', 'cms_page'];
        foreach (array_diff($requiredEntityTypes, $entityTypes) as $entityType) {
            $resultId = $this->mathRandom->getUniqueHash('_');
            $result[$resultId] = [
                'entity_type' => $entityType,
                'template' => $this->getDefaultTemplate($entityType)
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getDefaultTemplates()
    {
        return $this->defaultTemplates;
    }
}
