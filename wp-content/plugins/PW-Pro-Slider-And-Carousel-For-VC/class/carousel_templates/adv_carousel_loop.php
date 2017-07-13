<?php 
/*Query  Builder*/
$paged = 1;
$query=$pw_query;
$query=explode('|',$query);

$query_posts_per_page=10;
$query_post_type='post';
$query_meta_key='';
$query_orderby='date';
$query_order='ASC';

$query_by_id='';
$query_by_id_not_in='';
$query_by_id_in='';

$query_categories='';
$query_cat_not_in='';
$query_cat_in='';

$query_tags='';
$query_tags_in='';
$query_tags_not_in='';

$query_author='';
$query_author_in='';
$query_author_not_in='';

$query_tax_query='';

foreach($query as $query_part)
{
	$q_part=explode(':',$query_part);
	switch($q_part[0])
	{
		case 'post_type':
			$query_post_type=explode(',',$q_part[1]);
		break;
		
		case 'size':
			$query_posts_per_page=($q_part[1]=='All' ? -1:$q_part[1]);
		break;
		
		case 'order_by':
			
			$query_meta_key='';
			$query_orderby='';
			
			$public_orders_array=array('ID','date','author','title','modified','rand','comment_count','menu_order');
			if(in_array($q_part[1],$public_orders_array))
			{
				$query_orderby=$q_part[1];
			}else
			{
				$query_meta_key=$q_part[1];
				$query_orderby='meta_value_num';
			}
			
		break;
		
		case 'order':
			$query_order=$q_part[1];
		break;
		
		case 'by_id':
			$query_by_id=explode(',',$q_part[1]);
			$query_by_id_not_in=array();
			$query_by_id_in=array();
			foreach($query_by_id as $ids)
			{
				if($ids<0)
				{
					$query_by_id_not_in[]=abs($ids);
				}else
				{
					$query_by_id_in[]=$ids;
				}
			}
		break;
		
		case 'categories':
			$query_categories=explode(',',$q_part[1]);
			$query_cat_not_in=array();
			$query_cat_in=array();
			foreach($query_categories as $cat)
			{
				if($cat<0)
				{
					$query_cat_not_in[]=abs($cat);
				}else
				{
					$query_cat_in[]=$cat;
				}
			}
		break;
		
		case 'tags':
			$query_tags=explode(',',$q_part[1]);
			$query_tags_not_in=array();
			$query_tags_in=array();
			foreach($query_tags as $tags)
			{
				
				if($tags<0)
				{
					$query_tags_not_in[]=abs($tags);
				}else
				{
					$query_tags_in[]=$tags;
				}
			}
			
		break;
		
		case 'authors':
			$query_author=explode(',',$q_part[1]);
			$query_author_not_in=array();
			$query_author_in=array();
			foreach($query_author as $author)
			{
				if($tags<0)
				{
					$query_author_not_in[]=abs($author);
				}else
				{
					$query_author_in[]=$author;
				}
			}
			
		break;

		case 'tax_query':
			$all_tax=get_object_taxonomies( $query_post_type );
			$tax_query=array();
			$query_tax_query=array('relation' => 'OR');
			foreach ( $all_tax as $tax ) {
				$values=$tax;
				$query_taxs_in=array();
				$query_taxs_not_in=array();
				
				$query_taxs=explode(',',$q_part[1]);
				
				foreach($query_taxs as $taxs)
				{
					if($taxs<0)
					{
						$query_taxs_not_in[]=abs($taxs);
					}else
					{
						$query_taxs_in[]=$taxs;
					}
				}
				
				if(count($query_taxs_not_in)>0)
				{
					$query_tax_query[]=array(
						'taxonomy' => $tax,
						'field'    => 'id',
						'terms'    => $query_taxs_not_in,
						'operator' => 'NOT IN',
					);
				}else if(count($query_taxs_in)>0)
				{
					$query_tax_query[]=array(
						'taxonomy' => $tax,
						'field'    => 'id',
						'terms'    => $query_taxs_in,
						'operator' => 'IN',
					);
				}
			}	
			
		break;
	}
}
$this->rand_id=rand(0,1000);
$loop_var = array();
if ($heading_base=='category_base') $loop_var= $query_cat_in;
else if ($heading_base=='tag_base') $loop_var= $query_tags_in;
else if ($heading_base=='tax_base') $loop_var= explode(',',$q_part[1]);
/**/
//if don't enter any category,tags or taxonomy 
if (is_array($loop_var)){
$output.='
<div class="pl-tabs pl-tabs-'.$this->rand_id.' '.$tab_heading_layout.' '.$custom_class.'">
	<nav>
		<ul data-id="'.$this->rand_id.'" class="'.$this->rand_id.'">
';

foreach ($loop_var as $loop_single_var){
	$all_tax=get_object_taxonomies( $query_post_type );
	$tax_query=array();
	$query_tax_query=array('relation' => 'OR');
	$term_name='';
	foreach ( $all_tax as $tax ) {
		$term_name = get_term_by('id', $loop_single_var , $tax);
		if (isset($term_name->name)) break; 
	}
	
	$output .='
			<li data-rnd-id="'.$this->rand_id.'-'.$loop_single_var.'">
				<a class="pl-tabs-child" href="#section-bar-'.$loop_single_var.'"><span>'.$term_name->name.'</span></a>
			</li>
			';
}//end foreach
$output.='
	</ul>
</nav>			
';	
/*tab content*/
$output.='
<div class="content-wrap">
';
foreach ($loop_var as $loop_single_var){
	$base_array=array();
	if ($heading_base=='category_base') $base_array =  array('category__in'=>array($loop_single_var));
	else if ($heading_base=='tag_base') $base_array =  array('tag__in'=>$loop_single_var);
	else if ($heading_base=='tax_base') { 
		
		$all_tax=get_object_taxonomies( $query_post_type );
		$tax_query=array();
		$query_tax_query=array('relation' => 'OR');
		foreach ( $all_tax as $tax ) {
				$values=$tax;
				$query_taxs_in=array();
				$query_tax_query[]=array(
					'taxonomy' =>  $tax,
					'field'    => 'id',
					'terms'    => array(0=>$loop_single_var),
					'operator' => 'IN',
				);
			}	
		
		$base_array = array('tax_query'=>$query_tax_query);
		
	}
$query_final=array('post_type' => $query_post_type,
	'post_status'=>'publish',
	'posts_per_page'=>$query_posts_per_page,
	'meta_key' => $query_meta_key,
	'orderby' => $query_orderby,
	'order' => $query_order,
	'paged'=>$paged,
	
	'post__in'=>$query_by_id_in,
	'post__not_in'=>$query_by_id_not_in,
	
	'author__in'=>$query_author_in,
	'author__not_in'=>$query_author_not_in,
 );
	$query_final = array_merge(	$query_final , $base_array);
	
$car_row_counter = 0;

$output .='
<section id="section-bar-'.$loop_single_var.'">';
	//Navigation Icon
	$navigation_icon_left=$navigation_icon_right='';
	if ($show_navigation=='true'){
		$navigation_icon_left = $navigation_icon;
		$navigation_icon_right = str_replace('left','right',$navigation_icon);
	}	
	//Boxed Layout 3 Settings
	$layout_three_img_effect='';
	if ($layout_three_effect=='pl-bottom-to-top-img' ){
		$layout_three_img_effect ='pl-bottom-to-top';
		$layout_three_effect='pl-bottom-to-top';
	}
	else if ($layout_three_effect=='pl-top-to-bottom-img' ){
		$layout_three_img_effect ='pl-top-to-bottom';
		$layout_three_effect ='pl-top-to-bottom';
	}	
			
	$output .='
	<div class="pl-main-slider-cnt '.$rtl_layout.' '.$custom_class.' '.$show_play_pause.' '.$play_pause_position.' '.$play_pause_shape.'  '.$play_pause_fill.' '.$play_pause_on_hover.'">';
		$output .='
			<div class="pl-slider-cnt '.$slider_type.'-container '.$slider_type.' '.$slider_type.'-'.$this->rand_id.' '.$dot_position.' '.$dot_layout.' '.$show_thumbnail.' '.$thumbnail_layout.' '.$thumbnail_shape.' '.$thumbnail_position.' '.$navigation_position.' '.$navigation_shape.' '.$navigation_fill.' '.$navigation_on_hover.' '.$this->rand_id.'-'.$loop_single_var.'"  id="pl-slider-'.$this->rand_id.'" >';
		
		if ($slider_type=='pl-swiper'){
			$output.='<div class="pl-swiper-wrapper">';
		}
	
	$my_query = new WP_Query($query_final);
	$post_counter=$post_count=1;	
	while ( $my_query->have_posts() ) {
		
		$my_query->the_post(); // Get post from query
		$post = new stdClass(); // Creating post object.
		$post->id = get_the_ID();
		$post->link = get_permalink($post->id);
		$post->title = get_the_title($post->id);
		$post->excerpt = get_the_excerpt();
		/*Get Taxonomy*/
		
		$cat_tax='';
		$all_tax=get_object_taxonomies( $query_post_type );
		$current_value=array();
		if(is_array($all_tax) && count($all_tax)>0){
			foreach ( $all_tax as $tax ) {
				if($tax=="post_tag")
					continue;
					 
				$cat= $this->get_category_tag( $post->id , $tax, '', ',','');					
				if($cat!='')
					$cat_tax[]=$cat;
			}//end foreach
		}//end if is_array($all_tax)
		if (is_array($cat_tax) && ( count($cat_tax) > 0 )){
			$cat_tax=implode(',',$cat_tax);
			$cat_tax_array=explode(',',$cat_tax);
		}
		
		$post->author=get_the_author();
		$author_id = get_the_author_meta( 'ID' );
		$author_link = get_author_posts_url( $author_id );
		
		$excerpt_c=$post->excerpt;
		
		$comment_link=get_comments_link();
		$comment_num=get_comments_number( '0', '1', '% responses' );
		
		$img_id=get_post_meta( $post->id , '_thumbnail_id' ,true );
		$img=array();
		$default_size ='';
		
		$full_img = wp_get_attachment_image_src( $img_id , 'full' );
		$slider_img = wp_get_attachment_image_src( $img_id , $slider_image_size );
		
		if ($slider_layout=='pl-outerdesc-layout1'){
			include('public_carousel_layout/carousel_outerdesc_layout1.php');
		}
		else if ($slider_layout=='pl-outerdesc-layout3'){
			include('public_carousel_layout/carousel_outerdesc_layout2.php');
		}
		else if ($slider_layout=='pl-outerdesc-layout4'){
			include('public_carousel_layout/carousel_outerdesc_layout3.php');
		}
		else if ($slider_layout=='pl-boxed-layout1'){
			include('public_carousel_layout/carousel_boxed_layout1.php');
		}
		else if ($slider_layout=='pl-boxed-layout2'){
			include('public_carousel_layout/carousel_boxed_layout2.php');
		}
		else if ($slider_layout=='pl-boxed-layout3'){
			
			include('public_carousel_layout/carousel_boxed_layout3.php');
		}
		else if ($slider_layout=='pl-boxed-layout4'){
			include('public_carousel_layout/carousel_boxed_layout4.php');
		}
		else if ($slider_layout=='pl-boxed-layout5'){
			include('public_carousel_layout/carousel_boxed_layout5.php');
		}
		else if ($slider_layout=='pl-boxed-layout6'){
			include('public_carousel_layout/carousel_boxed_layout6.php');
		}
		else if ($slider_layout=='pl-boxed-layout7'){
			include('public_carousel_layout/carousel_boxed_layout7.php');
		}
		else if ($slider_layout=='pl-boxed-layout8'){
			include('public_carousel_layout/carousel_boxed_layout8.php');
		}
		
		$post_counter++;
		
		
		
	}//end while 
	//swiper slider has an additional div
	if ($slider_type=='pl-swiper'){
		$output.='</div>';
		if ($show_dot=='true'){
        	$output.='<div class="pl-swiper-pagination"></div>';
		}
		if ($show_navigation=='true'){
			$output.='
				<div class="pl-swiper-navigation">
					<div class="pl-swiper-button-prev"><i class="fa '.$navigation_icon_left.'"></i></div>
					<div class="pl-swiper-button-next"><i class="fa '.$navigation_icon_right.'"></i></div>
				</div>
			';	
		}
	}
	
	
	
	$output .='	
	</div><!--pl-slider-cnt -->';
	if ( $show_play_pause=='pl-slider-show-btn'  && $slider_type!='pl-smoothslides' ){
		$output .='
			<div class="pl-play-pause-cnt">
				<div class="pl-slider-btn pl-slider-play-'.$this->rand_id.'"><i class="fa fa-play"></i></div>
				<div class="pl-slider-btn pl-slider-stop-'.$this->rand_id.'"><i class="fa fa-pause"></i></div>
			</div>	';
	}//$show_play_pause
	$output .='	
</div><!--pl-main-slider-cnt -->
	';
	$output .='
	</section><!--end tab section -->	
	';
	wp_reset_query();
}//end foreach
	
if ($slider_type=='pl-owl'){

		//animateOut: "'.$slider_out_animation.'",animateIn: "'.$slider_in_animation.'",	
		$show_thumbnail = ($show_thumbnail=='pl-slider-show-thumb')?'true':'false';
		if ($show_thumbnail=='true') $show_dot='true';
		
		$output.='<script type="text/javascript">
			
			jQuery(document).ready(function() {	  
			  setTimeout(function(){
				  var pl_owl_slider_'.$this->rand_id.' = jQuery(".pl-owl-'.$this->rand_id.'");
				  pl_owl_slider_'.$this->rand_id.'.owlCarousel({
					autoplay:'.$slider_autoplay.',
					loop:'.$slider_speed.',
					autoplayHoverPause:true,
					nav:'.$show_navigation.',
					dots:'.$show_dot.',
					dotsData:'.$show_thumbnail.',
					lazyLoad:'.$lazy_load.',
					
					navText:[ \'<i class="fa '.$navigation_icon_left.'"></i>\', \'<i class="fa '.$navigation_icon_right.'"></i>\' ],
					margin:'.$item_margin.',
					responsiveClass:true,
					responsive:{
						0:{
							items:'.$item_mobile.',
						},
						768:{
							items:'.$item_tablet.',
						},
						992:{
							items:'.$item_desktop.',
						}	
					}
					});
			  },200);';
				
				if ($show_play_pause=='pl-slider-show-btn'){
					$output.='
					jQuery(".pl-slider-play-'.$this->rand_id.'").on("click",function(){
						pl_owl_slider_'.$this->rand_id.'.trigger("play.owl.autoplay",[1000])
					})
					jQuery(".pl-slider-stop-'.$this->rand_id.'").on("click",function(){
						pl_owl_slider_'.$this->rand_id.'.trigger("stop.owl.autoplay")
					})';
				}//$show_play_pause
			$output.='
			});
			</script>
			';
	}
else if ($slider_type=='pl-swiper') {
		$output.='<script type="text/javascript">
		jQuery(document).ready(function() {
		
			jQuery(".'.$this->rand_id.'").find("li").click(function(){
				jQuery(".pl-swiper-'.$this->rand_id.'").css("visibility", "hidden");
				setTimeout(function(){
					var swiper_'.$this->rand_id .' = new Swiper(".pl-swiper-'.$this->rand_id.'", {
						slidesPerView: 1,
						autoHeight: true,';
					if ($slider_autoplay=='true')	
						$output.='	
						autoplay: '.$slider_speed.',';
					else 
						$output.='	
						autoplay: false,';
					
					$output.='	
						pagination: \'.pl-swiper-pagination\',
						paginationClickable: true,
						paginationType: \'fraction\', 
						
						nextButton: \'.pl-swiper-button-next\',
						prevButton: \'.pl-swiper-button-prev\',
						paginationType: \''.$dot_type.'\', 
						
						effect: \''.$swiper_slider_effect.'\',
						
						grabCursor: true,
						spaceBetween: '.$item_margin.',
						slidesPerColumn: '.$carousel_row.',';
					if ($carousel_row==1){
						$output.='
							autoHeight: true,
						';	
					}
					if (($swiper_slider_effect=='slide') || ($swiper_slider_effect=='coverflow')){
						$output.='	
							breakpoints: {
							2000:{
								slidesPerView: '.$item_desktop.'
							},
							992: {
								slidesPerView: '.$item_desktop.'
							},
							768: {
								slidesPerView: '.$item_tablet.'
							},
							320: {
								slidesPerView: '.$item_mobile.'
							}
						 }';
					}
			$output.='		
				});
				jQuery(".pl-swiper-'.$this->rand_id.'").css("visibility", "visible");
			},100);
			});	
			jQuery(".'.$this->rand_id.' li:nth-child(1)").trigger("click");';	  
				  
			
		if ($show_play_pause=='pl-slider-show-btn'){
			$output.='
			jQuery(".pl-slider-play-'.$this->rand_id.'").on("click",function(){
				swiper_'.$this->rand_id .'.startAutoplay();
			})
			jQuery(".pl-slider-stop-'.$this->rand_id.'").on("click",function(){
				swiper_'.$this->rand_id .'.stopAutoplay();
			})';
		}//$show_play_pause
		$output.='
		});
		</script>
	';
	}



$output.='
	</div><!-- /content -->
</div><!-- /tabs -->
';
$output.='
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		[].slice.call( document.querySelectorAll( ".pl-tabs" ) ).forEach( function( el ) {
			new CBPFWTabs( el );
		});
		
	});
</script>';
}//end if is_array($loop_vra)
else {
	$output.='<h3>'.__('There are no items to display',PW_PRO_SLIDER_CAROUSEL_TEXTDOMAIN).'</h3>';
}
?>