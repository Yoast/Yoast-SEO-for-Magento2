<?php

namespace MaxServ\YoastSeo\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AnalysisTemplateSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \MaxServ\YoastSEO\Api\Data\AnalysisTemplateInterface[]
     */
    public function getItems();

    /**
     * @param \MaxServ\YoastSEO\Api\Data\AnalysisTemplateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
