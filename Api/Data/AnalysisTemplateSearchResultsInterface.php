<?php

namespace MaxServ\YoastSeo\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AnalysisTemplateSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface[]
     */
    public function getItems();

    /**
     * @param \MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
