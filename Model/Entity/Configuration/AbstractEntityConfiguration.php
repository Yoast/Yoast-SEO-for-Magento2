<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration;

use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Helper\Config;
use MaxServ\YoastSeo\Model\EntityConfigurationInterface;

abstract class AbstractEntityConfiguration implements EntityConfigurationInterface
{

    /**
     * @var AnalysisTemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param AnalysisTemplateRepositoryInterface $templateRepository
     * @param Config $config
     */
    public function __construct(
        AnalysisTemplateRepositoryInterface $templateRepository,
        Config $config
    ) {
        $this->templateRepository = $templateRepository;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'titleField' => $this->getTitleField(),
            'urlKeyField' => $this->getUrlKeyField(),
            'seoAttributeGroupCode' => $this->getSeoAttributeGroupCode(),
            'fieldWrapper' => $this->getFieldWrapper(),
            'template' => $this->getTemplate()
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTemplate()
    {
        $entityType = $this->getEntityType();
        $template = $this->templateRepository->getByEntityType($entityType);

        return $template->getContent();
    }
}
