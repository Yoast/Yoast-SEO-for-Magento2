<?php

namespace MaxServ\YoastSeo\Model\Config\Source\Product;

use Magento\Framework\Data\OptionSourceInterface;

class RedirectDeletedOptions implements OptionSourceInterface
{
    const TO_PARENT_CATEGORY = 'to_parent_category';
    const TO_CATEGORY = 'to_category';
    const TO_CMS_PAGE = 'to_cms_page';

    /**
     * @var array
     */
    protected $redirectOptions;

    /**
     * @param array $redirectOptions
     */
    public function __construct(
        array $redirectOptions = []
    ) {
        $this->redirectOptions = $redirectOptions;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return array_merge(
            [
                '0' => __("Don't Redirect"),
                self::TO_PARENT_CATEGORY => __('Parent Category Page'),
                self::TO_CATEGORY => __('Category Page'),
                self::TO_CMS_PAGE => __('CMS Page')
            ],
            $this->redirectOptions
        );
    }
}
