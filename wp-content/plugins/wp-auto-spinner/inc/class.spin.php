<?php 
/**
 * spin an article using a treasure database : treasure.dat
 */


class wp_auto_spin_spin{
	
	public $id;
	public $title;
	public $post;
	
	public $article; // spinned article
	
	function wp_auto_spin_spin($id,$title,$post){
		$this->id=$id;
		$this->title=$title;
		$this->post=$post;
	}
	
	/**
	 * function spin wrap : this plugin is a wraper for spin that switches between api spin and internal spin
	 */
	function spin_wrap(){
		
		//check if spinrewriter active 
		$opt= get_option('wp_auto_spin',array());
		
		if( in_array( 'OPT_AUTO_SPIN_REWRITER' , $opt)){
			
			return $this->spin_rewriter();
			
		}else{
			
			return $this->spin();
			
		}
		
		
	}
	
	/**
	 * function spin rewriter : using the spin rewriter api 
	 */
	function spin_rewriter(){
	 
		
		//chek if username and passowrd found
		$wp_auto_spinner_email= get_option('wp_auto_spinner_email','');
		$wp_auto_spinner_password=get_option('wp_auto_spinner_password','');
		$opt= get_option('wp_auto_spin',array());
		$wp_auto_spinner_quality = get_option('wp_auto_spinner_quality','medium');
		$wp_auto_spinner_execlude = get_option ( 'wp_auto_spinner_execlude', '' );
		 
		//execlude title words
		if(in_array('OPT_AUTO_SPIN_TITLE_EX',$opt)){
			$extitle=explode(' ', $this->title);

			$wp_auto_spinner_execlude = explode("\n", $wp_auto_spinner_execlude);
			$wp_auto_spinner_execlude= array_filter( array_merge($wp_auto_spinner_execlude ,$extitle ));
			$wp_auto_spinner_execlude=implode(",", $wp_auto_spinner_execlude);
		
		
		}else{
			
			$wp_auto_spinner_execlude = array_filter( explode("\n", $wp_auto_spinner_execlude));
			$wp_auto_spinner_execlude=implode(",", $wp_auto_spinner_execlude);
			
		}
		
		
		wp_auto_spinner_log_new('spinning', 'Trying to use spinrewriter api');
		
		if(trim($wp_auto_spinner_email) != '' && trim($wp_auto_spinner_password) != '' ){
			
			//running a quote call 
			require_once("SpinRewriterAPI.php");
			
			// Authenticate yourself.
			$spinrewriter_api = new SpinRewriterAPI($wp_auto_spinner_email, $wp_auto_spinner_password);
				
			// Make the actual API request and save response as a native PHP array.
			$api_response = $spinrewriter_api->getQuota();
			
			//check if response is a valid response i.e is array
			if(isset($api_response['status'])){
				
				//check if reponse status is OK or Error 
				if($api_response['status'] == 'OK'){
					
					//let's check if quote available 
					if($api_response['api_requests_available'] > 0){
						
						wp_auto_spinner_log_new('SpinRewriter', 'Quota '. $api_response['api_requests_available']);
						
						$protected_terms = "John, Douglas Adams, then";
						$spinrewriter_api->setProtectedTerms($wp_auto_spinner_execlude);
						
						// (optional) Set whether the One-Click Rewrite process automatically protects Capitalized Words outside the article's title.
						if(in_array('OPT_AUTO_SPIN_AutoProtectedTerms', $opt)){
							$spinrewriter_api->setAutoProtectedTerms(true);
						}else{
							$spinrewriter_api->setAutoProtectedTerms(false);
						}
						
						// (optional) Set the confidence level of the One-Click Rewrite process.
						$spinrewriter_api->setConfidenceLevel($wp_auto_spinner_quality);
						
						
						// (optional) Set whether the One-Click Rewrite process uses nested spinning syntax (multi-level spinning) or not.
						if(in_array('OPT_AUTO_SPIN_NestedSpintax', $opt)){
							$spinrewriter_api->setNestedSpintax(true);
						}else{
							$spinrewriter_api->setNestedSpintax(false);
						}
					 
						
						// (optional) Set whether Spin Rewriter rewrites complete sentences on its own.
						if(in_array('OPT_AUTO_SPIN_AutoSentences', $opt)){
							$spinrewriter_api->setAutoSentences(true);
						}else{
							$spinrewriter_api->setAutoSentences(false);
						}
					 
						
						// (optional) Set whether Spin Rewriter rewrites entire paragraphs on its own.
						if(in_array('OPT_AUTO_SPIN_AutoParagraphs', $opt)){
							$spinrewriter_api->setAutoParagraphs(true);
						}else{
							$spinrewriter_api->setAutoParagraphs(false);
						}
						
						// (optional) Set whether Spin Rewriter writes additional paragraphs on its own.
						if(in_array('OPT_AUTO_SPIN_AutoNewParagraphs', $opt)){
							$spinrewriter_api->setAutoNewParagraphs(true);
						}else{
							$spinrewriter_api->setAutoNewParagraphs(false);
						}
						
						// (optional) Set whether Spin Rewriter changes the entire structure of phrases and sentences.
						if(in_array('OPT_AUTO_SPIN_AutoSentenceTrees', $opt)){	
							$spinrewriter_api->setAutoSentenceTrees(true);
						}else{
							$spinrewriter_api->setAutoSentenceTrees(false);
						}
						
						// (optional) Set the desired spintax format to be used with the returned spun text.
						$spinrewriter_api->setSpintaxFormat("{|}");
						
						
						// Make the actual API request and save response as a native PHP array.
						$text = "John will book a room. Then he will read a book by Douglas Adams.";
						
						$article = stripslashes($this->title).' 911911 '. (stripslashes($this->post) );
						
						$api_response2 = $spinrewriter_api->getTextWithSpintax($article);
						
						//validate reply with OK 
						if(isset($api_response2['status'])){
							
							//status = OK
							if($api_response2['status']== 'OK'){
								
								wp_auto_spinner_log_new('SpinRewriter', 'status is ok i.e valid content returned' );

								$article=$api_response2['response'];
								
								$this->article=$article;
								
								
								//now article contains the synonyms on the form {test|test2}
								return $this->update_post();
								
							}else{
								wp_auto_spinner_log_new('SpinRewriter says', $api_response2['response'] );
							}
							
						}else{
							wp_auto_spinner_log_new('SpinRewriter', 'We could not get valid response ' );
						}
						
						 
						
					}else{
						wp_auto_spinner_log_new('SpinRewriter says', $api_response['response'] );
					}
					
				}else{
					wp_auto_spinner_log_new('SpinRewriter says', $api_response['response'] );
				}
				
			}else{
				wp_auto_spinner_log_new('spinning', 'Trying to use spinrewriter api');
			}
			 
		}//found email and password 
		
		wp_auto_spinner_log_new('SpinRewriter Skip', 'We will use the internal synonyms database instead');
		return $this->spin();
		
	}
	
/*
 * function wp_auto_spin_spin : spins an article by replacing synonyms from database treasure.dat
 * @article: the article to be spinned
 * return : the spinned article spinned or false if error.
 */	
	function spin() {
 
		$opt= get_option('wp_auto_spin',array());
		
		
		$article = stripslashes($this->title).'**9999**'.stripslashes($this->post);
		 
		//match links
		$htmlurls = array();
		
		if( ! in_array( 'OPT_AUTO_SPIN_LINKS' , $opt)){
			preg_match_all( "/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*?)<\/a>/s" ,$article,$matches,PREG_PATTERN_ORDER);
			$htmlurls=$matches[0];
		}
		 
 
		//html tags 
		preg_match_all("/<[^<>]+>/is",$article,$matches,PREG_PATTERN_ORDER);
		$htmlfounds=$matches[0];
		
		 
		//extract all fucken shortcodes
		$pattern="\[.*?\]";
		preg_match_all("/".$pattern."/s",$article,$matches2,PREG_PATTERN_ORDER);
		$shortcodes=$matches2[0];
		
		//javascript 
		preg_match_all("/<script.*?<\/script>/is",$article,$matches3,PREG_PATTERN_ORDER);
		$js=$matches3[0];
		
		//no spin items
		preg_match_all('{\[nospin\].*?\[/nospin\]}s', $article,$matches_ns);
		$nospin = $matches_ns[0];
		
		
		//extract all reserved words 
		$wp_auto_spinner_execlude=get_option('wp_auto_spinner_execlude','');
		$execlude=explode("\n",trim($wp_auto_spinner_execlude));
		
		//execlude title words 
		$autospin=get_option('wp_auto_spin',array());
		if(in_array('OPT_AUTO_SPIN_TITLE_EX',$autospin)){
			 $extitle=explode(' ', $this->title);
			 $execlude=array_merge($execlude ,$extitle );
		}
		

		
		$exword_founds=array(); // ini
		
		foreach($execlude as $exword){
 
			if(preg_match('/\b'. preg_quote(trim($exword),'/') .'\b/u', $article)) {
				$exword_founds[]=trim($exword);
			}
		}
		
		
		// merge shortcodes to html which should be replaced
		$htmlfounds=array_merge( $js, $htmlurls,$htmlfounds,$nospin ,$shortcodes );
 		
	 	 
	 	$i=1;
		foreach($htmlfounds as $htmlfound){
			$article=str_replace($htmlfound,'('.str_repeat('*', $i).')',$article);
			$i++;
		}
		
	 	
	 	//echo $article;
		//replacing execluded words 
		foreach($exword_founds as $exword){
			if(trim($exword) != ''){
				$article = preg_replace('/\b'. preg_quote(trim($exword),'/').'\b/u', '('.str_repeat('*', $i).')' , $article);
				$i++;
			}
		}
		
		 
		
	
		//open the treasures db

		$wp_auto_spinner_lang=get_option('wp_auto_spinner_lang','en');

		//original synonyms	
		$file=file(dirname(__FILE__)  .'/treasures_'.$wp_auto_spinner_lang.'.dat');
		
		//deleted synonyms update 
		$deleted= array_unique( get_option('wp_auto_spinner_deleted_'.$wp_auto_spinner_lang ,array() ) );
		foreach($deleted as $deleted_id){
			unset($file[$deleted_id]);
		}
		
		//updated synonyms update 
		$modified=get_option('wp_auto_spinner_modified_'.$wp_auto_spinner_lang ,array() );
		
		foreach($modified as $key=> $val){
			if(isset($file[$key])){
				$file[$key]=$val;
			}
		}
		
		//custom synonyms on top of synonyms
		$custom=get_option('wp_auto_spinner_custom_'.$wp_auto_spinner_lang ,array() );
		$file= array_merge($custom ,$file );
		//echo $article;
		
		//checking all words for existance
		foreach($file as $line){
			
			//echo 'line:'.$line;
			
			//each synonym word
			$synonyms=explode('|',$line);
			$synonyms=array_map('trim',$synonyms);
			
			
			if(in_array('OPT_AUTO_SPIN_ACTIVE_SHUFFLE', $autospin) ){
				$synonyms2=$synonyms;
			}else{
				$synonyms2=array($synonyms[0]);
			}
			
			
			
			foreach($synonyms2 as $word){
				//echo ' word:'. $word;
				 
				$word=str_replace('/','\/',$word);
				if(trim($word) != '' & ! in_array( strtolower($word), $execlude)){
					//check word existance 
					//echo '<br>'.$word;
					
					
					
					 //echo $word.'<br>';
					//replacing lower case words
				
					if(preg_match('/\b'. $word .'\b/u', $article)) {
					 
					  //replace the word with it's hash str_replace(array("\n", "\r"), '',$line)and add it to the array for restoring to prevent duplicate
					   
						//restructure line to make the original word as the first word
						$restruct=array($word);
						$restruct=array_merge($restruct,$synonyms);
						$restruct=array_unique($restruct);
						//$restruct=array_reverse($restruct);
						$restruct=implode('|',$restruct);
						
						
						$founds[md5($word)]= str_replace(array("\n", "\r"), '',$restruct) ;
						
						$article=preg_replace('/\b'.$word.'\b/u',md5($word),$article);
						
				
					  
					}
					
					
					//replacing upper case words
					$uword=$this->wp_auto_spinner_mb_ucfirst($word);
					
					//echo ' uword:'.$uword;
					
					if(preg_match('/\b'. $uword .'\b/u', $article)) {

						$restruct=array($word);
						$restruct=array_merge($restruct,$synonyms);
						$restruct=array_unique($restruct);
						//$restruct=array_reverse($restruct);
						$restruct=implode('|',$restruct);
							
						
						$founds[md5( $uword )]=  $this->wp_auto_spinner_upper_case( str_replace(array("\n", "\r"), '',$restruct)) ;
					
						$article=preg_replace('/\b'.$uword.'\b/u',md5($uword),$article);
							
					}
					
					 
					
					
					
				}
			}
			
	 
			
		}//foreach line of the synonyms file
		
	 	
		 
		
		//restore html tags
		$i=1;
		foreach($htmlfounds as $htmlfound){
			$article=str_replace( '('.str_repeat('*', $i).')',$htmlfound,$article);
			$i++;
		}
		
		
		//replacing execluded words
		foreach($exword_founds as $exword){
			if(trim($exword) != ''){
				$article=str_replace( '('.str_repeat('*', $i).')',$exword,$article);
				$i++;
			}
			
		}
		
		 
		 
		
		//replace hashes with synonyms
		if(count($founds) !=0){
			foreach ($founds as $key=>$val){
				$article=str_replace($key,'{'.$val.'}',$article);
			}
		}
		
	
		//deleting spin and nospin shortcodes
		$article = str_replace(array('[nospin]','[/nospin]'), '', $article);
		
		$this->article=$article;
		
	 
		//now article contains the synonyms on the form {test|test2}
		return $this->update_post();

		 
	}
	
	// spintax post , update data , return array of data
	function update_post(){
		
		$spinned =$this->article;
		
		 
		
		//synonyms
		if(stristr($spinned, '911911')){
			$spinned=str_replace('911911', '**9999**', $spinned);
		}
			
			
		$spinned_arr=explode('**9999**' , $spinned);
		
		
		$spinned_ttl=$spinned_arr[0];
		$spinned_cnt=$spinned_arr[1];
		
		
		//spintaxed wrirretten instance	 
		require_once('class.spintax.php');
		$spintax=new wp_auto_spinner_Spintax;
		$spintaxed =$spintax->spin($spinned);
		
		
		$spintaxed2=$spintax->editor_form;
		
		$spintaxed_arr=explode('**9999**',$spintaxed);
		$spintaxed_arr2=explode('**9999**',$spintaxed2);
		$spintaxed_ttl=$spintaxed_arr[0];
		$spintaxed_cnt=$spintaxed_arr[1];
		$spintaxed_cnt2=$spintaxed_arr2[1];
		
		
		//update post meta
		$post_id=$this->id;
		update_post_meta($post_id, 'spinned_ttl', $spinned_ttl);
		update_post_meta($post_id, 'spinned_cnt', $spinned_cnt);
		update_post_meta($post_id, 'spintaxed_ttl', $spintaxed_ttl);
		update_post_meta($post_id, 'spintaxed_cnt', $spintaxed_cnt);
		update_post_meta($post_id, 'spintaxed_cnt2', $spintaxed_cnt2);
		update_post_meta($post_id, 'original_ttl', stripslashes($this->title));
		update_post_meta($post_id, 'original_cnt', stripslashes($this->post) );
		
		$return = array();
		$return['spinned_ttl'] =  $spinned_ttl;
		$return['spinned_cnt'] =  $spinned_cnt ;
		$return['spintaxed_ttl'] =  $spintaxed_ttl ;
		$return['spintaxed_cnt'] = $spintaxed_cnt;
		$return['spintaxed_cnt2'] = $spintaxed_cnt2;
		$return['original_ttl'] = $this->title;
		$return['original_cnt'] = $this->post;
		
		return $return ;
		
	}
	
	// convert to upercase compatible with unicode chars
	function wp_auto_spinner_mb_ucfirst($string)
	{
		
		
		if (function_exists('mb_strtoupper')){
			$encoding="utf8";
			$firstChar = mb_substr($string, 0, 1, $encoding);
			$then = mb_substr($string, 1, null, $encoding);
			return mb_strtoupper($firstChar, $encoding) . $then;
		}else{
			return ucfirst($string);
		}
	}
	
	
	//check the first letter of the word and upercase words in the line
	function  wp_auto_spinner_upper_case($line){
	
			$w_arr=explode('|',$line);
	
			for( $i=0;$i< count($w_arr);$i++ ){
				$w_arr[$i] =  $this->wp_auto_spinner_mb_ucfirst($w_arr[$i]) ;
			}
	
			$line=implode('|',	$w_arr );
	
			return $line;
	
	
		 
	}
	
}//end class 