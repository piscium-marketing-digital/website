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
$loop_var = array();
if (($heading_base=='category_base') && (is_array($query_cat_in)) ) $loop_var= $query_cat_in;
else if ( ($heading_base=='tag_base') && (is_array($query_tags_in))) $loop_var= $query_tags_in;
else if ( ($heading_base=='tax_base') && (is_array($query_taxs_in))) $loop_var= explode(',',$q_part[1]);
/*Query Base Filter*/
$query_final='';

if (($heading_type == 'simple_heading') && (count($loop_var) > 0 ) ){
	$base_array=array();
	if ($heading_base=='category_base') $base_array =  array('category__in'=>array($loop_var[0]));
	else if ($heading_base=='tag_base') $base_array =  array('tag__in'=>$loop_var[0]);
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
					'terms'    => array(0=>$loop_var[0]),
					'operator' => 'IN',
				);
			}	
		$base_array = array('tax_query'=>$query_tax_query);
	}
	
	$query_final=array(
		'post_type' => $query_post_type,
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
}
else {	
	$query_final=array(
		'post_type' => $query_post_type,
		'post_status'=>'publish',
		'posts_per_page'=>$query_posts_per_page,
		'meta_key' => $query_meta_key,
		'orderby' => $query_orderby,
		'order' => $query_order,
		'paged'=>$paged,
		
		'post__in'=>$query_by_id_in,
		'post__not_in'=>$query_by_id_not_in,
		
		'category__in'=>$query_cat_in,
		'category__not_in'=>$query_cat_not_in,
		
		'tag__in'=>$query_tags_in,
		'tag__not_in'=>$query_tags_not_in,
		
		'author__in'=>$query_author_in,
		'author__not_in'=>$query_author_not_in,
		
		'tax_query'=>$query_tax_query
	 );
}


$this->rand_id=rand(0,1000);
$car_row_counter = 0;
if ($show_heading=='yes'){
	
	if ($heading_type == 'manual_heading' ){
		$output.='
			<div class="pl-header pl-header-'.$this->rand_id.' '.$heading_layout.' '.$rtl_layout.' '.$custom_class.'">
				<h3 class="pl-main-heading">'.$heading_text.'</h3>
			</div>
		';
	}
	
	if ( ($heading_type == 'simple_heading') && (count($loop_var) > 0 ) ){
		
		$all_tax=get_object_taxonomies( $query_post_type );
		$term_name=$cat_child=$cat_tax=$cat_link='';
		foreach ( $all_tax as $tax ) {
			$term_name = get_term_by('id', $loop_var[0] , $tax);
			$cat_child = get_term_children( $loop_var[0], $tax );
			
			if (isset($term_name->term_id)){
				$term = get_term( $term_name->term_id, $tax );
				$cat_link=get_term_link( $term);
				// If there was an error, continue to the next term.
				if ( is_wp_error( $cat_link ) ) {
					continue;
				}
			}
			$cat_tax = $tax;
			if (isset($term_name->name)) break; 
		}
		
		$cat_name = ($heading_text!='')?$heading_text:$term_name->name;
		
		
		$output.='
			<div class="pl-header pl-header-'.$this->rand_id.' '.$heading_layout.' '.$rtl_layout.' '.$custom_class.'" >
				<h3 class="pl-main-heading"><a href="'.esc_url( $cat_link ).'">'.$cat_name.'</a></h3>
				';
			if ( (count($cat_child)>0) && ($show_sub_heading=='yes')){	
				$output.='
				<div class="pl-sub-heading-cnt">
				';
				foreach($cat_child as $child){	
					$child_name = get_term_by('id', $child , $cat_tax);;
					$term = get_term( $child_name->term_id, $cat_tax );
					$sub_cat_link=get_term_link( $term);
					// If there was an error, continue to the next term.
					if ( is_wp_error( $sub_cat_link ) ) {
						continue;
					}
					$output.='
					<a class="pl-sub-heading" href="'.esc_url( $sub_cat_link ).'">'.$child_name->name.'</a>
					<span class="pl-meta-spil">,</span>
					';
				}
				
				$output.='
				</div>
				';
			}//end if count($cat_child)>0
			if ($show_view_btn=='yes')	{
				$output.='
				<a class="pl-more-btn" href="'.esc_url($cat_link).'">'.$view_text.'<i class="fa fa-angle-right '.$rtl_layout.'"></i></a>';
			}
			$output.='
			</div><!--end pl-header -->
		';
	}
}//if show_heading==yes

//Navigation Icon
$navigation_icon_left=$navigation_icon_right='';
if ($show_navigation=='true'){
	$navigation_icon_left = $navigation_icon;
	$navigation_icon_right = str_replace('left','right',$navigation_icon);
}
//IF SLIDER MOde WAS MULTI MUST SET IT'S OWN LAYOUT
$slider_layout_selected='';
if ($slider_mode=='pl-simple-slider'){
	$slider_layout_selected =$slider_layout;
}
else {
	$slider_layout_selected = 'pl-slider-layout6';
}
$output .='
<div class="pl-main-slider-cnt '.$rtl_layout.' '.$custom_class.' '.$show_play_pause.' '.$play_pause_position.' '.$play_pause_shape.'  '.$play_pause_fill.' '.$play_pause_on_hover.'">';
	$output .='
		<div class="pl-slider-cnt '.$slider_type.'-container '.$slider_type.' '.$slider_type.'-'.$this->rand_id.'  '.$slider_mode.' '.$slider_layout_selected.' '.$multi_slider_pattern.' '.$dot_position.' '.$dot_layout.' '.$show_thumbnail.' '.$thumbnail_layout.' '.$thumbnail_shape.' '.$thumbnail_position.' '.$navigation_position.' '.$navigation_shape.' '.$navigation_fill.' '.$navigation_on_hover.'"  id="pl-slider-'.$this->rand_id.'">';
	
	if ($slider_type=='pl-swiper'){
		$output.='<div class="pl-swiper-wrapper">';
	}
	$my_query = new WP_Query($query_final);
	
	$post_counter=$post_count=1;	
	$gallery_swiper ='';
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
		
		$thumb_img ='';
		if ($thumbnail_shape=='pl-slider-thumb-rect'){
			$thumb_img = wp_get_attachment_image_src( $img_id , 'pw_hor_image' );
		}
		else {
			$thumb_img = wp_get_attachment_image_src( $img_id , 'pw_rec_image' );
		}
		
		if ($slider_mode=='pl-simple-slider' && $slider_type=='pl-smoothslides'){
			$alt_output='';
			if ($slider_layout=='pl-slider-layout1'){
				include('smooth_slider_layouts/smooth_slider_layout1.php');
			}
			else if ($slider_layout=='pl-slider-layout2'){
				include('smooth_slider_layouts/smooth_slider_layout2.php');
			}
			else if ($slider_layout=='pl-slider-layout3'){
				include('smooth_slider_layouts/smooth_slider_layout3.php');
			}
			else if ($slider_layout=='pl-slider-layout4'){
				include('smooth_slider_layouts/smooth_slider_layout4.php');
			}
			else if ($slider_layout=='pl-slider-layout5'){
				include('smooth_slider_layouts/smooth_slider_layout5.php');
			}
			else if ($slider_layout=='pl-slider-layout6'){
				include('smooth_slider_layouts/smooth_slider_layout6.php');
			}
			
			$output .= '<img src="'. $slider_img[0] .'" alt=\''.$alt_output.'\' />';
			
		}
		
		else if ($slider_mode=='pl-simple-slider' && $slider_type!='pl-smoothslides'){
			
			if ($slider_layout=='pl-slider-layout1'){
				include('public_slider_layouts/simple_slider_layout1.php');
			}
			else if ($slider_layout=='pl-slider-layout2'){
				include('public_slider_layouts/simple_slider_layout2.php');
			}
			else if ($slider_layout=='pl-slider-layout3'){
				include('public_slider_layouts/simple_slider_layout3.php');
			}
			else if ($slider_layout=='pl-slider-layout4'){
				include('public_slider_layouts/simple_slider_layout4.php');
			}
			else if ($slider_layout=='pl-slider-layout5'){
				include('public_slider_layouts/simple_slider_layout5.php');
			}
			else if ($slider_layout=='pl-slider-layout6'){
				include('public_slider_layouts/simple_slider_layout6.php');
			}
			else if ($slider_layout=='pl-slider-layout7'){
				include('public_slider_layouts/simple_slider_layout7.php');
			}
		}
		
		else if ($slider_mode=='pl-multi-slider'){
			$is_last_item='false';	
			if ($multi_slider_pattern=='pl-multi-slider-pt1'){
				include('public_slider_layouts/multi_slider_pt1.php');
			}
			else if ($multi_slider_pattern=='pl-multi-slider-pt2'){
				include('public_slider_layouts/multi_slider_pt2.php');
			}
			else if ($multi_slider_pattern=='pl-multi-slider-pt3'){
				include('public_slider_layouts/multi_slider_pt3.php');
			}
			else if ($multi_slider_pattern=='pl-multi-slider-pt4'){
				include('public_slider_layouts/multi_slider_pt4.php');
			}
			else if ($multi_slider_pattern=='pl-multi-slider-pt5'){
				include('public_slider_layouts/multi_slider_pt5.php');
			}
			else if ($multi_slider_pattern=='pl-multi-slider-pt6'){
				include('public_slider_layouts/multi_slider_pt6.php');
			}
			else if ($multi_slider_pattern=='pl-multi-slider-pt7'){
				include('public_slider_layouts/multi_slider_pt7.php');
			}
		}
		
		//if show_thumbnail on swiper slider is true must creat a seperate carousel for thumbnails
		
		if ( $show_thumbnail=='pl-slider-show-thumb'  && $slider_type=='pl-swiper' ){
			
			if ($post_count==1) 
				$gallery_swiper .='<div class="pl-swiper-container pl-swiper-gallery-thumbs gallery-thumbs-'.$this->rand_id.' '.$show_thumbnail.' '.$thumbnail_layout.' '.$thumbnail_shape.' '.$thumbnail_position.' ">
					        <div class="pl-swiper-wrapper">';
			
			$gallery_swiper.='
			<div class="pl-slider-slide item">
				<img src="'.$thumb_img[0].'" title="'.$post->title.'" class="pl-thumb-tooltip-'.$this->rand_id.'" />
			</div>';
		}
	
	
		$post_counter++;
		$post_count++;
	}//end while 
	
	if ($slider_mode=='pl-multi-slider' && $is_last_item=='false'){
		
		$output .='</div><!--pl-slider-slide -->';
	}
	
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
	if ( $show_thumbnail=='pl-slider-show-thumb'  && $slider_type=='pl-swiper' ){
		$gallery_swiper .='</div>
				</div>';	
		
		$output.=$gallery_swiper;
	}
	
$output .='		
</div><!--pl-main-slider-cnt -->
	';
	wp_reset_query();
	if ($thumbnail_tooltip == 'true'){
		$output.='<script type="text/javascript">
		jQuery(document).ready(function() {	
			jQuery(".pl-thumb-tooltip-'.$this->rand_id.'").tipsy({gravity: "s", fade: true});
		});
		</script>
		';  
	}
	
	if ($slider_type=='pl-owl'){
		//	
		$show_thumbnail = ($show_thumbnail=='pl-slider-show-thumb')?'true':'false';
		if ($show_thumbnail=='true') $show_dot='true';
		$in_animation = $out_animation ='';
		if ($slider_out_animation!='pl-none-animation'){
			$out_animation='animateOut: \''.$slider_out_animation.'\',';
		}
		if ($slider_in_animation!='pl-none-animation'){
			$in_animation='animateIn: \''.$slider_in_animation.'\',';
		}
		$output.='<script type="text/javascript">
			jQuery(document).ready(function() {	  
				setTimeout(function(){
				  var pl_owl_slider_'.$this->rand_id.' = jQuery(".pl-owl-'.$this->rand_id.'");
				  pl_owl_slider_'.$this->rand_id.'.owlCarousel({
					items:1,
					autoplay:'.$slider_autoplay.',
					loop:'.$slider_speed.',
					autoplayHoverPause:true,
					nav:'.$show_navigation.',
					navText:[ \'<i class="fa '.$navigation_icon_left.'"></i>\', \'<i class="fa '.$navigation_icon_right.'"></i>\' ],	
					dots:'.$show_dot.',
					dotsData:'.$show_thumbnail.',
					lazyLoad:'.$lazy_load.',
					'.$out_animation .' '.$in_animation.'
					
					});
				}, 200);';
				
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
	else if ($slider_type=='pl-smoothslides') {
		$output.='<script type="text/javascript">
		jQuery(document).ready(function() {	  
			setTimeout(function(){
			  jQuery(".pl-smoothslides-'.$this->rand_id.'").each(function(index, value) { 
					  jQuery(this).smoothSlides({
							effectDuration: '.$slider_speed.',
							captions: \'true\',
							navigation: \''.$show_navigation.'\',
							pagination:  \''.$show_dot.'\',
							autoPlay: \''.$slider_autoplay.'\',
							nextText:\'<i class="fa '.$navigation_icon_right.'"></i>\',
							prevText:\'<i class="fa '.$navigation_icon_left.'"></i>\'
						});
				});
			}, 200);';
		$output.='
		});
		</script>
	';
	}
	else if ($slider_type=='pl-swiper') {
		$output.='<script type="text/javascript">
		jQuery(document).ready(function() {	 
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
						paginationType: \''.$dot_type.'\', 
						
						nextButton: \'.pl-swiper-button-next\',
						prevButton: \'.pl-swiper-button-prev\',
						
						effect: \''.$swiper_slider_effect.'\',
						grabCursor: true,
						centeredSlides: true,
						spaceBetween: 0,
				});
			}, 200);
		';
		if ( $show_thumbnail=='pl-slider-show-thumb'  && $slider_type=='pl-swiper' ){
			$output.='
			var galleryThumbs_'.$this->rand_id.' = new Swiper(".gallery-thumbs-'.$this->rand_id.'", {
				spaceBetween: 10,
				centeredSlides: true,
				slidesPerView: 5,
				touchRatio: 0.2,
				slideToClickedSlide: true,
				
			});
			swiper_'.$this->rand_id .'.params.control = galleryThumbs_'.$this->rand_id.';
			galleryThumbs_'.$this->rand_id.'.params.control = swiper_'.$this->rand_id .';
			';
		}
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
?>