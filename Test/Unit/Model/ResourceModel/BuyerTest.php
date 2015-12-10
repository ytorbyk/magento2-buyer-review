<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\BuyerReview\Test\Unit\Model\ResourceModel;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class BuyerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\BuyerReview\Model\ResourceModel\Buyer
     */
    protected $buyer;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Item\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $orderItemCollection;

    protected function setUp()
    {
        $orderItemCollectionFactory = $this->getMockBuilder('Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->buyer = $objectManagerHelper->getObject(
            'Tobai\BuyerReview\Model\ResourceModel\Buyer',
            [
                'orderItemCollectionFactory' => $orderItemCollectionFactory
            ]
        );

        $this->orderItemCollection = $this->getMockBuilder('Magento\Sales\Model\ResourceModel\Order\Item\Collection')
            ->disableOriginalConstructor()
            ->getMock();

        $orderItemCollectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->orderItemCollection);
    }

    public function testGetCustomersWithWithPurchaseDate()
    {
        $productId = 12;
        $customerIds = [4, 6, 9];

        $result = [4 => 'date 4', 6 => 'date 6', 9 => 'date 9'];

        $customerOrderItems = [
            $this->createOrderItem(4, 'date 4'),
            $this->createOrderItem(6, 'date 6'),
            $this->createOrderItem(9, 'date 9')
        ];

        $this->orderItemCollection->expects($this->once())
            ->method('join')
            ->with(
                ['order' => 'sales_order'],
                'main_table.order_id = order.entity_id',
                ['customer_id' => 'customer_id', 'purchase_date' => 'order.updated_at']
            )
            ->willReturnSelf();

        $this->orderItemCollection->expects($this->exactly(3))
            ->method('addFieldToFilter')
            ->withConsecutive(
                ['main_table.product_id', $productId],
                ['order.status', 'complete'],
                ['order.customer_id', ['in' => $customerIds]]
            )
            ->willReturnSelf();

        $this->orderItemCollection->expects($this->any())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($customerOrderItems));

        $this->assertEquals($result, $this->buyer->getCustomersWithWithPurchaseDate($productId, $customerIds));
    }

    /**
     * @param int $customerId
     * @param string $purchaseDate
     * @return \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Model\Order\Item
     */
    protected function createOrderItem($customerId, $purchaseDate)
    {
        $orderItem = $this->getMockBuilder('Magento\Sales\Model\Order\Item')
            ->disableOriginalConstructor()
            ->getMock();

        $orderItem->expects($this->atLeastOnce())
            ->method('getData')
            ->willReturnMap(
                [
                    ['customer_id', null, $customerId],
                    ['purchase_date', null, $purchaseDate]
                ]
            );

        return $orderItem;
    }
}
