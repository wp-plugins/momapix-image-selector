<?php

/* 
 * demo data
 * Account Momapix Url: http://my.momapix.com/prova
 * Account API Key: b2c101c0ffe1137f189967a8a376b8f1
 * 
*/
        $options = get_option( 'momapix_default_value' );

            if (count($_POST)) {

		$options['momapix_account']	=	$_POST['momapix_account'];
		$options['momapix_api_key']	=	$_POST['momapix_api_key'];
		
		update_option('momapix_default_value', $options);
		
		
		try {
			checkMomaPIXKey();
			echo '<div id="message" class="updated fade">Success!! Plugin is now enabled!</div>';
		} catch (Exception $e) {
			echo '<div id="message" class="error">Warning! '.$e->getMessage().'</div>';
		}

	}	else {

		try {
			checkMomaPIXKey();
		} catch (Exception $e) {
			echo '<div id="message" class="error">Warning! '.$e->getMessage().'</div>';
		}
	}
