<?php
/**
 *
 * Plugin Name: Microblog Poster
 * Plugin URI: http://efficientscripts.com/microblogposter
 * Description: Automatically publishes your new blog content to Social Networks. Auto-updates Twitter, Facebook, Linkedin, Plurk, Diigo, Delicious..
 * Version: 1.3.9
 * Author: Efficient Scripts
 * Author URI: http://efficientscripts.com/
 *
 *
 */

require_once "microblogposter_curl.php";
require_once "microblogposter_oauth.php";
require_once "microblogposter_bitly.php";



class MicroblogPoster_Poster
{
    
    /**
    * Activate function of this plugin called on activate action hook
    * 
    * 
    * @return  void
    */
    public static function activate()
    {
        global  $wpdb;
        
        $table_accounts = $wpdb->prefix . 'microblogposter_accounts';
        $table_logs = $wpdb->prefix . 'microblogposter_logs';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_accounts} (
            account_id int(11) NOT NULL AUTO_INCREMENT,
            username varchar(200) NOT NULL DEFAULT '',
            password varchar(200) DEFAULT NULL,
            consumer_key varchar(200) DEFAULT NULL,
            consumer_secret varchar(200) DEFAULT NULL,
            access_token varchar(200) DEFAULT NULL,
            access_token_secret varchar(200) DEFAULT NULL,
            type varchar(100) NOT NULL DEFAULT '',
            message_format text,
            extra text,
            PRIMARY KEY (account_id),
            UNIQUE username_type (username,type)
	)";
	
        $wpdb->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_logs} (
            log_id int(11) NOT NULL AUTO_INCREMENT,
            account_id int(11) NOT NULL,
            account_type varchar(100) NOT NULL DEFAULT '',
            username varchar(200) NOT NULL DEFAULT '',
            post_id bigint UNSIGNED NOT NULL,
            action_result tinyint NOT NULL,
            update_message text,
            log_type varchar(50) NOT NULL DEFAULT 'regular',
            log_message text,
            log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (log_id)
        )";
        
        $wpdb->query($sql);
    }
    
    /**
    * Main function of this plugin called on publish_post action hook
    * 
    *
    * @param   int  $post_ID ID of the new/updated post
    * @return  void
    */
    public static function update($post_ID)
    {
        //If this time Microblog Poster is disabled return immediatelly
	if ($_POST['microblogposteroff'] == "on")
        {
            return;
        }
        
        
        $post_categories = wp_get_post_categories($post_ID);
        
        if(is_array($post_categories) && !empty($post_categories))
        {
            $excluded_categories_name = "microblogposter_excluded_categories";
            $excluded_categories_value = get_option($excluded_categories_name, "");
            $excluded_categories = json_decode($excluded_categories_value, true);
            if(is_array($excluded_categories) && !empty($excluded_categories))
            {
                foreach($excluded_categories as $cat_id)
                {
                    if(in_array($cat_id, $post_categories))
                    {
                        return;
                    }
                }
            }
        }
        
        $shortcode_title_max_length_name = "microblogposter_plg_shortcode_title_max_length";
        $shortcode_firstwords_max_length_name = "microblogposter_plg_shortcode_firstwords_max_length";
        $shortcode_excerpt_max_length_name = "microblogposter_plg_shortcode_excerpt_max_length";
        $shortcode_title_max_length_value = get_option($shortcode_title_max_length_name, "");
        $shortcode_title_max_length = 110;
        if(intval($shortcode_title_max_length_value) &&
           intval($shortcode_title_max_length_value) >= 30 && intval($shortcode_title_max_length_value) <= 120)
        {
            $shortcode_title_max_length = $shortcode_title_max_length_value;
        }
        $shortcode_firstwords_max_length_value = get_option($shortcode_firstwords_max_length_name, "");
        $shortcode_firstwords_max_length = 90;
        if(intval($shortcode_firstwords_max_length_value) &&
           intval($shortcode_firstwords_max_length_value) >= 30 && intval($shortcode_firstwords_max_length_value) <= 120)
        {
            $shortcode_firstwords_max_length = $shortcode_firstwords_max_length_value;
        }
        $shortcode_excerpt_max_length_value = get_option($shortcode_excerpt_max_length_name, "");
        $shortcode_excerpt_max_length = 400;
        if(intval($shortcode_excerpt_max_length_value) &&
           intval($shortcode_excerpt_max_length_value) >= 100 && intval($shortcode_excerpt_max_length_value) <= 600)
        {
            $shortcode_excerpt_max_length = $shortcode_excerpt_max_length_value;
        }
        
        $post = get_post($post_ID);
        
        $post_content_actual = $post->post_content;
        $post_content_actual_lkn = MicroblogPoster_Poster::clean_up_and_shorten_content($post_content_actual, 350, ' ');
        $post_content_actual_tmb = MicroblogPoster_Poster::shorten_content($post_content_actual, 500, '.');
        
        $post_thumbnail_id = get_post_thumbnail_id($post_ID);
        $featured_image_src = '';
        if($post_thumbnail_id)
        {
            $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
            $featured_image_src_thumbnail = $image_attributes[0];
            $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'medium');
            $featured_image_src_medium = $image_attributes[0];
        }
        
	$post_title = $post->post_title;
        $post_title = MicroblogPoster_Poster::shorten_title($post_title, $shortcode_title_max_length, ' ');
        
        $permalink = get_permalink($post_ID);
	$update = $post_title . " $permalink";
        
        $post_content = array();
        $post_content[] = home_url();
        $post_content[] = $post_title;
        $post_content[] = $permalink;
        
        $bitly_api = new MicroblogPoster_Bitly();
        $bitly_api_user_value = get_option("microblogposter_plg_bitly_api_user", "");
        $bitly_api_key_value = get_option("microblogposter_plg_bitly_api_key", "");
        $bitly_access_token_value = get_option("microblogposter_plg_bitly_access_token", "");
        $shortened_permalink = '';
        if( ($bitly_api_user_value and $bitly_api_key_value) or $bitly_access_token_value )
        {
            $bitly_api->setCredentials($bitly_api_user_value, $bitly_api_key_value, $bitly_access_token_value);
            $shortened_permalink = $bitly_api->shorten($permalink);
            if($shortened_permalink)
            {
                $update = $post_title . " $shortened_permalink";
                $permalink = $shortened_permalink;
                
            }
        }
	$post_content[] = $shortened_permalink;
        
        $post_excerpt_manual = '';
        $post_excerpt_tmp = MicroblogPoster_Poster::strip_shortcodes_and_tags($post->post_excerpt);
        if($post_excerpt_tmp)
        {
            $post_excerpt_manual = $post_excerpt_tmp;
        }
        $post_content[] = $post_excerpt_manual;
	
        if($post_excerpt_manual != '')
        {
            $post_content[] = $post_excerpt_manual;
        }
        else
        {
            $post_excerpt = MicroblogPoster_Poster::shorten_content($post_content_actual, $shortcode_excerpt_max_length, '.');
            $post_content[] = $post_excerpt;
        }
        
        $author = '';
        if (!function_exists('get_user_by'))
        {
            require_once( ABSPATH . WPINC . '/pluggable.php' );
        }
        $user_ID = get_current_user_id();
        $loggedin_user = get_user_by('id', $user_ID);
        $author_tmp = $loggedin_user->display_name;
        if($author_tmp)
        {
            $author = $author_tmp;
        }
        $post_content[] = $author;
        
        $post_content_first_words = MicroblogPoster_Poster::clean_up_and_shorten_content($post_content_actual, $shortcode_firstwords_max_length, ' ');
        $post_content[] = $post_content_first_words;
        
	$tags = "";
	$posttags = get_the_tags($post_ID);
	if ($posttags) {
	    foreach($posttags as $tag) {
		    $tags .= $tag->slug . ','; 
	    }
	}
	$tags = rtrim($tags,',');
        
        @ini_set('max_execution_time', '0');
        
        MicroblogPoster_Poster::update_twitter($update, $post_content, $post_ID);
        MicroblogPoster_Poster::update_plurk($update, $post_content, $post_ID);
	MicroblogPoster_Poster::update_delicious($post_title, $permalink, $tags, $post_content, $post_ID);
        MicroblogPoster_Poster::update_friendfeed($post_title, $permalink, $post_content, $post_ID);
        MicroblogPoster_Poster::update_facebook($update, $post_content, $post_ID, $post_title, $permalink, $post_content_actual_lkn, $featured_image_src_thumbnail);
        MicroblogPoster_Poster::update_diigo($post_title, $permalink, $tags, $post_content, $post_ID);
        MicroblogPoster_Poster::update_linkedin($update, $post_content, $post_ID, $post_title, $permalink, $post_content_actual_lkn, $featured_image_src_medium);
        MicroblogPoster_Poster::update_tumblr($update, $post_content, $post_ID, $post_title, $permalink, $post_content_actual_tmb);
        
        MicroblogPoster_Poster::maintain_logs();
    }
    
    /**
    * Updates status on twitter
    *
    * @param string  $update Text to be posted on microblogging site
    * @param array $post_content
    * @return void
    */
    public static function update_twitter($update, $post_content, $post_ID)
    {   
        
        $twitter_accounts = MicroblogPoster_Poster::get_accounts('twitter');
        
        if(!empty($twitter_accounts))
        {
            foreach($twitter_accounts as $twitter_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($twitter_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $twitter_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if($twitter_account['message_format'])
                {
                    $twitter_account['message_format'] = str_ireplace('{manual_excerpt}', '', $twitter_account['message_format']);
                    $twitter_account['message_format'] = str_ireplace('{excerpt}', '', $twitter_account['message_format']);
                    $update = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $twitter_account['message_format']);
                }
                $result = MicroblogPoster_Poster::send_signed_request(
                    $twitter_account['consumer_key'],
                    $twitter_account['consumer_secret'],
                    $twitter_account['access_token'],
                    $twitter_account['access_token_secret'],
                    "https://api.twitter.com/1.1/statuses/update.json",
                    array("status"=>$update)
                );
                
                $action_result = 2;
                $result_dec = json_decode($result, true);
                if($result_dec && isset($result_dec['id']))
                {
                    $action_result = 1;
                    $result = "Success";
                }
                
                $log_data = array();
                $log_data['account_id'] = $twitter_account['account_id'];
                $log_data['account_type'] = "twitter";
                $log_data['username'] = $twitter_account['username'];
                $log_data['post_id'] = $post_ID;
                $log_data['action_result'] = $action_result;
                $log_data['update_message'] = $update;
                $log_data['log_message'] = $result;
                MicroblogPoster_Poster::insert_log($log_data);
            }
        }
        
    }
    
    /**
    * Updates status on plurk
    *
    * @param string  $update Text to be posted on microblogging site
    * @param array $post_content
    * @return void
    */
    public static function update_plurk($update, $post_content, $post_ID)
    {   
        
        $plurk_accounts = MicroblogPoster_Poster::get_accounts('plurk');
        
        if(!empty($plurk_accounts))
        {
            foreach($plurk_accounts as $plurk_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($plurk_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $plurk_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if($plurk_account['message_format'])
                {
                    $plurk_account['message_format'] = str_ireplace('{manual_excerpt}', '', $plurk_account['message_format']);
                    $plurk_account['message_format'] = str_ireplace('{excerpt}', '', $plurk_account['message_format']);
                    $update = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $plurk_account['message_format']);
                }
                
                $qualifier = "says";
                $extra = json_decode($plurk_account['extra'], true);
                if(is_array($extra))
                {    
                    if(isset($extra['qualifier']))
                    {
                        $qualifier = $extra['qualifier'];
                    }
                }
                $result = MicroblogPoster_Poster::send_signed_request(
                    $plurk_account['consumer_key'],
                    $plurk_account['consumer_secret'],
                    $plurk_account['access_token'],
                    $plurk_account['access_token_secret'],
                    "http://www.plurk.com/APP/Timeline/plurkAdd",
                    array("content"=>$update, "qualifier"=>$qualifier)
                );
                
                $action_result = 2;
                $result_dec = json_decode($result, true);
                if($result_dec && isset($result_dec['plurk_id']))
                {
                    $action_result = 1;
                    $result = "Success";
                }
                
                $log_data = array();
                $log_data['account_id'] = $plurk_account['account_id'];
                $log_data['account_type'] = "plurk";
                $log_data['username'] = $plurk_account['username'];
                $log_data['post_id'] = $post_ID;
                $log_data['action_result'] = $action_result;
                $log_data['update_message'] = $update;
                $log_data['log_message'] = $result;
                MicroblogPoster_Poster::insert_log($log_data);
            }
        }
        
    }
    
    
    /**
    * Updates status on delicious.com
    *
    * @param   string  $title Text to be posted on microblogging site
    * @param   string  $link
    * @param   string  $tags
    * @param array $post_content 
    * @return  void
    */
    public static function update_delicious($title, $link, $tags, $post_content, $post_ID)
    {
	
        $curl = new MicroblogPoster_Curl();
        $delicious_accounts = MicroblogPoster_Poster::get_accounts('delicious');
        
        if(!empty($delicious_accounts))
        {
            foreach($delicious_accounts as $delicious_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($delicious_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $delicious_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if($delicious_account['message_format'])
                {
                    $delicious_account['message_format'] = str_ireplace('{site_url}', '', $delicious_account['message_format']);
                    $delicious_account['message_format'] = str_ireplace('{url}', '', $delicious_account['message_format']);
                    $delicious_account['message_format'] = str_ireplace('{short_url}', '', $delicious_account['message_format']);
                    $descr = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $delicious_account['message_format']);
                }
                $is_raw = MicroblogPoster_SupportEnc::is_enc($delicious_account['extra']);
                if(!$is_raw)
                {
                    $delicious_account['password'] = MicroblogPoster_SupportEnc::dec($delicious_account['password']);
                }
                $extra = json_decode($delicious_account['extra'], true);
                if(is_array($extra))
                {
                    $include_tags = (isset($extra['include_tags']) && $extra['include_tags'] == 1)?true:false;
                }
                $curl->set_credentials($delicious_account['username'],$delicious_account['password']);
                $curl->set_user_agent("Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1");

                $link_enc=urlencode($link);
                $title_enc = urlencode($title);
                $descr_enc = urlencode($descr);
                $tags_enc = urlencode($tags);
                $update_message = $title." - ".$link;

                $url = "https://api.del.icio.us/v1/posts/add?url={$link_enc}&description={$title_enc}&extended={$descr_enc}&shared=yes";
                if($include_tags)
                {
                    $url .= "&tags=$tags_enc";
                    $update_message .= " ($tags)";
                }
                $result = $curl->fetch_url($url);
                
                $action_result = 2;
                if($result && stripos($result, 'code="done"')!==false)
                {
                    $action_result = 1;
                    $result = "Success";
                }
                
                $log_data = array();
                $log_data['account_id'] = $delicious_account['account_id'];
                $log_data['account_type'] = "delicious";
                $log_data['username'] = $delicious_account['username'];
                $log_data['post_id'] = $post_ID;
                $log_data['action_result'] = $action_result;
                $log_data['update_message'] = $update_message;
                $log_data['log_message'] = $result;
                MicroblogPoster_Poster::insert_log($log_data);
            }
        }
        
    }
    
    /**
    * Updates status on http://friendfeed.com/
    *
    * @param   string  $title Text to be posted on microblogging site
    * @param   string  $link
    * @param   array $post_content
    * @return  void
    */
    public static function update_friendfeed($title, $link, $post_content, $post_ID)
    {
	
	$curl = new MicroblogPoster_Curl();
        $friendfeed_accounts = MicroblogPoster_Poster::get_accounts('friendfeed');
        
        if(!empty($friendfeed_accounts))
        {
            foreach($friendfeed_accounts as $friendfeed_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($friendfeed_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $friendfeed_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if($friendfeed_account['message_format'])
                {
                    $friendfeed_account['message_format'] = str_ireplace('{site_url}', '', $friendfeed_account['message_format']);
                    $friendfeed_account['message_format'] = str_ireplace('{url}', '', $friendfeed_account['message_format']);
                    $friendfeed_account['message_format'] = str_ireplace('{short_url}', '', $friendfeed_account['message_format']);
                    $friendfeed_account['message_format'] = str_ireplace('{manual_excerpt}', '', $friendfeed_account['message_format']);
                    $friendfeed_account['message_format'] = str_ireplace('{excerpt}', '', $friendfeed_account['message_format']);
                    $title = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $friendfeed_account['message_format']);
                }
                $is_raw = MicroblogPoster_SupportEnc::is_enc($friendfeed_account['extra']);
                if(!$is_raw)
                {
                    $friendfeed_account['password'] = MicroblogPoster_SupportEnc::dec($friendfeed_account['password']);
                }
                $curl->set_credentials($friendfeed_account['username'],$friendfeed_account['password']);
	
                $url = "http://friendfeed-api.com/v2/entry";
                $post_args = array(
                    'body' => $title,
                    'link' => $link
                );

                $result = $curl->send_post_data($url, $post_args);
                
                $update_message = $title." - ".$link;
                
                $action_result = 2;
                $result_dec = json_decode($result, true);
                if($result_dec && isset($result_dec['id']))
                {
                    $action_result = 1;
                    $result = "Success";
                }
                
                $log_data = array();
                $log_data['account_id'] = $friendfeed_account['account_id'];
                $log_data['account_type'] = "friendfeed";
                $log_data['username'] = $friendfeed_account['username'];
                $log_data['post_id'] = $post_ID;
                $log_data['action_result'] = $action_result;
                $log_data['update_message'] = $update_message;
                $log_data['log_message'] = $result;
                MicroblogPoster_Poster::insert_log($log_data);
            }
            
        }
	
    }
    
    /**
    * Updates status on facebook
    *
    * @param string  $update Text to be posted on microblogging site
    * @param array $post_content 
    * @return void
    */
    public static function update_facebook($update, $post_content, $post_ID, $post_title, $permalink, $post_content_actual, $featured_image_src)
    {
        
        $curl = new MicroblogPoster_Curl();
        $facebook_accounts = MicroblogPoster_Poster::get_accounts('facebook');
        
        if(!empty($facebook_accounts))
        {
            foreach($facebook_accounts as $facebook_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($facebook_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $facebook_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if(!$facebook_account['extra'])
                {
                    continue;
                }
                
                if($facebook_account['message_format'])
                {
                    $update = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $facebook_account['message_format']);
                }
                $extra = json_decode($facebook_account['extra'], true);
                
                if(isset($extra['user_id']) && isset($extra['access_token']))
                {
                    
                    $post_data = array();
                    $post_data['update'] = $update;
                    $post_data['post_title'] = $post_title;
                    $post_data['permalink'] = $permalink;
                    $post_data['post_content_actual'] = $post_content_actual;
                    $post_data['featured_image_src'] = $featured_image_src;
                    
                    $acc_extra = $extra;
                    
                    if(isset($extra['target_type']) && $extra['target_type']=='page' && isset($extra['page_id']))
                    {
                        if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','update_facebook_page'))
                        {
                            $result = MicroblogPoster_Poster_Pro::update_facebook_page($curl, $acc_extra, $post_data);
                        }
                    }
                    elseif(isset($extra['target_type']) && $extra['target_type']=='group' && isset($extra['group_id']))
                    {
                        if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','update_facebook_group'))
                        {
                            $result = MicroblogPoster_Poster_Pro::update_facebook_group($curl, $acc_extra, $post_data);
                        }
                    }
                    else
                    {
                        $url = "https://graph.facebook.com/{$extra['user_id']}/feed";
                        $post_args = array(
                            'access_token' => $extra['access_token'],
                            'message' => $update
                        );

                        if(isset($extra['post_type']) && $extra['post_type'] == 'link')
                        {
                            $post_args['name'] = $post_title;
                            $post_args['link'] = $permalink;
                            $post_args['description'] = $post_content_actual;
                            $picture_url = '';
                            if(isset($extra['default_image_url']) && $extra['default_image_url'])
                            {
                                $picture_url = $extra['default_image_url'];
                            }
                            if($featured_image_src)
                            {
                                $picture_url = $featured_image_src;
                            }
                            $post_args['picture'] = $picture_url;
                        }

                        $result = $curl->send_post_data($url, $post_args);
                        
                    }
                    
                    $result_dec = json_decode($result, true);
                    
                    $action_result = 2;
                    if($result_dec && isset($result_dec['id']))
                    {
                        $action_result = 1;
                        $result = "Success";
                    }

                    $log_data = array();
                    $log_data['account_id'] = $facebook_account['account_id'];
                    $log_data['account_type'] = "facebook";
                    $log_data['username'] = $facebook_account['username'];
                    $log_data['post_id'] = $post_ID;
                    $log_data['action_result'] = $action_result;
                    $log_data['update_message'] = $update;
                    $log_data['log_message'] = $result;
                    MicroblogPoster_Poster::insert_log($log_data);
                }
                
            }
            
        }
    }
    
    /**
    * Updates status on diigo.com
    *
    * @param   string  $title Text to be posted on microblogging site
    * @param   string  $link
    * @param   string  $tags
    * @param array $post_content 
    * @return  void
    */
    public static function update_diigo($title, $link, $tags, $post_content, $post_ID)
    {
	
        $curl = new MicroblogPoster_Curl();
        $diigo_accounts = MicroblogPoster_Poster::get_accounts('diigo');
        
        if(!empty($diigo_accounts))
        {
            foreach($diigo_accounts as $diigo_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($diigo_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $diigo_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if($diigo_account['message_format'])
                {
                    $diigo_account['message_format'] = str_ireplace('{site_url}', '', $diigo_account['message_format']);
                    $diigo_account['message_format'] = str_ireplace('{url}', '', $diigo_account['message_format']);
                    $diigo_account['message_format'] = str_ireplace('{short_url}', '', $diigo_account['message_format']);
                    $descr = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $diigo_account['message_format']);
                }
                $is_raw = MicroblogPoster_SupportEnc::is_enc($diigo_account['extra']);
                if(!$is_raw)
                {
                    $diigo_account['password'] = MicroblogPoster_SupportEnc::dec($diigo_account['password']);
                }
                $extra = json_decode($diigo_account['extra'], true);
                if(is_array($extra))
                {
                    $include_tags = (isset($extra['include_tags']) && $extra['include_tags'] == 1)?true:false;
                    $api_key = $extra['api_key'];
                }
                $curl->set_credentials($diigo_account['username'], $diigo_account['password']);
                $curl->set_user_agent("Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1");

                $update_message = $descr." - ".$link;

                $url = "https://secure.diigo.com/api/v2/bookmarks";
                $post_args = array(
                    'key' => $api_key,
                    'title' => $title,
                    'desc' => $descr,
                    'url' => $link,
                    'shared' => 'yes'
                );
                if($include_tags)
                {
                    $post_args['tags'] = $tags;
                    $update_message .= " ($tags)";
                }
                $result = $curl->send_post_data($url, $post_args);
                $result_dec = json_decode($result, true);
                
                $action_result = 2;
                if($result_dec && isset($result_dec['code']) && $result_dec['code'] == 1)
                {
                    $action_result = 1;
                    $result = "Success";
                }
                else
                {
                    $result = "Please recheck your username/password and API Key.";
                }
                
                $log_data = array();
                $log_data['account_id'] = $diigo_account['account_id'];
                $log_data['account_type'] = "diigo";
                $log_data['username'] = $diigo_account['username'];
                $log_data['post_id'] = $post_ID;
                $log_data['action_result'] = $action_result;
                $log_data['update_message'] = $update_message;
                $log_data['log_message'] = $result;
                MicroblogPoster_Poster::insert_log($log_data);
            }
        }
        
    }
    
    /**
    * Updates status on linkedin
    *
    * @param string  $update Text to be posted on microblogging site
    * @param array $post_content 
    * @return void
    */
    public static function update_linkedin($update, $post_content, $post_ID, $post_title, $permalink, $post_content_actual, $featured_image_src)
    {
        
        $curl = new MicroblogPoster_Curl();
        $linkedin_accounts = MicroblogPoster_Poster::get_accounts('linkedin');
        
        if(!empty($linkedin_accounts))
        {
            foreach($linkedin_accounts as $linkedin_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($linkedin_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $linkedin_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if(!$linkedin_account['extra'])
                {
                    continue;
                }
                
                if($linkedin_account['message_format'])
                {
                    $update = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $linkedin_account['message_format']);
                }
                $extra = json_decode($linkedin_account['extra'], true);
                
                if(isset($extra['access_token']))
                {
                    
                    $post_data = array();
                    $post_data['update'] = $update;
                    $post_data['post_title'] = $post_title;
                    $post_data['permalink'] = $permalink;
                    $post_data['post_content_actual'] = $post_content_actual;
                    $post_data['featured_image_src'] = $featured_image_src;
                    
                    $acc_extra = $extra;
                    
                    if(isset($extra['target_type']) && $extra['target_type']=='group' && isset($extra['group_id']))
                    {
                        if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','update_linkedin_group'))
                        {
                            $result = MicroblogPoster_Poster_Pro::update_linkedin_group($curl, $acc_extra, $post_data);
                        }
                        
                        $action_result = 2;
                        if(!$result)
                        {
                            $action_result = 1;
                            $result = "Success";
                        }
                    }
                    elseif(isset($extra['target_type']) && $extra['target_type']=='company' && isset($extra['company_id']))
                    {
                        if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','update_linkedin_company'))
                        {
                            $result = MicroblogPoster_Poster_Pro::update_linkedin_company($curl, $acc_extra, $post_data);
                        }
                        
                        $action_result = 2;
                        if($result && stripos($result, '<update-key>')!==false && stripos($result, '</update-key>')!==false)
                        {
                            $action_result = 1;
                            $result = "Success";
                        }
                    }
                    else
                    {
                        $url = "https://api.linkedin.com/v1/people/~/shares/?oauth2_access_token={$extra['access_token']}";
                        
                        $body = new stdClass();
                        $body->comment = $update;

                        if(isset($extra['post_type']) && $extra['post_type'] == 'link')
                        {
                            $body->content = new stdClass();
                            $body->content->title = $post_title;
                            $body->content->{'submitted-url'} = $permalink;
                            $body->content->description = $post_content_actual;
                            $picture_url = '';// 180 wid, 110 hei
                            if(isset($extra['default_image_url']) && $extra['default_image_url'])
                            {
                                $picture_url = $extra['default_image_url'];
                            }
                            if($featured_image_src)
                            {
                                $picture_url = $featured_image_src;
                            }
                            $body->content->{'submitted-image-url'} = $picture_url;
                        }

                        $body->visibility = new stdClass();
                        $body->visibility->code = 'anyone';
                        $body_json = json_encode($body);

                        $curl->set_headers(array('Content-Type'=>'application/json'));
                        $result = $curl->send_post_data_json($url, $body_json);
                        
                        $action_result = 2;
                        if($result && stripos($result, '<update-key>')!==false && stripos($result, '</update-key>')!==false)
                        {
                            $action_result = 1;
                            $result = "Success";
                        }
                    }
                    
                    
                    $log_data = array();
                    $log_data['account_id'] = $linkedin_account['account_id'];
                    $log_data['account_type'] = "linkedin";
                    $log_data['username'] = $linkedin_account['username'];
                    $log_data['post_id'] = $post_ID;
                    $log_data['action_result'] = $action_result;
                    $log_data['update_message'] = $update;
                    $log_data['log_message'] = $result;
                    MicroblogPoster_Poster::insert_log($log_data);
                }
                
            }
            
        }
    }
    
    /**
    * Updates status on tumblr
    *
    * @param string  $update Text to be posted on microblogging site
    * @param array $post_content
    * @return void
    */
    public static function update_tumblr($update, $post_content, $post_ID, $post_title, $permalink, $post_content_actual)
    {   
        
        $tumblr_accounts = MicroblogPoster_Poster::get_accounts('tumblr');
        
        if(!empty($tumblr_accounts))
        {
            foreach($tumblr_accounts as $tumblr_account)
            {
                if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account'))
                {
                    $active = MicroblogPoster_Poster_Pro::filter_single_account($tumblr_account['account_id']);
                    if($active === false)
                    {
                        continue;
                    }
                    else
                    {
                        if(isset($active['message_format']) && $active['message_format'])
                        {
                            $tumblr_account['message_format'] = $active['message_format'];
                        }
                    }
                }
                
                if($tumblr_account['message_format'])
                {
                    $update = str_ireplace(MicroblogPoster_Poster::get_shortcodes(), $post_content, $tumblr_account['message_format']);
                }
                $extra = json_decode($tumblr_account['extra'], true);
                if(!$extra)
                {
                    continue;
                }
                
                $post_data = array();
                $post_data['update'] = $update;
                $post_data['post_title'] = $post_title;
                $post_data['permalink'] = $permalink;
                $post_data['post_content_actual'] = $post_content_actual;
                
                $acc_extra = $extra;
                
                if($extra['post_type'] == 'text')
                {
                    $result = MicroblogPoster_Poster::send_signed_request(
                        $tumblr_account['consumer_key'],
                        $tumblr_account['consumer_secret'],
                        $tumblr_account['access_token'],
                        $tumblr_account['access_token_secret'],
                        "http://api.tumblr.com/v2/blog/{$extra['blog_hostname']}/post",
                        array("type"=>'text',"title"=>$post_title,"body"=>$update)
                    );
                }
                elseif($extra['post_type'] == 'link')
                {
                    if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','update_tumblr_link'))
                    {
                        $result = MicroblogPoster_Poster_Pro::update_tumblr_link($tumblr_account, $acc_extra, $post_data);
                    }
                }
                
                $action_result = 2;
                $result_dec = json_decode($result, true);
                if($result_dec && isset($result_dec['meta']['msg']) && $result_dec['meta']['msg']=="Created")
                {
                    $action_result = 1;
                    $result = "Success";
                }
                
                $log_data = array();
                $log_data['account_id'] = $tumblr_account['account_id'];
                $log_data['account_type'] = "tumblr";
                $log_data['username'] = $tumblr_account['username'];
                $log_data['post_id'] = $post_ID;
                $log_data['action_result'] = $action_result;
                $log_data['update_message'] = $update;
                $log_data['log_message'] = $result;
                MicroblogPoster_Poster::insert_log($log_data);
            }
        }
        
    }
    
    /**
    * Sends OAuth signed request
    *
    * @param   string  $c_key Application consumer key
    * @param   string  $c_secret Application consumer secret
    * @param   string  $token Account access token
    * @param   string  $token_secret Account access token secret
    * @param   string  $api_url URL of the API end point
    * @param   string  $params Parameters to be passed
    * @return  void
    */
    public static function send_signed_request($c_key, $c_secret, $token, $token_secret, $api_url, $params)
    {
        $consumer = new MicroblogPosterOAuthConsumer($c_key, $c_secret);
        $access_token = new MicroblogPosterOAuthConsumer($token, $token_secret);
        
        $request = MicroblogPosterOAuthRequest::from_consumer_and_token(
                $consumer,
                $access_token,
                "POST",
                $api_url,
                $params
        );
        $hmac_method = new MicroblogPosterOAuthSignatureMethod_HMAC_SHA1();
        $request->sign_request($hmac_method, $consumer, $access_token);
        
        if(($pos=strpos($request,"?")) !== false)
        {
            $url = substr($request,0,$pos);
            $parameters = substr($request,$pos+1);
        }
        
        $curl = new MicroblogPoster_Curl();
        $result = $curl->send_post_data($url, $parameters);
        return $result;
    
    }
    
    /**
    * Get accounts from db
    *
    * @param   string  $type Type of account (=site)
    * @return  array
    */
    private static function get_accounts($type)
    {
        global  $wpdb;

        $table_accounts = $wpdb->prefix . 'microblogposter_accounts';
        
        $sql="SELECT * FROM $table_accounts WHERE type='{$type}'";
        $rows = $wpdb->get_results($sql, ARRAY_A);
        
        return $rows;
    }
    
    /**
    * Insert new log into db
    *
    * @param   array  $log_data Log message
    * @return  bool
    */
    public static function insert_log($log_data)
    {
        global  $wpdb;

        $table_logs = $wpdb->prefix . 'microblogposter_logs';
        
        $wpdb->escape_by_ref($log_data['log_message']);
        $wpdb->escape_by_ref($log_data['update_message']);
        $wpdb->escape_by_ref($log_data['username']);
        
        $sql="INSERT INTO $table_logs (account_id, account_type, username, post_id, action_result, update_message, log_message) 
            VALUES ('{$log_data['account_id']}','{$log_data['account_type']}','{$log_data['username']}','{$log_data['post_id']}','{$log_data['action_result']}','{$log_data['update_message']}','{$log_data['log_message']}')";
        $wpdb->query($sql);
        
        return true;
    }
    
    /**
    * 
    *
    * @param string $class_name
    * @param string $method_name
    * @return  bool
    */
    public static function is_method_callable($class_name, $method_name)
    {
        if( class_exists($class_name, false) && method_exists($class_name, $method_name) )
        {
            return true;
        }
        
        return false;
    }
    
    /**
    * Keeps logs table under 2000 rows
    *
    * @return  void
    */
    private static function maintain_logs()
    {
        global  $wpdb;

        $table_logs = $wpdb->prefix . 'microblogposter_logs';
        
        $sql="SELECT log_id FROM $table_logs ORDER BY log_id DESC LIMIT 2000";
        $rows = $wpdb->get_results($sql);
        if(is_array($rows) && count($rows)==2000)
        {
            $log_ids = "(";
            foreach($rows as $row)
            {
                $log_ids .= $row->log_id.",";
            }
            $log_ids = rtrim($log_ids, ",");
            $log_ids .= ")";
            
            $sql="DELETE FROM {$table_logs} WHERE log_id NOT IN {$log_ids}";
            $wpdb->query($sql);
        }
        
        return true;
    }
    
    /**
    * 
    * get_shortcodes
    * 
    * @return  array
    */
    private static function get_shortcodes()
    {
        return array('{site_url}', 
                    '{title}', 
                    '{url}', 
                    '{short_url}', 
                    '{manual_excerpt}',
                    '{excerpt}', 
                    '{author}', 
                    '{content_first_words}'
        );
    }
    
    /**
    * 
    * @param string $content
    * @param int $length
    * @param string $ending_char
    * @return string
    */
    private static function clean_up_and_shorten_content($content, $length, $ending_char)
    {
        $content = strip_shortcodes($content);
        $content = strip_tags($content);
        $content = preg_replace("|(\r\n)+|", " ", $content);
        $content = preg_replace("|(\t)+|", "", $content);
        $content = preg_replace("|\&nbsp\;|", "", $content);
        $content = substr($content, 0, $length);
        
        if(strlen($content) == $length)
        {
            $content = substr($content, 0, strrpos($content, $ending_char));
        }
        return $content;
    }
    
    /**
    * 
    * @param string $content
    * @param int $length
    * @param string $ending_char
    * @return string
    */
    private static function shorten_content($content, $length, $ending_char)
    {
        $content = strip_shortcodes($content);
        $content = strip_tags($content);
        $content = substr($content, 0, $length);
        
        if(strlen($content) == $length)
        {
            $content = substr($content, 0, strrpos($content, $ending_char)+1);
        }
        return $content;
    }
    
    /**
    * 
    * @param string $title
    * @param int $length
    * @param string $ending_char
    * @return string
    */
    private static function shorten_title($title, $length, $ending_char)
    {
        $title = substr($title, 0, $length);
        
        if(strlen($title) == $length)
        {
            $title = substr($title, 0, strrpos($title, $ending_char));
            $title .= "...";
        }
        return $title;
    }
    
    /**
    * 
    * @param string $content
    * @return string
    */
    private static function clean_up_content($content)
    {
        $content = strip_shortcodes($content);
        $content = strip_tags($content);
        $content = preg_replace("|(\r\n)+|", " ", $content);
        $content = preg_replace("|(\t)+|", "", $content);
        $content = preg_replace("|\&nbsp\;|", "", $content);
        
        return $content;
    }
    
    /**
    * 
    * @param string $content
    * @return string
    */
    private static function strip_shortcodes_and_tags($content)
    {
        $content = strip_shortcodes($content);
        $content = strip_tags($content);
        
        return $content;
    }
    
}

class MicroblogPoster_SupportEnc
{
    /**
    * Encodes the given string
    * 
    * @param string $str
    * @return  string
    */
    public static function enc($str)
    {
        $str = 'microblogposter_'.$str;
        $str = base64_encode($str);
        return $str;
    }
    
    /**
    * Decodes the given string
    * 
    * @param string $str
    * @return  string
    */
    public static function dec($str)
    {
        $str = base64_decode($str);
        $str = str_replace('microblogposter_', '', $str);
        return $str;
    }
    
    /**
    * Checks if enc
    * 
    * @param string $e
    * @return  bool
    */
    public static function is_enc($e)
    {
        $is_raw = true;
        $extra = json_decode($e, true);
        if(is_array($extra))
        {
            $is_raw = (isset($extra['penc']) && $extra['penc'] == 1)?false:true;
        }
        return $is_raw;
    }
}

register_activation_hook(__FILE__, array('MicroblogPoster_Poster', 'activate'));

add_action('publish_post', array('MicroblogPoster_Poster', 'update'));

$page_mode_name = "microblogposter_page_mode";
$page_mode_value = get_option($page_mode_name, "");
if($page_mode_value)
{
    add_action('publish_page', array('MicroblogPoster_Poster', 'update'));
}

$enabled_custom_types_name = "microblogposter_enabled_custom_types";
$enabled_custom_types_value = get_option($enabled_custom_types_name, "");
$enabled_custom_types = json_decode($enabled_custom_types_value, true);
if(is_array($enabled_custom_types) && !empty($enabled_custom_types))
{
    foreach($enabled_custom_types as $custom_type)
    {
        add_action('publish_' . $custom_type, array('MicroblogPoster_Poster', 'update'));
    }
}

//Displays a checkbox that allows users to disable Microblog Poster on a per post basis.
function microblogposter_meta()
{   
    $default_behavior_name = "microblogposter_default_behavior";
    $default_behavior_value = get_option($default_behavior_name, "");
    $default_behavior_update_name = "microblogposter_default_behavior_update";
    $default_behavior_update_value = get_option($default_behavior_update_name, "");
    $pro_control_dash_mode_name = "microblogposter_plg_control_dash_mode";
    $pro_control_dash_mode_value = get_option($pro_control_dash_mode_name, "");
    
    $screen = get_current_screen();
    if($screen->action != 'add')
    {
        $default_behavior_value = $default_behavior_update_value;
    }
    ?>
    <input type="checkbox" id="microblogposteroff" name="microblogposteroff" <?php if($default_behavior_value) echo 'checked="checked"';?> /> 
    <label for="microblogposteroff">Disable Microblog Poster this time?</label>
    
    <?php
    if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','show_control_dashboard') && !$pro_control_dash_mode_value)
    {
        MicroblogPoster_Poster_Pro::show_control_dashboard();
    }
    elseif(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','show_control_dashboard') && $pro_control_dash_mode_value=='1')
    {
        echo "<br />The Control Dashboard part for checking/unchecking specific accounts is disabled in plugin's settings. MicroblogPoster will cross-post to all your social accounts.";
    }
    else
    {
        ?>
        <style>
            #mbp_upgrade_notice_div_microblogposter
            {
                margin-top: 10px;
            }
        </style>
        
        <script>
            function mbp_show_upgrade_notice_microblogposter()
            {
                if(jQuery('#mbp_upgrade_notice_div_microblogposter').is(':visible'))
                {
                    jQuery('#mbp_upgrade_notice_div_microblogposter').hide();
                    jQuery('#mbp_upgrade_notice_lnk_microblogposter').html('Show complete Control Dashboard');
                }
                else
                {
                    jQuery('#mbp_upgrade_notice_div_microblogposter').show();
                    jQuery('#mbp_upgrade_notice_lnk_microblogposter').html('Hide complete Control Dashboard');
                }    
                
            }
        </script>
        &nbsp;<a href="#" id="mbp_upgrade_notice_lnk_microblogposter" onclick="mbp_show_upgrade_notice_microblogposter();return false;" >Show complete Control Dashboard</a>
        <div id="mbp_upgrade_notice_div_microblogposter" style="display:none;">Available with the Pro Add-on. <a href="http://efficientscripts.com/microblogposterpro" target="_blank">Upgrade Now</a></div>
        <?php
    }
    
}

//Displays a checkbox that allows users to disable Microblog Poster on a per page basis.
function microblogposter_pmeta()
{
    $default_pbehavior_name = "microblogposter_default_pbehavior";
    $default_pbehavior_value = get_option($default_pbehavior_name, "");
    $default_pbehavior_update_name = "microblogposter_default_pbehavior_update";
    $default_pbehavior_update_value = get_option($default_pbehavior_update_name, "");
    $pro_control_dash_mode_name = "microblogposter_plg_control_dash_mode";
    $pro_control_dash_mode_value = get_option($pro_control_dash_mode_name, "");
    
    $screen = get_current_screen();
    if($screen->action != 'add')
    {
        $default_pbehavior_value = $default_pbehavior_update_value;
    }
    ?>
    <input type="checkbox" id="microblogposteroff" name="microblogposteroff" <?php if($default_pbehavior_value) echo 'checked="checked"';?> /> 
    <label for="microblogposteroff">Disable Microblog Poster this time?</label>
    <?php
    if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','show_control_dashboard')  && !$pro_control_dash_mode_value)
    {
        MicroblogPoster_Poster_Pro::show_control_dashboard();
    }
    elseif(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','show_control_dashboard') && $pro_control_dash_mode_value=='1')
    {
        echo "<br />The Control Dashboard part for checking/unchecking specific accounts is disabled in plugin's settings. MicroblogPoster will cross-post to all your social accounts.";
    }
    else
    {
        ?>
        <style>
            #mbp_upgrade_notice_div_microblogposter
            {
                margin-top: 10px;
            }
        </style>
        
        <script>
            function mbp_show_upgrade_notice_microblogposter()
            {
                if(jQuery('#mbp_upgrade_notice_div_microblogposter').is(':visible'))
                {
                    jQuery('#mbp_upgrade_notice_div_microblogposter').hide();
                    jQuery('#mbp_upgrade_notice_lnk_microblogposter').html('Show complete Control Dashboard');
                }
                else
                {
                    jQuery('#mbp_upgrade_notice_div_microblogposter').show();
                    jQuery('#mbp_upgrade_notice_lnk_microblogposter').html('Hide complete Control Dashboard');
                }    
                
            }
        </script>
        &nbsp;<a href="#" id="mbp_upgrade_notice_lnk_microblogposter" onclick="mbp_show_upgrade_notice_microblogposter();return false;" >Show complete Control Dashboard</a>
        <div id="mbp_upgrade_notice_div_microblogposter" style="display:none;">Available with the Pro Add-on. <a href="http://efficientscripts.com/microblogposterpro" target="_blank">Upgrade Now</a></div>
        <?php
    }
}

//Displays a checkbox that allows users to disable Microblog Poster on a per custom type basis.
function microblogposter_custom_meta($post, $metabox)
{   
    $default_pbehavior_value = false;
    $pro_control_dash_mode_name = "microblogposter_plg_control_dash_mode";
    $pro_control_dash_mode_value = get_option($pro_control_dash_mode_name, "");
    
    $enabled_custom_updates_name = "microblogposter_enabled_custom_updates";
    $enabled_custom_updates_value = get_option($enabled_custom_updates_name, "");
    $enabled_custom_updates = json_decode($enabled_custom_updates_value, true);
    
    $screen = get_current_screen();
    if($screen->action != 'add')
    {
        if(is_array($enabled_custom_updates) && !empty($enabled_custom_updates))
        {
            if(in_array($metabox['args']['type'], $enabled_custom_updates))
            {
                $default_pbehavior_value = true;
            }
        }
    }
    ?>
    <input type="checkbox" id="microblogposteroff" name="microblogposteroff" <?php if($default_pbehavior_value) echo 'checked="checked"';?> /> 
    <label for="microblogposteroff">Disable Microblog Poster this time?</label>
    <?php
    if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','show_control_dashboard')  && !$pro_control_dash_mode_value)
    {
        MicroblogPoster_Poster_Pro::show_control_dashboard();
    }
    elseif(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','show_control_dashboard') && $pro_control_dash_mode_value=='1')
    {
        echo "<br />The Control Dashboard part for checking/unchecking specific accounts is disabled in plugin's settings. MicroblogPoster will cross-post to all your social accounts.";
    }
    else
    {
        ?>
        <style>
            #mbp_upgrade_notice_div_microblogposter
            {
                margin-top: 10px;
            }
        </style>
        
        <script>
            function mbp_show_upgrade_notice_microblogposter()
            {
                if(jQuery('#mbp_upgrade_notice_div_microblogposter').is(':visible'))
                {
                    jQuery('#mbp_upgrade_notice_div_microblogposter').hide();
                    jQuery('#mbp_upgrade_notice_lnk_microblogposter').html('Show complete Control Dashboard');
                }
                else
                {
                    jQuery('#mbp_upgrade_notice_div_microblogposter').show();
                    jQuery('#mbp_upgrade_notice_lnk_microblogposter').html('Hide complete Control Dashboard');
                }    
                
            }
        </script>
        &nbsp;<a href="#" id="mbp_upgrade_notice_lnk_microblogposter" onclick="mbp_show_upgrade_notice_microblogposter();return false;" >Show complete Control Dashboard</a>
        <div id="mbp_upgrade_notice_div_microblogposter" style="display:none;">Available with the Pro Add-on. <a href="http://efficientscripts.com/microblogposterpro" target="_blank">Upgrade Now</a></div>
        <?php
    }
}

//Add the checkbox defined above to post/page/custom type edit screen.
function microblogposter_meta_box()
{
    add_meta_box('microblogposter_domain','MicroblogPoster','microblogposter_meta','post','advanced','high');
    
    $page_mode_name = "microblogposter_page_mode";
    $page_mode_value = get_option($page_mode_name, "");
    if($page_mode_value)
    {
        add_meta_box('microblogposter_domain','MicroblogPoster','microblogposter_pmeta','page','advanced','high');
    }
    
    $enabled_custom_types_name = "microblogposter_enabled_custom_types";
    $enabled_custom_types_value = get_option($enabled_custom_types_name, "");
    $enabled_custom_types = json_decode($enabled_custom_types_value, true);
    if(is_array($enabled_custom_types) && !empty($enabled_custom_types))
    {
        foreach($enabled_custom_types as $custom_type)
        {
            add_meta_box('microblogposter_domain','MicroblogPoster','microblogposter_custom_meta',$custom_type,'advanced','high',array('type'=>$custom_type));
        }
    }
}
add_action('admin_menu', 'microblogposter_meta_box');

// Add settings link on plugin page
function microblogposter_plugin_settings_link($links) 
{ 
    $settings_link = '<a href="options-general.php?page=microblogposter.php">Settings</a>'; //get_admin_url()
    array_unshift($links, $settings_link); 
    return $links; 
}

$microblogposter_plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_{$microblogposter_plugin}", 'microblogposter_plugin_settings_link');

//Options

require_once "microblogposter_options.php";


?>