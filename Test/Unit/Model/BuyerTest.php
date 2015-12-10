<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\BuyerReview\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class BuyerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\BuyerReview\Model\Buyer
     */
    protected $buyer;

    /**
     * @var \Tobai\BuyerReview\Model\ResourceModel\Buyer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resource;

    protected function setUp()
    {
        $this->resource = $this->getMockBuilder('Tobai\BuyerReview\Model\ResourceModel\Buyer')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->buyer = $objectManagerHelper->getObject(
            'Tobai\BuyerReview\Model\Buyer',
            [
                'resource' => $this->resource
            ]
        );
    }

    public function testGetReviewsWithPurchaseDate()
    {
        $productId = 15;
        $customerIds = [2, 4, null, 5, 9];
        $customersWithPurchaseDate = [4 => 'date 4', 9 => 'date 9'];

        $reviews = [
            $this->createReviewModel(2, null),
            $this->createReviewModel(4, 'date 4'),
            $this->createReviewModel(null, null),
            $this->createReviewModel(5, null),
            $this->createReviewModel(9, 'date 9')
        ];

        $reviewCollection = $this->getMockBuilder('Magento\Review\Model\ResourceModel\Review\Collection')
            ->disableOriginalConstructor()
            ->getMock();

        $reviewCollection->expects($this->once())
            ->method('getColumnValues')
            ->with('customer_id')
            ->willReturn($customerIds);

        $reviewCollection->expects($this->any())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($reviews));

        $this->resource->expects($this->once())
            ->method('getCustomersWithWithPurchaseDate')
            ->with($productId, array_filter($customerIds))
            ->willReturn($customersWithPurchaseDate);

        $this->assertSame($reviewCollection, $this->buyer->getReviewsWithPurchaseDate($productId, $reviewCollection));
    }

    /**
     * @param int $customerId
     * @param string $purchaseDate
     * @return \PHPUnit_Framework_MockObject_MockObject|\Magento\Review\Model\Review
     */
    protected function createReviewModel($customerId, $purchaseDate)
    {
        $review = $this->getMockBuilder('Magento\Review\Model\Review')
            ->disableOriginalConstructor()
            ->getMock();

        $review->expects($this->atLeastOnce())
            ->method('getData')
            ->with('customer_id')
            ->willReturn($customerId);

        if ($purchaseDate) {
            $review->expects($this->once())
                ->method('setData')
                ->with('purchase_date', $purchaseDate)
                ->willReturnSelf();
        } else {
            $review->expects($this->never())->method('setData');
        }

        return $review;
    }
}
