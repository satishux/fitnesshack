<?php

//wp-load
require_once( '../../../wp-load.php' );

echo 'Cron job started at '. date( 'h:i:s');
wp_auto_spinner_spin_function();