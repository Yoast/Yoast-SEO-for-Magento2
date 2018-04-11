<?php

namespace MaxServ\YoastSeo\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class SeoScore extends Column
{
    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        $name = $this->getName();

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$name] = $this->prepareItemData($item);
            }
        }

        return $dataSource;
    }

    /**
     * @param array $item
     * @return string
     */
    protected function prepareItemData($item)
    {
        if (!isset($item['yoast_keyword_score'], $item['yoast_content_score'])) {
            $score = false;
        } else {
            $score = [];
            if (isset($item['yoast_keyword_score'])) {
                $score[] = $item['yoast_keyword_score'];
            }
            if (isset($item['yoast_content_score'])) {
                $score[] = $item['yoast_content_score'];
            }
            $score = array_sum($score) / count($score);
        }

        $html = '<div class="yoastScore-cell">'
            . '<span class="yoastScore-score yoastScore-score--'
            . $this->getScoreClass($score)
            . '"></span></div>';

        return $html;
    }

    protected function getScoreClass($score)
    {
        if (!$score) {
            return 'na';
        } elseif ($score <= 40) {
            return 'bad';
        } elseif ($score <= 70) {
            return 'ok';
        }

        return 'good';
    }
}
