<?php

namespace MaxServ\YoastSeo\Model\Entity\Attribute\Backend;

use Magento\Catalog\Model\ImageUploader;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;

class Image extends AbstractBackend
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param ImageUploader $imageUploader
     * @param RequestInterface $request
     */
    public function __construct(
        ImageUploader $imageUploader,
        RequestInterface $request
    ) {
        $this->imageUploader = $imageUploader;
        $this->request = $request;
    }

    /**
     * @param DataObject $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave($object)
    {
        $postData = $this->request->getPostValue('product');
        if (!$postData) {
            $postData = $this->request->getPost();
        }
        if ($postData) {
            if (!isset($postData['yoast_facebook_image'])) {
                $object->setYoastFacebookImage(null);
            } else {
                $this->updateImage($object, 'facebook');
            }
            if (!isset($postData['yoast_twitter_image'])) {
                $object->setYoastTwitterImage(null);
            } else {
                $this->updateImage($object, 'twitter');
            }
        }
        return $this;
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
                // silent
            }
        }
    }
}
