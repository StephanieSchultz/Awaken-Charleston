== Installation ==

 * Unzip the files and upload the folder into your plugins folder (wp-content/plugins/) overwriting old versions if they exist
 * Activate the plugin in your WordPress admin area.
 * Open the settings page for WooCommerce and click the "Payment Gateways" tab
 * Click on the sub tab for "USA ePay"
 * Configure your USA EPay express settings.  See below how to.
 
== Configuring USA ePay settings in the WooCommerce admin area ==

 * Enable/Disable - Enable or disable this gateway from being used on the site.
 * Title - This is the title that appears on the checkout page for this payment gateway.
 * Description - This setting controls the message that appears under the payment fields on the checkout page. Here you can list the types of cards you accept. 
 * Transaction Description - 
 * Source Key - Source key generated in the Merchant Console.  See below.
 * PIN - PIN for source key.  This is optional and only needed if a PIN is set in the Merchant Console.
 * Payment Type - Which payment command to run: Sale authorizes and charges the card. Authorize Only verifies funds only.
 * Test Mode - If checked the transaction will be simulated by USA ePay, but not actually processed.
 * Sandbox - If checked the sandbox server will be used.  This overrides the gateway URL parameter below.
 * Gateway URL Override - Override for URL of USA ePay
 * CVV - If checked the CVV field is displayed to the customer and required. 
 * Debugging - Receive emails containing the data sent to and from USA ePay. Does not include credit card number.
 * Debugging Email - Email of recipient of debug emails. 
 
 Press "Save changes" to apply your changes. 


== Where to find your USA ePay Credentials ==

To setup your USA ePay payment gateway you will need to retrieve your Source Key and optional PIN from your Merchant Console.

How to retrieve your Source Key: 

1.  Login to the Merchant Console at https://secure.usaepay.com/login
2.  Click on the "Settings" tab.
3.  Click on the "Source Keys" sub menu.
4.  You will see a list of existing source keys. Press the "Add Source" button to add a new key.
5.  In the "Source Info" box fill out the Name and Pin (this is optional) fields.  
6.  You can leave "Disabled" and "Test Mode" unchecked.  
7.  In the "Allowed Commands" box make sure  "Sale" and "Auth Only" are checked.
8.  Add an email address to the "Email Merchant Receipt To" text area.
9.  Press "Apply" to save your settings and stay on this page. 
10. The field next to "Key"  will now display your source key.  Copy this value to your Woo Commerce USA ePay settings page and "Save Settings"


