<?php

namespace MaxServ\YoastSeo\Observer;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

abstract class AbstractPrepareImageDataObserver implements ObserverInterface
{
    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        ImageUploader $imageUploader
    ) {
        $this->imageUploader = $imageUploader;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $object = $observer->getEvent()->getData($this->getObjectName());
        $this->updateImage($object, 'facebook');
        $this->updateImage($object, 'twitter');
    }

    /**
     * @param DataObject $object
     * @param string $type
     */
    protected function updateImage($object, $type)
    {
        $image = $object->getData('yoast_' . $type . '_image', null);
        if (is_array($image)) {
            if (isset($image[0]['name'])) {
                $image = $image[0]['name'];
            } else {
                $image = null;
            }
            $object->setData('yoast_' . $type . '_image', $image);
        }
        if ($image !== null) {
            try {
                $this->imageUploader->moveFileFromTmp($image);
            } catch (\Exception $e) {
                //$this->_logger->critical($e);
            }
        }
    }

    /**
     * @return string
     */
    abstract public function getObjectName();
}
