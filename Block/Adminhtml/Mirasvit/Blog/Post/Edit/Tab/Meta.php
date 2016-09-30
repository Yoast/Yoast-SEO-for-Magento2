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

namespace MaxServ\YoastSeo\Block\Adminhtml\Mirasvit\Blog\Post\Edit\Tab;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Form;

class Meta extends \Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab\Meta
{

    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);

        /** @var \Mirasvit\Blog\Model\Post $post */
        $post = $this->registry->registry('current_model');

        $fieldset = $form->addFieldset('post_meta_fieldset', [
            'class' => 'blog__post-fieldset'
        ]);

        $fieldset->addField('meta_title', 'text', [
            'label' => __('Meta Title'),
            'name'  => 'post[meta_title]',
            'value' => $post->getMetaTitle()
        ]);

        $fieldset->addField('meta_description', 'textarea', [
            'label' => __('Meta Description'),
            'name'  => 'post[meta_description]',
            'value' => $post->getMetaDescription()
        ]);

        $fieldset->addField('meta_keywords', 'textarea', [
            'label' => __('Meta Keywords'),
            'name'  => 'post[meta_keywords]',
            'value' => $post->getMetaKeywords()
        ]);

        $fieldset->addField('url_key', 'text', [
            'label' => __('URL Key'),
            'name'  => 'post[url_key]',
            'value' => $post->getUrlKey()
        ]);

        $fieldset->addField('focus_keyword', 'text', [
            'label' => __('Focus Keyword'),
            'name' => 'post[focus_keyword]',
            'value' => $post->getFocusKeyword()
        ]);

        $facebookFieldset = $form->addFieldset('yoast_meta_facebook', [
            'class' => 'yoastBox-facebook-fieldset'
        ]);

        $facebookFieldset->addField('yoast_facebook_title', 'text', [
            'label' => __('Facebook Title'),
            'name' => 'post[yoast_facebook_title]',
            'value' => $post->getYoastFacebookTitle()
        ]);

        $facebookFieldset->addField('yoast_facebook_description', 'textarea', [
            'label' => __('Facebook Description'),
            'name' => 'post[yoast_facebook_description]',
            'value' => $post->getYoastFacebookDescription()
        ]);

        $facebookFieldset->addField('yoast_facebook_image', 'image', [
            'label' => __('Facebook Image'),
            'name' => 'post[yoast_facebook_image]',
            'value' => $post->getYoastFacebookImage()
        ]);

        $twitterFieldset = $form->addFieldset('yoast_meta_twitter', [
            'class' => 'yoastBox-twitter-fieldset'
        ]);

        $twitterFieldset->addField('yoast_twitter_title', 'text', [
            'label' => __('Twitter Title'),
            'name' => 'post[yoast_twitter_title]',
            'value' => $post->getYoastTwitterTitle()
        ]);

        $twitterFieldset->addField('yoast_twitter_description', 'textarea', [
            'label' => __('Twitter Description'),
            'name' => 'post[yoast_twitter_description]',
            'value' => $post->getYoastTwitterDescription()
        ]);

        $twitterFieldset->addField('yoast_twitter_image', 'image', [
            'label' => __('Twitter Image'),
            'name' => 'post[yoast_twitter_image]',
            'value' => $post->getYoastTwitterImage()
        ]);

        return Form::_prepareForm();
    }

    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $yoastBox = $this->getLayout()->getBlock('maxserv_yoast_block');
        if ($yoastBox) {
            $html .= $yoastBox->toHtml();
        }

        return $html;
    }

}
