<?php

namespace MaxServ\YoastSeo\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterfaceFactory;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateSearchResultsInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateSearchResultsInterfaceFactory;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template as TemplateResource;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template\Collection;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template\CollectionFactory;

class AnalysisTemplateRepository implements AnalysisTemplateRepositoryInterface
{
    /**
     * @var AnalysisTemplateSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var AnalysisTemplateInterfaceFactory
     */
    protected $templateFactory;

    /**
     * @var TemplateResource
     */
    protected $templateResource;

    /**
     * @param AnalysisTemplateSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionFactory $collectionFactory
     * @param AnalysisTemplateInterfaceFactory $templateFactory
     * @param TemplateResource $templateResource
     */
    public function __construct(
        AnalysisTemplateSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        AnalysisTemplateInterfaceFactory $templateFactory,
        TemplateResource $templateResource
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->templateFactory = $templateFactory;
        $this->templateResource = $templateResource;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ) {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        /** @var AnalysisTemplateSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $collection->addFieldToFilter(
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
            }
        }

        $searchResults->setTotalCount($collection->getSize());

        $collection
            ->setPageSize($searchCriteria->getPageSize())
            ->setCurPage($searchCriteria->getCurrentPage());

        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        /** @var AnalysisTemplateInterface $template */
        $template = $this->templateFactory->create();

        $this->templateResource->load($template, $id);

        if (!$template->getId()) {
            throw new NoSuchEntityException(
                __('Could not find the template')
            );
        }

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function getByEntityType($entityType)
    {
        /** @var AnalysisTemplateInterface $template */
        $template = $this->templateFactory->create();

        $this->templateResource->load(
            $template,
            $entityType,
            AnalysisTemplateInterface::KEY_ENTITY_TYPE
        );

        if (!$template->getId()) {
            throw new NoSuchEntityException(
                __('Could not find the template')
            );
        }

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function save($template)
    {
        try {
            $this->templateResource->save($template);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function delete($template)
    {
        try {
            $this->templateResource->delete($template);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        $template = $this->get($id);

        return $this->delete($template);
    }
}
