<?php
if(!class_exists('pw_pro_slider_layout'))
{
	class pw_pro_slider_layout
	{
		public  $rand_id , $main_slider_type , $heading_main_color , $adv_heading_active_color   , $slider_border , $slider_back_color , $slider_title_font , $slider_meta_font , $slider_excerpt_font, $slider_button_font , $overlay_back ,$overlay_no_rgb, $height_slider , $slider_model ,  $custom_class ;
		function __construct()
		{
			add_action('vc_before_init',array($this,'pw_pro_slider_layout_init'));
			add_shortcode('pw_VC_pro_slider_layout_shortcode',array($this,'pw_pro_slider_layout_shortcode'));
		}	
		
		
		public function get_category_tag( $id = 0, $taxonomy, $before = '', $sep = '', $after = '', $count='all', $exclude = array() ){
		$terms = get_the_terms( $id, $taxonomy );

		if ( is_wp_error( $terms ) )
			return $terms;
	
		if ( empty( $terms ) )
			return false;
	
		$counter=0;
		foreach ( $terms as $term ) {
			if($counter<$count || $count=='all'){	
				
				if(!in_array($term->term_id,$exclude)) {
					$link = get_term_link( $term, $taxonomy );
					if ( is_wp_error( $link ) )
						return $link;
					$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
				}
				$counter++;
			}
		}
	
		$term_links = apply_filters( "term_links-$taxonomy", $term_links );
	
		return $before . join( $sep, $term_links ) . $after;
		}
		
		
		////////////excerpt
		public function excerpt($text,$excerpt_length,$content_type='excerpt') {
			global $post;
			if(trim($excerpt_length)=='') $excerpt_length=10;
			$limit=$excerpt_length;
			if($content_type=='excerpt')	
			{
				$excerpt = explode(' ', $text, $limit);
				if (count($excerpt)>=$limit) {
					array_pop($excerpt);
					$excerpt = implode(" ",$excerpt).'...';
				} else {
					$excerpt = implode(" ",$excerpt);
				}	
				$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
				return $excerpt;
			}else{
				$content = explode(' ', $text, $limit);
				if (count($content)>=$limit) {
					array_pop($content);
					$content = implode(" ",$content).'...';
				} else {
					$content = implode(" ",$content);
				}	
				//REMOVE SHORTCODE
				//$content = preg_replace('/\[.+\]/','', $content);
				
				$content = apply_filters('the_content', $content); 
				$content = str_replace(']]>', ']]&gt;', $content);
				return $content;
			}
		}

		function frontend_embed_pro_slider_layout($s_type, $rtl , $head_type , $tooltip)
		{
				
				/*Styles*/
				if ($s_type=='pl-owl'){
					wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_owlcarousel');
					wp_enqueue_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_owlcarousel');
				}
				if ($s_type=='pl-smoothslides'){
					wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_smoothslides');
					wp_enqueue_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_smoothslides');
				}
				if ($s_type=='pl-swiper'){
					wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_swiper');
					wp_enqueue_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_swiper');
				}
				
				if ($tooltip=='true'){
					wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tipsy');
					wp_enqueue_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tipsy');
				}
				if ($rtl=='pl-slider-rtl'){
					wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_slider_carousel_rtl');
				}
				
				if ($head_type=='adv_heading'){
					wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_slider_tab');
				}
				
				if ($head_type=='adv_heading'){
					wp_enqueue_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tab');
					wp_enqueue_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tab_modernizer');
				}
				
		}
		
		function pw_pro_slider_layout_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("PW Pro Slider",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
					"description" => __("Slider Post Layout",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
					"base" => "pw_VC_pro_slider_layout_shortcode",
					"class" => "",
					"controls" => "full",
					"icon" => plugin_dir_url_pw_pro_slider_carousel .'/img/slider-pro.jpg',
					"category" => __('PW Pro Post Layout', PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
					"params" => array(
									///////////////////////data base setting
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Heading Type",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "heading_type",
										"value" => array(
											__("Manual" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"manual_heading",
											__("Simple" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"simple_heading",
											__("Advanced (Tabed)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"adv_heading",											
											),
										"description" => "Choose type of heading"
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Heading Based",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "heading_base",
										"value" => array(
											__("Category" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"category_base",
											__("Tag" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"tag_base",
											__("Taxonomy" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"tax_base",											
											),
										"description" => "Choose tab based on",
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'simple_heading','adv_heading' )
										)
									),
									array(
										'type' => 'loop',
										'heading' => __( 'Your Query', PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN   ),
										'param_name' => 'pw_query',
										'settings' => array(
										  'size' => array( 'hidden' => false, 'value' => 10 ),
										  'order_by' => array( 'value' => 'date' ),
										),
										'description' => __( 'Create WordPress loop, to populate content from your site.', PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN   )
									),
									
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show Heading",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "show_heading",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes"										
										),
										"description" => "",
									),
									
									/*IF heading type is manual & simple_heaiding*/
									array(
										"type" => "pw_image_radio",
										
										"class" => "",
										"heading" => __("Heading Layout"),
										"param_name" => "heading_layout",
										"value" =>array(
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/heading-l1.png'."' />"=>"pl-header-layout1",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/heading-l2.png'."' />"=>"pl-header-layout2",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/heading-l3.png'."' />"=>"pl-header-layout3",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/heading-l4.png'."' />"=>"pl-header-layout4"
									),
										"description" => "Choose Layout of heading",
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'manual_heading','simple_heading' )
										)
									),
									
									array(
										"type" => "colorpicker",
										"class" => "",
										"heading" => __("Header Main Color",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "heading_main_color",
										"value" => '',
										"description" => __("Leave blank to ignore",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'manual_heading','simple_heading' )
										)
									),
									array(
										"type" => "textfield",
										"class" => "",
										"heading" => __("Heading Text",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "heading_text",
										"value" => "",
										"description" => "Text of heading",
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'manual_heading','simple_heading' )
										)
									),
									
									/*IF heading type is simple_heaiding*/
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show Sub Heading",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "show_sub_heading",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes"										
										),
										"description" => "",
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'simple_heading' )
										)
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show 'View All' Button",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "show_view_btn",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes"										
										),
										"description" => "",
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'simple_heading' )
										)
									),
									
									array(
										"type" => "textfield",
										"class" => "",
										"heading" => __("View All Button Text",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "view_text",
										"value" => "",
										"description" => "Text of view all button",
										'dependency' => array(
											'element' => 'show_view_btn',
											'value' => array( 'yes' )
										)
									),
									
									array(
										"type" => "pw_image_radio",
										
										"class" => "",
										"heading" => __("Tab Heading Layout"),
										"param_name" => "tab_heading_layout",
										"value" =>array(
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/tab-l1.png'."' />"=>"pl-tabs-style-bar",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/tab-l2.png'."' />"=>"pl-tabs-style-line",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/tab-l3.png'."' />"=>"pl-tabs-style-topline",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/tab-l4.png'."' />"=>"pl-tabs-style-linebox",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/tab-l5.png'."' />"=>"pl-tabs-style-flip"
									),
										"description" => "Choose Layout of tab heading",
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'adv_heading' )
										)
									),
									
									array(
										"type" => "colorpicker",
										"class" => "",
										"heading" => __("Tab Active Color",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "adv_heading_active_color",
										"value" => '',
										"description" => __("Leave blank to ignore",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										'dependency' => array(
											'element' => 'heading_type',
											'value' => array( 'adv_heading' )
										)
									),
									
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("RTL",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "rtl_layout",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-rtl"										
										),
										"description" => ""
									),
									
									
									/**********************************
									***********Slider Setting**********
									**********************************/
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Slider Type"),
										"param_name" => "slider_type",
										"value" =>array(
											__("OWL Slider" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-owl",
											__("Swiper Slider" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-swiper",
											__("Kenburn Slider" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-smoothslides"
											),
										"description" => __("Choose Slider Type.<br /> note: Kenburn Slider not work in advanced heading type", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
										"group" => "Slider Setting"
									),
									
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Slider Mode"),
										"param_name" => "slider_mode",
										"value" =>array(
											__("Simple Slider (one slide)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-simple-slider",
											__("Multi Slider (multi item in on slide)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-multi-slider",
											),
										"description" => __("Choose Slider Mode. Multi slider just work in OWL and swiper sliders", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
										"group" => "Slider Setting",
										'dependency' => array(
											'element' => 'slider_type',
											'value' => array( 'pl-owl','pl-swiper','pl-smoothslides' )
										)
									),
									array(
										"type" => "pw_image_radio",
										"class" => "",
										"heading" => __("Simple Slider Layout"),
										"param_name" => "slider_layout",
										"value" =>array(
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/simple-layout1.jpg'."' />"=>"pl-slider-layout1",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/simple-layout2.jpg'."' />"=>"pl-slider-layout2",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/simple-layout3.jpg'."' />"=>"pl-slider-layout3",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/simple-layout4.jpg'."' />"=>"pl-slider-layout4",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/simple-layout5.jpg'."' />"=>"pl-slider-layout5",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/simple-layout6.jpg'."' />"=>"pl-slider-layout6",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/simple-layout7.jpg'."' />"=>"pl-slider-layout7"),
										"description" => "Choose Slider Layouts",
										'group' => "Slider Setting",
										'dependency' => array(
											'element' => 'slider_mode',
											'value' => array( 'pl-simple-slider')
										)
									),
									array(
										"type" => "pw_image_radio",
										"class" => "",
										"heading" => __("Multi Slider Pattern"),
										"param_name" => "multi_slider_pattern",
										"value" =>array(
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/multi-layout1.jpg'."' />"=>"pl-multi-slider-pt1",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/multi-layout2.jpg'."' />"=>"pl-multi-slider-pt2",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/multi-layout3.jpg'."' />"=>"pl-multi-slider-pt3",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/multi-layout4.jpg'."' />"=>"pl-multi-slider-pt4",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/multi-layout5.jpg'."' />"=>"pl-multi-slider-pt5",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/multi-layout6.jpg'."' />"=>"pl-multi-slider-pt6",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/multi-layout7.jpg'."' />"=>"pl-multi-slider-pt7"),
										"description" => "Choose Multi Slider Pattern",
										'group' => "Slider Setting",
										'dependency' => array(
											'element' => 'slider_mode',
											'value' => array( 'pl-multi-slider')
										)
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Slide Height", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_height",
										"value" =>'470',
										"description" => "",
										'group' => "Slider Setting",
										'dependency' => array(
											'element' => 'slider_mode',
											'value' => array( 'pl-multi-slider')
										)
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Swipper Slider Effect"),
										"param_name" => "swiper_slider_effect",
										"value" =>array(
											__("Slide" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slide",
											__("Fade" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fade",
											__("Cube" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"cube",
											__("Flip" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flip"
											),
										"description" => __("Choose Slider Type", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
										"group" => "Slider Setting",
										'dependency' => array(
											'element' => 'slider_type',
											'value' => array( 'pl-swiper')
										)
									),
									array(
										"type" => "colorpicker",
										"class" => "",
										"heading" => __("Overlay Background Color",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "overlay_color",
										"value" => '',
										"description" => __("Leave blank to ignore",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"group" => "Slider Setting",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Slider Image Size",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_image_size",
										"value" => array(
											__("WP Full Size" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"full",
											__("WP Medium Size" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"meduim",
											__("WP Thumbnail Size" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"thumbnail",
											__("Horizontal 1 (800x380)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pw_hor2_image",
											__("Horizontal (768x506)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pw_hor_image",
											__("Vertical (470x634)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pw_ver_image",
											__("Rectangle (500x500)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pw_rec_image",
											),
										"description" => "Choose Slider Image Size",
										'group' => "Slider Setting",
									),
									
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Auto Play",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_autoplay",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"true"										
										),
										"description" => "",
										'group' => "Slider Setting",
										
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Slide Speed", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_speed",
										"value" =>'1000',
										"description" => "",
										'group' => "Slider Setting",
										
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Loop",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_loop",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"true"										),
										"description" => "",
										'group' => "Slider Setting",
										
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Navigation",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "show_navigation",
										"value" => array(
											__("No" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"false",
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"true",
										
											),
										"description" => "",
										'group' => "Slider Setting"
									),

										/*Navigation Option*/
										array(
											"type" => "pw_navigation_iconset",
											
											"class" => "",
											"heading" => __("Navigation Icon", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
											"param_name" => "navigation_icon",
											"description" => __("Choose Navigation Icon Set", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_navigation',
												'value' => array( 'true' )
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Navigation Position",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "navigation_position",
											"value" => array(
												__("Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-center",
												__("Top Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-topright",
												__("Top Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-topleft",
												__("Bottom Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-bottomright",
												__("Bottom Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-bottomleft",
											
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_navigation',
												'value' => array( 'true')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Navigation Shape",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "navigation_shape",
											"value" => array(
												__("Rectangle" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-rect",
												__("Square" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-squar",
												__("Circle" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-circle",
											
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_navigation',
												'value' => array( 'true')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Navigation Fill",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "navigation_fill",
											"value" => array(
												__("Fill" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-fill",
												__("Bordered" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-bordered",
												__("None" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-none",
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_navigation',
												'value' => array( 'true')
											)
										),
										array(
											"type" => "checkbox",
											"class" => "",
											"heading" => __("Show Navigation On Hover",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "navigation_on_hover",
											"value" => array(
												__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-nav-hover-show"										
											),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_navigation',
												'value' => array( 'true')
											)
										),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Dots(Pagination)",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "show_dot",
										"value" => array(
											__("No" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"false",
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"true",
										
											),
										"description" => "",
										'group' => "Slider Setting"
									),

										/*Dot Option*/
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Dots Position",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "dot_position",
											"value" => array(
												__("Under Slider - Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-bottom-center",
												__("Under Slider - Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-bottom-left",
												__("Under Slider - Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-bottom-right",
												
												__("Over Slider - Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-over-bottom-center",
												__("Over Slider - Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-over-bottom-left",
												__("Over Slider - Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-over-bottom-right",					
												
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_dot',
												'value' => array( 'true')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Dot Type",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "dot_type",
											"value" => array(
												__("Bullets" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bullets",
												__("Progress" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"progress",
												__("Fraction" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fraction",
												),
											"description" => "Just Work In Swiper Slider",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_dot',
												'value' => array( 'true')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Dot Layout",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "dot_layout",
											"value" => array(
												__("Layout 1" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-layout1",
												__("Layout 2" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-layout2",
												__("Layout 3" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-layout3",
												__("Layout 4" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-layout4",
												__("Layout 5" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-layout5",
												__("Layout 6" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-layout6",
												__("Layout 7" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-layout7",
												
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_dot',
												'value' => array( 'true')
											)
										),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Thumbnail",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "show_thumbnail",
										"value" => array(
											__("No" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-hide-thumb",
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>  "pl-slider-show-thumb",
										
											),
										"description" => "Just Work For OWL And Swiper Slider.",
										'group' => "Slider Setting",
										'dependency' => array(
											'element' => 'show_dot',
											'value' => array( 'false')
										)
									),

										/*Navigation Option*/
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Thumbnail Position",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "thumbnail_position",
											"value" => array(
												__("Under Slider - Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-bottom-center",
												__("Under Slider - Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-bottom-left",
												__("Under Slider - Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-bottom-right",
												
												__("Over Slider - Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-over-bottom-center",
												__("Over Slider - Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-over-bottom-left",
												__("Over Slider - Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-over-bottom-right",					
											
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_thumbnail',
												'value' => array( 'pl-slider-show-thumb')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Thumbnail Shape",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "thumbnail_shape",
											"value" => array(
												__("Rectangle" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-rect",
												__("Square" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-squar",
												__("Circle" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-circle",
											
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_thumbnail',
												'value' => array( 'pl-slider-show-thumb')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Thumbnail Layout",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "thumbnail_layout",
											"value" => array(
												__("Layout 1" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-layout1",
												__("Layout 2" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-thumb-layout2",
											
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_thumbnail',
												'value' => array( 'pl-slider-show-thumb')
											)
										),
										array(
											"type" => "checkbox",
											"class" => "",
											"heading" => __("Show Tooltip On Hover",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "thumbnail_tooltip",
											"value" => array(
												__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"true"										
											),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_thumbnail',
												'value' => array( 'pl-slider-show-thumb')
											)
										),
									/*Play & Pause Button*/
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Play & Pause",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "show_play_pause",
										"value" => array(
											__("No" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-hide-btn",
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-show-btn",
										
											),
										"description" => "",
										'group' => "Slider Setting"
									),

										/*Play & Pause Option*/
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Play & Pause Position",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "play_pause_position",
											"value" => array(
												__("Top Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-topright",
												__("Top Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-topleft",
												__("Bottom Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-bottomright",
												__("Bottom Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-bottomleft",
											
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_play_pause',
												'value' => array( 'pl-slider-show-btn')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Play & Pause Shape",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "play_pause_shape",
											"value" => array(
												__("Rectangle" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-rect",
												__("Square" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-squar",
												__("Circle" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-circle",
											
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_play_pause',
												'value' => array( 'pl-slider-show-btn')
											)
										),
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Play & Pause Fill",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "play_pause_fill",
											"value" => array(
												__("fill" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-fill",
												__("Bordered" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-bordered",
												__("None" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-none",
												),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_play_pause',
												'value' => array( 'pl-slider-show-btn')
											)
										),
										array(
											"type" => "checkbox",
											"class" => "",
											"heading" => __("Show Play & Pause On Hover",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "play_pause_on_hover",
											"value" => array(
												__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-hover-show"										
											),
											"description" => "",
											'group' => "Slider Setting",
											'dependency' => array(
												'element' => 'show_play_pause',
												'value' => array( 'pl-slider-show-btn')
											)
										),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Lazy Load",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "lazy_load",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"true"										
										),
										"description" => "",
										'group' => "Slider Setting",
									),			
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("In Animation",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_in_animation",
										"value" => array(
											__("None" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-none-animation",
											/*public*/
											__("bounce" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounce",
											__("flash" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flash",
											__("pulse" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pulse",
											__("rubberBand" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rubberBand",
											__("shake" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"shake",
											__("swing" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"swing",
											__("tada" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"tada",
											__("wobble" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"wobble",
											__("jello" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"jello",
											/*bounce*/
											__("bounceIn" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceIn",
											__("bounceInDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceInDown",
											__("bounceInLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceInLeft",
											__("bounceInRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceInRight",
											__("bounceInUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceInUp",
											/*fade*/
											__("fade" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fade",
											__("fadeInDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInDown",
											__("fadeInDownBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInDownBig",
											__("fadeInLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInLeft",
											__("fadeInLeftBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInLeftBig",
											__("fadeInRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInRight",
											__("fadeInRightBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInRightBig",
											__("fadeInUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInUp",
											__("fadeInUpBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeInUpBig",
											/*flip*/
											__("flipInX" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flipInX",
											__("flipInY" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flipInY",
											__("lightSpeedIn" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"lightSpeedIn",
											/*rotate*/
											__("rotateIn" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateIn",
											__("rotateInDownLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateInDownLeft",
											__("rotateInDownRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateInDownRight",
											__("rotateInUpLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateInUpLeft",
											__("rotateInUpRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateInUpRight",
											/*slide*/
											__("slideInDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideInDown",
											__("slideInLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideInLeft",
											__("slideInRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideInRight",
											__("slideInUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideInUp",
											/*zoom*/												
											__("zoomIn" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomIn",
											__("zoomInDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomInDown",
											__("zoomInLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomInLeft",
											__("zoomInRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomInRight",
											__("zoomInUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomInUp",
											/*roll*/
											__("rollIn" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rollIn",
											
											),
										"description" => "Choose In Animation ",
										'group' => "Slider Setting",
										'dependency' => array(
											'element' => 'slider_type',
											'value' => array( 'pl-owl' )
										)
									),
									
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Out Animation",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_out_animation",
										"value" => array(
											__("None" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-none-animation",
											/*public*/
											__("bounce" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounce",
											__("flash" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flash",
											__("pulse" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pulse",
											__("rubberBand" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rubberBand",
											__("shake" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"shake",
											__("swing" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"swing",
											__("tada" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"tada",
											__("wobble" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"wobble",
											__("jello" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"jello",
											/*bounce*/
											__("bounceOut" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceOut",
											__("bounceOutDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceOutDown",
											__("bounceOutLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceOutLeft",
											__("bounceOutRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceOutRight",
											__("bounceOutUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"bounceOutUp",
											/*fade*/
											__("fade" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fade",
											__("fadeOutDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutDown",
											__("fadeOutDownBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutDownBig",
											__("fadeOutLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutLeft",
											__("fadeOutLeftBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutLeftBig",
											__("fadeOutRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutRight",
											__("fadeOutRightBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutRightBig",
											__("fadeOutUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutUp",
											__("fadeOutUpBig" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"fadeOutUpBig",
											/*flip*/
											__("flipOutX" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flipOutX",
											__("flipOutY" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flipOutY",
											__("lightSpeedOut" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"lightSpeedOut",
											/*rotate*/
											__("rotateOut" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateOut",
											__("rotateOutDownLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateOutDownLeft",
											__("rotateOutDownRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateOutDownRight",
											__("rotateOutUpLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateOutUpLeft",
											__("rotateOutUpRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rotateOutUpRight",
											/*slide*/
											__("slideOutDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideOutDown",
											__("slideOutLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideOutLeft",
											__("slideOutRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideOutRight",
											__("slideOutUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"slideOutUp",
											/*zoom*/												
											__("zoomOut" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomOut",
											__("zoomOutDown" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomOutDown",
											__("zoomOutLeft" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomOutLeft",
											__("zoomOutRight" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomOutRight",
											__("zoomOutUp" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"zoomOutUp",
											/*roll*/
											__("rollOut" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"rollOut",
											
											),
										"description" => "Choose Out Animation ",
										'group' => "Slider Setting",
										'dependency' => array(
											'element' => 'slider_type',
											'value' => array( 'pl-owl' )
										)
									),
									
									/* Show OR Hide Items*/
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Title",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_title",
										"value" => array(
											__("Yes, of course" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes",
											__("No, Thanks" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"no",
											
											),
										"description" => "",
										'group' => "Show / Hide Elements"
									),
									array(
										"type" => "pw_adv_font",
										"class" => "",
										"heading" => __("Title Font",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_title_font",
										"value" => "",
										"description" => "",
										'group' => "Show / Hide Elements",
										'dependency' => array(
											'element' => 'slider_show_title',
											'value' => array( 'yes' )
										)
									),	
									
									/*Meta*/
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Metas",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_meta",
										"value" => array(
											__("No, Thanks" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"no",
											__("Yes, of course" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes",
											),
										"description" => "",
										'group' => "Show / Hide Elements"
									),
									/*Meta Items*/
									array(
										"type" => "pw_adv_font",
										"class" => "",
										"heading" => __("Meta Font",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_meta_font",
										"value" => "",
										"description" => "",
										'group' => "Show / Hide Elements",
										'dependency' => array(
											'element' => 'slider_show_meta',
											'value' => array( 'yes' )
										)
									),	
									
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show Author",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_author",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes"										
										),
										"description" => "",
										'group' => "Show / Hide Elements",
										'dependency' => array(
											'element' => 'slider_show_meta',
											'value' => array( 'yes' )
										)
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show Comments",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_comments",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes"										
										),
										"description" => "",
										'group' => "Show / Hide Elements",
										'dependency' => array(
											'element' => 'slider_show_meta',
											'value' => array( 'yes' )
										)
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show category",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_category",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes"										
										),
										"description" => "",
										'group' => "Show / Hide Elements",
										'dependency' => array(
											'element' => 'slider_show_meta',
											'value' => array( 'yes' )
										)
									),
										/*category item*/
										array(
											"type" => "pw_number",
											"class" => "",
											"heading" => __("Category Number", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "slider_cat_number",
											"value" =>'1',
											"description" => "",
											'group' => "Show / Hide Elements",
											'dependency' => array(
												'element' => 'slider_show_category',
												'value' => array( 'yes' )
											)
										),
									
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Show Date",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_date",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes"										
										),
										"description" => "",
										'group' => "Show / Hide Elements",
										'dependency' => array(
											'element' => 'slider_show_meta',
											'value' => array( 'yes' )
										)
									),
										/*date item*/
										array(
											"type" => "textfield",
											"class" => "",
											"heading" => __("Date Format",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "slider_date_format",
											"value" => "",
											"description" => "",
											'group' => "Show / Hide Elements",
											'dependency' => array(
												'element' => 'slider_show_date',
												'value' => array( 'yes' )
											)
										),
										
										
									/*Excerpt*/
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Excerpt",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_excerpt",
										"value" => array(
											__("No, Thanks" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"no",
											__("Yes, of course" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes",
											),
										"description" => "",
										'group' => "Show / Hide Elements"
									),
										/*Excerpt Items*/
										array(
											"type" => "pw_simple_font",
											"class" => "",
											"heading" => __("Excerpt Font",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "slider_excerpt_font",
											"value" => "",
											"description" => "",
											'group' => "Show / Hide Elements",
											'dependency' => array(
												'element' => 'slider_show_excerpt',
												'value' => array( 'yes' )
											)
										),	
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Excerpt Source",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "slider_excerpt_source",
											"value" => array(
												__("Excerpt" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"excerpt",
												__("Full Content" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"full_content",
												),
											"description" => "",
											'group' => "Show / Hide Elements",
											"dependency" => array(
												'element' => 'slider_show_excerpt',
												'value' => array( 'yes' )
											)
										),
										array(
											"type" => "pw_number",
											"class" => "",
											"heading" => __("Excerpt Lenght",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "slider_excerpt_len",
											"value" => "",
											"description" => "",
											'group' => "Show / Hide Elements",
											'dependency' => array(
												'element' => 'slider_show_excerpt',
												'value' => array( 'yes' )
											)
										),
										
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Show Button",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_show_button",
										"value" => array(
											__("No, Thanks" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"no",
											__("Yes, of course" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"yes",
											),
										"description" => "",
										'group' => "Show / Hide Elements"
									),	
										/*Button Items*/
										array(
											"type" => "pw_adv_font",
											"class" => "",
											"heading" => __("Button Font",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "slider_button_font",
											"value" => "",
											"description" => "",
											'group' => "Show / Hide Elements",
											'dependency' => array(
												'element' => 'slider_show_button',
												'value' => array( 'yes' )
											)
										),	
									
										array(
											"type" => "textfield",
											"class" => "",
											"heading" => __("Button Text",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "slider_btn_txt",
											"value" => "",
											"description" => "",
											'group' => "Show / Hide Elements",
											'dependency' => array(
												'element' => 'slider_show_button',
												'value' => array( 'yes' )
											)
										),

									/*******************************
									**********CUSTOM CSS/JS*********
									*******************************/	
									array(
										"type" => "textfield",
										"class" => "",
										"heading" => __("Custom Class",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "custom_class",
										"value" => "",
										"description" => "",
										'group' => "Custom Class"
									),
									/*array(
										"type" => "textarea",
										"class" => "",
										"heading" => __("My CSS",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										'param_name' => 'my_css',
										"value" => "",
										"description" => "",
										'group' => "Custom Class"
									),*/
									
									/*******************************
									 *PREETS
									 *******************************/         
									 array(
									  "type" => "apply_preset",
									  "class" => "",
									  "heading" => __("Apply Preset",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
									  "param_name" => "slider_current_preset",
									  "target" => "slider",
									  "group" => "Presets"
									 ),											
								)
					) );
			}
		}
		
		// Shortcode handler function for  icon block
		function pw_pro_slider_layout_shortcode($atts)
		{
			/*General*/
			$heading_type=
			$heading_base=
			$pw_query=
			$show_heading=
			$heading_layout=
			$heading_main_color=
			$heading_text=
			$show_sub_heading=
			$show_view_btn=
			$view_text=
			$tab_heading_layout=
			$adv_heading_active_color=

			$rtl_layout=
			
			/*Slider Setting*/
			$slider_type =
			$slider_mode = 
			$slider_layout = 
			$multi_slider_pattern =
			$slider_height =
			$overlay_color=
			$slider_image_size =
			$slider_autoplay = 
			$slider_speed =
			$slider_loop = 
			$show_navigation = 
			$navigation_icon =
			$navigation_fill = 
			$navigation_shape = 
			$navigation_position =
			$navigation_on_hover =
			$show_dot =
			$dot_type = 
			$dot_layout = 
			$dot_position =
			$show_thumbnail =
			$thumbnail_layout =
			$thumbnail_shape =
			$thumbnail_position =
			$thumbnail_tooltip =
			$show_play_pause = 
			$play_pause_fill = 
			$play_pause_shape = 
			$play_pause_position = 
			$play_pause_on_hover = 
			$lazy_load = 
			$slider_out_animation = 
			$slider_in_animation = 
			$swiper_slider_effect=
			/*Slider Setting*/
			$slider_show_title=
			$slider_title_font=
			$slider_show_meta=
			$slider_meta_font=
			$slider_show_author=
			$slider_show_comments=
			$slider_show_category=
			$slider_cat_number=
			$slider_show_date=
			$slider_date_format=
			$slider_show_excerpt=
			$slider_excerpt_font=
			$slider_excerpt_source=
			$slider_excerpt_len=
			$slider_show_button=
			$slider_button_font=
			$slider_btn_txt=
			
			$custom_class=
			$slider_current_preset=
			$output='';
			extract(shortcode_atts( array(
				'heading_type'=>'manual_heading',
				'heading_base'=>'category_base',
				'pw_query'=>'',
				'show_heading'=>'no',
				'heading_layout'=>'pl-header-layout1',
				'heading_main_color'=>'#444444',
				'heading_text'=>'',
				'show_sub_heading'=>'no',
				'show_view_btn'=>'no',
				'view_text'=>__("View All",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				'tab_heading_layout'=>'pl-tabs-style-bar',
				'adv_heading_active_color'=>'#444444',
				'rtl_layout'=>'no-rtl',
				/*Slider Setting*/
				'slider_type' => 'pl-owl',
				'slider_mode' => 'pl-simple-slider', 
				'slider_layout' => 'pl-slider-layout1', 
				'multi_slider_pattern' => 'pl-multi-slider-pt1',
				'slider_height' => '470',
				'overlay_color' => 'rgba(0,0,0,0.7)',
				'slider_image_size' => 'full',
				'slider_autoplay' => 'false', 
				'slider_speed' => '3000',
				'slider_loop' => 'false', 
				'show_navigation' => 'false', 
				'navigation_icon' => 'fa-angle-left',
				'navigation_fill' => 'pl-slider-nav-fill', 
				'navigation_shape' => 'pl-slider-nav-rect', 
				'navigation_position' => 'pl-slider-nav-center',
				'navigation_on_hover' => 'pl-slider-nav-hover-hide',
				'show_dot' => 'false', 
				'dot_type' => 'bullets',
				'dot_layout' => 'pl-slider-dot-layout1', 
				'dot_position' => 'pl-slider-dot-bottom-center',
				'show_thumbnail' => 'pl-slider-hide-thumb',
				'thumbnail_layout' => 'pl-slider-thumb-layout1',
				'thumbnail_shape' => 'pl-slider-thumb-rect',
				'thumbnail_position' => 'pl-slider-thumb-bottom-center',
				'thumbnail_tooltip' => 'yes',
				'show_play_pause' => 'pl-slider-hide-btn', 
				'play_pause_fill' => 'pl-slider-btn-fill', 
				'play_pause_shape' => 'pl-slider-btn-rect', 
				'play_pause_position' => 'pl-slider-btn-rect', 
				'play_pause_on_hover' => 'pl-slider-btn-hover-hide', 
				'lazy_load' => 'false', 
				'slider_out_animation' => 'pl-none-animation', 
				'slider_in_animation' => 'pl-none-animation', 
				'swiper_slider_effect' => 'slide',
				/*Show / Hide Elements*/	
				'slider_show_title'=>'yes',
				'slider_title_font'=>'inherit,20,#333333,#333333',
				'slider_show_meta'=>'no',
				'slider_meta_font'=>'inherit,11,#333333,#333333',
				'slider_show_author'=>'no',
				'slider_show_comments'=>'no',
				'slider_show_category'=>'no',
				'slider_cat_number'=>'1',
				'slider_show_date'=>'no',
				'slider_date_format'=>'F j, Y',
				'slider_show_excerpt'=>'no',
				'slider_excerpt_font'=>'inherit,13,#333333',
				'slider_excerpt_source'=>'excerpt',
				'slider_excerpt_len'=>'15',
				'slider_show_button'=>'no',
				'slider_button_font'=>'inherit,11,#333333,#333333',
				'slider_btn_txt'=>'read more',
				
				'custom_class'=>'',
				'slider_current_preset'=>'',
				),$atts));
			
				
			$this->frontend_embed_pro_slider_layout($slider_type , $rtl_layout , $heading_type , $thumbnail_tooltip);
			
			if ($heading_type=='manual_heading' || $heading_type=='simple_heading'){
				include("slider_templates/simple_slider_loop.php");
			}
			else {
				include("slider_templates/adv_slider_loop.php");
			}
			$this->main_slider_type = $slider_type;
			$this->slider_model = $slider_mode;
			$this->height_slider = $slider_height;
			$this->overlay_back = $overlay_color;
			$this->overlay_no_rgb = $overlay_color;
			$overlay_color = explode(',',$overlay_color);
			//make no rgba color for slider responsive
			if ( count($overlay_color) == 4  ){
				$overlay_color[3] = '1)';
				$this->overlay_no_rgb = $overlay_color  ;
				$this->overlay_no_rgb = implode(',',$this->overlay_no_rgb);
			}
			$this->heading_main_color = $heading_main_color;
			$this->adv_heading_active_color = $adv_heading_active_color;
			
			$this->slider_title_font = explode (',' , $slider_title_font);
			$this->slider_meta_font= explode (',' , $slider_meta_font);
			$this->slider_excerpt_font=explode (',' , $slider_excerpt_font);
			$this->slider_button_font=explode (',' , $slider_button_font);
			
			
			$this->pl_slider_custom_css();
			return $output;	
		}
		
		function pl_slider_custom_css()  {
			
			$inline_css='';
			$imported_font = $slider_title_font = $slider_meta_font = $slider_excerpt_font = $slider_button_font  = $mini_title_font = $mini_meta_font = $mini_excerpt_font = array('inherit');
			$font_counter = 0;
			if ($this->slider_title_font[0]!='inherit') {
				$imported_font[$font_counter++] = $this->slider_title_font[0]; 
				$slider_title_font = explode(':',str_replace('+',' ',$this->slider_title_font[0]));
				
			} 
			if ($this->slider_meta_font[0]!='inherit') {
				$imported_font[$font_counter++] = $this->slider_meta_font[0]; 
				$slider_meta_font = explode(':',str_replace('+',' ',$this->slider_meta_font[0]));
			} 
			if ($this->slider_excerpt_font[0]!='inherit') {
				$imported_font[$font_counter++] = $this->slider_excerpt_font[0]; 
				$slider_excerpt_font = explode(':',str_replace('+',' ',$this->slider_excerpt_font[0]));
			} 
			if ($this->slider_button_font[0]!='inherit') {
				$imported_font[$font_counter++] = $this->slider_button_font[0]; 
				$slider_button_font = explode(':',str_replace('+',' ',$this->slider_button_font[0]));
			} 
			
			$imported_font= array_filter(array_unique($imported_font));
			
			$sep='|';$font_family='';
			foreach ( $imported_font as $font ){
				if ($font_family==''){$sep='';}
				if ($font!='inherit')
					$font_family .= $sep . $font;
				$sep='|';
			}
			
			wp_enqueue_style('pw-custom-css', plugin_dir_url_pw_pro_slider_carousel . 'css/custom-css.css', array() , null); 
			if (($imported_font[0]!='inherit')){
				$inline_css .= '
						   @import url(http://fonts.googleapis.com/css?family='. $font_family.');
						   ';
			}//end if
			$inline_css.='
				/*Heading Main Color*/
				.pl-header-'.$this->rand_id.' .pl-sub-heading:hover {
					color:'.$this->heading_main_color.';
				}
				.pl-header-'.$this->rand_id.' .pl-more-btn, 
				.pl-header-'.$this->rand_id.' .pl-more-btn:hover{
					color:'.$this->heading_main_color.';
				}
				
				.pl-header-'.$this->rand_id.'.pl-header-layout1{
					border-color:'.$this->heading_main_color.';
				} 
				.pl-header-'.$this->rand_id.'.pl-header-layout1 .pl-main-heading,
				.pl-header-'.$this->rand_id.'.pl-header-layout1 .pl-main-heading a,
				.pl-header-'.$this->rand_id.'.pl-header-layout1 .pl-main-heading a:hover{
					color:'.$this->heading_main_color.';
				}
				
				.pl-header-'.$this->rand_id.'.pl-header-layout2{
					border-color:'.$this->heading_main_color.';
				} 
				.pl-header-'.$this->rand_id.'.pl-header-layout2 .pl-main-heading{
					background-color:'.$this->heading_main_color.'
				}
				
				.pl-header-'.$this->rand_id.'.pl-header-layout3{
					border-color:'.$this->heading_main_color.';
				} 
				.pl-header-'.$this->rand_id.'.pl-header-layout3 .pl-main-heading,
				.pl-header-'.$this->rand_id.'.pl-header-layout3 .pl-main-heading a,
				.pl-header-'.$this->rand_id.'.pl-header-layout3 .pl-main-heading a:hover{
					color:'.$this->heading_main_color.'
				}
				
				
				.pl-header-'.$this->rand_id.'.pl-header-layout4 .pl-main-heading,
				.pl-header-'.$this->rand_id.'.pl-header-layout4 .pl-main-heading a,
				.pl-header-'.$this->rand_id.'.pl-header-layout4 .pl-main-heading a:hover{
					color:'.$this->heading_main_color.'
				}
				.pl-header-'.$this->rand_id.'.pl-header-layout4 .pl-main-heading:before{
					background-color:'.$this->heading_main_color.'
				}
				
				/*Tab Aain Color*/
				
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-line nav li.tab-current a,
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-line nav a:hover, 
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-line nav a:focus {
					box-shadow: inset 0 -2px '.$this->adv_heading_active_color.';
					color: '.$this->adv_heading_active_color.';
				}
				
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-bar nav ul{
					border-color:'.$this->adv_heading_active_color.';
				}
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-bar nav ul li.tab-current a,
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-bar nav ul li a:hover,
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-bar nav ul li a:focus{
					background: '.$this->adv_heading_active_color.';
				}
				
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-topline nav li.tab-current a,
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-topline nav li a:hover{
					box-shadow: inset 0 3px 0 '.$this->adv_heading_active_color.';
    				color: '.$this->adv_heading_active_color.';
				}
				
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-linebox nav a:hover::after, 
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-linebox nav a:focus::after, 
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-linebox nav li.tab-current a::after{
					background: '.$this->adv_heading_active_color.';
				}
				
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-flip nav li.tab-current a::after,
				.pl-tabs-'.$this->rand_id.'.pl-tabs-style-flip .content-wrap{
					background-color: '.$this->adv_heading_active_color.';
				}
				
			';
			//OVERLAY OR CONTAINER BACKGROUND
			
			if ($this->overlay_back != ''){
				$inline_css.='
				.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout1 .pl-slider-content,
				.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout2 .pl-slider-content,
				.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout3 .pl-slider-content,
				.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout4 .pl-slider-content,
				.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout5 .pl-slider-content .pl-slide-center-cnt,
				.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout7 .pl-slider-content				{
					background-color:'.$this->overlay_back.';
				}
				.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout7 .pl-slider-content:before{
					border-color: transparent '.$this->overlay_back.' transparent transparent;
				}';
				
				
			}	
			$inline_css.='
				/*Slider title*/
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-title a{
					';
						$inline_css .=($this->slider_title_font[0] !='inherit')?'font-family:"'.$slider_title_font[0].'"!important;':'';
				$inline_css.='
					font-size:'.$this->slider_title_font[1].'px;
					color:'.$this->slider_title_font[2].'!important;
					
				}
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-title a:hover{
					color:'.$this->slider_title_font[3].'!important;	
				}
				/*Slider meta*/
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-meta .pl-meta-item a,
				.'.$this->main_slider_type.'-'.$this->rand_id.'  .pl-meta .pl-meta-item .meta-text, 
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-over-category a,
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-top-meta .pl-meta-item a
				{
					';
						$inline_css .=($this->slider_meta_font[0] !='inherit')?'font-family:"'.$slider_meta_font[0].'"!important;':'';
				$inline_css.='
					font-size:'.$this->slider_meta_font[1].'px;
					color:'.$this->slider_meta_font[2].';
					
				}
				.'.$this->main_slider_type.'-'.$this->rand_id.'  .pl-meta .pl-meta-item i{
					font-size:'.$this->slider_meta_font[1].'px;
					color:'.$this->slider_meta_font[2].';
				}
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-meta .pl-meta-item a:hover, 
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-over-category a:hover{
					color:'.$this->slider_meta_font[3].';	
				}
				
				/*Slider Excerpt*/
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-excerpt ,
				.'.$this->main_slider_type.'-'.$this->rand_id.'  .pl-excerpt p{
					';
						$inline_css .=($this->slider_excerpt_font[0] !='inherit')?'font-family:"'.$slider_excerpt_font[0].'"!important;':'';
				$inline_css.='
					font-size:'.$this->slider_excerpt_font[1].'px;
					color:'.$this->slider_excerpt_font[2].';
					
				}';
				$inline_css.='
				/*Slider Button*/
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-buttons .pl-readmore-btn {
					';
						$inline_css .=($this->slider_button_font[0] !='inherit')?'font-family:"'.$slider_button_font[0].'"!important;':'';
				$inline_css.='
					font-size:'.$this->slider_button_font[1].'px;
					color:'.$this->slider_button_font[2].';
					
				}
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-buttons .pl-readmore-btn:hover {
					color:'.$this->slider_button_font[3].';
				}
				@media (max-width: 767px) {
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout1 .pl-slider-content,
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout2 .pl-slider-content,
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout4 .pl-slider-content,
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout7 .pl-slider-content
					 {
						background-color:'.$this->overlay_no_rgb.';
					}
				}
				@media (max-width: 480px) {
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout1 .pl-slider-content,
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout2 .pl-slider-content,
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout4 .pl-slider-content,
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-slider-layout7 .pl-slider-content
					 {
						background-color:'.$this->overlay_no_rgb.';
					}
				}
				';
				
				/*SLIDER HEIGHT FOR MULTI SLIDER*/
				$div_height = round ($this->height_slider / 2 );
				if ($this->slider_model=='pl-multi-slider'){
					$inline_css.='
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-multi-item.pl-item-big ,
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-multi-item.pl-item-big .pl-slider-thumbnail
					{
						height:'.$this->height_slider.'px;
					}
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-multi-item.pl-item-mini,
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-multi-item.pl-item-mini.pl-second-item,
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-multi-item.pl-item-mini .pl-slider-thumbnail
					{
						height:'.$div_height.'px;
					}
					.'.$this->main_slider_type.'-'.$this->rand_id.'.pl-multi-slider-pt4 .pl-multi-item.pl-item-big{
						height:'.$div_height.'px;
					}
					
					
					';
					
					
				}
				
				
			wp_add_inline_style( 'pw-custom-css', $inline_css );
		}
	}
	////instantiate the class
}
new pw_pro_slider_layout();
?>