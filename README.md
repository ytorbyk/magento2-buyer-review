<h2>Welcome</h2>

ToBai Buyer Review is a Magento 2 extension. It identifies which product reviews were written by real buyers and mark these reviews on frontend. This helps potential customers to get more useful information about the product they want to buy.

More information about the extension you can find at this page - <a href="http://www.to-bai.com/magento-2-extensions/buyer-review.html" target="_blank">http://www.to-bai.com/magento-2-extensions/buyer-review.html</a>



<h2>Installation</h2>

Please follow next instructions to successfully install ToBai Buyer Review in your Magento 2 store.

1. Disable the cache with this command:

        bin/magento cache:disable

2. Add extension to composer require section using this command:

        composer require tobai/magento2-buyer-review ~1.0.0

3. Enable module and upgrade with this commands:

        bin/magento module:enable --clear-static-content Tobai_BuyerReview
        bin/magento setup:upgrade

4. Check under Stores->Configuration->Advanced->Advanced that the module ToBai_BuyerReview is present. If ToBai_BuyerReview displays in alphabetical order, you successfully installed the reference module!

5. Flush and enable the cache with this commands:
        
        bin/magento cache:flush
        bin/magento cache:enable

Now you should see new ToBai tab at Stores > Configuration. When you click at this tab you will see Buyer Review section.

Before enabling cache you may compile DI. For compiling run command (before and after "var/di" directory must be deleted):

    bin/magento setup:di:compile


