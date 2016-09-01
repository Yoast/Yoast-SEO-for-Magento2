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

namespace MaxServ\YoastSeo\Block\Adminhtml\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

/**
 * Class AnalysisTemplates
 * @package MaxServ\YoastSeo\Block\Adminhtml\Config\Form\Field
 *
 * @todo: template as textarea
 */
class AnalysisTemplates extends AbstractFieldArray
{

    /**
     * @var EntityType
     */
    protected $entityTypeRenderer;

    protected $textAreaRenderer;

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
