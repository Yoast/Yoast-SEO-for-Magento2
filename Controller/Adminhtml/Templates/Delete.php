<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;

class Delete extends AbstractAction
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
        /** @var Redirect $redirect */
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('*/*/index');

        $id = $this->getRequest()->getParam(
            AnalysisTemplateInterface::KEY_TEMPLATE_ID
        );
        if (!$id) {
            $this->messageManager->addErrorMessage(
                __('No id supplied')
            );
            return $redirect;
        }

        try {
            $template = $this->templateRepository->get($id);
            $this->templateRepository->delete($template);
            $this->messageManager->addSuccessMessage(
                __('Template has been deleted')
            );
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('No template found'));
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage(__('Could not delete template'));
        }

        return $redirect;
    }
}
