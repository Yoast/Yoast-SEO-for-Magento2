<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;

class Edit extends AbstractAction
{
    /**
     * @var AnalysisTemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @param Context $context
     * @param AnalysisTemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        Context $context,
        AnalysisTemplateRepositoryInterface $templateRepository
    ) {
        parent::__construct($context);
        $this->templateRepository = $templateRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam(
            AnalysisTemplateInterface::KEY_TEMPLATE_ID
        );

        /** @var Redirect $redirect */
        $redirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $this->templateRepository->get($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(
                    __($e->getMessage())
                );
                return $redirect->setPath('*/*/');
            }
        }

        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu('MaxServ_YoastSeo::templates');
        $page->getConfig()->getTitle()->set('Edit template');

        return $page;
    }
}
