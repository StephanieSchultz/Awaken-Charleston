<?php
/*

Plugin Name: Add Fee WooCommerce
Plugin URI: http://www.trottyzone.com/product/add-fee-woocommerce
Description: Allows customer or admin to add amount to checkout fee(s). Tax, Tips, Surcharge etc.
Version: pro1.1
Author: Ephrain Marchan
Author URI: http://www.trottyzone.com
License: GPLv2 or later
*/

/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/


if ( ! defined( 'ABSPATH' ) ) die();

load_plugin_textdomain('atwoo-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages/');



// Hook for adding admin menus
if ( is_admin() ){ // admin actions
         // Hook for adding admin menu
        add_action( 'admin_menu', 'atwoo_admin_menu' );


      // Display the 'Settings' link in the plugin row on the installed plugins list page
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'atwoo_admin_plugin_actions', -10);

       add_action( 'admin_init', 'atwoo_register_setting' );
}

function atwoo_register_setting() {	
register_setting( 'atwoo_options', 'atwoo_settings' );
}

// action function for above hook
function atwoo_admin_menu() {

    // Add a new submenu under Settings:
    add_options_page( __('Add Fee WooCommerce', 'atwoo-plugin'), __('Add Fee WooCommerce', 'atwoo-plugin'), 'manage_options', __FILE__ , 'atwoo__options_page');

}
// fc_settings_page() displays the page content 
function atwoo__options_page() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	
// read options values
$options = get_option( 'atwoo_settings' );

        // Save the posted value in the database
update_option( 'atwoo_settings', $options );

?>

<?php 

    // Now display the settings editing screen

    echo '<div class="wrap"></div>';
    
    // icon for settings
    echo '<div id="icon-themes" class="icon32"><br></div>';

    // header

	echo '<div style="clear:both;"><h2 class="nav-tab-wrapper add_tip_wrap">
<span class="add_name_heading">' . __( 'Add Fee WooCommerce', 'atwoo-plugin' ) . '</span>
<a class="nav-tab nav-tab-active" href="#">Pro Version</a>
<a class="nav-tab" href="http://www.trottyzone.com/add-fee-woocommerce-documentation/">Documentation</a> 
<a class="nav-tab" href="http://www.trottyzone.com/store/">More Plugins</a> 
</h2></div>';
    // settings form 
    
    ?>

<form name="form" method="post" action="options.php" id="frm1">

<?php
        	settings_fields( 'atwoo_options' );
			$options = get_option( 'atwoo_settings' );
		?>

<span style="margin-left:20px;float: left;margin-top: -12px;"><?php submit_button(); ?></span>

<style type="text/css">
.widefat.atwoo-table thead tr th {
font-size: 16px;
}
.add_tip_wrap {
float: left;
clear: both;
}
.add_name_heading {
margin-right: 20px;
}
.small_atto {
font-size: 12px;
border-top: 1px solid #888;
}
.toggle_shower {
color: #278ab7;
}
.current_opener {
color: red;
}
.widefat td {
padding: 20px;
font-size: 14px;
}
span.percentage {
margin-top: 20px;
}
</style>

<script type="text/javascript">
jQuery(document).ready(function() {

jQuery(function () {
    jQuery(".add_fee_show_hide").click(function() {
        
jQuery('.widefat th div span',this).toggleClass('current_opener');

  jQuery(this).next().toggle();
      if( jQuery('.slidingDiv').length > 1) {
            jQuery('.slidingDiv table:vissible').hide();

            jQuery(this).next().show();
       }
    }); 
}); 
});

jQuery(document).ready(function(){

    jQuery(".hide_stuff_change_tog").click(function(){
  jQuery(".toggle_shower").toggleClass('current_opener');
     
  });
});
</script>
<div class="add_fee_show_hide">
<a href="#">
<table class="widefat" border="1" style="width:85%;margin-top:20px;">
<thead>
<tr>
<th><?php _e('Customer enter amount', 'atwoo-plugin');  ?></th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a>
</div>


<div class="add_fee_slidingDiv" style="display:none;">
<table class="widefat atwoo-table" border="1" style="width:85%;">

<tbody>
<td></td>
<td></td>
        </tr>
</tbody>

<tbody>
             <tr>

		<td><?php _e('Enable user to enter amount' , 'atwoo-plugin' ); ?></td>

		<td><input name="atwoo_settings[user_amount_alert]" type="checkbox" value="1" 
     <?php if (  1 == ($options['user_amount_alert']) ) echo 'checked="checked"'; ?>   /></td>


             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Enable for all products' , 'atwoo-plugin' ); ?></td>

		<td><input name="atwoo_settings[products_enable]" type="checkbox" value="1" 
     <?php if (  1 == ($options['products_enable']) ) echo 'checked="checked"'; ?>   /></td>


             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Show for Products with ID\'s of' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[id_product_user_amount]" 
	value="<?php echo esc_attr( $options['id_product_user_amount'] ); ?>" placeholder="1625,255,588" /></td>

             </tr>
       </tbody>


       <tbody>
             <tr>

		<td><?php _e('Plain Text Field' , 'atwoo-plugin' ); ?><br><span class="small_atto"><?php _e('First half of notice' , 'atwoo-plugin' ); ?></span></td>
          <td><textarea style="width:100%;height:50px;" type="text" name="atwoo_settings[text_one]" placeholder="Say thanks for the delivery"><?php echo esc_attr( $options['text_one'] ); ?></textarea> </td>


             </tr>
       </tbody>

<tbody>
             <tr>

		<td><?php _e('Link Section' , 'atwoo-plugin' ); ?><br><span class="small_atto"><?php _e('Second Half of Notice, where the customer will click to add amount' , 'atwoo-plugin' ); ?></span></td>
          <td><textarea style="width:100%;height:50px;" type="text" name="atwoo_settings[link_section]" placeholder="Click here to add tip for delivery Guy" ><?php echo esc_attr( $options['link_section'] ); ?></textarea> </td>


             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Placeholder' , 'atwoo-plugin' ); ?><br><span class="small_atto"><?php _e('Example to show customer what to enter' , 'atwoo-plugin' ); ?></span></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[place_tip]" 
              value="<?php echo esc_attr( $options['place_tip'] ); ?>" placeholder="Enter amount value" /></td>

             </tr>
       </tbody>


	<tbody>
             <tr>

		<td><?php _e('Apply Button Text' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[button]" value="<?php echo esc_attr( $options['button'] ); ?>" placeholder="Apply Button Text" /></td>

             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Fee Name' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[fee_name]" value="<?php echo esc_attr( $options['fee_name'] ); ?>" placeholder="Fee Name" /></td>

             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Incremental Value' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[increment_amount]" 
	value="<?php echo esc_attr( $options['increment_amount'] ); ?>" placeholder="5" /></td>

             </tr>
       </tbody>



</table>
      </div>  

<div class="add_fee_show_hide">
<a href="#">
<table class="widefat" border="1" style="width:85%;margin-top:20px;">
<thead>
<tr>
<th><?php _e('Surcharge', 'atwoo-plugin');  ?></th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a>
</div>


<div class="add_fee_slidingDiv" style="display:none;">
<table class="widefat atwoo-table" border="1" style="width:85%;">

<tbody>
<td></td>
<td></td>
        </tr>
</tbody>


<tbody>
             <tr>

		<td><?php _e('Surcharge Name' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[custom_charge_surcharge_name]" 
	value="<?php echo esc_attr( $options['custom_charge_surcharge_name'] ); ?>" placeholder="My Surcharge Name" /></td>

             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Enable Surcharge' , 'atwoo-plugin' ); ?></td>

		<td><input name="atwoo_settings[enable_surcharge_tick]" type="checkbox" value="1" 
     <?php if (  1 == ($options['enable_surcharge_tick']) ) echo 'checked="checked"'; ?>   /></td>


             </tr>
       </tbody>

<tbody>
             <tr>

		<td><?php _e('Enable for all products' , 'atwoo-plugin' ); ?></td>

		<td><input name="atwoo_settings[products_enable_surcharge]" type="checkbox" value="1" 
     <?php if (  1 == ($options['products_enable_surcharge']) ) echo 'checked="checked"'; ?>   /></td>


             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Show for Products with ID\'s of' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[id_product_surcharge]" 
	value="<?php echo esc_attr( $options['id_product_surcharge'] ); ?>" placeholder="1625,255,588" /></td>

             </tr>
       </tbody>



<tbody>
             <tr>

		<td><?php _e('Add Shipping Fee' , 'atwoo-plugin' ); ?><br><span class="small_atto"><?php _e('This will be added to the cart total' , 'atwoo-plugin' ); ?></span></td>

		<td><input name="atwoo_settings[add_shipping_fee]" type="checkbox" value="1" 
     <?php if (  1 == ($options['add_shipping_fee']) ) echo 'checked="checked"'; ?>   /></td>


             </tr>
       </tbody>

<tbody>
             <tr>

		<td><?php _e('Surcharge Percentage Number(s)' , 'atwoo-plugin' ); ?><br><span class="small_atto"><?php _e('This will be multiplied to the cart total amount' , 'atwoo-plugin' ); ?></span></td>
          <td><input style="width:20%;" type="text" name="atwoo_settings[custom_charge_surcharge]" placeholder="12"
	value="<?php echo esc_attr( $options['custom_charge_surcharge'] ); ?>" /> <span class="percentage">%</span></td>

             </tr>
       </tbody>

</table>
</div>

<div class="add_fee_show_hide">
<a href="#">
<table class="widefat" border="1" style="width:85%;margin-top:25px;">
<thead>
<tr>
<th><?php _e('Define Amount', 'atwoo-plugin');  ?></th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a>
</div>


<div class="add_fee_slidingDiv" style="display:none;">
<table class="widefat atwoo-table" border="1" style="width:85%;">

<tbody>
        
<td></td>
<td></td>
        </tr>
</tbody>



<tbody>
             <tr>

		<td><?php _e('Defined amount name' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[defined_charge_tip_name]" 
	value="<?php echo esc_attr( $options['defined_charge_tip_name'] ); ?>" placeholder="My Custom Amount Name" /></td>

             </tr>
       </tbody>



<tbody>
             <tr>

		<td><?php _e('Enable Defined Amount' , 'atwoo-plugin' ); ?></td>

		<td><input name="atwoo_settings[enable_defined_tick]" type="checkbox" value="1" 
     <?php if (  1 == ($options['enable_defined_tick']) ) echo 'checked="checked"'; ?>   /></td>


             </tr>
       </tbody>

<tbody>
             <tr>

		<td><?php _e('Enable for all products' , 'atwoo-plugin' ); ?></td>

		<td><input name="atwoo_settings[products_enable_defined]" type="checkbox" value="1" 
     <?php if (  1 == ($options['products_enable_defined']) ) echo 'checked="checked"'; ?>   /></td>


             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Show for Products with ID\'s of' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[id_product_dcustom]" 
	value="<?php echo esc_attr( $options['id_product_dcustom'] ); ?>" placeholder="1625,255,588" /></td>

             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Calculate defined amount' , 'atwoo-plugin' ); ?><br><span class="small_atto"><?php _e('You can enter a straight value. example 12. Or Calculate values' , 'atwoo-plugin' ); ?></span></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[defined_charge_tip]" placeholder="(12 * 3) / (4 + 8)"
	value="<?php echo esc_attr( $options['defined_charge_tip'] ); ?>" /></td>

             </tr>
       </tbody>
</table>
</div>


<div class="add_fee_show_hide">
<a href="#">
<table class="widefat" border="1" style="width:85%;margin-top:25px;">
<thead>
<tr>
<th><?php _e('Messages', 'atwoo-plugin');  ?></th>
<th><div style="font-size: 30px;float:right;padding-right:3px;"><span class="toggle_shower">&equiv;</span></div></th>
</tr>
</thead>
</table>
</a>
</div>


<div class="add_fee_slidingDiv" style="display:none;">
<table class="widefat atwoo-table" border="1" style="width:85%;">

<tbody>
        
<td></td>
<td></td>
        </tr>
</tbody>



	<tbody>
             <tr>

		<td><?php _e('Success Message' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[success_message]" 
	value="<?php echo esc_attr( $options['success_message'] ); ?>" placeholder="Tip added successfully" /></td>

             </tr>
       </tbody>


<tbody>
             <tr>

		<td><?php _e('Error Message' , 'atwoo-plugin' ); ?></td>
          <td><input style="width:100%;" type="text" name="atwoo_settings[empty_message]" 
	value="<?php echo esc_attr( $options['empty_message'] ); ?>" placeholder="Amount field is empty" /></td>

             </tr>
       </tbody>



</table>
</div>
</form>
<?php
} // end option page


// Build array of links for rendering in installed plugins list
function atwoo_admin_plugin_actions($links) {

	$atwoo_plugin_links = array(
          '<a href="options-general.php?page=add-fee-woocommerce/add-fee-woocommerce.php">'.__('Settings').'</a>',
           '<a href="http://www.trottyzone.com/forums/forum/wordpress-plugins">'.__('Support').'</a>'
                             );

	return array_merge( $atwoo_plugin_links, $links );

}


// enable user to enable amount
function enable_validate_user_amount() {
$options = get_option( 'atwoo_settings' );	
if ( $options['user_amount_alert'] == '1' ) { return true; }
}


if ( enable_validate_user_amount() ) {
add_action('woocommerce_before_checkout_form', 'atwoo_checkbox');
function atwoo_checkbox(){

global $woocommerce, $post, $wpdb;
foreach ($woocommerce->cart->cart_contents as $key => $values ) {
  $options = get_option( 'atwoo_settings' );	

if ( isset($options['id_product_user_amount']) && !empty($options['id_product_user_amount']) ) {

$loop_global_id_atto_camount = $options['id_product_user_amount'];

// move into usable array
$loop_global_id_atto_camount_array = explode(',',$loop_global_id_atto_camount);		
  if(in_array($values['product_id'],$loop_global_id_atto_camount_array) && ($woocommerce->cart->cart_contents_count < 2) ){

$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable'] == 1) ) {

// form and script
?>
<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery(".add-tip-woocommerce").click(function(){
     jQuery(".add-tip-woocommerce1").slideToggle(450);
     
  });
    
});
</script>

<script type="text/javascript">
jQuery(document).ready(function($){
var increment = <?php echo $options['increment_amount']; ?>;

$('.plus, .minus').on('click', function() {
    // the current total
    var total = parseInt($('.input-text-atwoo').text());

    // increment the total based on the class
    total += (this.className == 'plus' ? 1 : -1) * increment;

if ( total > 0 ) {
    // update the div's total
    $('.input-text-atwoo').text(total);
    // update the input's total
    $('.input-text-atwoo').val(total);
} 
});

$('.input-text-atwoo').on('change', function() {
    // update the div's total
    $('.input-text-atwoo').text( $(this).val() );
});
});

jQuery(document).ready(function($){
$('#atwoo.woocommerce-info').click(function() {
    $('.input-text-atwoo').text('1');
    $('.input-text-atwoo').val('1');
}); });
</script>

<script type="text/javascript">
function add_tip_validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

</script>
<style type="text/css">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}
</style>


<div class="add-tip-woocommerce">
<p class="woocommerce-info" id="atwoo">
		<?php echo $options['text_one']; ?><a href="#"><?php echo $options['link_section']; ?></a>
	</p></div>

<div class="add-tip-woocommerce1" style="display:none;border: 1px solid #e0dadf;padding: 20px;margin: 2em 0 2em 0;text-align: left;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">
<form method="post">

	<p class="form-row form-row-first" style="float:left;width:40%;margin-top: -0.2%;margin-left: 20%;">

<input type="button" value="-" class="minus">
		<input onkeypress='add_tip_validate(event)' style="line-height:2em;text-align: center;width: 50%;" type="number" name="atwoo_amount" class="input-text-atwoo" placeholder="<?php echo $options['place_tip']; ?>" id="atwoo_amount" value="<?php echo $_POST['atwoo_amount']; ?>">
<input type="button" value="+" class="plus">

	</p>

	<p class="form-row form-row-last" style="float:left;width: inherit;">
		<input type="submit" class="button" id="submit_atwoo" name="apply_amount" value="<?php echo $options['button']; ?>">
	</p>

	<div class="clear"></div>
</form></div>


<?php 

// checks if submitted fields are empty and adds error
if ( empty( $_POST['atwoo_amount'] ) && isset( $_POST['atwoo_amount'] ) ) {
echo '<ul class="woocommerce-error">
			<li>'.$options['empty_message'].'</li>
	</ul>';
}

if ( !empty( $_POST['atwoo_amount'] ) && isset( $_POST['atwoo_amount'] ) ) {
update_option('atwoo_amount_up', $_POST['atwoo_amount'] );

echo '<div class="woocommerce-message">'.$options['success_message'].'</div>';
}

if ( ! isset( $_POST['atwoo_amount'] ) && empty( $_POST['atwoo_amount'] ) ) {
update_option('atwoo_amount_up', '0' );
}

break;
        }}}

if ( isset($options['id_product_user_amount']) && empty($options['id_product_user_amount']) ) {

$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable'] == 1) ) {

// form and script
?>
<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery(".add-tip-woocommerce").click(function(){
     jQuery(".add-tip-woocommerce1").slideToggle(450);
     
  });
    

});
</script>
<script type="text/javascript">
jQuery(document).ready(function($){
var increment = <?php echo $options['increment_amount']; ?>;

$('.plus, .minus').on('click', function() {
    // the current total
    var total = parseInt($('.input-text-atwoo').text());

    // increment the total based on the class
    total += (this.className == 'plus' ? 1 : -1) * increment;

if ( total > 0 ) {
    // update the div's total
    $('.input-text-atwoo').text(total);
    // update the input's total
    $('.input-text-atwoo').val(total);
} 
});

$('.input-text-atwoo').on('change', function() {
    // update the div's total
    $('.input-text-atwoo').text( $(this).val() );
});
});

jQuery(document).ready(function($){
$('#atwoo.woocommerce-info').click(function() {
    $('.input-text-atwoo').text('1');
    $('.input-text-atwoo').val('1');
}); });
</script>

<script type="text/javascript">
function add_tip_validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
</script>
<style type="text/css">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}
</style>



<div class="add-tip-woocommerce">
<p class="woocommerce-info" id="atwoo">
		<?php echo $options['text_one']; ?><a href="#"><?php echo $options['link_section']; ?></a>
	</p></div>

<div class="add-tip-woocommerce1" style="display:none;border: 1px solid #e0dadf;padding: 20px;margin: 2em 0 2em 0;text-align: left;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">
<form method="post">

	<p class="form-row form-row-first" style="float:left;width:40%;margin-top: -0.2%;margin-left: 20%;">

<input type="button" value="-" class="minus">
		<input onkeypress='add_tip_validate(event)' style="line-height:2em;text-align: center;width: 50%;" type="number" name="atwoo_amount" class="input-text-atwoo" placeholder="<?php echo $options['place_tip']; ?>" id="atwoo_amount" value="<?php echo $_POST['atwoo_amount']; ?>">
<input type="button" value="+" class="plus">

	</p>

	<p class="form-row form-row-last" style="float:left;width: inherit;">
		<input type="submit" class="button" id="submit_atwoo" name="apply_amount" value="<?php echo $options['button']; ?>">
	</p>

	<div class="clear"></div>
</form></div>


<?php 

// checks if submitted fields are empty and adds error
if ( empty( $_POST['atwoo_amount'] ) && isset( $_POST['atwoo_amount'] ) ) {
echo '<ul class="woocommerce-error">
			<li>'.$options['empty_message'].'</li>
	</ul>';
}

if ( !empty( $_POST['atwoo_amount'] ) && isset( $_POST['atwoo_amount'] ) ) {
update_option('atwoo_amount_up', $_POST['atwoo_amount'] );

echo '<div class="woocommerce-message">'.$options['success_message'].'</div>';
}

if ( ! isset( $_POST['atwoo_amount'] ) && empty( $_POST['atwoo_amount'] ) ) {
update_option('atwoo_amount_up', '0' );
}

break;
        }}
}}

add_action( 'woocommerce_before_calculate_totals','woocommerce_custom_user_charge' );
function woocommerce_custom_user_charge() {
global $woocommerce, $post;
$options = get_option( 'atwoo_settings' );
if ( get_option('atwoo_amount_up') !== '0'  ) {
foreach ($woocommerce->cart->cart_contents as $key => $values ) {

if ( isset($options['id_product_user_amount']) && !empty($options['id_product_user_amount']) ) {

$loop_global_id_atto_camount = $options['id_product_user_amount'];

// move into usable array
$loop_global_id_atto_camount_array = explode(',',$loop_global_id_atto_camount);		
  if(in_array($values['product_id'],$loop_global_id_atto_camount_array) && ($woocommerce->cart->cart_contents_count < 2) ){

$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable'] == 1) ) {

if ( is_ajax() ) { 
	$fee_name = ''.$options['fee_name'].'';
	$user_amount = get_option('atwoo_amount_up');
	$user_charge = ( $user_amount );	
	$woocommerce->cart->add_fee( $fee_name, $user_charge, false, '' );
	}

        }}}} 

if ( isset($options['id_product_user_amount']) && empty($options['id_product_user_amount']) ) {

$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable'] == 1) ) {

if ( is_ajax() ) { 
	$fee_name = ''.$options['fee_name'].'';
	$user_amount = get_option('atwoo_amount_up');
	$user_charge = ( $user_amount );	
	$woocommerce->cart->add_fee( $fee_name, $user_charge, false, '' );
	}
	
        }}
                          
	}
}}

// end of user to enter amount



// enable surcharge
function enable_validate_surcharge() {
$options = get_option( 'atwoo_settings' );	
if ( $options['enable_surcharge_tick'] == '1' ) { return true; }
}

if ( enable_validate_surcharge() ) {
add_action( 'woocommerce_before_calculate_totals','woocommerce_custom_surcharge_tip' );
function woocommerce_custom_surcharge_tip() {
global $woocommerce, $post;
$options = get_option( 'atwoo_settings' );

foreach ($woocommerce->cart->cart_contents as $key => $values ) {

if ( isset($options['id_product_surcharge']) && !empty($options['id_product_surcharge']) ) {

$loop_global_id_atto_surcharge = $options['id_product_surcharge'];

// move into usable array
$loop_global_id_atto_surcharge_array = explode(',',$loop_global_id_atto_surcharge);		
  if(in_array($values['product_id'],$loop_global_id_atto_surcharge_array) && ($woocommerce->cart->cart_contents_count < 2) ){


$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable_surcharge'] == 1) ) {
 
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

 	$surcharge_name = ''.$options['custom_charge_surcharge_name'].'';
	$percentage = $options['custom_charge_surcharge'] / 100;

if ($options['add_shipping_fee'] == 1) {
	$surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $percentage;	
	$woocommerce->cart->add_fee( $surcharge_name, $surcharge, false, '' );

}
if ($options['add_shipping_fee'] !== 1) {
	$surcharge = ( $woocommerce->cart->cart_contents_total ) * $percentage;	
	$woocommerce->cart->add_fee( $surcharge_name, $surcharge, false, '' );
}

break;
        }}}   // end of Id select

if ( isset($options['id_product_surcharge']) && !empty($options['id_product_surcharge']) ) {

$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable_surcharge'] == 1) ) {
 
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

 	$surcharge_name = ''.$options['custom_charge_surcharge_name'].'';
	$percentage = $options['custom_charge_surcharge'] / 100;

if ($options['add_shipping_fee'] == 1) {
	$surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $percentage;	
	$woocommerce->cart->add_fee( $surcharge_name, $surcharge, false, '' );

}
if ($options['add_shipping_fee'] !== 1) {
	$surcharge = ( $woocommerce->cart->cart_contents_total ) * $percentage;	
	$woocommerce->cart->add_fee( $surcharge_name, $surcharge, false, '' );
}

break;
        }}   

 
}}}


// enable defined amount
function enable_validate_defined_amount() {
$options = get_option( 'atwoo_settings' );	
if ( $options['enable_defined_tick'] == '1' ) { return true; }
}

if ( enable_validate_defined_amount() ) {
add_action( 'woocommerce_before_calculate_totals','woocommerce_custom_defined_amount_tip' );
function woocommerce_custom_defined_amount_tip() {
global $woocommerce, $post;
$options = get_option( 'atwoo_settings' );

foreach ($woocommerce->cart->cart_contents as $key => $values ) {

if ( isset($options['id_product_dcustom']) && !empty($options['id_product_dcustom']) ) {

$loop_global_id_atto_dcustom = $options['id_product_surcharge'];

// move into usable array
$loop_global_id_atto_dcustom_array = explode(',',$loop_global_id_atto_dcustom);		
  if(in_array($values['product_id'],$loop_global_id_atto_dcustom_array) && ($woocommerce->cart->cart_contents_count < 2) ){

$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable_defined'] == 1) ) {

	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

 	$defined_charge_name = ''.$options['defined_charge_tip_name'].'';
	$defined_amount_woo = $options['defined_charge_tip'];	
	$woocommerce->cart->add_fee( $defined_charge_name, $defined_amount_woo, false, '' );

break;
        }}}   

if ( isset($options['id_product_dcustom']) && empty($options['id_product_dcustom']) ) {

$_product = $values['data'];

if( $_product->needs_shipping() || ($options['products_enable_defined'] == 1) ) {

	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

 	$defined_charge_name = ''.$options['defined_charge_tip_name'].'';
	$defined_amount_woo = $options['defined_charge_tip'];	
	$woocommerce->cart->add_fee( $defined_charge_name, $defined_amount_woo, false, '' );

break;
        }}   

}}}