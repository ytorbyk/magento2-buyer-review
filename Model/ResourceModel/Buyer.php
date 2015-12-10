<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\BuyerReview\Model\ResourceModel;

class Buyer
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory
     */
    protected $orderItemCollectionFactory;

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemCollectionFactory
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemCollectionFactory
    ) {
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
    }

    /**
     * @param int $productId
     * @param array $customerIds
     * @return array
     */
    public function getCustomersWithWithPurchaseDate($productId, $customerIds)
    {
        $customerOrderItemCollection = $this->getCustomerOrderItemCollection($productId, $customerIds);
        $result = $this->prepareCustomersWithPurchaseDate($customerOrderItemCollection);
        return $result;
    }

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Item\Collection $customerOrderItemCollection
     * @return array
     */
    protected function prepareCustomersWithPurchaseDate($customerOrderItemCollection)
    {
        $result = [];
        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        foreach ($customerOrderItemCollection as $orderItem) {
            $result[$orderItem->getData('customer_id')] = $orderItem->getData('purchase_date');
        }
        return $result;
    }

    /**
     * @param int $productId
     * @param array $customerIds
     * @return \Magento\Sales\Model\ResourceModel\Order\Item\Collection
     */
    protected function getCustomerOrderItemCollection($productId, $customerIds)
    {
        $customerOrderItemCollection = $this->orderItemCollectionFactory->create()
            ->join(
                ['order' => 'sales_order'],
                'main_table.order_id = order.entity_id',
                ['customer_id' => 'customer_id', 'purchase_date' => 'order.updated_at']
            )
            ->addFieldToFilter('main_table.product_id', $productId)
            ->addFieldToFilter('order.status', 'complete')
            ->addFieldToFilter('order.customer_id', ['in' => $customerIds]);
        return $customerOrderItemCollection;
    }
}
