<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\BuyerReview\Model;

class Buyer
{
    /**
     * @var \Tobai\BuyerReview\Model\ResourceModel\Buyer
     */
    protected $resource;

    /**
     * @param \Tobai\BuyerReview\Model\ResourceModel\Buyer $resource
     */
    public function __construct(
        \Tobai\BuyerReview\Model\ResourceModel\Buyer $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * @param int $productId
     * @param \Magento\Review\Model\ResourceModel\Review\Collection $reviewsCollection
     * @return \Magento\Review\Model\Review[]
     */
    public function getReviewsWithPurchaseDate($productId, $reviewsCollection)
    {
        $customersWithPurchaseDate = $this->resource->getCustomersWithWithPurchaseDate($productId, $this->getCustomerList($reviewsCollection));

        /** @var \Magento\Review\Model\Review $review */
        foreach ($reviewsCollection as $review) {
            if (isset($customersWithPurchaseDate[$review->getData('customer_id')])) {
                $review->setData('purchase_date', $customersWithPurchaseDate[$review->getData('customer_id')]);
            }
        }
        return $reviewsCollection;
    }

    /**
     * @param \Magento\Review\Model\ResourceModel\Review\Collection $reviewsCollection
     * @return int[]
     */
    protected function getCustomerList($reviewsCollection)
    {
        return array_filter($reviewsCollection->getColumnValues('customer_id'));
    }
}
