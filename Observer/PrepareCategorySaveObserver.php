<?php

namespace MaxServ\YoastSeo\Observer;

class PrepareCategorySaveObserver extends AbstractPrepareImageDataObserver
{
    /**
     * @inheritDoc
     */
    public function getObjectName()
    {
        return 'category';
    }
}
