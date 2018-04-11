<?php

namespace MaxServ\YoastSeo\Ui\Component\Listing\Column\Analysis\Template;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;

class ActionsColumn extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        $name = $this->getName();

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$name]['edit'] = [
                    'href' => $this->getUrl('edit', $item),
                    'label' => __('Edit')
                ];
                $item[$name]['delete'] = [
                    'href' => $this->getUrl('delete', $item),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete'),
                        'message' => __('Are you sure you want to delete this item?')
                    ]
                ];
            }
        }

        return $dataSource;
    }

    protected function getUrl($action, $item)
    {
        $idColumn = AnalysisTemplateInterface::KEY_TEMPLATE_ID;
        $idValue = $item[$idColumn];
        return $this->urlBuilder->getUrl(
            'yoastseo/templates/' . $action,
            [$idColumn => $idValue]
        );
    }
}
