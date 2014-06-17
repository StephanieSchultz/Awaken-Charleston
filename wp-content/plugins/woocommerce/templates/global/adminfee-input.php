<?php
/**
 * Created by PhpStorm.
 * User: stephanieschultz
 * Date: 6/14/14
 * Time: 1:02 PM
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="adminfee">
    <label>Would you like to increase your donation by paying the 3% processing fee?</label><br>
    <input type="radio" name="adminfee" value="1.03"/> Yes! Add 3% to my gift.<br>
    <input type="radio" name="adminfee" value="1"/>No, please pay admin fee for me.
</div>



<?php

 // $adminfee = $_POST["adminfee"];
