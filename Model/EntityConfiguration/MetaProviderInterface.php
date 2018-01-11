<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    Maxserv: MaxServ_YoastSeo
 * @package     Maxserv: MaxServ_YoastSeo
 * @author      Vincent Hornikx <vincent.hornikx@maxserv.com>
 * @copyright   Copyright (c) 2017 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Model\EntityConfiguration;

use Magento\Framework\View\Layout;

interface MetaProviderInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getUrl();

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
     * @param string $imageUrl
     * @return array
     */
    public function getImageMeta($imageUrl);

    /**
     * @return string
     */
    public function getOpenGraphTitle();

    /**
     * @return string
     */
    public function getOpenGraphDescription();

    /**
     * @return string
     */
    public function getOpenGraphImage();

    /**
     * @return array
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
     * @return string
     */
    public function getTwitterImage();

    /**
     * @param Layout $layout
     * @return $this
     */
    public function setLayout($layout);

    /**
     * @return Layout
     */
    public function getLayout();
}
