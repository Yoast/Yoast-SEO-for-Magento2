<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Ui\Component\MassAction\Filter;
use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template\Collection;
use MaxServ\YoastSeo\Model\ResourceModel\Analysis\Template\CollectionFactory;

class MassDelete extends AbstractAction
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var AnalysisTemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param AnalysisTemplateRepositoryInterface $templateRepository
     * @param CollectionFactory $locationCollectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        AnalysisTemplateRepositoryInterface $templateRepository,
        CollectionFactory $locationCollectionFactory
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->templateRepository = $templateRepository;
        $this->collectionFactory = $locationCollectionFactory;
    }

    public function execute()
    {
        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = 0;
        $errors = 0;

        foreach ($collection as $template) {
            try {
                $this->templateRepository->delete($template);
                $collectionSize++;
            } catch (CouldNotDeleteException $e) {
                $errors++;
            }
        }

        if ($errors) {
            $this->messageManager->addErrorMessage(__('%1 templates could not be deleted.', $errors));
        }
        if ($collectionSize) {
            $this->messageManager->addSuccessMessage(__('A total of %1 templates have been deleted.', $collectionSize));
        }

        /** @var Redirect $result */
        $result = $this->resultRedirectFactory->create();
        return $result->setPath('*/*/index');
    }
}
