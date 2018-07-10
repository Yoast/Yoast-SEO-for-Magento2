<?php

namespace MaxServ\YoastSeo\Model\Entity\Configuration;

use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Helper\Config;
use MaxServ\YoastSeo\Model\Entity\MetaProviderInterface;
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
     * @var MetaProviderInterface
     */
    protected $metaProvider;

    /**
     * @param AnalysisTemplateRepositoryInterface $templateRepository
     * @param Config $config
     * @param MetaProviderInterface $metaProvider
     */
    public function __construct(
        AnalysisTemplateRepositoryInterface $templateRepository,
        Config $config,
        MetaProviderInterface $metaProvider
    ) {
        $this->templateRepository = $templateRepository;
        $this->config = $config;
        $this->metaProvider = $metaProvider;
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
            'template' => $this->getTemplate(),
            'metaKeywordField' => $this->getMetaKeywordField()
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

    /**
     * @return MetaProviderInterface
     */
    public function getMetaProvider()
    {
        return $this->metaProvider;
    }
}
