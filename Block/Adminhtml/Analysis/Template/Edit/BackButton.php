<?php

namespace MaxServ\YoastSeo\Block\Adminhtml\Analysis\Template\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends AbstractButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*/index')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
