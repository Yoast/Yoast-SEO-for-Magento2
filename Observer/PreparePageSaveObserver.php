<?php

namespace MaxServ\YoastSeo\Observer;

class PreparePageSaveObserver extends AbstractPrepareImageDataObserver
{
    /**
     * @inheritdoc
     */
    public function getObjectName()
    {
        return 'page';
    }
}
