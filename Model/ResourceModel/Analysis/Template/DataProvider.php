<?php

namespace MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template\Collection;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var LocationInterfaceFactory
     */
    protected $locationFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ReadInterface
     */
    protected $mediaDirectory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;

        $this->collection = $collectionFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var AnalysisTemplateInterface $template */
        foreach ($this->getCollection() as $template) {
            $this->loadedData[$template->getId()] = $template->getData();
        }

        $data = $this->dataPersistor->get('yoastseo_template');
        if (!empty($data)) {
            /** @var LocationInterface $location */
            $template = $this->locationFactory->create();
            $template->setData($data);
            $this->loadedData[$template->getId()] = $template->getData();
            $this->dataPersistor->clear('yoastseo_template');
        }

        return $this->loadedData;
    }
}
