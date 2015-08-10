<?php function wp_auto_spinner_settings(){ ?>

<?php

$itm_prefix='wp_auto_spinner';
$itm_id = 4092452 ;
//delete_option($itm_prefix.'_license_active');
$proxify = true;

$license_active=get_option($itm_prefix.'_license_active','');


//purchase check
if( isset($_POST[$itm_prefix.'_license']) && trim($license_active) === '' ){

	//save it
	update_option($itm_prefix.'_license' , $_POST[ $itm_prefix.'_license'] );

	//activating
	//curl ini
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT,20);
	curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
	curl_setopt($ch, CURLOPT_COOKIEJAR , "cookie.txt");

	//curl get
	$x='error';

	//change domain ?
	$append='';

	if( isset($_POST['wp_auto_spin']) && in_array('OPT_CHANGE_DOMAIN', $_POST['wp_auto_spin']) ){
		$append='&changedomain=yes';
	}

	
	$url='http://deandev.com/license/index.php?itm='.$itm_id.'&domain='.$_SERVER['HTTP_HOST'].'&purchase='.trim($_POST[$itm_prefix.'_license']).$append;

	if(WP_VALVE_PROXY) $url = 'http://labnol-proxy-server.appspot.com/'.str_replace('http://', '', $url);
	
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_URL, trim($url));
	while (trim($x) != ''  ){
		$exec=curl_exec($ch);
		$x=curl_error($ch);
	}

	$resback=$exec;

	$resarr=json_decode($resback);

	if(isset($resarr->message)){
		$license_message=$resarr->message;

		//activate the plugin
		update_option($itm_prefix.'_license_active', 'active');
		update_option($itm_prefix.'_license_active_date', time('now'));
		$license_active=get_option($itm_prefix.'_license_active','');

	}else{
		if(isset($resarr->error))
			$license_error=$resarr->error;
	}



}else{
	 
}	
	// update options on post
	if (isset ( $_POST ['wp_auto_spinner_lang'] )) {
		
		if (! isset ( $_POST ['wp_auto_spin'] )) {
			update_option ( 'wp_auto_spin', array () );
		} else {
			update_option ( 'wp_auto_spin', $_POST ['wp_auto_spin'] );
		}
		
		if (! isset ( $_POST ['wp_spinner_types'] )) {
			update_option ( 'wp_spinner_types', array (
					'post' 
			) );
		} else {
			update_option ( 'wp_spinner_types', $_POST ['wp_spinner_types'] );
		}
		
		if (! isset ( $_POST ['post_category'] )) {
			update_option ( 'wp_auto_spin_execl', array () );
		} else {
			update_option ( 'wp_auto_spin_execl', $_POST ['post_category'] );
		}
		
		update_option ( 'wp_auto_spinner_lang', $_POST ['wp_auto_spinner_lang'] );
		update_option ( 'wp_auto_spinner_execlude', $_POST ['wp_auto_spinner_execlude'] );
		update_option ( 'wp_auto_spinner_email', $_POST ['wp_auto_spinner_email'] );
		update_option ( 'wp_auto_spinner_password', $_POST ['wp_auto_spinner_password'] );
		update_option ( 'wp_auto_spinner_quality', $_POST ['wp_auto_spinner_quality'] );
		update_option ( 'wp_auto_spinner_license', $_POST ['wp_auto_spinner_license'] );
		
		echo '<div class="updated" id="message"><p>Plugin settings <strong>updated successfully</strong>. </p></div>';
	}
	
	// data
	$wp_auto_spinner_lang = get_option ( 'wp_auto_spinner_lang', 'en' );
	$wp_auto_spinner_execlude = get_option ( 'wp_auto_spinner_execlude', '' );
	$wp_auto_spinner_email= get_option('wp_auto_spinner_email','');
	$wp_auto_spinner_password=get_option('wp_auto_spinner_password','');
	$wp_auto_spinner_quality = get_option('wp_auto_spinner_quality','medium');
	$queue_url = admin_url('admin.php?page=wp_auto_spinner_queue');
	
	?>
<div class="wrap">
	<div style="margin-left: 8px" class="icon32" id="icon-options-general">
		<br>
	</div>

	<form method="POST" class="TTWForm-auto-spin">
	
	<h2>Settings <input type="submit" name="save" value="Save Changes" class="button-primary"></h2>
		<div id="dashboard-widgets-wrap">

			<div class="metabox-holder columns-1" id="dashboard-widgets">
				<div class="postbox-container" id="postbox-container-1">
					<div>
						
						<?php if(trim($license_active) !='') {?>
						
						
						<div class="postbox">
							<h3 class="hndle">
								<span>Basic options</span>
							</h3>
							<div class="inside">
								<!--  insider start -->

								 
								<table class="form-table">
									<tbody>
										
										
									
										<tr>
											<th scope="row"><label>Thesaurus Language</label></th>
											<td>
												<select name="wp_auto_spinner_lang" id="field1zz">
													<option value="en" <?php opt_selected('en',$wp_auto_spinner_lang) ?>>English</option>
													<option value="du" <?php opt_selected('du',$wp_auto_spinner_lang) ?>>Dutch</option>
													<option value="ge" <?php opt_selected('ge',$wp_auto_spinner_lang) ?>>German</option>
													<option value="fr" <?php opt_selected('fr',$wp_auto_spinner_lang) ?>>French</option>
													<option value="sp" <?php opt_selected('sp',$wp_auto_spinner_lang) ?>>Spanish</option>
													<option value="po" <?php opt_selected('po',$wp_auto_spinner_lang) ?>>Portuguese</option>
													<option value="ro" <?php opt_selected('ro',$wp_auto_spinner_lang) ?>>Romanian</option>
													<option value="tr" <?php opt_selected('tr',$wp_auto_spinner_lang) ?>>Turkish</option>
		
												</select>
			
												<div class="description">Choose what language the content will be rewritten in .</div></td>
										</tr>
										
										<tr>
											<th scope="row"><label>Auto Spin ?</label></th>
											<td>
												<input name="wp_auto_spin[]" id="field-wp_auto_spin_active-1" value="OPT_AUTO_SPIN_ACTIVE" type="checkbox">  Activate <strong>automatic spining mode</strong> for posts .
												<div style="padding-top:5px" class="description">Tick this option to spin bots posts automatically. when this option is active any bot post get added to your wordpress it will be sent to the <a href="<?php echo $queue_url ?>">spinning queue</a> . note that you should setup the cron job below for the plugin to process items that get sent to the <a href="<?php echo $queue_url ?>">spinning queue</a></div>
											
												
										</tr>
										<tr>
											<th scope="row"><label>Spin Title ?</label></th>
											<td>
			
												<input name="wp_auto_spin[]" id="field-wp_auto_spin_active-1" value="OPT_AUTO_SPIN_ACTIVE_TTL" type="checkbox">   When automatically spinning an article <strong>spin post title</strong> also .
										</tr>
										
									
										
										<tr>
											<th scope="row"><label>Don't spin the content  ?</label></th>
											<td>
			
												<input name="wp_auto_spin[]"  value="OPT_AUTO_SPIN_DEACTIVE_CNT" type="checkbox"> Tick this option to deactivate content spinning . looks like a wierd option but this is suitable for people who want to spin just the title .
										</tr>
			 
										<tr>
											<th scope="row"><label>Spin Slug ?</label></th>
											<td>
			
												<input name="wp_auto_spin[]" id="field-wp_auto_spin_active-1" value="OPT_AUTO_SPIN_SLUG" type="checkbox">   Spin the <abbr title="Permalink">post slug</abbr> ? .
									
										</tr>
										
										<tr>
											<th scope="row"><label>Spin Link texts ?</label></th>
											<td>
			
												<input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_LINKS" type="checkbox">   When spinning an article <strong>spin link text</strong> also .
										</tr>
										
										<tr>
											<th scope="row"><label>Auto spin manually written </label></th>
											<td>
			
												<input name="wp_auto_spin[]" id="field-wp_auto_spin_active-1" value="OPT_AUTO_SPIN_ACTIVE_MANUAL" type="checkbox">   Automatically spin <abbr title="(this will spin posts you are writing manually so when you write and preview post it will get spinned instantly)(not recommended)"><strong>manually written</strong></abbr> articles .
										</tr>
										
										<tr>
											<th scope="row"><label>Spin instantly ? </label></th>
											<td>
			
												<input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_PUBLISH" type="checkbox">   Auto spin instantly <abbr title="Fire spinning processing once the post get published regularly it get added to the spin queue"><strong>on publish</strong></abbr> .
												<div style="padding-top:5px" class="description">By default, Bots posts get added to the <a href="<?php echo $queue_url ?>">spinning queue</a> where the cron do process them one every minute . if you wan't to spin them instantly tick this option .</div> 
										</tr>
										
										<tr>
											<th scope="row"><label>Activate Shuffule mode ? (Not recommended)</label></th>
											<td>
			
												<input name="wp_auto_spin[]"  value="OPT_AUTO_SPIN_ACTIVE_SHUFFLE" type="checkbox">Replace <strong>any word</strong> in the set by another in synonyms set    
												
												<div class="discription">By default if we have a synonyms set on the form   word1|word2|word3  if the content contains word1 it will be replaced by word2 or word3 but if it contains word2 it will not be replaced as the plugin replaces the first word of the set not the counter . by ticking this option if word2 or word3 are in the content they will be replaced by word1 or another word on the set.</div> 
												
										</tr>
										
										<tr>
											<th scope="row"><label>Spin scheduled posts instantly ?</label></th>
											<td>
			
												<input name="wp_auto_spin[]"  value="OPT_AUTO_SPIN_SCHEDULED" type="checkbox">By default scheduled posts get spinned once published tick here if you don't want them to wait untill published    
												
										</tr>
										
									</tbody>
								</table>
									

									<p style="padding-top: 5px">

										<input class="button" name="submit" value="Save Settings" type="submit">

									</p>



								</div>
								<!--/TTWForm-contain-->



								<!-- insider End -->

							</div>
						</div>

						<div class="postbox" >
							<h3 class="hndle">
								<span>Custom Post Types <span class="postbox-title-action"></span></span>
							</h3>
							<div class="inside">
								<!-- insider 3 -->
								
								<table class="form-table">
									<tbody>
								
										<tr>
											<th scope="row"><label>Post Types ?</label></th>
											<td>
			
												<?php
						
														$ptype = get_post_types ( array (), 'names', 'or' );
														foreach ( $ptype as $type ) {
															if (post_type_supports ( $type, 'editor' )) {
																$post_types [] = $type;
															}
														}
														
														foreach ( $post_types as $post_type ) {
												?>
					
														
																 
																<input name="wp_spinner_types[]" value="<?php echo $post_type ?>" type="checkbox"> <span class="option-title">
													       			 <?php echo $post_type ?> 
													                </span>
																 
																
												 <?php
														}
															
												?>
												
												<div class="description">Choose what post types the plugin will support so it shows it's box when you edit this post type</div>
									
		
										</tr>
										
									</tbody>
								</table>
									

								
															<p style="padding-top: 5px">
									<input type="submit" value="Save Settings" name="submit" class="button">
								</p>



								<!-- /insider 3 -->
							</div>
						</div>
						
						
						<div class="postbox" >
							<h3 class="hndle">
								<span>Cron Job Setup<span class="postbox-title-action"></span></span>
							</h3>
							<div class="inside">
								<!-- insider 3 -->
								
								<?php $lastrun=get_option('wp_auto_spinner_last_run',1392146043); ?>
	
								<table class="form-table">
									<tbody>
									    <tr>
											<th scope="row"><label>Internal Cron </label></th>
											<td>
			
											<input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_CRON" type="checkbox"> <span class="option-title"> Use Wordpress Built-in Cron instead . </span>
										</tr>
										<tr>
											<th scope="row"><label>Cron Status </label></th>
											<td>
			
												<p style="margin-top: -13px;"><strong>Current</strong> server time is  <strong>( <?php echo date('h:i:s') ?> )</strong> , Cron last run at <strong>( <?php echo date("h:i:s",$lastrun ) ?> )</strong> this is <strong> ( <?php echo $timdiff = time('now') - $lastrun ?> )</strong> seconds ago and it runs every <strong>( 60 )</strong> seconds to process one item from the <a href="<?php echo $queue_url ?>">spinning queue</a> so it should run again after <strong>( <?php echo( 60 - $timdiff )  ?> )</strong> seconds.
										</tr>
										
										<tr>
											<th scope="row"><label>Run Now </label></th>
											<td>
			
												<?php $cronurl=site_url('?wp_auto_spinner=cron',__FILE__)?>
												( <a target="blank" href="<?php echo $cronurl ?>">Start now </a>)
												
												<div class="description">Click this link to trigger the cron manually which will process one item from the <a href="<?php echo $queue_url ?>">spinning queue</a></div>
										</tr>
										
										<tr>
											<th scope="row"><label>Cron Command </label></th>
											<td>
												
												    <div style="background-color: #FFFBCC; border: 1px solid #E6DB55; color: #555555; padding: 5px; width: 97%; margin-top: 10px">
														
														<?php
								
															echo 'curl ' . $cronurl;
														?> 
													</div>
													<div class="description">Add this command to your hosting crontab. refer to documentation if you need help</div>
													
													<p>if the above command didn't work use the one bleow</p>

																		
								
										</tr>
										
										<tr>
											<th scope="row"><label>Alternate Command</label></th>
											<td>
			
													<div style="background-color: #FFFBCC; border: 1px solid #E6DB55; color: #555555; padding: 5px; width: 97%; margin-top: 10px">
													<?php
														$cronpath = dirname ( __FILE__ ) . '/cron.php';
														echo 'php ' . $cronpath;
														?>
													</div>												
												<div class="description">This is another alternative to the above command in case the above didn't work for your host</div>
										</tr>
									
									</tbody>
								</table>
								
								
								<p style="padding-top: 5px">
									<input type="submit" class="button" name="submit" value="Save Settings">
								</p>


								<!-- /insider 3 -->
							</div>
						</div>


 
						<div class="postbox" >
							<h3 class="hndle">
								<span>Execlusion and protected terms <span class="postbox-title-action"></span></span>
							</h3>
							<div class="inside">
								<!-- insider 3 -->
								
								<table class="form-table">
									<tbody>
									    <tr>
											<th scope="row"><label>Execlude Title Words </label></th>
											<td>
			
											<input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_TITLE_EX" type="checkbox"> <span class="option-title"> Execlude title words from spinning in the post content . </span>
										</tr>
										<tr>
											<th scope="row"><label>Reserved Words </label></th>
											<td>
			
											<textarea style="width: 100%" rows="5" cols="20" name="wp_auto_spinner_execlude" id="field-wp_auto_spinner_execlude"><?php echo stripslashes($wp_auto_spinner_execlude) ?></textarea>
											<div class="description">Words in this box will not be spinned , add them one per line</div>
										</tr>
										<tr>
											<th scope="row"><label>Execluded Categories </label></th>
											<td>
			
											<div id="taxonomy-category" class="categorydiv">
												<div id="category-all" class="tabs-panel">
													<input type="hidden" name="post_category[]" value="0">
													<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
														<?php wp_category_checklist(); ?>
													</ul>
												</div>
			
											</div>
											<div class="description">Posts belong to these categories will not be spinned.</div>
										</tr>
										
										</tbody>
									</table>

								 

								<p style="padding-top: 5px">
									<input type="submit" value="Save Settings" name="submit" class="button">
								</p>

								<!-- /insider 3 -->
							</div>
						</div>

						 


						<div class="postbox" >
							<h3 class="hndle">
								<span>Spin Rewriter (OPTIONAL) <span class="postbox-title-action"></span></span>
							</h3>
							<div class="inside">
								<!-- insider 3 -->

								<p>By Default, the plugin uses it's internal synonyms database but you still can use <a href="http://www.spinrewriter.com/?ref=11ade">spinrewiter</a> API to spin the posts .</p>



								<table  class="form-table">
								
									<tr>
										<th scope="row">SpinRewriter Active </td>
										<td><input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_REWRITER" type="checkbox"> <span class="option-title"> Use spinrewriter.com api . </span></td>
									</tr>
							
									<tr>
										<th scope="row">SpinRewriter email  </td>
										<td><input  style="width:100%" value="<?php echo $wp_auto_spinner_email ;  ?>" name="wp_auto_spinner_email" id="field-spinner-email" type="text"></td>
									</tr>

									<tr>
										<th scope="row">SpinRewriter api key  </td>
										<td><input  style="width:100%" value="<?php echo $wp_auto_spinner_password ;  ?>" name="wp_auto_spinner_password" id="field-spinner-password" type="password"></td>
									</tr>
									
									<?php 
										
										if(trim($wp_auto_spinner_email) != '' && trim($wp_auto_spinner_password) != ''){
											
											 require_once("inc/SpinRewriterAPI.php");

											// Authenticate yourself.
											$spinrewriter_api = new SpinRewriterAPI($wp_auto_spinner_email, $wp_auto_spinner_password);
											
											// Make the actual API request and save response as a native PHP array.
											$api_response = $spinrewriter_api->getQuota();
											
											// Output the API response.
											if(isset($api_response['response'])){
												echo '<tr><th scope="row">Status</th><td>'.$api_response['response'].'</td></tr>';
											}

										}
									
									?>
									
									<tr>
										<th scope="row">Confidence Level</td>
										<td>
											
											 
										 <select name="wp_auto_spinner_quality" >
											<option value="low" <?php opt_selected('low',$wp_auto_spinner_quality) ?>>Low</option>
											<option value="medium" <?php opt_selected('medium',$wp_auto_spinner_quality) ?>>Medium</option>
											<option value="high" <?php opt_selected('high',$wp_auto_spinner_quality) ?>>High</option>
											

										</select>
									 
										
										</td>
									</tr>
									<?php /*
									<tr>
										<th scope="row">Nested Spin  </th>
										<td><input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_NestedSpintax" type="checkbox"> <span class="option-title"> Set whether the One-Click Rewrite process uses nested spinning syntax (multi-level spinning) or not. . </span></td>
									</tr>
									*/
									?>
									
									<tr>
										<th scope="row">Auto Sentences  </th>
										<td><input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_AutoSentences" type="checkbox"> <span class="option-title"> Set whether Spin Rewriter rewrites complete sentences on its own. </span></td>
									</tr>
									<tr>
										<th scope="row">Auto Paragraphs  </th>
										<td><input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_AutoParagraphs" type="checkbox"> <span class="option-title"> Set whether Spin Rewriter rewrites entire paragraphs on its own. </span></td>
									</tr>
									<tr>
										<th scope="row">Auto New Paragraphs  </th>
										<td><input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_AutoNewParagraphs" type="checkbox"> <span class="option-title"> Set whether Spin Rewriter writes additional paragraphs on its own. </span></td>
									</tr>
									<tr>
										<th scope="row">Auto Sentence Trees</th>
										<td><input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_AutoSentenceTrees" type="checkbox"> <span class="option-title"> Set whether Spin Rewriter changes the entire structure of phrases and sentences.</span></td>
									</tr>
									
									<tr>
										<th scope="row">Auto Protected Terms</th>
										<td><input name="wp_auto_spin[]" value="OPT_AUTO_SPIN_AutoProtectedTerms" type="checkbox"> <span class="option-title"> Set whether Spin Rewriter protect capped letters from spinning.</span></td>
									</tr>


									
									
								</table>

 								<p style="padding-top: 5px">
									<input type="submit" value="Save Settings" name="submit" class="button">
								</p>



								<!-- /insider 3 -->
							</div>
						</div>

						<?php } ?>

						<div class="postbox" >
							<h3 class="hndle">
								<span>License<span class="postbox-title-action"></span></span>
							</h3>
							<div class="inside">
								<!-- insider 3 -->
								
						 		<table class="form-table">
									<tbody>
										
										
									
										<tr>
											<th scope="row"><label>Purchase Code</label></th>
											<td><input class="widefat" name="wp_auto_spinner_license" value="<?php echo get_option('wp_auto_spinner_license','') ?>"   type="text">
			
												<div class="description">If you don't know what is your purchase code check this <a href="http://www.youtube.com/watch?v=eAHsVR_kO7A">video</a> on how to get it   .</div></td>
										</tr>
										
										<?php if( isset($license_error) && stristr($license_error,	 'another')  ) {?>
										
										<tr>
											<th scope="row"><label> Change domain </label></th>
											<td><input name="wp_auto_spin[]"   value="OPT_CHANGE_DOMAIN" type="checkbox"> <span class="option-title"> Disable license at the other domain and use it with this domain </span></td>
										</tr>
										
										<?php } ?>
										
										<tr>
											<th scope="row"><label>License Status</label></th>
											<td>
			
												<div class="description"><?php 
												
												if(trim($license_active) !=''){
													echo 'Active';
												}else{
													echo 'Inactive ';
													if(isset($license_error)) echo '<p><span style="color:red">'.$license_error.'</span></p>';
												}
												
												?></div></td>
										</tr>
			
										
									</tbody>
								</table>

								<p style="padding-top: 5px">
									<input type="submit" class="button" name="submit" value="Save Settings">
								</p>


								<!-- /insider 3 -->
							</div>
						</div>						
						

					</div>
				</div>


			</div>


			<div class="clear"></div>
		 
	</form>
	<!--/TTWForm-->
</div>

<script type="text/javascript">
    var $vals = '<?php  $opt= get_option('wp_auto_spin',array()); $wp_spinner_types = get_option('wp_spinner_types',array('post','product') ) ; print_r( implode('|',array_merge($opt,$wp_spinner_types) ) ); ?>';
    $val_arr = $vals.split('|');
    jQuery('input:checkbox').removeAttr('checked');
    jQuery.each($val_arr, function (index, value) {
        if (value != '') {
            jQuery('input:checkbox[value="' + value + '"]').attr('checked', 'checked');
        }
    });

    var $vals = '<?php  $opt= get_option('wp_auto_spin_execl',array()); print_r(implode('|',$opt)); ?>';
    $val_arr = $vals.split('|');

    jQuery.each($val_arr, function (index, value) {
        if (value != '') {
            jQuery('input:checkbox[value="' + value + '"]').attr('checked', 'checked');
        }
    });
    
</script>

<?php } ?>