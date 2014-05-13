<?php
add_action('init', 'of_options');
if (!function_exists('of_options')) {

    function of_options() {
        // VARIABLES
        $themename = 'Cloriato Pro Responsive Theme';
        $shortname = "of";
        // Populate OptionsFramework option in array for use in theme
        global $of_options;
        $of_options = get_option('of_options');
        // Multicheck Defaults   
        $file_rename = array("on" => "On", "off" => "Off");
        $back_array = array("image" => "Image", "color" => "Color");
        //Stylesheet Reader
        $alt_stylesheets = array("black" => "black", "cyan" => "cyan", "green" => "green", "pink" => "pink", "red" => "red", "yellow" => "yellow", "" => "blue");
        //Option for on off
        $cols_two = array("on" => "On", "off" => "Off");
        $cols_three = array("on" => "On", "off" => "Off");
        // Test data
        $test_array = array("one" => "One", "two" => "Two", "three" => "Three", "four" => "Four", "five" => "Five");
        // Multicheck Array
        $multicheck_array = array("one" => "OK");
        // Multicheck Defaults
        $multicheck_defaults = array("one" => "1", "five" => "1");
        // Background Defaults
        $background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat', 'position' => 'top center', 'attachment' => 'scroll');
        // Pull all the categories into an array
        $options_categories = array();
        $options_categories_obj = get_categories();
        foreach ($options_categories_obj as $category) {
            $options_categories[$category->cat_ID] = $category->cat_name;
        }
        // Pull all the pages into an array
        $options_pages = array();
        $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $options_pages[''] = 'Select a page:';
        foreach ($options_pages_obj as $page) {
            $options_pages[$page->ID] = $page->post_title;
        }
        // If using image radio buttons, define a directory path
        $imagepath = get_stylesheet_directory_uri() . '/images/';
        $options = array();
        $options[] = array("name" => "General Settings",
            "type" => "heading");
        $options[] = array("name" => "Custom Logo",
            "desc" => "Choose your own logo. Optimal Size: 160px Wide by 30px Height",
            "id" => "inkthemes_logo",
            "type" => "upload");
        //Background Image
        $options[] = array("name" => "Body Background Image",
            "desc" => "Choose your own background image,pattern.",
            "id" => "inkthemes_bodybg",
            "type" => "upload");
        $options[] = array("name" => "Custom Favicon",
            "desc" => "Specify a 16px x 16px image that will represent your website's favicon.",
            "id" => "inkthemes_favicon",
            "type" => "upload");
        $options[] = array("name" => "Tracking Code",
            "desc" => "Paste your Google Analytics (or other) tracking code here.",
            "id" => "inkthemes_analytics",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Front Page On/Off",
            "desc" => "Check on for enabling front page or check off for enabling blog page in front page",
            "id" => "re_nm",
            "std" => "on",
            "type" => "radio",
            "options" => $file_rename);
//------------------------------------------------------------------//
//-----------This code is used for creating slider settings---------//							
//------------------------------------------------------------------//						
        $options[] = array("name" => "Slider Settings",
            "type" => "heading");
        //First slider
        $options[] = array("name" => "Slide 1 Image",
            "desc" => "Choose Image for your Slide1. Optimal Size: 950px x 350px",
            "id" => "inkthemes_slideimage1",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Slide 1 Caption Heading",
            "desc" => "Enter the Heading for Slide1 Caption",
            "id" => "inkthemes_slideheading1",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 1 Caption Description",
            "desc" => "Enter description for Slide1 Caption",
            "id" => "inkthemes_slidedescription1",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 1 Link",
            "desc" => "Enter the Link URL for Slide 1",
            "id" => "inkthemes_slidelink1",
            "std" => "",
            "type" => "text");
        //Second Slider
        $options[] = array("name" => "Slide 2 Image",
            "desc" => "Choose Image for your Slider. Optimal Size: 950px x 350px",
            "id" => "inkthemes_slideimage2",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Slide 2 Caption Heading",
            "desc" => "Enter the Heading for Slide 2 caption",
            "id" => "inkthemes_slideheading2",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 2 Caption Description",
            "desc" => "Enter description for Slide 2 caption",
            "id" => "inkthemes_slidedescription2",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 2 Link",
            "desc" => "Enter the Link URL for Slide 2",
            "id" => "inkthemes_slidelink2",
            "std" => "",
            "type" => "text");
        //Third Slider
        $options[] = array("name" => "Slide 3 Image",
            "desc" => "Choose Image for your Slider. Optimal Size: 950px x 350px",
            "id" => "inkthemes_slideimage3",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Slide 3 Caption Heading",
            "desc" => "Enter the Heading for Slide3 Caption",
            "id" => "inkthemes_slideheading3",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 3 Caption Description",
            "desc" => "Enter description for Slide3",
            "id" => "inkthemes_slidedescription3",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 3 Link",
            "desc" => "Enter the Link URL for Slide 3",
            "id" => "inkthemes_slidelink3",
            "std" => "",
            "type" => "text");
        //Fourth Slider
        $options[] = array("name" => "Slide 4 Image",
            "desc" => "Choose Image for your Slider. Optimal Size: 950px x 350px",
            "id" => "inkthemes_slideimage4",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Slide 4 Caption Heading",
            "desc" => "Enter the Heading for Slide4 Caption",
            "id" => "inkthemes_slideheading4",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 4 Caption Description",
            "desc" => "Enter description for Slide4",
            "id" => "inkthemes_slidedescription4",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 4 Link",
            "desc" => "Enter the Link URL for Slide 4",
            "id" => "inkthemes_slidelink4",
            "std" => "",
            "type" => "text");
			
			 //Fifth Slider
        $options[] = array("name" => "Slide 5 Image",
            "desc" => "Choose Image for your Slider. Optimal Size: 950px x 350px",
            "id" => "inkthemes_slideimage5",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Slide 5 Caption Heading",
            "desc" => "Enter the Heading for Slide5 Caption",
            "id" => "inkthemes_slideheading5",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 5 Caption Description",
            "desc" => "Enter description for Slide5",
            "id" => "inkthemes_slidedescription5",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 5 Link",
            "desc" => "Enter the Link URL for Slide 5",
            "id" => "inkthemes_slidelink5",
            "std" => "",
            "type" => "text");
			
		 //Six Slider
        $options[] = array("name" => "Slide 6 Image",
            "desc" => "Choose Image for your Slider. Optimal Size: 950px x 350px",
            "id" => "inkthemes_slideimage6",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Slide 6 Caption Heading",
            "desc" => "Enter the Heading for Slide6 Caption",
            "id" => "inkthemes_slideheading6",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 6 Caption Description",
            "desc" => "Enter description for Slide6",
            "id" => "inkthemes_slidedescription6",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Slide 6 Link",
            "desc" => "Enter the Link URL for Slide 6",
            "id" => "inkthemes_slidelink6",
            "std" => "",
            "type" => "text");	
//------------------------------------------------------------------//
//---------This code is used for creating homepage settings---------//							
//------------------------------------------------------------------//
        $options[] = array("name" => "Home Page Settings",
            "type" => "heading");
        //Homepage Two columns on/off
        $options[] = array("name" => "Homepage Two Columns On/Off",
            "desc" => "If you want to enable the homepage two columns, check the on button else if you want to disable two columns check off button and press save button.",
            "id" => "two_cols",
            "std" => "on",
            "type" => "radio",
            "options" => $cols_two);
        //Homepage Three columns on/off
        $options[] = array("name" => "Homepage Three Columns On/Off",
            "desc" => "If you want to enable the homepage three columns, check the on button else if you want to disable three columns check off button and press save button.",
            "id" => "three_cols",
            "std" => "on",
            "type" => "radio",
            "options" => $cols_three);
        $options[] = array("name" => "Home Page Heading",
            "desc" => "Enter your heading text for home page",
            "id" => "inkthemes_mainheading",
            "std" => "",
            "type" => "textarea");
        //***Code for homepage main heading description***//
        $options[] = array("name" => "Homepage Heading Description",
            "desc" => "Enter heading descriptions",
            "id" => "inkthemes_heading_desc",
            "std" => "",
            "type" => "textarea");
//------------------------------------------------------------------//
//---------Homepage featured two columns---------//							
//------------------------------------------------------------------//
        $options[] = array("name" => "Home Page 2 Cols",
            "type" => "heading");
        //**Column left heading
        $options[] = array("name" => "Left Column Heading",
            "desc" => "Enter heading for column left",
            "id" => "inkthemes_col_left_heading",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Column Left Image",
            "desc" => "Choose your image for column left",
            "id" => "inkthemes_col_left_image",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Left Column Content",
            "desc" => "Enter text description for column left. You can put html tags, embed code in this area.",
            "id" => "inkthemes_col_left_desc",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Left Column ReadMore Link",
            "desc" => "Enter url for column left redirect link",
            "id" => "inkthemes_col_left_readmore",
            "std" => "",
            "type" => "text");
        //**Column right heading
        $options[] = array("name" => "Right Column Heading",
            "desc" => "Enter heading for column right",
            "id" => "inkthemes_col_right_heading",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Right Column Content",
            "desc" => "Enter text description for column right. You can put html tags, embed code in this area.",
            "id" => "inkthemes_col_right_desc",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Right Column ReadMore Link",
            "desc" => "Enter url for column right redirect link",
            "id" => "inkthemes_col_right_readmore",
            "std" => "",
            "type" => "text");
        //Code for homepage three columns
        $options[] = array("name" => "Home Page 3 Cols",
            "type" => "heading");
        //**Featured Content  
        $options[] = array("name" => "First Column Heading",
            "desc" => "Enter your heading line for first Feature",
            "id" => "inkthemes_headline1",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "First Column Image",
            "desc" => "Choose image for your first Feature. Optimal size 285px x 150px",
            "id" => "inkthemes_wimg1",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "First Column Content",
            "desc" => "Enter your Feature content for first column",
            "id" => "inkthemes_feature1",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "First Column Link",
            "desc" => "Enter your link for first Feature",
            "id" => "inkthemes_link1",
            "std" => "",
            "type" => "text");
//***Code for second column***//
        $options[] = array("name" => "Second Column Heading",
            "desc" => "Enter your heading line for second Feature",
            "id" => "inkthemes_headline2",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Second Column Image",
            "desc" => "Choose image for your second Feature. Optimal size 285px x 150px",
            "id" => "inkthemes_fimg2",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Second Column Content",
            "desc" => "Enter your Feature content for column second",
            "id" => "inkthemes_feature2",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Second Column Link",
            "desc" => "Enter your link for column second Feature",
            "id" => "inkthemes_link2",
            "std" => "",
            "type" => "text");
//***Code for third column***//	
        $options[] = array("name" => "Third Column Heading",
            "desc" => "Enter your heading line for third column Feature",
            "id" => "inkthemes_headline3",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Third Column Image",
            "desc" => "Choose image for your column thrid Feature. Optimal size 285px x 150px",
            "id" => "inkthemes_fimg3",
            "std" => "",
            "type" => "upload");
        $options[] = array("name" => "Third Column Content",
            "desc" => "Enter your content for third column Feature",
            "id" => "inkthemes_feature3",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Third Feature Link",
            "desc" => "Enter your link for column third widget",
            "id" => "inkthemes_link3",
            "std" => "",
            "type" => "text");
//****=============================================================================****//
//****-----------This code is used for creating color styleshteet options----------****//							
//****=============================================================================****//				
        $options[] = array("name" => "Styling Options",
            "type" => "heading");
        $options[] = array("name" => "Theme Color Options",
            "desc" => "Select your themes alternative color scheme.",
            "id" => "inkthemes_altstylesheet",
            "std" => "black",
            "type" => "select",
            "options" => $alt_stylesheets);

        $options[] = array("name" => "Custom CSS",
            "desc" => "Quickly add some CSS to your theme by adding it to this block.",
            "id" => "inkthemes_customcss",
            "std" => "",
            "type" => "textarea");
//------------------------------------------------------------------//
//-------------This code is used for creating social logos----------//							
//------------------------------------------------------------------//
        $options[] = array("name" => "Social Icons",
            "type" => "heading");
        $options[] = array("name" => "Facebook URL",
            "desc" => "Enter your Facebook URL if you have one.",
            "id" => "inkthemes_facebook",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "Stumbleupon URL",
            "desc" => "Enter your stumbleupon URL if you have one.",
            "id" => "inkthemes_upon",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "Twitter URL",
            "desc" => "Enter your Twitter URL if you have one.",
            "id" => "inkthemes_twitter",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "RSS Feed URL",
            "desc" => "Enter your RSS Feed URL if you have one.",
            "id" => "inkthemes_rss",
            "std" => "",
            "type" => "text");
//------------------------------------------------------------------//
//-------------This code is used for creating SEO description-------//							
//------------------------------------------------------------------//					
        $options[] = array("name" => "SEO Options",
            "type" => "heading");
        $options[] = array("name" => "Meta Keywords (comma separated)",
            "desc" => "Meta keywords provide search engines with additional information about topics that appear on your site. This only applies to your home page. Keyword Limit Maximum 8",
            "id" => "inkthemes_keyword",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Meta Description",
            "desc" => "You should use meta descriptions to provide search engines with additional information about topics that appear on your site. This only applies to your home page.Optimal Length for Search Engines, Roughly 155 Characters",
            "id" => "inkthemes_description",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Meta Author Name",
            "desc" => "You should write the full name of the author here. This only applies to your home page.",
            "id" => "inkthemes_author",
            "std" => "",
            "type" => "textarea");
        update_option('of_template', $options);
        update_option('of_themename', $themename);
        update_option('of_shortname', $shortname);
    }

}
?>
