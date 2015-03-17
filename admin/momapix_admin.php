<?php

function momapix_menu_page(){

    
    include 'api_setup.php';
	

?>
<div id="icon-options-general" class="icon32">
<br></br>
</div>
<div id="momapix-general" class="wrap">
<h2>Momapix Settings </h2>
<?php if ( isset( $_GET['message'] )
&& $_GET['message'] == '1' ) { ?>
<div id='message' class='updated fade'><p><strong>Settings Saved</strong></p></div>
<?php } ?>
<form method="post" action="admin-post.php">
<input type="hidden" name="action" value="save_momapix_default_value" />
<!-- Adding security through hidden referrer field -->
<?php wp_nonce_field( 'momapix' ); ?>
Account Momapix Url: <input type="text" name="momapix_account" value="<?php  if(substr($options['momapix_account'], -1) != "/") $options['momapix_account'] .= "/" ; echo esc_html( $options['momapix_account'] );?>" size="55"/><br />
Account API Key: <input type="text" name="momapix_api_key" value="<?php echo esc_html($options['momapix_api_key']); ?>" size="55"/><br />

<input type="submit" value="Submit"
class="button-primary"/>
</form>
</div>
<?php }

add_action( 'admin_init', 'momapix_admin_init' );

// function momapix_config_page() {
// Retrieve plugin configuration options from database

function momapix_admin_init() {
add_action( 'admin_post_save_momapix_default_value',
'process_momapix_default_value' );
}

function process_momapix_default_value() {
// Check that user has proper security level
if ( !current_user_can( 'manage_options' ) )
wp_die( 'Not allowed' );
// Check that nonce field created in configuration form
// is present
check_admin_referer( 'momapix' );
// Retrieve original plugin options array
$options = get_option( 'momapix_default_value' );
// Cycle through all text form fields and store their values
// in the options array
foreach ( array( 'momapix_account','momapix_api_key' ) as $option_name ) {
if ( isset( $_POST[$option_name] ) ) {
$options[$option_name] =
sanitize_text_field( $_POST[$option_name] );
}
}

// Store updated options array to database

update_option( 'momapix_default_value', $options );
// Redirect the page to the configuration form that was
// processed
wp_redirect( add_query_arg(
array( 'page' => 'momapixpage',
'message' => '1' ),
admin_url( 'options-general.php' ) ) );
exit;
}
