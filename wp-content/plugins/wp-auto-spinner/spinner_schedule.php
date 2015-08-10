<?php
add_filter ( 'cron_schedules', 'wp_auto_spinner_once_a_minute' );
function wp_auto_spinner_once_a_minute($schedules) {
	
	// Adds once weekly to the existing schedules.
	$schedules ['once_a_minute'] = array (
			'interval' => 60,
			'display' => __ ( 'once a minute' ) 
	);
	return $schedules;
}

if (! wp_next_scheduled ( 'wp_auto_spinner_spin_hook' )) {
	wp_schedule_event ( time (), 'once_a_minute', 'wp_auto_spinner_spin_hook' );
}

add_action ( 'wp_auto_spinner_spin_hook', 'wp_auto_spinner_spin_function_wrap' );

function wp_auto_spinner_spin_function_wrap(){
	
	$autospin = get_option ( 'wp_auto_spin', array () );
	
	//if internal cron not active return
	if(!   in_array('OPT_AUTO_SPIN_CRON', $autospin) ) return;
	
	wp_auto_spinner_spin_function();
	
	
}

function wp_auto_spinner_spin_function() {

	$autospin = get_option ( 'wp_auto_spin', array () );
	
	
	$lastrun=get_option('wp_auto_spinner_last_run',1392146043);
	
	$timenow=time('now');
	
	$timediff=$timenow - $lastrun ;
	
	if($timediff < 60 ) {
	
		return;
	}
	
	update_option('wp_auto_spinner_last_run', $timenow);
	
	 
	
	if (in_array ( 'OPT_AUTO_SPIN_ACTIVE', $autospin )) {
		
		
		// log call
		//wp_auto_spinner_log_new ( 'cron call', 'we should now process one waiting to be spinned article' );
		
		// get one post deserve spin
		// get execluded cateogries array
		$execl = get_option ( 'wp_auto_spin_execl', array () );
		$post_status= array('publish','draft');
		
		if(in_array('OPT_AUTO_SPIN_SCHEDULED',$autospin)) $post_status= array('publish','future');
		
		// The Query
		$the_query = new WP_Query ( array (
				'category__not_in' => $execl,
				'post_status'=> $post_status,
				'posts_per_page' => 1,
				 
				'ignore_sticky_posts' => true,
				'post_type' => 'any',
				 
				'meta_query' => array(
						
						array(
								'key' => 'wp_auto_spinner_scheduled',
								'compare' => 'EXISTS',
						),array(
								'key' => 'spinned_cnt',
								'compare' => 'NOT EXISTS',
						)
						
				 )
				
		) );
		
		// The Loop
		if ($the_query->have_posts ()) {
			while ( $the_query->have_posts () ) {
				
				 
				$the_query->the_post ();
				
				$newid=get_the_id();
				
				 
				 
				if(trim($newid) == '') return ;
				
				 wp_auto_spinner_log_new ( 'Cron Call >> top post  ', get_the_id()  );
				 $post_id=get_the_id();
				 
				 wp_auto_spinner_post_spin($post_id);
				 
				 break;
				 
			
			}
		} else {
			// no posts found
			//wp_auto_spinner_log_new ( 'Cron >> Cancel', 'No waiting to be spinned posts' );
		}
		
		/* Restore original Post Data */
		wp_reset_postdata ();
	} else {
		
	}
	
	// wp_auto_spinner_the_content_filter()
}