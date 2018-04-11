<?php

namespace MaxServ\YoastSeo\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;

class EntityConfigurationManagement
{
    /**
     * @var array
     */
    protected $entityConfigurations;

    /**
     * @var AnalysisTemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @param AnalysisTemplateRepositoryInterface $templateRepository
     * @param array $entityConfigurations
     */
    public function __construct(
        AnalysisTemplateRepositoryInterface $templateRepository,
        array $entityConfigurations = []
    ) {
        $this->entityConfigurations = $entityConfigurations;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @param string $dataSource
     * @return array
     */
    public function getByDataSource($dataSource)
    {
        $entityType = str_replace('_form', '', $dataSource);

        if (!isset($this->entityConfigurations[$entityType])) {
            return [];
        }

        $configuration = $this->entityConfigurations[$entityType];

        try {
            $template = $this->templateRepository->getByEntityType($entityType);
            $configuration['template'] = $template->getContent();
        } catch (NoSuchEntityException $e) {
            $configuration['template'] = '';
        }

        return $configuration;
    }
}
