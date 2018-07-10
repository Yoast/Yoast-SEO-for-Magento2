<?php

namespace MaxServ\YoastSeo\Controller\Adminhtml\Image;

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;

class Upload extends Action
{
    const ADMIN_RESOURCE = 'MaxServ_YoastSeo::admin';

    /**
     * Image uploader
     *
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Upload file controller action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $fieldKey = $this->getFieldKey();
        try {
            $result = $this->imageUploader->saveFileToTmpDir($fieldKey);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * @return string
     */
    protected function getFieldKey()
    {
        $key = $this->_request->getPost('yoast_image_key', false);

        return $key;
    }
}
