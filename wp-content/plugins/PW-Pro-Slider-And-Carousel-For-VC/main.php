<?php
/*
Plugin Name: PW Pro Slider And Carousel For Visual Composer
Plugin URI: http://proword.net/
Description: Pro Slider And Carousel Layout For Visual Composer
Author: Proword
Version: 1.6
Author URI: http://proword.net/
Text Domain: pw_pro_slider_carousel
 */
define('plugin_dir_url_pw_pro_slider_carousel', plugin_dir_url( __FILE__ ));
define ('__PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__',plugins_url('', __FILE__));
define( '__PW_PRO_SLIDER_CAROUSEL_ROOT_VC__', dirname(__FILE__));

define ('PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN','pw_pro_slider_carousel');
//PERFIX
define ('__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__', 'pro-slider-carousel' );


class pw_pro_slider_carousel_plugin  {
	public function __construct() 
	{
		//Add Shortcode Post And Page
		add_filter('init', array( $this,'pw_pro_slider_carousel_shortcodes_add_scripts'));
		
		//Add And Remove Param
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		
		//Add Image Size
		add_image_size( 'pw_hor2_image', '800', '380', true);
		add_image_size( 'pw_hor_image', '768', '506', true );
		add_image_size( 'pw_ver_image', '470', '634', true );
		add_image_size( 'pw_rec_image', '500', '500', true );

		add_action('admin_enqueue_scripts',array($this,'pw_pro_slider_carousel_admin_scripts'));
		
		//add presets
		add_action('vc_after_init', array($this,'pw_slider_carousel_presets'));
		$this->includes();
	}
	
	//Add And Remove Param
	public function integrateWithVC() {
		// Check if Visual Composer is installed
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}
		if(function_exists('vc_add_shortcode_param')){
			vc_add_shortcode_param('pw_googlefonts', array($this,'pw_googlefonts_settings_field'));
			vc_add_shortcode_param('pw_number' , array($this, 'pw_number_settings_field' ) );
			vc_add_shortcode_param('pw_button' , array($this, 'pw_button_settings_field' ) );
			
			vc_add_shortcode_param('pw_image_radio',array($this,'pw_image_radio_settings_field'));
			vc_add_shortcode_param('pw_fontawesome', array($this,'fontawesome_settings_field'));
			vc_add_shortcode_param('pw_navigation_iconset', array($this,'navigation_iconset_settings_field'));
			
			vc_add_shortcode_param('pw_simple_font', array($this,'pw_simple_font_settings_field'));
			vc_add_shortcode_param('pw_adv_font', array($this,'pw_adv_font_settings_field'));
			vc_add_shortcode_param('pw_border_option', array($this,'pw_border_settings_field'));
			
			vc_add_shortcode_param('apply_preset', array($this,'pw_preset_settings_field'));
			
		}
		else if(function_exists('add_shortcode_param'))
		{
			add_shortcode_param('pw_googlefonts', array($this,'pw_googlefonts_settings_field'));
			add_shortcode_param('pw_number' , array($this, 'pw_number_settings_field' ) );
			add_shortcode_param('pw_button' , array($this, 'pw_button_settings_field' ) );
			
			add_shortcode_param('pw_image_radio',array($this,'pw_image_radio_settings_field'));
			add_shortcode_param('pw_fontawesome', array($this,'fontawesome_settings_field'));
			add_shortcode_param('pw_navigation_iconset', array($this,'navigation_iconset_settings_field'));
			
			add_shortcode_param('pw_simple_font', array($this,'pw_simple_font_settings_field'));
			add_shortcode_param('pw_adv_font', array($this,'pw_adv_font_settings_field'));
			add_shortcode_param('pw_border_option', array($this,'pw_border_settings_field'));
			
			add_shortcode_param('apply_preset', array($this,'pw_preset_settings_field'));
		}
			
			
		
	}
	
	
	function pw_pro_slider_carousel_admin_scripts()
		{
			wp_enqueue_style('pw_pro_slider_carousel_font-awesome', __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__ .'/css/fontawesome/font-awesome.css');
			wp_enqueue_style('pw_pro_slider_carousel_font-awesome_style', __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__ .'/css/fontawesome/style.css');	
			
			wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_custom_css', plugin_dir_url_pw_pro_slider_carousel.'/css/fontawesome/font-awesome.css');
			
		//	wp_enqueue_script('jquery');
			//wp_enqueue_script('wp-color-picker');		
			
			wp_enqueue_style('pw_pro_slider_carousel_font-custom-js', __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__ .'/css/back-end/custom_css_back.css');
			
		}	
		
	//PARAM
	
	//APPLY PRESET PARAM
	function pw_preset_settings_field($settings, $value){
		$dependency = '';
		$output='<input name="'.$settings['param_name']
					 .'" class="wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="pw_current_preset" type="text" value="'
					 .$value.'" ' . $dependency . '/>';
		$output='';
		
		$preset_array_carousel=array(
			array(
				"id" 	=> "0",
				"label" => __("Carousel Layout 1",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L1.jpg',
			),
			array(
				"id" 	=> "1",
				"label" => __("Carousel Layout 2",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L2.jpg',
			),
			array(
				"id" 	=> "2",
				"label" => __("Carousel Layout 3",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L3.jpg',
			),array(
				"id" 	=> "3",
				"label" => __("Carousel Layout 4",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L4.jpg',
			),array(
				"id" 	=> "4",
				"label" => __("Carousel Layout 5",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L5.jpg',
			),array(
				"id" 	=> "5",
				"label" => __("Carousel Layout 6",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L6.jpg',
			),array(
				"id" 	=> "6",
				"label" => __("Carousel Layout 7",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L7.jpg',
			),array(
				"id" 	=> "7",
				"label" => __("Carousel Layout 8",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L8.jpg',
			),array(
				"id" 	=> "8",
				"label" => __("Carousel Layout 9",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L9.jpg',
			),array(
				"id" 	=> "9",
				"label" => __("Carousel Layout 10",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L10.jpg',			),		array(
				"id" 	=> "10",
				"label" => __("Carousel Layout 11",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L11.jpg',
			),array(
				"id" 	=> "11",
				"label" => __("Carousel Layout 12",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/carousel-L12.jpg',
			)
			
		);
		
		$preset_array_simple_slider=array(
			array(
				"id" 	=> "0",
				"label" => __("Simple Slider Layout 1",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-L1.jpg',
			),
			array(
				"id" 	=> "1",
				"label" => __("Simple Slider Layout 2",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-L2.jpg',
			),
			array(
				"id" 	=> "2",
				"label" => __("Simple Slider Layout 3",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-L3.jpg',
			),array(
				"id" 	=> "3",
				"label" => __("Simple Slider Layout 4",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-L4.jpg',
			),array(
				"id" 	=> "4",
				"label" => __("Simple Slider Layout 5",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-L5.jpg',
			),array(
				"id" 	=> "5",
				"label" => __("Simple Slider Layout 6",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-L6.jpg',
			),array(
				"id" 	=> "6",
				"label" => __("Simple Slider Layout 7",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-L7.jpg',
			),
			array(
				"id" 	=> "7",
				"label" => __("Simple Slider Flip Effect",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-flip.jpg',
			),
			array(
				"id" 	=> "8",
				"label" => __("Simple Slider Cube Effect",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/simple-cube.jpg',
			)
		);
		
		$preset_array_multi_slider=array(
			array(
				"id" 	=> "0",
				"label" => __("Multi Slider Layout 1",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/multi-L1.jpg',
			),
			array(
				"id" 	=> "1",
				"label" => __("Multi Slider Layout 2",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/multi-L2.jpg',
			),
			array(
				"id" 	=> "2",
				"label" => __("Multi Slider Layout 3",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/multi-L3.jpg',
			),array(
				"id" 	=> "3",
				"label" => __("Multi Slider Layout 4",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/multi-L4.jpg',
			),array(
				"id" 	=> "4",
				"label" => __("Multi Slider Layout 5",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/multi-L5.jpg',
			),array(
				"id" 	=> "5",
				"label" => __("Multi Slider Layout 6",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/multi-L6.jpg',
			),array(
				"id" 	=> "6",
				"label" => __("Multi Slider Layout 7",  PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN  ),
				"icon" 	=> __PW_PRO_SLIDER_CAROUSEL_ROOT_VC_URL__.'/img/presets/multi-L7.jpg',
			)
		);
		
		$type=$settings['target'];
		
		if($type=='carousel'){
			$i=0;
			foreach($preset_array_carousel as $car){
				$output.="<span data-id='".$i."' class='apply_preset_btn pw_radio_img_items pw_preset_img'><img src='".$car['icon']."' alt='".$car['label']."'/></span>";
				$i++;
			}
		}else{
			
			$i=0;
			foreach($preset_array_simple_slider as $slider){
				$output.="<span data-id='".$i."' class='apply_preset_btn pw_radio_img_items pw_preset_img'><img src='".$slider['icon']."' alt='".$slider['label']."'/></span>";
				$i++;
			}
			
			foreach($preset_array_multi_slider as $slider){
				$output.="<span data-id='".$i."' class='apply_preset_btn pw_radio_img_items pw_preset_img'><img src='".$slider['icon']."' alt='".$slider['label']."'/></span>";
				$i++;
			}
		}
		
	
		
		$output.='
			<script>
				jQuery(document).ready(function($){
					
					setTimeout(function(){
						$(".vc_ui-settings-button").trigger("click");
						$(".vc_ui-settings-button").trigger("click");	
					},500);
					

					$(".apply_preset_btn").each(function(){
						var id=$(this).attr("data-id");
						
						if(id===$("#pw_current_preset").val()){
							$(this).val(id+" CUR");
						}
					});
					
					$(".apply_preset_btn").click(function(){
						$(".vc_ui-settings-button").trigger("click");
						
						var id=$(this).attr("data-id");
						
						$("#pw_current_preset").val(id);
						
						$(".vc_ui-dropdown-content").find(".vc_ui-list-bar").find("li").eq( id ).find("button").trigger("click");
						
					});
				});
			</script>
		';
		
		return $output;
	}
	
	//Number Param 
	function pw_number_settings_field($settings, $value){
		$dependency = '';
		$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
		$min = isset($settings['min']) ? $settings['min'] : '';
		$max = isset($settings['max']) ? $settings['max'] : '';
		$suffix = isset($settings['suffix']) ? $settings['suffix'] : '';		   
		$output = '<input name="'.$settings['param_name']
				.'" class="wpb_vc_param_value wpb-textinput '
				.$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="'
				.$settings['param_name'].'" type="number" min="'.$min.'" max="'.$max.'" value="'.$value.'" ' . $dependency . 'style="max-width:100px; margin-right: 10px;" />'.$suffix;
				
		return $output;
	}

	/*image radio Param*/
	function pw_image_radio_settings_field($settings, $value) {
			$param_line='';
			$dependency = '';

			$optoin='';
			$current_value =  $value;
			$values = $settings['value'];
			foreach ( $values as $label => $v ) {
				$selected='';	
				if($v==$value)		
					$selected='SELECTED';
				$optoin.='<option value="' . $v . '" '.$selected.'> ' . $v . '</option>';
			}

			$param_line = '<select style="display:none"  name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="'.$settings['param_name'].'-radio-img" type="hidden">'.$optoin.'</select>';

			
					 
			$current_value =  $value;
			$values = $settings['value'];
			foreach ( $values as $label => $v ) {
				$class = ($v==$current_value) ? ' pw_radio_img_checked' : '';
				$param_line .= '
					<span data-value="' . $v . '" class="pw_radio_img_items '
					 .$settings['param_name'].'_type_radio '.$class.'"> ' . __($label, "js_composer").'</span>';
			}
           $param_line.= '<script type="text/javascript">
							jQuery(document).ready(function(){								
								jQuery(".'
					 .$settings['param_name'].'_type_radio").click(function(){
						 			jQuery(this).siblings().removeClass("pw_radio_img_checked");
									jQuery(this).addClass("pw_radio_img_checked");
									/*jQuery("#'.$settings['param_name'].'-radio-img").val(jQuery(this).attr("data-value"));*/									
									jQuery("#'.$settings['param_name'].'-radio-img option[value="+jQuery(this).attr("data-value")+"]").prop("selected", true);
									jQuery("#'.$settings['param_name'].'-radio-img").trigger("change");
								});
							});
						</script>';
			
			return $param_line;
		}
		
	/*FontAweome*/
	function fontawesome_settings_field($settings, $value) {
			$dependency = '';
			$ret = include("includes/font-awesome.php");
			return  '<div class="pw_iconpicker my_param_block">'
					 .'<input name="'.$settings['param_name']
					 .'" class="wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="font_icon" type="hidden" value="'
					 .$value.'" ' . $dependency . '/>'.
					 $ret
					 .'</div>'
					.'<script type="text/javascript">
							jQuery(document).ready(function(){
								if(jQuery("#font_icon").val()!="")
								{
									jQuery("."+jQuery("#font_icon").val()).siblings( ".active" ).removeClass( "active" );
									jQuery("."+jQuery("#font_icon").val()).addClass("active");
								}
								
								jQuery(".pw_iconpicker i").click(function(){
									var val=(jQuery(this).attr("class").split(" ")[0]!="fa-none" ? jQuery(this).attr("class").split(" ")[0]:"");
									jQuery("#font_icon").val(val);
									jQuery(this).siblings( ".active" ).removeClass( "active" );
									jQuery(this).addClass("active");
								});
							});
						</script>					
					'; 
 		}
	
	function navigation_iconset_settings_field($settings, $value) {
			$dependency = '';
			$ret = include("includes/navigation-iconset.php");
			return  '<div class="pw_iconpicker pw_nav_iconset my_param_block">'
					 .'<input name="'.$settings['param_name']
					 .'" class="wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="font_icon" type="hidden" value="'
					 .$value.'" ' . $dependency . '/>'.
					 $ret
					 .'</div>'
					.'<script type="text/javascript">
							jQuery(document).ready(function(){
								if(jQuery("#font_icon").val()!="")
								{
									jQuery(".pl-iconset-cnt").removeClass( "active" );
									jQuery("#"+jQuery("#font_icon").val()).addClass("active");
								}
								
								jQuery(".pw_iconpicker .pl-iconset-cnt").click(function(){
									var val=(jQuery(this).attr("id").split(" ")[0]!="fa-none" ? jQuery(this).attr("id").split(" ")[0]:"");
									jQuery("#font_icon").val(val);
									jQuery(this).siblings( ".active" ).removeClass( "active" );
									jQuery(this).addClass("active");
								});
							});
						</script>					
					'; 
 		}
			
	/*google font param*/	
	function pw_get_googlefonts($selected=''){
			require __PW_PRO_SLIDER_CAROUSEL_ROOT_VC__.'/includes/google-fonts.php';
			$font_options='';
			foreach($fonts_array as $key=>$value){
				$font_options.='<option '.selected($selected,$key,0).' value="'.$key.'">'.$value.'</option>';
			}
			return $font_options;
		}	
	function pw_googlefonts_settings_field($settings, $value){
			$dependency = '';
			
			$param_line='';
			
			$param_line.='
	
			<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-select '
				.$settings['param_name'].' '.$settings['type'].'_field" id="'.$settings['param_name'].'-family">
				<option value="inherit">Inherit</option>
				'.$this->pw_get_googlefonts($value).'
			</select>
			
			<script type="text/javascript">
				function isNumber(n) {
				  return !isNaN(parseFloat(n)) && isFinite(n);
				}
			
				jQuery(document).ready(function(){
					
					if(jQuery("#'.$settings['param_name'].'-family").val()!="inherit")
					{
						jQuery("head").append("<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family="+jQuery("#'.$settings['param_name'].'-family").val()+"\" />");	
						
						var $font_family=jQuery("#'.$settings['param_name'].'-family").val();
						var $font_arr=$font_family.split(":");
						if($font_arr.length>0 && isNumber($font_arr[1]))
						{
							$font_weight=$font_arr[1];
							$font_name=$font_arr[0].replace("+"," ");
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css({"font-family":$font_name,"font-weight":$font_weight});
						}else
						{
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery("#'.$settings['param_name'].'-family").find(":selected").text());
						}
						
						jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery("#'.$settings['param_name'].'-family").find(":selected").text());
					}
					
					jQuery("#'.$settings['param_name'].'-family").change(function(){
						jQuery("head").append("<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family="+jQuery(this).val()+"\" />");	
						
						var $font_family=jQuery(this).val();
						var $font_arr=$font_family.split(":");
						if($font_arr.length>0 && isNumber($font_arr[1]))
						{
							$font_weight=$font_arr[1];
							$font_name=$font_arr[0].replace("+"," ");
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css({"font-family":$font_name,"font-weight":$font_weight});
						}else
						{
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery(this).find(":selected").text());
						}
					});
					
				});
			
			</script>
			<p class="pw-check-font-'.$settings['param_name'].'-family">Grumpy wizards make toxic brew for the evil Queen and Jack.</p>
			
			';
			return $param_line;
		}
	
	//Simple Font Param
	function pw_simple_font_settings_field($settings, $value){
		$dependency='';
		
		$meta=array('inherit',0,"#FFFFFF");
		if($value!='')
		{
			$meta=explode(",",$value);
		}else{
			$value='';
		}
		
		$param_line = '<input name="'.$settings['param_name']
			 .'" class="wpb_vc_param_value wpb-textinput '
			 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="'.$settings['param_name'].'-simple_font" type="hidden" value="'.$value.'" ' . $dependency . '/>
			 <div class="pl-field-cnt">
			 <label>Font Name</label>
			 <select name="'.$settings['param_name'].'" class="'.$settings['param_name'].'-simple_font wpb_vc_param_value wpb-select '
				.$settings['param_name'].' '.$settings['type'].'_field" id="'.$settings['param_name'].'-family" >
				<option value="inherit">Inherit</option>
				'.$this->pw_get_googlefonts($meta[0]).'
			</select>
			</div>
			<div class="pl-field-cnt">
			 <label>Size</label>
			 <input name="'.$settings['param_name']
			 .'" class="'.$settings['param_name'].'-simple_font wpb_vc_param_value wpb-textinput '
			 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="number" value="'.$meta[1].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;" />
			 </div>
			 <div class="pl-field-cnt">
			 <label>Color</label>
			 <input name="'.$settings['param_name']
			 .'" class="'.$settings['param_name'].'-colorpicker-simple_font '.$settings['param_name'].'-simple_font wpb_vc_param_value wpb-textinput '
			 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="text" value="'.$meta[2].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;" data-default-color="#7C7C7C"/>
			 </div>
			 
			<script type="text/javascript">
				function isNumber(n) {
				  return !isNaN(parseFloat(n)) && isFinite(n);
				}
			
				jQuery(document).ready(function(){
					
					if(jQuery("#'.$settings['param_name'].'-family").val()!="inherit")
					{
						jQuery("head").append("<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family="+jQuery("#'.$settings['param_name'].'-family").val()+"\" />");	
						
						var $font_family=jQuery("#'.$settings['param_name'].'-family").val();
						var $font_arr=$font_family.split(":");
						if($font_arr.length>0 && isNumber($font_arr[1]))
						{
							$font_weight=$font_arr[1];
							$font_name=$font_arr[0].replace("+"," ");
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css({"font-family":$font_name,"font-weight":$font_weight});
						}else
						{
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery("#'.$settings['param_name'].'-family").find(":selected").text());
						}
						
						jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery("#'.$settings['param_name'].'-family").find(":selected").text());
					}
					
					jQuery("#'.$settings['param_name'].'-family").change(function(){
						jQuery("head").append("<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family="+jQuery(this).val()+"\" />");	
						
						var $font_family=jQuery(this).val();
						var $font_arr=$font_family.split(":");
						if($font_arr.length>0 && isNumber($font_arr[1]))
						{
							$font_weight=$font_arr[1];
							$font_name=$font_arr[0].replace("+"," ");
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css({"font-family":$font_name,"font-weight":$font_weight});
						}else
						{
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery(this).find(":selected").text());
						}
					});
					
				});
			
			</script>
			<p class="pw-check-font-'.$settings['param_name'].'-family">Grumpy wizards make toxic brew for the evil Queen and Jack.</p>
			
				
			<script type="text/javascript">
			
				var value_font=[];
				jQuery(document).ready(function(){
					
					jQuery(".'.$settings['param_name'].'-colorpicker-simple_font").wpColorPicker({
						change:function(event, ui) {
							
							value_font=[];
							jQuery("#'.$settings['param_name'].'-simple_font").val("");
							
							jQuery(".'.$settings['param_name'].'-simple_font").each(function(){
								
								if(jQuery(this).hasClass("'.$settings['param_name'].'-colorpicker-simple_font"))
									return;
								
								var font_field=jQuery(this).val();
								if(font_field!=""){
									value_font.push(font_field);			
								}else{
									font_field=0;
									value_font.push(font_field);				
								}
							});	
							value_font.push(ui.color.toString());
							var cur_val=value_font.join(",");
							jQuery("#'.$settings['param_name'].'-simple_font").val(cur_val);
						}
					});
					
					jQuery(".'.$settings['param_name'].'-simple_font").live("change",function(){
						value_font=[];
						jQuery("#'.$settings['param_name'].'-simple_font").val("");
						
						jQuery(".'.$settings['param_name'].'-simple_font").each(function(){
							var font_field=jQuery(this).val();
							if(font_field!=""){
								value_font.push(font_field);			
							}else{
								font_field=0;
								value_font.push(font_field);				
							}
						});	
						var cur_val=value_font.join(",");
						jQuery("#'.$settings['param_name'].'-simple_font").val(cur_val);
					});
					
				});
				
			</script>';
			 
			 
			 
		return $param_line;	 
	}
	
	//Advanced Font Param
	function pw_adv_font_settings_field($settings, $value){
		$dependency='';
		
		$meta=array('inherit',0,"#FFFFFF","#CCCCCC");
		if($value!='')
		{
			$meta=explode(",",$value);
		}else{
			$value='';
		}
		
		$param_line = '<input name="'.$settings['param_name']
			 .'" class="wpb_vc_param_value wpb-textinput '
			 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="'.$settings['param_name'].'-adv_font" type="hidden" value="'.$value.'" ' . $dependency . '/>
			 <div class="pl-field-cnt">
			 <label>Font Name</label>
			 <select name="'.$settings['param_name'].'" class="'.$settings['param_name'].'-adv_font wpb_vc_param_value wpb-select '
				.$settings['param_name'].' '.$settings['type'].'_field" id="'.$settings['param_name'].'-family">
				<option value="inherit">Inherit</option>
				'.$this->pw_get_googlefonts($meta[0]).'
			</select>
			</div>
			<div class="pl-field-cnt">
			 <label>Size</label>
			 <input name="'.$settings['param_name']
			 .'" class="'.$settings['param_name'].'-adv_font wpb_vc_param_value wpb-textinput '
			 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="number" value="'.$meta[1].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;" />
			 </div>
			 <div class="pl-field-cnt">
			 <label>Color</label>
			 <input name="'.$settings['param_name']
			 .'" class="main_color '.$settings['param_name'].'-colorpicker-adv_font '.$settings['param_name'].'-adv_font wpb_vc_param_value wpb-textinput '
			 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="text" value="'.$meta[2].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;" data-default-color="#7C7C7C"/>
			 </div>
			 <div class="pl-field-cnt">
			 <label>Hover Color</label>
			 <input name="'.$settings['param_name']
			 .'" class="hover_color '.$settings['param_name'].'-colorpicker-adv_font '.$settings['param_name'].'-adv_font wpb_vc_param_value wpb-textinput '
			 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="text" value="'.$meta[3].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;" data-default-color="#7C7C7C"/>
			</div>
			
			
			<script type="text/javascript">
				function isNumber(n) {
				  return !isNaN(parseFloat(n)) && isFinite(n);
				}
			
				jQuery(document).ready(function(){
					
					if(jQuery("#'.$settings['param_name'].'-family").val()!="inherit")
					{
						jQuery("head").append("<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family="+jQuery("#'.$settings['param_name'].'-family").val()+"\" />");	
						
						var $font_family=jQuery("#'.$settings['param_name'].'-family").val();
						var $font_arr=$font_family.split(":");
						if($font_arr.length>0 && isNumber($font_arr[1]))
						{
							$font_weight=$font_arr[1];
							$font_name=$font_arr[0].replace("+"," ");
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css({"font-family":$font_name,"font-weight":$font_weight});
						}else
						{
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery("#'.$settings['param_name'].'-family").find(":selected").text());
						}
						
						jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery("#'.$settings['param_name'].'-family").find(":selected").text());
					}
					
					jQuery("#'.$settings['param_name'].'-family").change(function(){
						jQuery("head").append("<link rel=\"stylesheet\" href=\"http://fonts.googleapis.com/css?family="+jQuery(this).val()+"\" />");	
						
						var $font_family=jQuery(this).val();
						var $font_arr=$font_family.split(":");
						if($font_arr.length>0 && isNumber($font_arr[1]))
						{
							$font_weight=$font_arr[1];
							$font_name=$font_arr[0].replace("+"," ");
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css({"font-family":$font_name,"font-weight":$font_weight});
						}else
						{
							jQuery(".pw-check-font-'.$settings['param_name'].'-family").css("font-family",jQuery(this).find(":selected").text());
						}
					});
					
				});
			
			</script>
			<p class="pw-check-font-'.$settings['param_name'].'-family">Grumpy wizards make toxic brew for the evil Queen and Jack.</p>
			
				
			<script type="text/javascript">
			
				var value_font=[];
				jQuery(document).ready(function(){
					
					jQuery(".'.$settings['param_name'].'-colorpicker-adv_font").wpColorPicker({
						change:function(event, ui) {
							
							value_font=[];
							jQuery("#'.$settings['param_name'].'-adv_font").val("");
							
							jQuery(".'.$settings['param_name'].'-adv_font").each(function(){
								
								
								var font_field=jQuery(this).val();
								if(font_field!=""){
									value_font.push(font_field);			
								}else{
									font_field=0;
									value_font.push(font_field);				
								}
							});	
							
							if(jQuery(this).hasClass("main_color"))
								value_font.splice(2, 0, ui.color.toString());	
								
							if(jQuery(this).hasClass("hover_color"))
								value_font.splice(3, 0, ui.color.toString());	
							
							//value_font.push(ui.color.toString());
							var cur_val=value_font.join(",");
							jQuery("#'.$settings['param_name'].'-adv_font").val(cur_val);
						}
					});
					
					jQuery(".'.$settings['param_name'].'-adv_font").live("change",function(){
						value_font=[];
						jQuery("#'.$settings['param_name'].'-adv_font").val("");
						
						jQuery(".'.$settings['param_name'].'-adv_font").each(function(){
							var font_field=jQuery(this).val();
							if(font_field!=""){
								value_font.push(font_field);			
							}else{
								font_field=0;
								value_font.push(font_field);				
							}
						});	
						var cur_val=value_font.join(",");
						jQuery("#'.$settings['param_name'].'-adv_font").val(cur_val);
					});
					
				});
				
			</script>';
			 
			 
			 
		return $param_line;	 
	}
	
	//Border Setting Patam
	function pw_border_settings_field($settings, $value){
			$dependency='';
			
			$meta=array(0,0,0,0,'dashed',"#dddddd");
			if($value!='')
			{
				$meta=explode(",",$value);
			}else{
				$value='';
			}
			
			$param_line = '<input name="'.$settings['param_name']
				 .'" class="wpb_vc_param_value wpb-textinput '
				 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" id="'.$settings['param_name'].'-border-option" type="hidden" value="'.$value.'" ' . $dependency . '/>
				 <div class="pl-field-cnt pl-half-four-field">
					 <label>Top</label>
					 <input name="'.$settings['param_name']
					 .'" class="'.$settings['param_name'].'-border-element wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="number" value="'.$meta[0].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;" />
				 </div>			 
				 <div class="pl-field-cnt pl-half-four-field">
					 <label>Right</label>
					 <input name="'.$settings['param_name']
					 .'" class="'.$settings['param_name'].'-border-element wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="number" value="'.$meta[1].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;" />
				 </div>
				 <div class="pl-field-cnt pl-half-four-field">
					 <label>Bottom</label>
					 <input name="'.$settings['param_name']
					 .'" class="'.$settings['param_name'].'-border-element wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="number" value="'.$meta[2].'" ' . $dependency . '  style="max-width:50px; margin-right: 10px;" />
				 </div>
				 <div class="pl-field-cnt pl-half-four-field">
					 <label>Left</label>
					 <input name="'.$settings['param_name']
					 .'" class="'.$settings['param_name'].'-border-element wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="number" value="'.$meta[3].'" ' . $dependency . ' style="max-width:50px; margin-right: 10px;"  />
				 </div>
				 <div class="pl-field-cnt">
					 <label>Type</label>
					 <select name="'.$settings['param_name'].'" class="'.$settings['param_name'].'-border-element wpb_vc_param_value wpb-select '
					 .$settings['param_name'].' '.$settings['type'].'_field" id="'.$settings['param_name'].'-type" >
					 <option value="dashed" "'.selected("dashed",$meta[4],0).'">Dashed</option>
					 <option value="solid" "'.selected("solid",$meta[4],0).'">Solid</option>
					 <option value="dotted" "'.selected("dotted",$meta[4],0).'">Dotted</option>
					 <option value="double" "'.selected("double",$meta[4],0).'">Double</option>
					 <option value="thin" "'.selected("thin",$meta[4],0).'">Thin</option>
					</select>
				 </div>
				 <div class="pl-field-cnt">
					 <label>Color</label>
					 <input name="'.$settings['param_name']
					 .'" class="'.$settings['param_name'].'-colorpicker-border '.$settings['param_name'].'-border-element wpb_vc_param_value wpb-textinput '
					 .$settings['param_name'].' '.$settings['type'].' '.$settings['class'].'" type="text" value="'.$meta[5].'" ' . $dependency . '  data-default-color="#7C7C7C"/>
				 </div>
				 
				 
				<script type="text/javascript">
				
					var value_font=[];
					jQuery(document).ready(function(){
						
						jQuery(".'.$settings['param_name'].'-colorpicker-border").wpColorPicker({
							change:function(event, ui) {
								
								value_border=[];
								jQuery("#'.$settings['param_name'].'-border-option").val("");
								
								jQuery(".'.$settings['param_name'].'-border-element").each(function(){
									if(jQuery(this).hasClass("'.$settings['param_name'].'-colorpicker-border"))
										return;
										
									var border_field=jQuery(this).val();
									if(border_field!=""){
										value_border.push(border_field);			
									}else{
										border_field=0;
										value_border.push(border_field);				
									}
								});
								value_border.push(ui.color.toString());
								var cur_val=value_border.join(",");
								jQuery("#'.$settings['param_name'].'-border-option").val(cur_val);
							}
						});
						
						jQuery(".'.$settings['param_name'].'-border-element").change(function(){
							value_border=[];
							jQuery("#'.$settings['param_name'].'-border-option").val("");
							
							jQuery(".'.$settings['param_name'].'-border-element").each(function(){
								var border_field=jQuery(this).val();
								if(border_field!=""){
									value_border.push(border_field);			
								}else{
									border_field=0;
									value_border.push(border_field);				
								}
							});	
							var cur_val=value_border.join(",");
							jQuery("#'.$settings['param_name'].'-border-option").val(cur_val);
						});
						
					});
					
				</script>';
				 
				 
				 
			return $param_line;	 
		}
		
		
	public function includes()	{

		require_once( 'class/pro_slider_layout.php');
		require_once( 'class/pro_carousel_layout.php');
	}
	
	function pw_pro_slider_carousel_shortcodes_add_scripts() {
		if(!is_admin()) {
			/*Public Styles*/
			wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_fontawesome', plugin_dir_url_pw_pro_slider_carousel.'/css/fontawesome/font-awesome.css');
			wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_public', plugin_dir_url_pw_pro_slider_carousel.'/css/public.css');
			
			wp_register_style( __PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_animate', plugin_dir_url_pw_pro_slider_carousel.'/css/animation/animate.css', false, '1.0.0' );
			wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_animate');
						
			wp_register_style( __PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_owlcarousel', plugin_dir_url_pw_pro_slider_carousel.'/css/owl/owl.carousel.css', false, '1.0.0' );
			wp_register_style( __PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_smoothslides', plugin_dir_url_pw_pro_slider_carousel.'/css/smoothslides/smoothslides.theme.css', false, '1.0.0' );
			wp_register_style( __PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_swiper', plugin_dir_url_pw_pro_slider_carousel.'/css/swiper/swiper.css', false, '1.0.0' );
			
			wp_register_style( __PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_slider_carousel_rtl', plugin_dir_url_pw_pro_slider_carousel.'/css/rtl/slider-carousel-rtl.css', false, '1.0.0' );
			
			wp_register_style( __PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_slider_tab', plugin_dir_url_pw_pro_slider_carousel.'/css/tab/tabs.css', false, '1.0.0' );
			
			
			wp_register_style( __PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tipsy', plugin_dir_url_pw_pro_slider_carousel.'/css/tipsy/tipsy.css', false, '1.0.0' );
			
			wp_enqueue_style(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_vs_custom_css', plugin_dir_url_pw_pro_slider_carousel.'/css/custom-css.css');
			
			/*SCRIPTS*/
			wp_enqueue_script('jquery');
			
			/* Scripts */
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_wow', plugin_dir_url_pw_pro_slider_carousel.'js/viewport-checker/wow.js', 'jquery');
			
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_scroller', plugin_dir_url_pw_pro_slider_carousel.'js/scroller/jquery.slimscroll.js', 'jquery');
			
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_owlcarousel', plugin_dir_url_pw_pro_slider_carousel.'js/owl/owl.carousel.js', 'jquery');
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_smoothslides', plugin_dir_url_pw_pro_slider_carousel.'js/smoothslides/smoothslides-2.2.1.js', 'jquery');
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_swiper', plugin_dir_url_pw_pro_slider_carousel.'js/swiper/swiper.jquery.js', 'jquery');

			
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tab', plugin_dir_url_pw_pro_slider_carousel.'js/tab/cbpFWTabs.js', 'jquery');
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tab_modernizer', plugin_dir_url_pw_pro_slider_carousel.'js/tab/modernizr.custom.js', 'jquery');
			
			wp_register_script(__PW_PRO_SLIDER_CAROUSEL_FIELDS_PERFIX__.'pw_pl_tipsy', plugin_dir_url_pw_pro_slider_carousel.'js/tipsy/jquery.tipsy.js', 'jquery');
			
			
		}else
		{
		}
	}	
	
	
	function pw_slider_carousel_presets() {
		//Carousel Layouts
		do_action( 'vc_register_settings_preset', 'Carousel Layout 1', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'overlay_color' => '#f4f4f4' ,
				'slider_image_size' => 'pw_hor_image',
				'image_hover' => 'pl-zoomin' ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 3 ,
				'item_tablet' => 2 ,
				'item_margin' => 10 ,
				'show_navigation' => true ,
				'navigation_position' => 'pl-slider-nav-topright' ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'show_dot' => true ,
				'dot_layout' => 'pl-slider-dot-layout4' ,
				'slider_title_font' => 'inherit,18,#565656,#424242,#424242' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'inherit,11,#b2b2b2,#b2b2b2,#b2b2b2' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'navigation_icon' => 'fa-long-arrow-left'
				), false ); 
		do_action( 'vc_register_settings_preset', 'Carousel Layout 2', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-outerdesc-layout3' ,
				'overlay_color' => '#ffffff' ,
				'slider_image_size' => 'pw_hor_image' ,
				'image_hover' => 'pl-zoomin' ,
				'slider_autoplay' => true ,
				'slider_speed' => 5000 ,
				'slider_loop' => true ,
				'item_desktop' => 4 ,
				'item_tablet' => 2 ,
				'item_margin' => 10 ,
				'show_navigation' => true ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'navigation_on_hover' => 'pl-slider-nav-hover-show' ,
				'slider_title_font' => 'Raleway:300,18,#565656,#424242,#424242' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Raleway:300,11,#b2b2b2,#b2b2b2,#b2b2b2' ,
				'slider_show_author' => 'yes' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'custom_class' => 'bordered-layout2' ,
				'navigation_icon' => 'fa-long-arrow-left'
				), false ); 		
		do_action( 'vc_register_settings_preset', 'Carousel Layout 3', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-outerdesc-layout4' ,
				'overlay_color' => '#3f3f3f' ,
				'slider_image_size' => 'pw_rec_image' ,
				'image_hover' => 'pl-grayscale' ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 5 ,
				'item_tablet' => 2 ,
				'item_margin' => 10 ,
				'show_navigation' => true ,
				'navigation_position' => 'pl-slider-nav-topright' ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'show_dot' => true ,
				'dot_layout' => 'pl-slider-dot-layout7' ,
				'lazy_load' => true ,
				'slider_title_font' => 'Josefin+Slab:700,14,#f2f2f2,#e0e0e0' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Josefin+Sans:100,11,#f4f4f4,#f7f7f7' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'slider_show_excerpt' => 'yes' ,
				'slider_excerpt_font' => 'Josefin+Sans:100,15,#FFFFFF' ,
				'slider_show_button' => 'yes' ,
				'slider_button_font' => 'inherit,13,#FFFFFF,#CCCCCC' ,
				'custom_class' => 'bordered-layout3' ,
				'navigation_icon' => 'fa-arrow-left' 
				), false ); 	
		do_action( 'vc_register_settings_preset', 'Carousel Layout 4', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-boxed-layout1' ,
				'overlay_color' => 'rgba(33,33,33,0.82)' ,
				'slider_image_size' => 'pw_ver_image' ,
				'image_hover' => 'pl-zoomin' ,
				'slider_autoplay' => true ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 4 ,
				'item_tablet' => 2 ,
				'show_navigation' => true ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'navigation_fill' => 'pl-slider-nav-bordered' ,
				'show_dot' => true ,
				'slider_title_font' => 'Raleway:900,20,#ffffff,#ffffff' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Raleway:300,11,#efefef,#ededed,#ededed' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'slider_show_button' => 'yes' ,
				'slider_button_font' => 'Raleway:300,15,#FFFFFF,#f4f4f4,#f4f4f4' ,
				'slider_btn_txt' => 'READ MORE' ,
				'navigation_icon' => 'fa-chevron-left' 
				), false ); 
		do_action( 'vc_register_settings_preset', 'Carousel Layout 5', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-boxed-layout2' ,
				'overlay_color' => 'rgba(0,0,0,0.35)' ,
				'slider_image_size' => 'pw_rec_image' ,
				'image_hover' => 'pl-zoomin-long' ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 4 ,
				'item_tablet' => 2 ,
				'show_navigation' => true ,
				'navigation_position' => 'pl-slider-nav-topright' ,
				'navigation_fill' => 'pl-slider-nav-bordered' ,
				'show_dot' => true ,
				'dot_position' => 'pl-slider-dot-over-bottom-center' ,
				'dot_layout' => 'pl-slider-dot-layout5' ,
				'lazy_load' => true ,
				'slider_title_font' => 'Advent+Pro:600,25,#ffffff,#f4f4f4' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Advent+Pro:300,11,#ffffff,#ffffff' ,
				'slider_show_category' => 'yes' ,
				'slider_cat_number' => 2 ,
				'navigation_icon' => 'fa-long-arrow-left' 
				), false );
		do_action( 'vc_register_settings_preset', 'Carousel Layout 6', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-boxed-layout3' ,
				'overlay_color' => '#23cbdd' ,
				'slider_image_size' => 'pw_hor_image' ,
				'image_hover' => 'pl-zoomin' ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 3 ,
				'item_tablet' => 2 ,
				'item_margin' => 10 ,
				'show_navigation' => true ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'show_dot' => true ,
				'dot_layout' => 'pl-slider-dot-layout7' ,
				'slider_title_font' => 'inherit,20,#ffffff,#ffffff' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'inherit,11,#ffffff,#ffffff,#ffffff' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'slider_show_excerpt' => 'yes' ,
				'slider_excerpt_font' => 'inherit,14,#FFFFFF' ,
				'navigation_icon' => 'fa-arrow-left'
				 ), false );
		 do_action( 'vc_register_settings_preset', 'Carousel Layout 7', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_type' => 'pl-swiper' ,
				'slider_layout' => 'pl-boxed-layout4' ,
				'swiper_slider_effect' => 'coverflow' ,
				'overlay_color' => 'rgba(255,255,255,0.83)' ,
				'slider_image_size' => 'pw_rec_image' ,
				'image_hover' => 'pl-zoomin' ,
				'slider_autoplay' => true ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 3 ,
				'item_tablet' => 2 ,
				'item_margin' => 10 ,
				'show_navigation' => true ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'navigation_on_hover' => 'pl-slider-nav-hover-show' ,
				'show_dot' => true ,
				'dot_position' => 'pl-slider-dot-over-bottom-center' ,
				'dot_type' => 'fraction' ,
				'dot_layout' => 'pl-slider-dot-layout2' ,
				'lazy_load' => true ,
				'slider_title_font' => 'Raleway:900,18,#565656,#424242,#424242' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Raleway:300,11,#727272,#565656,#595959' ,
				'slider_show_author' => 'yes' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'navigation_icon' => 'fa-arrow-left'
				), false );	
		do_action( 'vc_register_settings_preset', 'Carousel Layout 8', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-boxed-layout5' ,
				'overlay_color' => '#f4f4f4' ,
				'slider_image_size' => 'pw_ver_image' ,
				'image_hover' => 'pl-zoomin' ,
				'slider_autoplay' => true ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 4 ,
				'item_tablet' => 3 ,
				'item_margin' => 10 ,
				'show_navigation' => true ,
				'navigation_position' => 'pl-slider-nav-topright' ,
				'navigation_fill' => 'pl-slider-nav-bordered' ,
				'show_dot' => true ,
				'dot_position' => 'pl-slider-dot-bottom-left' ,
				'dot_layout' => 'pl-slider-dot-layout4' ,
				'show_play_pause' => 'pl-slider-show-btn' ,
				'play_pause_position' => 'pl-slider-btn-bottomright' ,
				'play_pause_fill' => 'pl-slider-btn-none' ,
				'play_pause_on_hover' => 'pl-slider-btn-hover-show' ,
				'lazy_load' => true ,
				'slider_title_font' => 'Playball,20,#ffffff,#ffffff' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Playball,11,#ffffff,#efefef,#ededed' ,
				'slider_show_author' => 'yes' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'navigation_icon' => 'fa-hand-o-left' 
				), false );
		do_action( 'vc_register_settings_preset', 'Carousel Layout 9', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-boxed-layout6' ,
				'overlay_color' => 'rgba(129,215,66,0.29)' ,
				'slider_image_size' => 'pw_hor_image' ,
				'image_hover' => 'pl-zoomin' ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 3 ,
				'item_tablet' => 2 ,
				'show_navigation' => true ,
				'navigation_position' => 'pl-slider-nav-topright' ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'show_dot' => true ,
				'dot_layout' => 'pl-slider-dot-layout4' ,
				'dot_position' => 'pl-slider-dot-over-bottom-left' ,
				'slider_title_font' => 'Raleway:900,20,#ffffff,#ffffff' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Raleway:300,11,#ffffff,#ffffff,#ffffff' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'navigation_icon' => 'fa-long-arrow-left' 
				), false );
		do_action( 'vc_register_settings_preset', 'Carousel Layout 10', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-boxed-layout7' ,
				'overlay_color' => 'rgba(129,215,66,0.29)' ,
				'slider_image_size' => 'pw_ver_image' ,
				'image_hover' => 'pl-zoomin' ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 3 ,
				'item_tablet' => 3 ,
				'show_navigation' => true ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'show_dot' => true ,
				'dot_layout' => 'pl-slider-dot-layout5' ,
				'lazy_load' => true ,
				'slider_title_font' => 'Raleway:900,20,#ffffff,#ffffff,#ffffff' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Raleway:300,11,#ffffff,#ffffff,#ffffff' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'navigation_icon' => 'fa-long-arrow-left' 
				), false );
		do_action( 'vc_register_settings_preset', 'Carousel Layout 11', 'pw_VC_pro_carousel_layout_shortcode', 
				array ( 
				'slider_layout' => 'pl-boxed-layout8' ,
				'overlay_color' => 'rgba(0,0,0,0.73)' ,
				'slider_image_size' => 'pw_rec_image' ,
				'image_hover' => 'pl-grayscale' ,
				'slider_speed' => 3000 ,
				'slider_loop' => true ,
				'item_desktop' => 5 ,
				'item_tablet' => 3 ,
				'show_navigation' => true ,
				'navigation_shape' => 'pl-slider-nav-squar' ,
				'navigation_on_hover' => 'pl-slider-nav-hover-show' ,
				'show_dot' => true ,
				'dot_layout' => 'pl-slider-dot-layout2' ,
				'lazy_load' => true ,
				'slider_title_font' => 'inherit,17,#ffffff,#ffffff,#ffffff' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'inherit,11,#ffffff,#ffffff,#ffffff' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'navigation_icon' => 'fa-angle-double-left' 
				), false );
				
		/*Sliders Layout*/		
		do_action( 'vc_register_settings_preset', 'Simple Slider Layout 1', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
				'heading_layout' => 'pl-slider-layout2' ,
				'overlay_color' => 'rgba(0,0,0,0.76)' ,
				'slider_image_size' => 'pw_hor2_image' ,
				'slider_autoplay' => true ,
				'slider_speed' => 3000 ,
				'show_navigation' => true ,
				'navigation_position' => 'pl-slider-nav-topright' ,
				'show_dot' => true ,
				'dot_position' => 'pl-slider-dot-over-bottom-center' ,
				'dot_layout' => 'pl-slider-dot-layout7' ,
				'lazy_load' => true ,
				'slider_in_animation' => 'flipInX' ,
				'slider_out_animation' => 'flipOutX' ,
				'slider_title_font' => 'Playfair+Display:regular,20,#ffffff,#d63131' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Playfair+Display:regular,13,#ffffff,#d63131' ,
				'slider_show_author' => 'yes' ,
				'slider_show_comments' => 'yes' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'slider_show_excerpt' => 'yes' ,
				'slider_excerpt_font' => 'Playfair+Display:regular,13,#FFFFFF' ,
				'multi_slider_layout' => 'pl-multi-pt5' ,
				'navigation_icon' => 'fa-chevron-left' 
				), false );
		do_action( 'vc_register_settings_preset', 'Simple Slider Layout 2', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_layout' => 'pl-slider-layout2' ,
			'overlay_color' => 'rgba(129,215,66,0.71)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_shape' => 'pl-slider-nav-squar' ,
			'navigation_fill' => 'pl-slider-nav-bordered' ,
			'navigation_on_hover' => 'pl-slider-nav-hover-show' ,
			'show_dot' => true ,
			'dot_position' => 'pl-slider-dot-over-bottom-center' ,
			'dot_layout' => 'pl-slider-dot-layout2' ,
			'lazy_load' => true ,
			'slider_in_animation' => 'flipInY' ,
			'slider_out_animation' => 'flipOutY' ,
			'slider_title_font' => 'inherit,27,#ffffff,#f7f7f7,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'inherit,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-long-arrow-left' 
			), false );
	do_action( 'vc_register_settings_preset', 'Simple Slider Layout 3', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_layout' => 'pl-slider-layout3' ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'show_dot' => true ,
			'dot_position' => 'pl-slider-dot-over-bottom-center' ,
			'dot_layout' => 'pl-slider-dot-layout7' ,
			'lazy_load' => true ,
			'slider_in_animation' => 'flipInX' ,
			'slider_out_animation' => 'flipOutX' ,
			'slider_title_font' => 'Righteous,27,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'inherit,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-angle-left' 
			), false );
	do_action( 'vc_register_settings_preset', 'Simple Slider Layout 4', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_layout' => 'pl-slider-layout4' ,
			'overlay_color' => 'rgba(37,193,232,0.78)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-topleft' ,
			'navigation_shape' => 'pl-slider-nav-squar' ,
			'show_thumbnail' => 'pl-slider-show-thumb' ,
			'thumbnail_position' => 'pl-slider-thumb-over-bottom-center' ,
			'thumbnail_tooltip' => true ,
			'slider_title_font' => 'Raleway:900,27,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Raleway:300,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-angle-left' 
			), false );
	do_action( 'vc_register_settings_preset', 'Simple Slider Layout 5', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'slider_layout' => 'pl-slider-layout5' ,
			'overlay_color' => 'rgba(0,0,0,0.78)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_autoplay' => true ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_fill' => 'pl-slider-nav-bordered' ,
			'navigation_on_hover' => 'pl-slider-nav-hover-show' ,
			'show_dot' => true ,
			'dot_position' => 'pl-slider-dot-over-bottom-center' ,
			'dot_layout' => 'pl-slider-dot-layout4' ,
			'lazy_load' => true ,
			'slider_title_font' => 'Playball,25,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Playball,13,#ffffff,#eaeaea' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'navigation_icon' => 'fa-long-arrow-left' 
			), false );
	do_action( 'vc_register_settings_preset', 'Simple Slider Layout 6', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_layout' => 'pl-slider-layout6' ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_autoplay' => true ,
			'slider_speed' => 3000 ,
			'slider_loop' => true ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-topright' ,
			'navigation_shape' => 'pl-slider-nav-squar' ,
			'show_dot' => true ,
			'lazy_load' => true ,
			'slider_in_animation' => 'bounceIn' ,
			'slider_out_animation' => 'bounceOutUp' ,
			'slider_title_font' => 'Titillium+Web:900,27,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'inherit,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'slider_show_excerpt' => 'yes' ,
			'slider_excerpt_font' => 'inherit,15,#FFFFFF' ,
			'slider_excerpt_len' => 20 ,
			'slider_show_button' => 'yes' ,
			'slider_button_font' => 'Titillium+Web:600,17,#ffffff,#eaeaea' ,
			'slider_btn_txt' => 'Continue Reading' ,
			'navigation_icon' => 'fa-arrow-left' 
			), false );
	do_action( 'vc_register_settings_preset', 'Simple Slider Layout 7', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_layout' => 'pl-slider-layout7' ,
			'overlay_color' => 'rgba(38,38,38,0.8)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'slider_loop' => true ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-topright' ,
			'navigation_fill' => 'pl-slider-nav-bordered' ,
			'show_dot' => true ,
			'dot_position' => 'pl-slider-dot-over-bottom-right' ,
			'dot_layout' => 'pl-slider-dot-layout5' ,
			'show_play_pause' => 'pl-slider-show-btn' ,
			'play_pause_position' => 'pl-slider-btn-bottomleft',
			'play_pause_fill' => 'pl-slider-btn-none' ,
			'play_pause_on_hover' => 'pl-slider-btn-hover-show' ,
			'lazy_load' => true ,
			'slider_in_animation' => 'flipInY' ,
			'slider_out_animation' => 'flipOutY' ,
			'slider_title_font' => 'Raleway:900,20,#ffffff,#30cbd3' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Raleway:300,13,#ffffff,#30cbd3,#34d6e5' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_cat_number' => 2 ,
			'slider_show_date' => 'yes' ,
			'slider_show_excerpt' => 'yes' ,
			'slider_excerpt_font' => 'Raleway:300,15,#FFFFFF' ,
			'slider_excerpt_len' => 20 ,
			'slider_show_button' => 'yes' ,
			'slider_button_font' => 'Righteous,18,#ffffff,#30cbd3' ,
			'slider_btn_txt' => 'Read More' ,
			'navigation_icon' => 'fa-caret-left' 
			), false );
		
		do_action( 'vc_register_settings_preset', 'Simple Slider Flip Effect', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
				'heading_layout' => 'pl-slider-layout2' ,
				'slider_layout' => 'pl-slider-layout5' ,
				'slider_type' => 'pl-swiper',
				'swiper_slider_effect' => 'flip',
				'overlay_color' => 'rgba(10,10,10,0.34)' ,
				'slider_image_size' => 'pw_rec_image' ,
				'slider_autoplay' => true ,
				'slider_speed' => 3000 ,
				'show_navigation' => true ,
				'navigation_position' => 'pl-slider-nav-topright' ,
				'lazy_load' => true ,
				'slider_title_font' => 'Righteous,13,#ffffff,#f7f7f7' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Raleway:300,11,#ffffff,#ffffff,#ffffff' ,
				'slider_show_category' => 'yes' ,
				'slider_show_date' => 'yes' ,
				'multi_slider_layout' => 'pl-multi-pt5' ,
				'navigation_icon' => 'fa-arrow-left' 
				), false );
			
		
			do_action( 'vc_register_settings_preset', 'Simple Slider cube Effect', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
				'heading_layout' => 'pl-slider-layout2' ,
				'slider_layout' => 'pl-slider-layout3' ,
				'slider_type' => 'pl-swiper',
				'swiper_slider_effect' => 'cube',
				'overlay_color' => 'rgba(0,0,0,0.4)' ,
				'slider_image_size' => 'pw_rec_image' ,
				'slider_autoplay' => true ,
				'slider_speed' => 2500 ,
				'show_navigation' => true ,
				'navigation_on_hover' => 'pl-slider-nav-hover-show',
				'lazy_load' => true ,
				'slider_title_font' => 'Raleway:900,13,#FFFFFF,#ffffff,#ffffff' ,
				'slider_show_meta' => 'yes' ,
				'slider_meta_font' => 'Raleway:300,11,#FFFFFF,#f2f2f2,#f4f4f4' ,
				'slider_show_category' => 'yes' ,
				'multi_slider_layout' => 'pl-multi-pt5' ,
				'navigation_icon' => 'fa-long-arrow-left' 
				), false );
		//Multi Slider
		do_action( 'vc_register_settings_preset', 'Multi Slider Layout 1', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_mode' => 'pl-multi-slider' ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'show_dot' => true ,
			'dot_layout' => 'pl-slider-dot-layout4' ,
			'lazy_load' => true ,
			'slider_in_animation' => 'flipInX' ,
			'slider_out_animation' => 'flipOutX' ,
			'slider_title_font' => 'inherit,20,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'inherit,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-long-arrow-left' 
			), false );
		do_action( 'vc_register_settings_preset', 'Multi Slider Layout 2', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_mode' => 'pl-multi-slider' ,
			'multi_slider_pattern' => 'pl-multi-slider-pt2' ,
			'slider_height' => 530 ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-topleft' ,
			'navigation_shape' => 'pl-slider-nav-squar' ,
			'navigation_fill' => 'pl-slider-nav-bordered' ,
			'lazy_load' => true ,
			'slider_title_font' => 'inherit,20,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'inherit,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-angle-left' 
			), false );
		do_action( 'vc_register_settings_preset', 'Multi Slider Layout 3', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_mode' => 'pl-multi-slider' ,
			'multi_slider_pattern' => 'pl-multi-slider-pt3' ,
			'slider_height' => 530 ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-bottomright' ,
			'show_dot' => true ,
			'dot_layout' => 'pl-slider-dot-layout4' ,
			'lazy_load' => true ,
			'slider_title_font' => 'Righteous,27,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Raleway:300,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-angle-left' 
			), false );
		do_action( 'vc_register_settings_preset', 'Multi Slider Layout 4', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_mode' => 'pl-multi-slider' ,
			'multi_slider_pattern' => 'pl-multi-slider-pt4' ,
			'slider_height' => 530 ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'show_dot' => true ,
			'dot_position' =>'pl-slider-dot-over-bottom-center',
			'dot_layout' => 'pl-slider-dot-layout7' ,
			'lazy_load' => true ,
			'slider_title_font' => 'Raleway:900,20,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Raleway:300,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-angle-left' 
			), false );
		do_action( 'vc_register_settings_preset', 'Multi Slider Layout 5', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_mode' => 'pl-multi-slider' ,
			'multi_slider_pattern' => 'pl-multi-slider-pt5' ,
			'slider_height' => 530 ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-topleft',
			'navigation_shape' => 'pl-slider-nav-squar' ,
			'navigation_fill' => 'pl-slider-nav-bordered' ,
			'show_dot' => true ,
			'dot_position' =>' pl-slider-dot-bottom-left',
			'dot_layout' => 'pl-slider-dot-layout2' ,
			'lazy_load' => true ,
			'slider_title_font' => 'Habibi,25,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Habibi,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-arrow-circle-left' 
			), false );	
		do_action( 'vc_register_settings_preset', 'Multi Slider Layout 6', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_mode' => 'pl-multi-slider' ,
			'multi_slider_pattern' => 'pl-multi-slider-pt6' ,
			'slider_height' => 450 ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-topleft',
			'navigation_fill' => 'pl-slider-nav-bordered' ,
			'show_dot' => true ,
			'lazy_load' => true ,
			'slider_title_font' => 'Raleway:900,25,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Raleway:300,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-arrow-circle-left' 
			), false );	
		do_action( 'vc_register_settings_preset', 'Multi Slider Layout 7', 'pw_VC_pro_slider_layout_shortcode',
			array ( 
			'heading_layout' => 'pl-slider-layout2' ,
			'slider_mode' => 'pl-multi-slider' ,
			'multi_slider_pattern' => 'pl-multi-slider-pt7' ,
			'slider_height' => 450 ,
			'slider_autoplay' => true ,
			'overlay_color' => 'rgba(0,0,0,0.21)' ,
			'slider_image_size' => 'pw_hor2_image' ,
			'slider_speed' => 3000 ,
			'show_navigation' => true ,
			'navigation_position' => 'pl-slider-nav-topleft',
			'navigation_on_hover' => 'pl-slider-nav-hover-show',
			'show_dot' => true ,
			'dot_layout' => 'pl-slider-dot-layout4',
			'lazy_load' => true ,
			'slider_in_animation' => 'zoomIn' ,
			'slider_out_animation' => 'zoomOut',
			'slider_title_font' => 'Raleway:900,25,#ffffff,#f7f7f7' ,
			'slider_show_meta' => 'yes' ,
			'slider_meta_font' => 'Raleway:300,13,#ffffff,#eaeaea,#e8e8e8' ,
			'slider_show_author' => 'yes' ,
			'slider_show_comments' => 'yes' ,
			'slider_show_category' => 'yes' ,
			'slider_show_date' => 'yes' ,
			'slider_show_excerpt' => 'yes',
			'slider_excerpt_font' => 'Raleway:300,15,#FFFFFF',
			'multi_slider_layout' => 'pl-multi-pt5' ,
			'navigation_icon' => 'fa-angle-double-left' 
			), false );	
			
	}

}
new pw_pro_slider_carousel_plugin();
?>