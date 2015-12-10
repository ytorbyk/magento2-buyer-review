<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\BuyerReview\Test\Unit\Block\Review;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class ListViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\BuyerReview\Block\Review\ListView|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $listView;

    /**
     * @var \Tobai\BuyerReview\Model\Buyer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $buyer;

    /**
     * @var \Tobai\BuyerReview\Model\Config\General|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generalConfig;

    /**
     * @var \Magento\Review\Model\ResourceModel\Review\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $reviewCollectionFactory;

    protected function setUp()
    {
        $this->buyer = $this->getMockBuilder('Tobai\BuyerReview\Model\Buyer')
            ->disableOriginalConstructor()
            ->getMock();

        $this->generalConfig = $this->getMockBuilder('Tobai\BuyerReview\Model\Config\General')
            ->disableOriginalConstructor()
            ->getMock();

        $this->reviewCollectionFactory = $this->getMockBuilder('Magento\Review\Model\ResourceModel\Review\CollectionFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $listViewConstructorArgs = $objectManagerHelper->getConstructArguments(
            'Tobai\BuyerReview\Block\Review\ListView',
            [
                'buyer' => $this->buyer,
                'generalConfig' => $this->generalConfig,
                'collectionFactory' => $this->reviewCollectionFactory
            ]
        );

        $this->listView = $this->getMockBuilder('Tobai\BuyerReview\Block\Review\ListView')
            ->setConstructorArgs($listViewConstructorArgs)
            ->setMethods(['getProductId', 'getReviewsCollection'])
            ->getMock();
    }

    /**
     * @param bool $isActive
     * @param int|null $productId
     * @dataProvider getReviewItemsDataProvider
     */
    public function testGetReviewItems($isActive, $productId)
    {
        $this->generalConfig->expects($this->once())
            ->method('isActive')
            ->willReturn($isActive);

        $reviewCollection = $this->getMockBuilder('Magento\Review\Model\ResourceModel\Review\Collection')
            ->disableOriginalConstructor()
            ->getMock();

        $this->listView->expects($this->once())
            ->method('getReviewsCollection')
            ->willReturn($reviewCollection);

        if ($isActive) {
            $this->listView->expects($this->once())
                ->method('getProductId')
                ->willReturn($productId);

            $this->buyer->expects($this->once())
                ->method('getReviewsWithPurchaseDate')
                ->with($productId, $reviewCollection)
                ->willReturn($reviewCollection);
        } else {
            $this->listView->expects($this->never())->method('getProductId');
            $this->buyer->expects($this->never())->method('getReviewsWithPurchaseDate');
        }

        $this->assertEquals($reviewCollection, $this->listView->getReviewItems());
    }

    /**
     * @return array
     */
    public function getReviewItemsDataProvider()
    {
        return [
            'enabled' => [true , 16],
            'disabled' => [false, null]
        ];
    }

    /**
     * @param bool $isShowIcon
     * @dataProvider isShowPurchasedIconDataProvider
     */
    public function testIsShowPurchasedIcon($isShowIcon)
    {
        $this->generalConfig->expects($this->once())
            ->method('isShowIcon')
            ->willReturn($isShowIcon);
        $this->assertEquals($isShowIcon, $this->listView->isShowPurchasedIcon());
    }

    /**
     * @return array
     */
    public function isShowPurchasedIconDataProvider()
    {
        return [
            'enabled' => [true],
            'disabled' => [false]
        ];
    }

    /**
     * @param bool $isShowText
     * @dataProvider isShowPurchasedTextDataProvider
     */
    public function testIsShowPurchasedText($isShowText)
    {
        $this->generalConfig->expects($this->once())
            ->method('isShowText')
            ->willReturn($isShowText);
        $this->assertEquals($isShowText, $this->listView->isShowPurchasedText());
    }

    /**
     * @return array
     */
    public function isShowPurchasedTextDataProvider()
    {
        return [
            'enabled' => [true],
            'disabled' => [false]
        ];
    }

    /**
     * @param string $text
     * @dataProvider getPurchasedTextDataProvider
     */
    public function testGetPurchasedText($text)
    {
        $this->generalConfig->expects($this->once())
            ->method('getText')
            ->willReturn($text);
        $this->assertEquals($text, $this->listView->getPurchasedText());
    }

    /**
     * @return array
     */
    public function getPurchasedTextDataProvider()
    {
        return [
            ['text sample 1'],
            ['text sample 2']
        ];
    }
}
