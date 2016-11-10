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
 * @author      Vincent Hornikx <vincent.hornikx@maxser.com>
 * @copyright   Copyright (c) 2016 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */

namespace MaxServ\YoastSeo\Plugin\Framework\View\Page;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Page\Config;

class ConfigPlugin
{

    protected $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @param Config $subject
     * @param string $robots
     * @return string
     */
    public function afterGetRobots(Config $subject, $robots)
    {
        if (($robots == 'INDEX,FOLLOW' && $this->isPaged()) || $this->isSearch()) {
            return 'NOINDEX,FOLLOW';
        }

        return $robots;
    }

    /**
     * @return bool
     */
    protected function isPaged()
    {
        $page = $this->request->getParam('p', 1);

        return $page > 1;
    }

    /**
     * @return bool
     */
    protected function isSearch()
    {
        return $this->request->getModuleName() === 'catalogsearch';
    }
}
