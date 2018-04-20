<?php

namespace MaxServ\YoastSeo\Model\Entity;

interface MetaProviderInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return string
     */
    public function getOpenGraphTitle();

    /**
     * @return string
     */
    public function getOpenGraphDescription();

    /**
     * @return mixed
     */
    public function getOpenGraphImage();

    /**
     * @return mixed
     */
    public function getOpenGraphVideo();

    /**
     * @return string
     */
    public function getTwitterTitle();

    /**
     * @return string
     */
    public function getTwitterDescription();

    /**
     * @return mixed
     */
    public function getTwitterImage();

    /**
     * @param string $imageUrl
     * @return array
     */
    public function getImageMeta($imageUrl);

    /**
     * @return float
     */
    public function getPrice();
}
