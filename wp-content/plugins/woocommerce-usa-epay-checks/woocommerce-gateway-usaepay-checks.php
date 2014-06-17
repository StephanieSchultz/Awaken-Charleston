<?php
/*
Plugin Name:USA ePay Gateway for Checks
Description: Extends WooCommerce with a <a href="https://www.usaepay.com" target="_blank">USA ePay</a> gateway for check processing. A USA ePay gateway account, and a server with SSL support and an SSL certificate is required for security reasons.
Author: Black Web Marketing
Author URI: http://blackwebmarketing.com
Version: 1.0.0
Text Domain: wc-gateway-usaepay-checks
Domain Path: /languages/
Note: Based on USA ePay Gateway by Grow Development version 1.2.1

*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '8672c81a80cc602c7b9aade5bd701f9c', '18700' );

include_once (__DIR__.'/../woocommerce-gateway-usa-epay/usaepay.php');


add_action('plugins_loaded', 'woocommerce_gateway_usaepay_checks_init', 0);

function woocommerce_gateway_usaepay_checks_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

	/**
	 * Localisation
	 */
	load_plugin_textdomain( 'wc-gateway-usaepay-checks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	define('USAEPAY_DIR', WP_PLUGIN_DIR . "/" . plugin_basename( dirname(__FILE__) ) . '/');
	

	/**
	 * USA ePay Gateway Class
	 **/
	class WC_Gateway_Usaepay_Checks extends WC_Payment_Gateway {

		public function __construct() {
			global $woocommerce;

			$this->id   				= 'usaepaychecks';
			$this->method_title 		= __( 'USA ePay', 'wc-gateway-usaepay-checks' );
			$this->method_description 	= __( 'USA ePay allows customers to checkout using a personal check', 'wc-gateway-usaepay-checks');
			$this->logo 				= untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/images/usaepay.jpg';
			$this->icon   				= untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/images/cards.png';
			$this->has_fields  			= false;

			// Load the form fields
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();

			// Get setting values
			$this->enabled    		= $this->settings['enabled'];
			$this->title    		= $this->settings['title'];
			$this->description  	= $this->settings['description'];
			$this->trandescription  = $this->settings['trandescription'];
			$this->sourcekey  		= $this->settings['sourcekey'];
			$this->pin    			= $this->settings['pin'];
			$this->command   		= $this->settings['command'];
           $this->custreceipt      = $this->settings['custreceipt'];
			$this->testmode  		= $this->settings['testmode'];
			$this->usesandbox  		= $this->settings['usesandbox'];
			$this->gatewayurl  		= $this->settings['gatewayurl'];
			$this->cvv    			= $this->settings['cvv'];
			$this->debugon   		= $this->settings['debugon'];
			$this->debugrecipient  	= $this->settings['debugrecipient'];

			// Actions
			add_action('admin_notices', array(&$this, 'usaepay_ssl_check'));
			add_action('woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options'));
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options'));
	
		}


		/**
		 * Check if SSL is enabled and notify the user
		 */
		function usaepay_ssl_check() {

			if (get_option('woocommerce_force_ssl_checkout')=='no' && $this->enabled=='yes') :

				echo '<div class="error"><p>'.sprintf(__('USA ePay is enabled and the <a href="%s">Force secure checkout</a> option is disabled; your checkout is not secure! Please enable this feature and ensure your server has a valid SSL certificate installed.', 'wc-gateway-usaepay-checks'), admin_url('admin.php?page=woocommerce')).'</p></div>';

			endif;
		}


		/**
		 * Initialize Gateway Settings Form Fields
		 */
		function init_form_fields() {

			$this->form_fields = array(
				'enabled' => array(
					'title' => __( 'Enable/Disable', 'wc-gateway-usaepay-checks' ),
					'label' => __( 'Enable USA ePay Checks Gateway', 'wc-gateway-usaepay-checks' ),
					'type' => 'checkbox',
					'description' => '',
					'default' => 'no'
				),
				'title' => array(
					'title' => __( 'Title', 'wc-gateway-usaepay-checks' ),
					'type' => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'wc-gateway-usaepay-checks' ),
					'default' => __( 'Personal Check', 'wc-gateway-usaepay-checks' )
				),
				'description' => array(
					'title' => __( 'Description', 'wc-gateway-usaepay-checks' ),
					'type' => 'textarea',
					'description' => __( 'This controls the description which is displayed to the customer.', 'wc-gateway-usaepay-checks' ),
					'default' => 'Pay securely with a Personal Check'
				),
				'trandescription' => array(
					'title' => __( 'Transaction Description', 'wc-gateway-usaepay-checks' ),
					'type' => 'textarea',
					'description' => __( 'This controls the description that is added to the transaction sent to USA ePay.', 'wc-gateway-usaepay-checks' ),
					'default' => 'Order from W
					.'
				),
				'sourcekey' => array(
					'title' => __( 'Source Key', 'wc-gateway-usaepay-checks' ),
					'type' => 'text',
					'description' => __( 'Source Key generated by the Merchant Console', 'wc-gateway-usaepay-checks' ),
					'default' => ''
				),
				'pin' => array(
					'title' => __( 'PIN', 'wc-gateway-usaepay-checks' ),
					'type' => 'text',
					'description' => __( 'Pin for Source Key. This field is required only if the merchant has set a Pin in the merchant console.', 'wc-gateway-usaepay-checks' ),
					'default' => ''
				),
				'command' => array(
					'title' => __( 'Payment Type', 'wc-gateway-usaepay-checks' ),
					'type' => 'select',
					'description' => __( 'Payment command to run. ', 'wc-gateway-usaepay-checks' ),
					'options' => array('sale'=>'Sale',
						'authonly'=>'Authorize Only'),
					'default' => ''
				),
                'custreceipt' => array(
                    'title' => __( 'Receipt Email', 'wc-gateway-usaepay-checks' ),
                    'label' => __( 'Send receipt email', 'wc-gateway-usaepay-checks' ),
                    'type' => 'checkbox',
                    'description' => __( 'If checked USA ePay will send a receipt email to the customer in addition to WooCommerce order email.', 'wc-gateway-usaepay-checks' ),
                    'default' => 'no'
                ),
				'testmode' => array(
					'title' => __( 'Test Mode', 'wc-gateway-usaepay-checks' ),
					'label' => __( 'Enable Test Mode', 'wc-gateway-usaepay-checks' ),
					'type' => 'checkbox',
					'description' => __( 'If checked then the transaction will be simulated by USA ePay, but not actually processed.', 'wc-gateway-usaepay-checks' ),
					'default' => 'no'
				),
				'usesandbox' => array(
					'title' => __( 'Sandbox', 'wc-gateway-usaepay-checks' ),
					'label' => __( 'Enable Sandbox', 'wc-gateway-usaepay-checks' ),
					'type' => 'checkbox',
					'description' => __( 'If checked the sandbox server will be used. Overrides the gateway url parameter.', 'wc-gateway-usaepay-checks' ),
					'default' => 'no'
				),
				'gatewayurl' => array(
					'title' => __( 'Gateway URL Override', 'wc-gateway-usaepay-checks' ),
					'type' => 'text',
					'description' => __( 'Override for URL of USA ePay gateway processor. Optional, leave blank for default URL.', 'wc-gateway-usaepay-checks' ),
					'default' => ''
				),
	/*			'cvv' => array(
					'title' => __( 'CVV', 'wc-gateway-usaepay-checks' ),
					'label' => __( 'Require customer to enter credit card CVV code', 'wc-gateway-usaepay-checks' ),
					'type' => 'checkbox',
					'description' => __( '', 'wc-gateway-usaepay-checks' ),
					'default' => 'no'
				),*/
				'debugon' => array(
					'title' => __( 'Debugging', 'wc-gateway-usaepay-checks' ),
					'label' => __( 'Enable debug emails', 'wc-gateway-usaepay-checks' ),
					'type' => 'checkbox',
					'description' => __( 'Receive emails containing the data sent to and from USA ePay.', 'wc-gateway-usaepay-checks' ),
					'default' => 'no'
				),
				'debugrecipient' => array(
					'title' => __( 'Debugging Email', 'wc-gateway-usaepay-checks' ),
					'type' => 'text',
					'description' => __( 'Who should receive the debugging emails.', 'wc-gateway-usaepay-checks' ),
					'default' =>  get_option('admin_email')
				),
			);
		}


		/**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 */
		public function admin_options() {
?>
			<p><a href="http://www.usaepay.com/" target="_blank"><img src="<?php echo $this->logo;?>" /></a></p>
			<h3><?php _e('USA ePay', 'wc-gateway-usaepay-checks'); ?></h3>
	    	<p><?php _e( 'USA ePay allows customers to checkout using a personal check by adding check payment fields on the checkout page and then sending the details to USA ePay for verification.', 'wc-gateway-usaepay-checks' ); ?></p>
	    	<table class="form-table">
	    		<?php $this->generate_settings_html(); ?>
			</table><!--/.form-table-->
	    	<?php
		}


		/**
		 * Payment fields for USA ePay Check Processing.
		 */
		function payment_fields() {
			?>
			<fieldset>
				<p class="form-row form-row-first">
					<label for="usaepay_check_account"><?php echo __("Checking Account Number", 'wc-gateway-usaepay-checks') ?> <span class="required">*</span></label>
					<input type="text" class="input-text" id="usaepay_check_account" name="usaepay_check_account" />
				</p>
				<div class="clear"></div>

				<p class="form-row">
					<label for="usaepay_check_routing"><?php echo __("Bank Routing Number", 'wc-gateway-usaepay-checks') ?> <span class="required">*</span></label>
					<input type="text" class="input-text" id="usaepay_check_routing" name="usaepay_check_routing" />
                  <input type="hidden" id="usaepay_check_accounttype" value="checking">

				</p>
				<div class="clear"></div>
			<!--	<p class="form-row form-row-first">
					<label for="usaepay_expmonth"><?php // echo __("Expiration date", 'wc-gateway-usaepay-checks') ?> <span class="required">*</span></label>
					<select name="usaepay_expmonth" id="usaepay_expmonth" class="woocommerce-select woocommerce-cc-month">
						<option value=""><?php // _e('Month', 'wc-gateway-usaepay-checks') ?></option>
						<?php /*
							$months = array();
							for ($i = 1; $i <= 12; $i++) {
								$timestamp = mktime(0, 0, 0, $i, 1);
								$months[date('m', $timestamp)] = date('F', $timestamp);
							}
							foreach ($months as $num => $name) {
								printf('<option value="%s">%s</option>', $num, $name);
							}*/
						?>
					</select>
					<select name="usaepay_expyear" id="usaepay_expyear" class="woocommerce-select woocommerce-cc-year">
						<option value=""><?php // _e('Year', 'wc-gateway-usaepay-checks') ?></option>
						<?php /*
							$years = array();
							for ($i = date('y'); $i <= date('y') + 15; $i++) {
								printf('<option value="%u">20%u</option>', $i, $i);
							} */
						?>
					</select>
				</p>
				
				<?php if ($this->cvv == 'yes') : ?>
					<p class="form-row form-row-last">
						<label for="usaepay_cvv"><?php _e("Card security code", 'wc-gateway-usaepay-checks') ?> <span class="required">*</span></label>
						<input type="text" class="input-text" id="usaepay_cvv" name="usaepay_cvv" maxlength="4" style="width:45px" />
					</p>
				<?php endif; ?>
				
				<div class="clear"></div>-->
				<p><?php echo $this->description ?></p> */
			</fieldset>
			<?php
		}


		/**
		 * Process the payment and return the result
		 **/

		function process_payment( $order_id ) {
			global $woocommerce;

			$order = new WC_Order( $order_id );

			// Create request
			$tran = new umTransaction;

			$tran->key = $this->sourcekey;
			$tran->pin = $this->pin;
			$tran->ip = $_SERVER['REMOTE_ADDR']; // This allows fraud blocking on the customers ip address
			if (strlen($this->gatewayurl) != 0) {
				$tran->gateway = $this->gatewayurl;
				$gatewayurl = $this->gatewayurl;
			} else {
				if ( $this->usesandbox == 'yes') {
					$gatewayurl = "https://sandbox.usaepay.com/gate";
				} else {
					$gatewayurl = "https://www.usaepay.com/gate";
				}
			}

error_log("CUST RECEIPT:".$this->custreceipt);
            if ( $this->custreceipt == 'yes')
                $tran->custreceipt = 'yes';

			if ( $this->testmode == 'yes') {
				$tran->testmode = 'yes';
			} else {
				$tran->testmode = 0;
			}

			if ( $this->usesandbox == 'yes') {
				$tran->usesandbox = true;
			} else {
				$tran->usesandbox = false;
			}

			$tran->command = $this->command;
//This is the credit card stuff from the original usaepay plugin
/*			$tran->card   = $this->get_post('usaepay_ccnum');
			$tran->exp   = $this->get_post('usaepay_expmonth') . $this->get_post('usaepay_expyear');
			$tran->amount  = $order->order_total;
			$tran->invoice  = $order->id;
			$tran->cardholder = $this->get_post('usaepay_cardholdername');
			$tran->street  = $order->billing_address_1;
			$tran->zip   = $order->billing_postcode;
			$tran->description = $this->trandescription;
			if ($this->cvv == 'yes') {
				$tran->cvv2   = $this->get_post('usaepay_cvv');
			}
*/			

//This is my probably incorrect interpretation of how to handle the check processing
			$tran->account   = $this->get_post('usaepay_check_account');
			$tran->routing   = $this->get_post('usaepay_check_routing');
			$tran->checknum = $this->get_post('usaepay_check_checknum');
			//this could be a hidden field
			$tran->accounttype = $this->get_post('usaepay_check_accounttype');
			//is ssn dlnum dlstate really needed for ach?
			$tran->ssn  = $this->get_post('usaepay_check_ssn');
			$tran->dlnum = $this->get_post('usaepay_check_dlnum');
			$tran->dlstate = $this->get_post('usaepay_check_dlstate');
			$tran->amount  = $order->order_total;
			$tran->invoice  = $order->id;
			
			//

			// Billing Fields
			$tran->billfname = $order->billing_first_name;
			$tran->billlname = $order->billing_last_name;
			$tran->billcompany = $order->billing_company;
			$tran->billstreet = $order->billing_address_1;
			$tran->billstreet2 = $order->billing_address_2;
			$tran->billcity = $order->billing_city;
			$tran->billstate = $order->billing_state;
			$tran->billzip = $order->billing_postcode;
			$tran->billcountry = $order->billing_country;
			$tran->billphone = $order->billing_phone;
			$tran->email = $order->billing_email;

			// Shipping Fields
			$tran->shipfname = $order->shipping_first_name;
			$tran->shiplname = $order->shipping_last_name;
			$tran->shipcompany = $order->shipping_company;
			$tran->shipstreet = $order->shipping_address_1;
			$tran->shipstreet2 = $order->shipping_address_2;
			$tran->shipcity = $order->shipping_city;
			$tran->shipstate = $order->shipping_state;
			$tran->shipzip = $order->shipping_postcode;
			$tran->shipcountry = $order->shipping_country;

			$usaepay_request = array (
				"key" => $this->sourcekey,
				"pin" => $this->pin,
				"customer ip" => $_SERVER['REMOTE_ADDR'],
				"gatewayurl" => $gatewayurl,
				"testmode" => $this->testmode,
				"usessandbox" => $this->usesandbox,
				"command" => $this->command,
				"card" => "[Card number not available for debug]",
				"expiration" => $this->get_post('usaepay_expmonth') . $this->get_post('usaepay_expyear'),
				"amount" => $order->order_total,
				"order id" => $order->id,
				"cardholder" => $this->get_post('usaepay_cardholdername'),
				"street" => $order->billing_address_1,
				"zip" => $order->billing_postcode,
				"description" => $this->trandescription,
				"cvv" => $this->get_post('usaepay_cvv'),
				"billfname" => $order->billing_first_name,
				"billlname" => $order->billing_last_name,
				"billcompany" => $order->billing_company,
				"billstreet" => $order->billing_address_1,
				"billstreet2" => $order->billing_address_2,
				"billcity" => $order->billing_city,
				"billstate" => $order->billing_state,
				"billzip" => $order->billing_postcode,
				"billcountry" => $order->billing_country,
				"billphone" => $order->billing_phone,
				"email" => $order->billing_email,
				"shipfname" => $order->shipping_first_name,
				"shiplname" => $order->shipping_last_name,
				"shipcompany" => $order->shipping_company,
				"shipstreet" => $order->shipping_address_1,
				"shipstreet2" => $order->shipping_address_2,
				"shipcity" => $order->shipping_city,
				"shipstate" => $order->shipping_state,
				"shipzip" => $order->shipping_postcode,
				"shipcountry" => $order->shipping_country
			);

			$this->send_debugging_email( "USA ePay Gateway: " . $gatewayurl . "\n\nSENDING REQUEST:" . print_r($usaepay_request, true));

			// ************************************************
			// Process request

			if ($tran->Process()) {
				// Successful payment

				// Send debug email
				$success_text = "URL: " . $gatewayurl . "\n\nRESULT: Card Approved\n";
				$success_text.= "Authcode: " . $tran->authcode . "\n";
				$success_text.= "Result: " . $tran->result . "\n";
				$success_text.= "AVS Result: " . $tran->avs . "\n";
				if ($this->cvv == 'yes') {
					$success_text.= "CVV2 Result: " . $tran->cvv2 . "\n";
				} else {
					$success_text.= "CVV2 not collected\n";
				}

				$this->send_debugging_email( $success_text);

				$order->add_order_note( __('USA ePay payment completed', 'wc-gateway-usaepay-checks') . ' (Result: ' . $tran->result . ')' );
				$order->payment_complete();
				$woocommerce->cart->empty_cart();

				// Empty awaiting payment session
				if ( preg_match('/1\.[0-9]*\.[0-9]*/', WOOCOMMERCE_VERSION )){
					unset($_SESSION['order_awaiting_payment']);
				} else {
					unset( $woocommerce->session->order_awaiting_payment );
				}

				// Return thank you redirect
				return array(
					'result'  => 'success',
					'redirect' => $this->get_return_url( $order )
				);


			} else {

				// Send debug email
				$error_text = "USA ePay Gateway Error.\nResponse reason text: " . $tran->error . "\n";
				$error_text.= "Result: " .$tran->result . "\n";
				if (@$tran->curlerror) $error_text .= "\nCurl Error: ". $tran->curlerror;
				$this->send_debugging_email( $error_text );

				$cancelNote = __('USA ePay payment failed', 'wc-gateway-usaepay-checks') .' '.
					__('Payment was rejected due to an error', 'wc-gateway-usaepay-checks') . ': "' . $tran->error . '". ';

				$order->add_order_note( $cancelNote );

				$this->debug(__('There was an error processing your payment', 'wc-gateway-usaepay-checks') . ': ' . $tran->result . '', 'error');
			}


		}

		/**
		 * validate_fields function.
		 *
		 * @access public
		 * @return void
		 */
		public function validate_fields() {
			global $woocommerce;

			$cardName = $this->get_post('usaepay_cardholdername');
			$cardNumber = $this->get_post('usaepay_ccnum');
			$cardCSC = $this->get_post('usaepay_cvv');
			$cardExpirationMonth = $this->get_post('usaepay_expmonth');
			$cardExpirationYear = '20' . $this->get_post('usaepay_expyear');

			if ($this->cvv=='yes') {
				//check security code
				if (!ctype_digit($cardCSC)) {
					$this->debug(__('Card security code is invalid (only digits are allowed)', 'wc-gateway-usaepay-checks'), 'error');
					return false;
				}
			}

			//check expiration data
			$currentYear = date('Y');

			if (!ctype_digit($cardExpirationMonth) || !ctype_digit($cardExpirationYear) ||
				$cardExpirationMonth > 12 ||
				$cardExpirationMonth < 1 ||
				$cardExpirationYear < $currentYear ||
				$cardExpirationYear > $currentYear + 20
			) {
				$this->debug(__('Card expiration date is invalid', 'wc-gateway-usaepay-checks'), 'error');
				return false;
			}

			//check card number
			$cardNumber = str_replace(array(' ', '-'), '', $cardNumber);

			if (empty($cardNumber) || !ctype_digit($cardNumber)) {
				$this->debug(__('Card number is invalid', 'wc-gateway-usaepay-checks'), 'error');
				return false;
			}

			if (empty($cardName)) {
				$this->debug(__('You must enter the name of card holder', 'wc-gateway-usaepay-checks'), 'error');
				return false;
			}

			return true;
		}

		/**
		 * Get post data if set
		 **/
		private function get_post($name) {
			if (isset($_POST[$name])) {
				return $_POST[$name];
			}
			return NULL;
		}

		/**
		 * Output a message or error
		 * @param  string $message
		 * @param  string $type
		 */
		public function debug( $message, $type = 'notice' ) {
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
				wc_add_notice( $message, $type );
			} else {
				global $woocommerce;
				$woocommerce->add_message( $message );
			}
		}

		/**
		 * Send debugging email
		 **/
		private function send_debugging_email( $debug ) {

			if ($this->debugon!='yes') return; // Debug must be enabled
			if (!$this->debugrecipient) return; // Recipient needed

			// Send the email
			wp_mail( $this->debugrecipient, __('USA ePay Debug', 'wc-gateway-usaepay-checks'), $debug );

		}

	}

	/**
	 * Plugin page links
	 */
	function wc_usa_epay_checks_plugin_links( $links ) {
	
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_Gateway_Usaepay' ) . '">' . __( 'Settings', 'wc-gateway-usaepay-checks' ) . '</a>',
			'<a href="http://support.woothemes.com/">' . __( 'Support', 'wc-gateway-usaepay-checks' ) . '</a>',
			'<a href="http://docs.woothemes.com/document/usa-epay">' . __( 'Docs', 'wc-gateway-usaepay-checks' ) . '</a>',
		);
	
		return array_merge( $plugin_links, $links );
	}
	
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_usa_epay_checks_plugin_links' );
	
	/**
	 * Add the gateway to woocommerce
	 **/
	function add_usaepay_checks_gateway( $methods ) {
		$methods[] = 'WC_Gateway_Usaepay'; return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'add_usaepay_checks_gateway' );
}