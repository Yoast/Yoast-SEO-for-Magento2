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

namespace MaxServ\YoastSeo\Model\Adminhtml\System\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Math\Random;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use MaxServ\YoastSeo\Helper\Analysis\TemplatesHelper;

class AnalysisTemplates extends Value
{

    /**
     * @var Random
     */
    protected $mathRandom;

    /**
     * @var TemplatesHelper
     */
    protected $templatesHelper;

    /**
     * AnalysisTemplates constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param Random $mathRandom
     * @param TemplatesHelper $templatesHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        Random $mathRandom,
        TemplatesHelper $templatesHelper,
        array $data = []
    ) {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->mathRandom = $mathRandom;
        $this->templatesHelper = $templatesHelper;
    }

    /**
     * Unserialize and format configuration for adminhtml config
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();

        if (!empty($value) && is_string($value)) {
            $value = unserialize($value);
        }

        $result = $this->templatesHelper->getEditorArray($value);
        $this->setValue($result);
    }

    /**
     * Format and serialize configuration as [<entityType> => <template>]
     */
    public function beforeSave()
    {
        $value = $this->getValue();

        unset($value['__empty']);
        $result = [];
        foreach ($value as $row) {
            $result[$row['entity_type']] = $row['template'];
        }
        $value = serialize($result);

        $this->setValue($value);
    }
}
