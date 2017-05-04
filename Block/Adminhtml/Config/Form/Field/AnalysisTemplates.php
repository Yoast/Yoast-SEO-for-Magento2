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

namespace MaxServ\YoastSeo\Block\Adminhtml\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\DataObject;
use Magento\Framework\Math\Random;
use MaxServ\YoastSeo\Helper\Analysis\TemplatesHelper;

/**
 * Class AnalysisTemplates
 * @package MaxServ\YoastSeo\Block\Adminhtml\Config\Form\Field
 */
class AnalysisTemplates extends AbstractFieldArray
{

    /**
     * @var EntityType
     */
    protected $entityTypeRenderer;

    /**
     * @var TextArea
     */
    protected $textAreaRenderer;

    /**
     * @var TemplatesHelper
     */
    protected $templatesHelper;

    /**
     * AnalysisTemplates constructor.
     * @param Context $context
     * @param TemplatesHelper $templatesHelper
     * @param Random $mathRandom
     * @param array $data
     */
    public function __construct(
        Context $context,
        TemplatesHelper $templatesHelper,
        Random $mathRandom,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->templatesHelper = $templatesHelper;
    }

    /**
     * @return EntityType
     */
    public function getEntityTypeRenderer()
    {
        if (empty($this->entityTypeRenderer)) {
            $this->entityTypeRenderer = $this->getLayout()->createBlock(
                'MaxServ\YoastSeo\Block\Adminhtml\Config\Form\Field\EntityType',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->entityTypeRenderer->setClass('entity_type_select');
        }

        return $this->entityTypeRenderer;
    }

    /**
     * @return TextArea
     */
    public function getTextAreaRenderer()
    {
        if (empty($this->textAreaRenderer)) {
            $this->textAreaRenderer = $this->getLayout()->createBlock(
                'MaxServ\YoastSeo\Block\Adminhtml\Config\Form\Field\TextArea',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->textAreaRenderer;
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        if (!$element->getValue()) {
            $value = $this->templatesHelper->getEditorArray();
            $element->setValue($value);
        }
        return parent::_getElementHtml($element);
    }

    protected function _prepareToRender()
    {

        $this->addColumn('entity_type', [
            'label' => __('Entity type'),
            'renderer' => $this->getEntityTypeRenderer()
        ]);

        $this->addColumn('template', [
            'label' => __('Template'),
            'renderer' => $this->getTextAreaRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Template');
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $optionExtraAttr = [];
        $optionKey = 'option_' . $this->getEntityTypeRenderer()->calcOptionHash($row->getData('entity_type'));
        $optionExtraAttr[$optionKey] = 'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }

}
