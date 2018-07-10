<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Templates;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use MaxServ\YoastSeo\Api\AnalysisTemplateRepositoryInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterface;
use MaxServ\YoastSeo\Api\Data\AnalysisTemplateInterfaceFactory;

class Save extends AbstractAction
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var AnalysisTemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @var AnalysisTemplateInterfaceFactory
     */
    protected $templateFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param AnalysisTemplateRepositoryInterface $templateRepository
     * @param AnalysisTemplateInterfaceFactory $templateFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        AnalysisTemplateRepositoryInterface $templateRepository,
        AnalysisTemplateInterfaceFactory $templateFactory
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->templateRepository = $templateRepository;
        $this->templateFactory = $templateFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->messageManager->addErrorMessage(
                __('No post value')
            );
            return $redirect->setPath('*/*/');
        }

        $id = $this->getRequest()->getParam(
            AnalysisTemplateInterface::KEY_TEMPLATE_ID
        );

        if ($id) {
            try {
                /** @var AnalysisTemplateInterface $template */
                $template = $this->templateRepository->get($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(
                    __('This template no longer exists')
                );
                return $redirect->setPath('*/*/');
            }
        } else {
            /** @var AnalysisTemplateInterface $template */
            $template = $this->templateFactory->create();
        }

        $template->addData($data);

        try {
            $this->templateRepository->save($template);
            $this->messageManager->addSuccessMessage(
                __('Template has been saved')
            );
            $this->dataPersistor->clear('yoastseo_template');
            if ($this->getRequest()->getParam('back')) {
                return $redirect->setPath('*/*/edit', [
                    AnalysisTemplateInterface::KEY_TEMPLATE_ID => $template->getId(),
                    '_current' => true
                ]);
            }
            return $redirect->setPath('*/*/');
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Could not save the template')
            );
        }

        $this->dataPersistor->set(
            'yoastseo_template',
            $template->getData()
        );

        return $redirect->setPath('*/*/edit', [
            AnalysisTemplateInterface::KEY_TEMPLATE_ID => $id
        ]);
    }
}
