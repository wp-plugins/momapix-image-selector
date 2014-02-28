<?php

   
include 'api_setup.php';
	
?>


<form id="momapix_form_data" method="post" action="#">


<div id="momapix-general2" class="wrap">
<!--
    <h2>Momapix Settings </h2>
-->

<input type="hidden" name="action"
value="save_momapix_default_value" />
<!-- Adding security through hidden referrer field -->
<?php wp_nonce_field( 'momapix2' ); ?>
Account Momapix Url: <input type="text" name="momapix_account" value="<?php echo esc_html($options['momapix_account']); ?>" size="55"/><br />
Account API Key: <input type="text" name="momapix_api_key" value="<?php echo esc_html($options['momapix_api_key']); ?>" size="55"/><br />

<input type="submit" value="Submit" class="button-primary"/>
</form>
</div>

    <div id="momapix-general2" class="wrap">
       <p id="top_msg"> 
        Activate your Momapix Free Account 
        <a href="http://zanca.it/wordpress/momapix-wordpress-plugin/" target="_blank">CLICK HERE</a>
       </p>
    </div>