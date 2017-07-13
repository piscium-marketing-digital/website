<?php
if(!class_exists('pw_pro_carousel_layout'))
{
	class pw_pro_carousel_layout
	{
		public  $rand_id , $main_slider_type, $heading_main_color , $adv_heading_active_color   , $slider_border , $slider_back_color , $slider_title_font , $slider_meta_font , $slider_excerpt_font, $slider_button_font , $overlay_back,  $custom_class ;
		function __construct()
		{
			add_action('vc_before_init',array($this,'pw_pro_carousel_layout_init'));
			add_shortcode('pw_VC_pro_carousel_layout_shortcode',array($this,'pw_pro_carousel_layout_shortcode'));
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
		
		function pw_pro_carousel_layout_init()
		{
			if(function_exists('vc_map'))
			{
				vc_map( array(
					"name" => __("PW Pro Carousel",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
					"description" => __("Carousel Post Layout",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
					"base" => "pw_VC_pro_carousel_layout_shortcode",
					"class" => "",
					"controls" => "full",
					"icon" => plugin_dir_url_pw_pro_slider_carousel .'/img/carousel-pro.jpg',
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
									***********Carousel Setting**********
									**********************************/
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Carousel Type"),
										"param_name" => "slider_type",
										"value" =>array(
											__("OWL Slider" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-owl",
											__("Swiper Slider" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-swiper",
											),
										"description" => __("Choose Carousel Type", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
										"group" => "Carousel Setting"
									),
									array(
										"type" => "pw_image_radio",
										"class" => "",
										"heading" => __("Carousel Layout"),
										"param_name" => "slider_layout",
										"value" =>array(
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-outer-layout1.jpg'."' />"=>"pl-outerdesc-layout1",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-outer-layout2.jpg'."' />"=>"pl-outerdesc-layout3",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-outer-layout3.jpg'."' />"=>"pl-outerdesc-layout4",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout1.jpg'."' />"=>"pl-boxed-layout1",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout2.jpg'."' />"=>"pl-boxed-layout2",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout3.jpg'."' />"=>"pl-boxed-layout3",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout4.jpg'."' />"=>"pl-boxed-layout4",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout5.jpg'."' />"=>"pl-boxed-layout5",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout6.jpg'."' />"=>"pl-boxed-layout6",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout7.jpg'."' />"=>"pl-boxed-layout7",
											"<img src='".__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/carousel-boxed-layout8.jpg'."' />"=>"pl-boxed-layout8"),
										"description" => "Choose Carousel Layouts",
										'group' => "Carousel Setting",
										
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Layout 3 Effects"),
										"param_name" => "layout_three_effect",
										"value" =>array(
											__("Move Overlay From Bottom To Top - With Move Image" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-bottom-to-top-img",
											__("Move Overlay From Bottom To Top - Without Move Image" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-bottom-to-top",
											__("Move Overlay From Top To Bottom - With Move Image" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-top-to-bottom-img",
											__("Move Overlay From Top To Bottom - Without Move Image" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-top-to-bottom"
											),
										"description" => __("Choose Slider Type", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
										"group" => "Carousel Setting",
										'dependency' => array(
											'element' => 'slider_layout',
											'value' => array( 'pl-boxed-layout3')
										)
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Layout 3 Content Align"),
										"param_name" => "layout_three_align",
										"value" =>array(
											__("Top" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-boxed-detail-top",
											__("Bottom" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-boxed-detail-bottom",
											__("Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-boxed-detail-center",
											),
										"description" => __("Choose Slider Type", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
										"group" => "Carousel Setting",
										'dependency' => array(
											'element' => 'slider_layout',
											'value' => array( 'pl-boxed-layout3')
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
											__("Flip" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"flip",
											__("Coverflow" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"coverflow"
											),
										"description" => __("Choose Slider Type, Note: cube and flip effects just work when 1 item display on carousel", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
										"group" => "Carousel Setting",
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
										"group" => "Carousel Setting",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Carousel Image Size",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_image_size",
										"value" => array(
											__("WP Full Size" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"full",
											__("WP Medium Size" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"meduim",
											__("WP Thumbnail Size" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"thumbnail",
											__("Horizontal (768x506)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pw_hor_image",
											__("Vertical (470x634)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pw_ver_image",
											__("Rectangle (500x500)" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pw_rec_image",
											),
										"description" => "Choose Carousel Image Size",
										'group' => "Carousel Setting",
									),
									array(
										"type" => "dropdown",
										"class" => "",
										"heading" => __("Image Hover Effect",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "image_hover",
										"value" => array(
											__("None" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-noeffect",
											__("Zoom In" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-zoomin",
											__("Zoom In Long" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-zoomin-long",
											__("Zoom Out" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-zoomout",
											__("Grayscale" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-grayscale",
											__("Opacity" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-opacity",
											__("Rotate left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-rotateleftt",
											__("Rotate Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-rotateright",
											__("Rotate Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-rotateright",
											
											),
										"description" => "Choose Image Hover",
										'group' => "Carousel Setting",
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
										'group' => "Carousel Setting",
										
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Slide Speed", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_speed",
										"value" =>'1000',
										"description" => "",
										'group' => "Carousel Setting",
										
									),
									array(
										"type" => "checkbox",
										"class" => "",
										"heading" => __("Loop",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "slider_loop",
										"value" => array(
											__("Yes" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"true"										),
										"description" => "",
										'group' => "Carousel Setting",
										
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Item Per View On Desktop", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "item_desktop",
										"value" =>'1',
										"description" => "",
										'group' => "Carousel Setting"
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Item Per View On Tablet", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "item_tablet",
										"value" =>'1',
										"description" => "",
										'group' => "Carousel Setting"
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Item Per View On Mobile", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "item_mobile",
										"value" =>'1',
										"description" => "",
										'group' => "Carousel Setting"
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Carousel Rows", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "carousel_row",
										"value" =>'1',
										"min"=>'1',
										"description" => "",
										'group' => "Carousel Setting",
										'dependency' => array(
											'element' => 'slider_type',
											'value' => array( 'pl-swiper')
										)
									),
									array(
										"type" => "pw_number",
										"class" => "",
										"heading" => __("Item Margin", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
										"param_name" => "item_margin",
										"value" =>'0',
										"description" => "",
										'group' => "Carousel Setting"
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
										'group' => "Carousel Setting"
									),


										/*Navigation Option*/
										array(
											"type" => "pw_navigation_iconset",
											
											"class" => "",
											"heading" => __("Navigation Icon", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
											"param_name" => "navigation_icon",
											"description" => __("Choose Navigation Icon Set", PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN ),
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
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
										'group' => "Carousel Setting"
									),

										/*Dot Option*/
										array(
											"type" => "dropdown",
											"class" => "",
											"heading" => __("Dots Position",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
											"param_name" => "dot_position",
											"value" => array(
												__("Under Carousel - Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-bottom-center",
												__("Under Carousel - Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-bottom-left",
												__("Under Carousel - Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-bottom-right",
												
												__("Over Carousel - Center" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-over-bottom-center",
												__("Over Carousel - Left" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-over-bottom-left",
												__("Over Carousel - Right" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-dot-over-bottom-right",					
												
												),
											"description" => "Under Carousel Dots, Don't Show In Swiper Carousel",
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
											'dependency' => array(
												'element' => 'show_dot',
												'value' => array( 'true')
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
										'group' => "Carousel Setting"
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
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
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
												__("Fill" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-fill",
												__("Bordered" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-bordered",
												__("None" ,  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  )=>"pl-slider-btn-none",
												),
											"description" => "",
											'group' => "Carousel Setting",
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
											'group' => "Carousel Setting",
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
										'group' => "Carousel Setting",
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
									  "param_name" => "current_preset",
									  "target" => "carousel",
									  "group" => "Presets"
									 ),									
								)
					) );
			}
		}
		
		// Shortcode handler function for  icon block
		function pw_pro_carousel_layout_shortcode($atts)
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
			
			/*Carousel Setting*/
			$slider_type =
			$slider_layout = 
			$layout_three_effect=
			$layout_three_align=
			$overlay_color=
			$slider_image_size =
			$image_hover=
			$slider_autoplay = 
			$slider_speed =
			$slider_loop = 
			
			$item_desktop = 
			$item_tablet = 
			$item_mobile = 
			$carousel_row=
			$item_margin = 
			
			$show_navigation = 
			$navigation_icon=
			$navigation_fill = 
			$navigation_shape = 
			$navigation_position =
			$navigation_on_hover =
			$show_dot = 
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
			$swiper_slider_effect=
			/*Carousel Setting*/
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
			$current_preset=
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
				/*Carousel Setting*/
				'slider_type' => 'pl-owl',
				'slider_layout' => 'pl-outerdesc-layout1', 
				'layout_three_effect'=>'pl-bottom-to-top',
				'layout_three_align'=>'pl-boxed-detail-top',
				'overlay_color' => '',
				'slider_image_size' => 'full',
				'image_hover'=>'pl-noeffect',
				'slider_autoplay' => 'false', 
				'slider_speed' => '3000',
				'slider_loop' => 'false', 
				'item_desktop' => '1',
				'item_tablet' => '1',
				'item_mobile' => '1',
				'carousel_row' => '1',
				'item_margin' => '0',
			
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
				'show_play_pause' => 'pl-slider-hide-btn', 
				'play_pause_fill' => 'pl-slider-btn-fill', 
				'play_pause_shape' => 'pl-slider-btn-rect', 
				'play_pause_position' => 'pl-slider-btn-topright', 
				'play_pause_on_hover' => 'pl-slider-btn-hover-hide', 
				'lazy_load' => 'false', 
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
				'current_preset'=>'',
				),$atts));
			
				
			$this->frontend_embed_pro_slider_layout($slider_type , $rtl_layout , $heading_type , $thumbnail_tooltip);
			
			
			if ($heading_type=='manual_heading' || $heading_type=='simple_heading'){
				include("carousel_templates/simple_carousel_loop.php");
			}
			else {
				include("carousel_templates/adv_carousel_loop.php");
			}
			
			$this->main_slider_type = $slider_type;
			$this->overlay_back = $overlay_color;
			$this->heading_main_color = $heading_main_color;
			$this->adv_heading_active_color = $adv_heading_active_color;
			
			$this->slider_title_font = explode (',' , $slider_title_font);
			$this->slider_meta_font= explode (',' , $slider_meta_font);
			$this->slider_excerpt_font=explode (',' , $slider_excerpt_font);
			$this->slider_button_font=explode (',' , $slider_button_font);
			
			$this->pl_boox_custom_css();
			return $output;	
		}
		
		function pl_boox_custom_css()  {
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
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-outerdesc-cnt{
					background-color:'.$this->overlay_back.';
				}
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-outerdesc-layout3 .pl-triangle{
					border-color: transparent transparent '.$this->overlay_back.' transparent;
				}';
				/*Slider Overlay*/
				$inline_css.='
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-thumbnail-overlay{
						background-color:'.$this->overlay_back.';
					}
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-boxed-layout3 .pl-boxed-detail-unvis{
						background-color:'.$this->overlay_back.';
					}
					.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-boxed-layout4 .pl-boxed-detail-vis{
						background-color:'.$this->overlay_back.';
					}
					.'.$this->main_slider_type.'-'.$this->rand_id.' div.pl-boxed-layout6 div.pl-mask::before{
						background-color:'.$this->overlay_back.';
					}
					.'.$this->main_slider_type.'-'.$this->rand_id.' div.pl-boxed-layout8 div.pl-mask::before{
						box-shadow: 0 0 0 30px '.$this->overlay_back.';
					}
				';
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
				.'.$this->main_slider_type.'-'.$this->rand_id.' .pl-cat-meta .pl-meta-item a{
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
				}';
								
			wp_add_inline_style( 'pw-custom-css', $inline_css );
		}
	}
	////instantiate the class
}
new pw_pro_carousel_layout();
?>