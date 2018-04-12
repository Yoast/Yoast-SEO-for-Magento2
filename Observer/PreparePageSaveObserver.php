<?php

namespace MaxServ\YoastSeo\Observer;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class PreparePageSaveObserver implements ObserverInterface
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
        $page = $observer->getEvent()->getPage();
        $this->updateImage($page, 'facebook');
        $this->updateImage($page, 'twitter');
    }

    /**
     * @param Page $subject
     * @param string $type
     */
    protected function updateImage($subject, $type)
    {
        $image = $subject->getData('yoast_' . $type . '_image', null);
        if (is_array($image)) {
            if (isset($image[0]['name'])) {
                $image = $image[0]['name'];
            } else {
                $image = null;
            }
            $subject->setData('yoast_' . $type . '_image', $image);
        }
        if ($image !== null) {
            try {
                $this->imageUploader->moveFileFromTmp($image);
            } catch (\Exception $e) {
                //$this->_logger->critical($e);
            }
        }
    }
}
