<?php

namespace MaxServ\YoastSeo\Setup;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterfaceFactory;
use Psr\Log\LoggerInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AnalysisTemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @var AnalysisTemplateInterfaceFactory
     */
    protected $templateFactory;

    /**
     * @var array
     */
    protected $templates;

    /**
     * @var ModuleDataSetupInterface
     */
    protected $setup;

    /**
     * @var EavConfig
     */
    protected $eavConfig;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var EavSetup
     */
    protected $eavSetup;

    /**
     * @param LoggerInterface $logger
     * @param AnalysisTemplateRepositoryInterface $templateRepository
     * @param AnalysisTemplateInterfaceFactory $templateFactory
     * @param EavConfig $eavConfig
     * @param EavSetupFactory $eavSetupFactory
     * @param array $templates
     */
    public function __construct(
        LoggerInterface $logger,
        AnalysisTemplateRepositoryInterface $templateRepository,
        AnalysisTemplateInterfaceFactory $templateFactory,
        EavConfig $eavConfig,
        EavSetupFactory $eavSetupFactory,
        array $templates = []
    ) {
        $this->logger = $logger;
        $this->templateRepository = $templateRepository;
        $this->templateFactory = $templateFactory;
        $this->eavConfig = $eavConfig;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->templates = $templates;
    }

    /**
     * @inheritDoc
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->setup = $setup;
        $this->eavSetup = $this->eavSetupFactory->create([
            'setup' => $setup
        ]);

        $this->setup->startSetup();
        $this->installTemplates();
        $this->installAttributes();
        $this->setup->endSetup();
    }
    
    protected function installTemplates()
    {
        foreach ($this->templates as $templateData) {
            try {
                /** @var AnalysisTemplateInterface $template */
                $template = $this->templateRepository->getByEntityType(
                    $templateData['entity_type']
                );
            } catch (NoSuchEntityException $e) {
                /** @var AnalysisTemplateInterface $template */
                $template = $this->templateFactory->create();
            }

            $template
                ->setEntityType($templateData['entity_type'])
                ->setContent($templateData['content']);

            try {
                $this->templateRepository->save($template);
            } catch (CouldNotSaveException $e) {
                $this->logger->critical($e);
            }
        }
    }

    protected function installAttributes()
    {
        $this->installProductAttributes();
        $this->installCategoryAttributes();
    }

    protected function installProductAttributes()
    {
        $entityType = $this->eavConfig->getEntityType(Product::ENTITY);
        $entityTypeId = $entityType->getId();

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_focus_keyword', [
            'type' => 'varchar',
            'label' => 'Yoast Focus Keyword',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_keyword_score', [
            'type' => 'int',
            'label' => 'Yoast Keyword Score',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_content_score', [
            'type' => 'int',
            'label' => 'Yoast Content Score',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_facebook_image', [
            'type' => 'varchar',
            'label' => 'Yoast Facebook Image',
            'backend' => 'MaxServ\YoastSeo\Model\Entity\Attribute\Backend\Image',
            'input' => 'image',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_facebook_title', [
            'type' => 'varchar',
            'label' => 'Yoast Facebook Title',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_facebook_description', [
            'type' => 'text',
            'label' => 'Yoast Facebook Description',
            'input' => 'textarea',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_twitter_image', [
            'type' => 'varchar',
            'label' => 'Yoast Twitter Image',
            'backend' => 'MaxServ\YoastSeo\Model\Entity\Attribute\Backend\Image',
            'input' => 'image',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_twitter_title', [
            'type' => 'varchar',
            'label' => 'Yoast Twitter Title',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_twitter_description', [
            'type' => 'text',
            'label' => 'Yoast Twitter Description',
            'input' => 'textarea',
            'global' => 'store',
            'required' => false
        ]);
    }

    protected function installCategoryAttributes()
    {

        $entityType = $this->eavConfig->getEntityType(Category::ENTITY);
        $entityTypeId = $entityType->getId();

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_focus_keyword', [
            'type' => 'varchar',
            'label' => 'Yoast Focus Keyword',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_keyword_score', [
            'type' => 'int',
            'label' => 'Yoast Keyword Score',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_content_score', [
            'type' => 'int',
            'label' => 'Yoast Content Score',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_facebook_image', [
            'type' => 'varchar',
            'label' => 'Yoast Facebook Image',
            'backend' => 'MaxServ\YoastSeo\Model\Entity\Attribute\Backend\Image',
            'input' => 'image',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_facebook_title', [
            'type' => 'varchar',
            'label' => 'Yoast Facebook Title',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_facebook_description', [
            'type' => 'text',
            'label' => 'Yoast Facebook Description',
            'input' => 'textarea',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_twitter_image', [
            'type' => 'varchar',
            'label' => 'Yoast Twitter Image',
            'backend' => 'MaxServ\YoastSeo\Model\Entity\Attribute\Backend\Image',
            'input' => 'image',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_twitter_title', [
            'type' => 'varchar',
            'label' => 'Yoast Twitter Title',
            'input' => 'text',
            'global' => 'store',
            'required' => false
        ]);

        $this->eavSetup->addAttribute($entityTypeId, 'yoast_twitter_description', [
            'type' => 'text',
            'label' => 'Yoast Twitter Description',
            'input' => 'textarea',
            'global' => 'store',
            'required' => false
        ]);
    }
}
