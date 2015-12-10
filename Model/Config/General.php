<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\BuyerReview\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class General
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->scopeConfig->isSetFlag('tobai_buyer_review/general/active');
    }

    /**
     * @return bool
     */
    public function isShowIcon()
    {
        return $this->isActive() && $this->scopeConfig->isSetFlag('tobai_buyer_review/general/show_icon');
    }

    /**
     * @return bool
     */
    public function isShowText()
    {
        return $this->isActive() && $this->scopeConfig->isSetFlag('tobai_buyer_review/general/show_text');
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->scopeConfig->getValue('tobai_buyer_review/general/text');
    }
}
