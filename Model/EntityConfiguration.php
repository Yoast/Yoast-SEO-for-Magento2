<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    Maxserv: MaxServ_YoastSeo
 * @package     Maxserv: MaxServ_YoastSeo
 * @author      Vincent Hornikx <vincent.hornikx@maxser.com>
 * @copyright   Copyright (c) 2016 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Model;

use Magento\Framework\ObjectManagerInterface;
use MaxServ\YoastSeo\Model\EntityConfiguration\ImageProvider;
use MaxServ\YoastSeo\Model\EntityConfiguration\ImageProviderInterface;
use MaxServ\YoastSeo\Model\EntityConfiguration\MetaProvider;
use MaxServ\YoastSeo\Model\EntityConfiguration\MetaProviderInterface;
use MaxServ\YoastSeo\Model\EntityConfiguration\TemplateProcessorInterface;

class EntityConfiguration
{

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $entityLabel;

    /**
     * @var string
     */
    protected $defaultTemplate;

    /**
     * @var string
     */
    protected $templateProcessor;

    /**
     * @var TemplateProcessorInterface
     */
    protected $templateProcessorInstance;

    /**
     * @var string
     */
    protected $imageProvider;

    /**
     * @var ImageProvider
     */
    protected $imageProviderInstance;

    /**
     * @var string
     */
    protected $metaProvider;

    /**
     * @var MetaProvider
     */
    protected $metaProviderInstance;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * EntityConfiguration constructor.
     * @param ObjectManagerInterface $objectManager
     * @param string $entityName
     * @param string $entityLabel
     * @param string $defaultTemplate
     * @param string $templateProcessor
     * @param string $imageProvider
     * @param string $metaProvider
     * @param bool $isRequired
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        $entityName,
        $entityLabel,
        $defaultTemplate,
        $templateProcessor,
        $imageProvider,
        $metaProvider,
        $isRequired
    ) {
        $this->objectManager = $objectManager;
        $this->entityName = $entityName;
        $this->entityLabel = $entityLabel;
        $this->defaultTemplate = $defaultTemplate;
        $this->templateProcessor = $templateProcessor;
        $this->imageProvider = $imageProvider;
        $this->metaProvider = $metaProvider;
        $this->isRequired = $isRequired;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @return string
     */
    public function getEntityLabel()
    {
        return $this->entityLabel;
    }

    /**
     * @return string
     */
    public function getDefaultTemplate()
    {
        return $this->defaultTemplate;
    }

    /**
     * @return TemplateProcessorInterface
     * @throws \Exception
     */
    public function getTemplateProcessor()
    {
        if (empty($this->templateProcessorInstance)) {
            $templateProcessorInstance = $this->objectManager->get($this->templateProcessor);
            if (!$templateProcessorInstance instanceof TemplateProcessorInterface) {
                throw new \Exception('Template processor must implement \MaxServ\YoastSeo\Model\TemplateProcessorInterface');
            }
            $this->templateProcessorInstance = $templateProcessorInstance;
        }

        return $this->templateProcessorInstance;
    }

    /**
     * @return ImageProviderInterface
     * @throws \Exception
     */
    public function getImageProvider()
    {
        if (empty($this->imageProviderInstance)) {
            $imageProviderInstance = $this->objectManager->get($this->imageProvider);
            if (!$imageProviderInstance instanceof ImageProviderInterface) {
                throw new \Exception('Template processor must implement \MaxServ\YoastSeo\Model\ImageProviderInterface');
            }
            $this->imageProviderInstance = $imageProviderInstance;
        }

        return $this->imageProviderInstance;
    }

    /**
     * @return MetaProviderInterface
     * @throws \Exception
     */
    public function getMetaProvider()
    {
        if (empty($this->metaProviderInstance)) {
            $metaProviderInstance = $this->objectManager->get($this->metaProvider);
            if (!$metaProviderInstance instanceof MetaProviderInterface) {
                throw new \Exception('Template processor must implement \MaxServ\YoastSeo\Model\MetaProviderInterface');
            }
            $this->metaProviderInstance = $metaProviderInstance;
        }

        return $this->metaProviderInstance;
    }

    /**
     * @return bool
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }
}
