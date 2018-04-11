<?php

namespace MaxServ\YoastSeo\Block\Adminhtml\Analysis\Template\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;

class DeleteButton extends AbstractButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getTemplateId()) {
            $data = [
                'label' => __('Delete Template'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this item?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', [
            AnalysisTemplateInterface::KEY_TEMPLATE_ID => $this->getTemplateId()
        ]);
    }
}
