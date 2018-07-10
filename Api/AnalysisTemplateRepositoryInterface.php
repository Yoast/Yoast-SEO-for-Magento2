<?php

namespace MaxServ\YoastSeo\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface AnalysisTemplateRepositoryInterface
{
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MaxServ\YoastSeo\Api\Data\AnalysisTemplateSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $id
     * @return \MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface
     * @throws NoSuchEntityException
     */
    public function get($id);

    /**
     * @param string $entityType
     * @return \MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface
     * @throws NoSuchEntityException
     */
    public function getByEntityType($entityType);

    /**
     * @param \MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface $template
     * @return \MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface
     * @throws CouldNotSaveException
     */
    public function save($template);

    /**
     * @param \MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface $template
     * @return bool true if deleted
     * @throws CouldNotDeleteException
     */
    public function delete($template);

    /**
     * @param int $id
     * @return bool true if deleted
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($id);
}
