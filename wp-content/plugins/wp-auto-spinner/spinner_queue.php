<?php
function wp_auto_spinner_queue_fn() {
	?>




<div class="wrap">
	<h2>Waiting Spinning Queue</h2>
 
	<?php $lastrun=get_option('wp_auto_spinner_last_run',1392146043); ?>
	
	<div id="welcome-panel" class="welcome-panel">
		<p style="margin-top: -13px;"><strong>Current</strong> server time is  <strong>( <?php echo date('h:i:s') ?> )</strong> , Cron last run at <strong>( <?php echo date("h:i:s",$lastrun ) ?> )</strong> this is <strong> ( <?php echo $timdiff = time('now') - $lastrun ?> )</strong> seconds ago and it runs every <strong>( 60 )</strong> seconds to process one item from the queue so it should run again after <strong>( <?php echo( 60 - $timdiff )  ?> )</strong> seconds.
	</div>
	
	<h3>Waiting published posts</h3>
	
	
	<table class="widefat fixed">
		<thead>
			<tr>
				<th class="column-date">Index</th>
				<th>Post</th>
				<th class="column-response">Published</th>

			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>index</th>
				<th>Post</th>
				<th>Published</th>
			</tr>
		</tfoot>
		<tbody>

		<?php
	
	$autospin = get_option ( 'wp_auto_spin', array () );
	if (in_array ( 'OPT_AUTO_SPIN_ACTIVE', $autospin )) {
		
		global $post;
		
		// get execluded cateogries array
		$execl = get_option ( 'wp_auto_spin_execl', array () );
		
		// The Query
		$the_query = new WP_Query ( array (
				'category__not_in' => $execl,
				'post_status'=>array('publish','draft'),
				'posts_per_page' => 100,
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
		$i = 1;
		if ($the_query->have_posts ()) {
			
			while ( $the_query->have_posts () ) {
				echo '<tr>';
				$the_query->the_post ();
				echo ' <td>' . $i . '</td>';
				echo ' <td><a href="' . $post->guid . '">' . $post->post_title . '</a></td>';
				echo ' <td>' . $post->post_modified . '</td>';
				echo '</tr>';
				
				$i ++;
			}
		} else {
			// no posts found
			echo '<tr><td colspan="3"><strong>no posts waiting for spinning . </td></tr>';
		}
		
		/* Restore original Post Data */
		wp_reset_postdata ();
	} else {
		echo '<tr><td colspan="3"><strong>Automatic Spinning mode is inactive</strong> thus no posts waiting for spinning . </td></tr>';
	}
	
	?>
 

		</tbody>
	</table>
	
	<p>Total published waiting posts (<?php echo  $the_query->found_posts; ?>) </p>
	
	<h3>Waiting future posts </h3>
	<p>Posts below will be processed once published</p>
	<table class="widefat fixed">
		<thead>
			<tr>
				<th class="column-date">Index</th>
				<th>Post</th>
				<th class="column-response">Published</th>

			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>index</th>
				<th>Post</th>
				<th>Published</th>
			</tr>
		</tfoot>
		<tbody>

		<?php
	
	$autospin = get_option ( 'wp_auto_spin', array () );
	if (in_array ( 'OPT_AUTO_SPIN_ACTIVE', $autospin )) {
		
		global $post;
		
		// get execluded cateogries array
		$execl = get_option ( 'wp_auto_spin_execl', array () );
		
		// The Query
		$the_query = new WP_Query ( array (
				'category__not_in' => $execl,
				'post_status'=>'future',
				'posts_per_page' => 100,
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
		$i = 1;
		if ($the_query->have_posts ()) {
			
			while ( $the_query->have_posts () ) {
				echo '<tr>';
				$the_query->the_post ();
				echo ' <td>' . $i . '</td>';
				echo ' <td><a href="' . $post->guid . '">' . $post->post_title . '</a></td>';
				echo ' <td>' . $post->post_modified . '</td>';
				echo '</tr>';
				
				$i ++;
			}
		} else {
			// no posts found
			echo '<tr><td colspan="3"><strong>no future posts waiting for spinning . </td></tr>';
		}
		
		/* Restore original Post Data */
		wp_reset_postdata ();
	} else {
		echo '<tr><td colspan="3"><strong>Automatic Spinning mode is inactive</strong> thus no posts waiting for spinning . </td></tr>';
	}
	
	?>
 

		</tbody>
	</table>
	
	<p>Total waiting scheduled posts (<?php echo  $the_query->found_posts; ?>) </p>
	
		
	
	
</div>
<?php
}