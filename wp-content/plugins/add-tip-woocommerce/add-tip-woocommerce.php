<?php

/*



Plugin Name: Add Tip WooCommerce

Plugin URI: http://www.trottyzone.com/product/add-fee-woocommerce

Description: Allows customer or admin to add amount to checkout fee(s). Tax, Tips, Surcharge etc.

Version: 1.3

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

    add_options_page( __('Add Tip WooCommerce', 'atwoo-plugin'), __('Add Tip WooCommerce', 'atwoo-plugin'), 'manage_options', __FILE__ , 'atwoo__options_page');



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



    echo '<div class="wrap">';

    

    // icon for settings

    echo '<div id="icon-themes" class="icon32"><br></div>';



    // header



    echo "<h2>" . __( 'Add Tip WooCommerce', 'atwoo-plugin' ) . "</h2>";



    // settings form 

    

    ?>

<div class="updated add-tip-woo" style="width:85%;">
<p>Try <a href="http://www.trottyzone.com/product/add-fee-woocommerce/">Full version</a> for added features such as to:<br>
* Added defined amount to checkout<br>
* Add Surcharge<br>
* Enable for only certainly products<br>
* Allow customer to use plus(+) and minus(-) buttons to increment amount<br>
* Enable for shipping goods only and more...</p>

</div>

<form name="form" method="post" action="options.php" id="frm1">



<?php

        	settings_fields( 'atwoo_options' );

			$options = get_option( 'atwoo_settings' );

		?>



<style type="text/css">

.widefat.atwoo-table thead tr th {

font-size: 16px;

}

.small_atto {

font-size: 12px;

border-top: 1px solid #888;

}

.widefat td {

padding: 20px;

font-size: 14px;

}

span.percentage {

margin-top: 20px;

}

.grey_me {

background: grey;

}

</style>

<table class="widefat atwoo-table" border="1" style="width:85%;margin-top:25px;">



<thead>

	<tr>

		<th><?php _e('Name Of Section' , 'atwoo-plugin' ); ?></th>

		<th><?php _e('Values (by default it is set for Shipping Goods only)' , 'atwoo-plugin' ); ?></th>

			

	</tr>

</thead>





<tbody>

             <tr>



		<td><?php _e('Enable user to enter amount' , 'atwoo-plugin' ); ?></td>



		<td><input name="atwoo_settings[user_amount_alert]" type="checkbox" value="1" 

     <?php if (  1 == ($options['user_amount_alert']) ) echo 'checked="checked"'; ?>   /></td>





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


<?php submit_button(); ?>

</form>

<?php

} // end option page





// Build array of links for rendering in installed plugins list

function atwoo_admin_plugin_actions($links) {



	$atwoo_plugin_links = array(

          '<a href="options-general.php?page=add-tip-woocommerce/add-tip-woocommerce.php">'.__('Settings').'</a>',

           '<a href="http://www.trottyzone.com/forums/forum/wordpress-plugins">'.__('Support').'</a>'

                             );



	return array_merge( $atwoo_plugin_links, $links );



}





// enable user to enable amount

add_action('woocommerce_before_checkout_form', 'atwoo_checkbox');

function atwoo_checkbox(){



global $woocommerce, $post, $wpdb;

  $options = get_option( 'atwoo_settings' );	



if ( $options['user_amount_alert'] == '1' ) { 



// form and script

?>

<script type="text/javascript">

jQuery(document).ready(function(){



    jQuery(".add-tip-woocommerce").click(function(){

     jQuery(".add-tip-woocommerce1").slideToggle(450);

     

  });

    



});



</script>







<div class="add-tip-woocommerce">

<p class="woocommerce-info" id="atwoo">

		<?php echo $options['text_one']; ?><a href="#"><?php echo $options['link_section']; ?></a>

	</p></div>



<div class="add-tip-woocommerce1" style="display:none;border: 1px solid #e0dadf;padding: 20px;margin: 2em 0 2em 0;text-align: left;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">

<form method="post">



	<p class="form-row form-row-first" style="float:left;width:40%;margin-top: -0.2%;margin-left: 20%;">



		<input style="line-height:2em;text-align: center;width: 50%;" type="text" name="atwoo_amount" class="input-text-atwoo" placeholder="<?php echo $options['place_tip']; ?>" id="atwoo_amount" value="<?php echo $_POST['atwoo_amount']; ?>">





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

}}





add_action( 'woocommerce_before_calculate_totals','woocommerce_custom_user_charge' );
function woocommerce_custom_user_charge() {

global $woocommerce, $post;

$options = get_option( 'atwoo_settings' );

if ( get_option('atwoo_amount_up') !== '0'  ) {

if ( $options['user_amount_alert'] == '1' ) { 



	$fee_name = ''.$options['fee_name'].'';

	$user_amount = get_option('atwoo_amount_up');

	$user_charge = ( $user_amount );	

	$woocommerce->cart->add_fee( $fee_name, $user_charge, false, '' );

	

}}}