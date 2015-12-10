<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\BuyerReview\Block\Review;

/**
 * Class ListView
 */
class ListView extends \Magento\Review\Block\Product\View\ListView
{
    /**
     * @var \Tobai\BuyerReview\Model\Buyer
     */
    protected $buyer;

    /**
     * @var \Tobai\BuyerReview\Model\Config\General
     */
    protected $generalConfig;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory
     * @param \Tobai\BuyerReview\Model\Buyer $buyer
     * @param \Tobai\BuyerReview\Model\Config\General $generalConfig
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \Tobai\BuyerReview\Model\Buyer $buyer,
        \Tobai\BuyerReview\Model\Config\General $generalConfig,
        array $data = []
    ) {
        $this->buyer = $buyer;
        $this->generalConfig = $generalConfig;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $collectionFactory,
            $data
        );
    }

    /**
     * @return \Magento\Review\Model\Review[]
     */
    public function getReviewItems()
    {
        return $this->generalConfig->isActive()
            ? $this->buyer->getReviewsWithPurchaseDate($this->getProductId(), $this->getReviewsCollection())
            : $this->getReviewsCollection();
    }

    /**
     * @return bool
     */
    public function isShowPurchasedIcon()
    {
        return $this->generalConfig->isShowIcon();
    }

    /**
     * @return bool
     */
    public function isShowPurchasedText()
    {
        return $this->generalConfig->isShowText();
    }

    /**
     * @return string
     */
    public function getPurchasedText()
    {
        return $this->generalConfig->getText();
    }
}
