<?php

namespace MaxServ\YoastSeo\Block\Schema;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\CatalogInventory\Model\Stock\Item as StockItem;
use Magento\CatalogInventory\Model\StockRegistry;
use Magento\Directory\Block\Currency;
use Magento\Framework\DataObject;
use Magento\Review\Model\ResourceModel\Rating\Option\Vote\Collection as VoteCollection;
use Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory as VoteCollectionFactory;
use Magento\Review\Model\Review;
use Magento\Review\Model\ReviewFactory;
use Magento\Review\Model\ResourceModel\Review\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;

class Product extends AbstractProduct
{

    /**
     * @var ReviewFactory
     */
    protected $reviewFactory;

    /**
     * @var ReviewCollectionFactory
     */
    protected $reviewCollectionFactory;

    /**
     * @var VoteFactory
     */
    protected $voteCollectionFactory;

    /**
     * @var DataObject
     */
    protected $ratingSummary;

    /**
     * @var StockRegistry
     */
    protected $stockRegistry;

    public function __construct(
        Context $context,
        ReviewFactory $reviewFactory,
        ReviewCollectionFactory $reviewCollectionFactory,
        VoteCollectionFactory $voteCollectionFactory,
        StockRegistry $stockRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->reviewFactory = $reviewFactory;
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->voteCollectionFactory = $voteCollectionFactory;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        $schema = [
            '@context' => 'http://schema.org',
            '@type' => 'Product',
            'name' => $this->getProduct()->getName(),
            'description' => $this->getProductDescription(),
            'image' => $this->getImage(
                $this->getProduct(),
                'product_base_image'
            )->getImageUrl(),
            'url' => $this->getProduct()->getProductUrl()
        ];
        $additional = [
            'aggregateRating' => $this->getAggregateRating(),
            'offers' => $this->getOffer(),
            'review' => $this->getReviews()
        ];
        $schema = array_merge($schema, array_filter($additional));

        return json_encode($schema);
    }

    /**
     * @param $attributeCode
     * @return bool|mixed|string
     */
    protected function getProductAttributeValue($attributeCode)
    {
        try {
            $attributeValue = $this->getProduct()->getAttributeText($attributeCode);
        } catch (\Exception $e) {
            $attributeValue = false;
        }
        if (!$attributeValue) {
            $attributeValue = $this->getProduct()->getDataUsingMethod(
                $attributeCode
            );
        }

        return $attributeValue;
    }

    /**
     * @return string
     */
    protected function getProductDescription()
    {
        $product = $this->getProduct();
        $description = $product->getShortDescription();
        if (!$description) {
            $description = $product->getDescription();
        }

        return $description;
    }

    /**
     *
     */
    protected function loadReviewSummary()
    {
        if (empty($this->ratingSummary)) {
            $ratingSummary = $this->getProduct()->getRatingSummary();
            if (!$ratingSummary) {
                /** @var Review $review */
                $review = $this->reviewFactory->create();
                $review->getEntitySummary($this->getProduct());
                $ratingSummary = $this->getProduct()->getRatingSummary();
            }
            $this->ratingSummary = $ratingSummary;
        }
    }

    /**
     * @return array
     */
    protected function getAggregateRating()
    {
        $this->loadReviewSummary();
        // if product has not ratings return empty array so that rating is excluded from schema
        if ($this->ratingSummary->getReviewsCount() == 0) {
            return [];
        }
        $aggregateRating = [
            '@type' => 'AggregateRating',
            'ratingValue' => round(
                $this->ratingSummary->getRatingSummary() / 20
            ),
            'reviewCount' => $this->ratingSummary->getReviewsCount()
        ];

        return $aggregateRating;
    }

    /**
     * @return array
     */
    protected function getOffer()
    {
        /** @var Currency $currencyBlock */
        $currencyBlock = $this->getLayout()->getBlock('opengraph.currency');
        /** @var StockItem $stockItem */
        $stockItem = $this->stockRegistry->getStockItem(
            $this->getProduct()->getId()
        );
        if ($stockItem->getIsInStock()) {
            $availability = 'http://schema.org/InStock';
        } else {
            $availability = 'http://schema.org/OutOfStock';
        }
        $offer = [
            '@type' => 'Offer',
            'availability' => $availability,
            'price' => (string)number_format(
                (float)$this->getProduct()->getFinalPrice(),
                2
            ),
            'priceCurrency' => $currencyBlock->getCurrentCurrencyCode()
        ];

        return $offer;
    }

    /**
     * @return array
     */
    protected function getReviews()
    {
        /** @var ReviewCollection $reviewCollection */
        $reviewCollection = $this->reviewCollectionFactory->create();
        $reviewCollection
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->addEntityFilter('product', $this->getProduct()->getId())
            ->addStatusFilter(Review::STATUS_APPROVED);
        $reviews = [];
        foreach ($reviewCollection as $review) {
            /** @var Review $review */
            /** @var VoteCollection $voteCollection */
            $voteCollection = $this->getVotesCollection($review);
            $lowest = PHP_INT_MAX;
            $highest = 0;
            $total = 0;
            foreach ($voteCollection as $vote) {
                $value = round($vote->getPercent() / 20);
                $lowest = min($lowest, $value);
                $highest = max($highest, $value);
                $total += $value;
            }
            $average = $total / $voteCollection->getSize();
            $reviews[] = [
                '@type' => 'Review',
                'author' => $review->getNickname(),
                'datePublished' => date(
                    "Y-m-d",
                    strtotime($review->getCreatedAt())
                ),
                'description' => $review->getDetail(),
                'name' => $review->getTitle(),
                'reviewRating' => [
                    '@type' => 'Rating',
                    'bestRating' => $highest,
                    'ratingValue' => $average,
                    'worstRating' => $lowest
                ]
            ];
        }

        return $reviews;
    }

    /**
     * @param Review $review
     * @return VoteCollection
     */
    protected function getVotesCollection($review)
    {
        $voteCollection = $this->voteCollectionFactory->create();
        $voteCollection
            ->setReviewFilter($review->getId())
            ->setStoreFilter($this->_storeManager->getStore()->getId())
            ->addRatingInfo($this->_storeManager->getStore()->getId())
            ->load();

        return $voteCollection;
    }
}
