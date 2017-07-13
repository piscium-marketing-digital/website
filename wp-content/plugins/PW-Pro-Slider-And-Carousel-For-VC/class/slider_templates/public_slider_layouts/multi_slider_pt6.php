<?php
if ($post_counter==1){
	$output.='<div class="pl-slider-slide item" >';
	$output.='<div class="pl-multi-item  pl-item-big">';
		$output.='<div class="pl-slider-content">';
				if ($slider_show_meta=='yes'){
					$output.='
					<div class="pl-meta">
					';
						if ($slider_show_date=='yes') { 
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-clock-o"></i>
								<span class="meta-text">'. get_the_date($slider_date_format) .'</span>
							</span>
							';
						}
						if ($slider_show_category=='yes') {
							$output.='
							<div class="pl-meta-item">
							<i class="fa fa-folder-o"></i>';
							for ($i=0;$i < $slider_cat_number ; $i++){
								 if (isset($cat_tax_array[$i]))
									 $output.=$cat_tax_array[$i].'<div class="pl-meta-spil">,</div>';
							 }//end for
							 $output.='
							 </div>';
						}//end if
						if ($slider_show_author=='yes') {
							$output.=' 
							<span class="pl-meta-item">
								<i class="fa fa-user"></i><a href="#">'.$post->author.'</a>
							</span>
							';
						}
						if ($slider_show_author=='yes') {
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-comments"></i>
								<a href="'.$comment_link.'">'.$comment_num.'</a>
							</span>
							';
						}
					$output.='  
					</div>
					';
				}//if show_meta==yes
				if ($slider_show_title=='yes') {
					$output.=' 
					<div class="pl-title">
						<a href="'. $post->link .'">'.$post->title .'</a>
					</div>
					';
				}//end if
				if ($slider_show_excerpt=='yes') {
					$output.=' 
					<div class="pl-excerpt">
						'.$this->excerpt($excerpt_c,$slider_excerpt_len).'
					</div>
					';
				}//end if
				if ($slider_show_button=='yes') {
					$output.=' 
					<div class="pl-buttons">
						<a class="pl-btn pl-readmore-btn" href="'. $post->link .'">'.$slider_btn_txt.'</a>
					</div>
					';
				}//end if
		  $output.='</div>';
		  
		  $output.='
		  <div class="pl-slider-thumbnail " style="background-image:url('.$slider_img[0].');">
			  <a href="'. $post->link .'"><div class="pl-thumbnail-overlay"></div></a>
		  </div>
		  ';
	$output.='</div>';

	$is_last_item='false';
} //if post_counter==1

if ($post_counter==2){
	$output.='<div class="pl-multi-item  pl-item-big">';
		$output.='<div class="pl-slider-content">';
				if ($slider_show_meta=='yes'){
					$output.='
					<div class="pl-meta">
					';
						if ($slider_show_date=='yes') { 
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-clock-o"></i>
								<span class="meta-text">'. get_the_date($slider_date_format) .'</span>
							</span>
							';
						}
						if ($slider_show_category=='yes') {
							$output.='
							<div class="pl-meta-item">
							<i class="fa fa-folder-o"></i>';
							for ($i=0;$i < $slider_cat_number ; $i++){
								 if (isset($cat_tax_array[$i]))
									 $output.=$cat_tax_array[$i].'<div class="pl-meta-spil">,</div>';
							 }//end for
							 $output.='
							 </div>';
						}//end if
						if ($slider_show_author=='yes') {
							$output.=' 
							<span class="pl-meta-item">
								<i class="fa fa-user"></i><a href="#">'.$post->author.'</a>
							</span>
							';
						}
						if ($slider_show_author=='yes') {
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-comments"></i>
								<a href="'.$comment_link.'">'.$comment_num.'</a>
							</span>
							';
						}
					$output.='  
					</div>
					';
				}//if show_meta==yes
				if ($slider_show_title=='yes') {
					$output.=' 
					<div class="pl-title">
						<a href="'. $post->link .'">'.$post->title .'</a>
					</div>
					';
				}//end if
				if ($slider_show_excerpt=='yes') {
					$output.=' 
					<div class="pl-excerpt">
						'.$this->excerpt($excerpt_c,$slider_excerpt_len).'
					</div>
					';
				}//end if
				if ($slider_show_button=='yes') {
					$output.=' 
					<div class="pl-buttons">
						<a class="pl-btn pl-readmore-btn" href="'. $post->link .'">'.$slider_btn_txt.'</a>
					</div>
					';
				}//end if
		  $output.='</div>';
		  
		  $output.='
		  <div class="pl-slider-thumbnail " style="background-image:url('.$slider_img[0].');">
			  <a href="'. $post->link .'"><div class="pl-thumbnail-overlay"></div></a>
		  </div>
		  ';
	$output.='</div><!--pl-item-mini -->';
	
	$is_last_item='false';
} //if post_counter==2
if ($post_counter==3){
	$output.='<div class="pl-multi-item  pl-item-mini">';
		$output.='<div class="pl-slider-content">';
				if ($slider_show_meta=='yes'){
					$output.='
					<div class="pl-meta">
					';
						if ($slider_show_date=='yes') { 
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-clock-o"></i>
								<span class="meta-text">'. get_the_date($slider_date_format) .'</span>
							</span>
							';
						}
						if ($slider_show_category=='yes') {
							$output.='
							<div class="pl-meta-item">
							<i class="fa fa-folder-o"></i>';
							for ($i=0;$i < $slider_cat_number ; $i++){
								 if (isset($cat_tax_array[$i]))
									 $output.=$cat_tax_array[$i].'<div class="pl-meta-spil">,</div>';
							 }//end for
							 $output.='
							 </div>';
						}//end if
						if ($slider_show_author=='yes') {
							$output.=' 
							<span class="pl-meta-item">
								<i class="fa fa-user"></i><a href="#">'.$post->author.'</a>
							</span>
							';
						}
						if ($slider_show_author=='yes') {
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-comments"></i>
								<a href="'.$comment_link.'">'.$comment_num.'</a>
							</span>
							';
						}
					$output.='  
					</div>
					';
				}//if show_meta==yes
				if ($slider_show_title=='yes') {
					$output.=' 
					<div class="pl-title">
						<a href="'. $post->link .'">'.$post->title .'</a>
					</div>
					';
				}//end if
				if ($slider_show_excerpt=='yes') {
					$output.=' 
					<div class="pl-excerpt">
						'.$this->excerpt($excerpt_c,$slider_excerpt_len).'
					</div>
					';
				}//end if
				if ($slider_show_button=='yes') {
					$output.=' 
					<div class="pl-buttons">
						<a class="pl-btn pl-readmore-btn" href="'. $post->link .'">'.$slider_btn_txt.'</a>
					</div>
					';
				}//end if
		  $output.='</div>';
		  
		  $output.='
		  <div class="pl-slider-thumbnail " style="background-image:url('.$slider_img[0].');">
			  <a href="'. $post->link .'"><div class="pl-thumbnail-overlay"></div></a>
		  </div>
		  ';
	$output.='</div><!--pl-item-mini -->';
	
	$is_last_item='false';
} //if post_counter==3
if ($post_counter==4){
	$output.='<div class="pl-multi-item  pl-item-mini">';
		$output.='<div class="pl-slider-content">';
				if ($slider_show_meta=='yes'){
					$output.='
					<div class="pl-meta">
					';
						if ($slider_show_date=='yes') { 
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-clock-o"></i>
								<span class="meta-text">'. get_the_date($slider_date_format) .'</span>
							</span>
							';
						}
						if ($slider_show_category=='yes') {
							$output.='
							<div class="pl-meta-item">
							<i class="fa fa-folder-o"></i>';
							for ($i=0;$i < $slider_cat_number ; $i++){
								 if (isset($cat_tax_array[$i]))
									 $output.=$cat_tax_array[$i].'<div class="pl-meta-spil">,</div>';
							 }//end for
							 $output.='
							 </div>';
						}//end if
						if ($slider_show_author=='yes') {
							$output.=' 
							<span class="pl-meta-item">
								<i class="fa fa-user"></i><a href="#">'.$post->author.'</a>
							</span>
							';
						}
						if ($slider_show_author=='yes') {
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-comments"></i>
								<a href="'.$comment_link.'">'.$comment_num.'</a>
							</span>
							';
						}
					$output.='  
					</div>
					';
				}//if show_meta==yes
				if ($slider_show_title=='yes') {
					$output.=' 
					<div class="pl-title">
						<a href="'. $post->link .'">'.$post->title .'</a>
					</div>
					';
				}//end if
				if ($slider_show_excerpt=='yes') {
					$output.=' 
					<div class="pl-excerpt">
						'.$this->excerpt($excerpt_c,$slider_excerpt_len).'
					</div>
					';
				}//end if
				if ($slider_show_button=='yes') {
					$output.=' 
					<div class="pl-buttons">
						<a class="pl-btn pl-readmore-btn" href="'. $post->link .'">'.$slider_btn_txt.'</a>
					</div>
					';
				}//end if
		  $output.='</div>';
		  
		  $output.='
		  <div class="pl-slider-thumbnail " style="background-image:url('.$slider_img[0].');">
			  <a href="'. $post->link .'"><div class="pl-thumbnail-overlay"></div></a>
		  </div>
		  ';
	$output.='</div><!--pl-item-mini -->';
	
	$is_last_item='false';
} //if post_counter==4
if ($post_counter==5){
	$output.='<div class="pl-multi-item  pl-item-mini">';
		$output.='<div class="pl-slider-content">';
				if ($slider_show_meta=='yes'){
					$output.='
					<div class="pl-meta">
					';
						if ($slider_show_date=='yes') { 
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-clock-o"></i>
								<span class="meta-text">'. get_the_date($slider_date_format) .'</span>
							</span>
							';
						}
						if ($slider_show_category=='yes') {
							$output.='
							<div class="pl-meta-item">
							<i class="fa fa-folder-o"></i>';
							for ($i=0;$i < $slider_cat_number ; $i++){
								 if (isset($cat_tax_array[$i]))
									 $output.=$cat_tax_array[$i].'<div class="pl-meta-spil">,</div>';
							 }//end for
							 $output.='
							 </div>';
						}//end if
						if ($slider_show_author=='yes') {
							$output.=' 
							<span class="pl-meta-item">
								<i class="fa fa-user"></i><a href="#">'.$post->author.'</a>
							</span>
							';
						}
						if ($slider_show_author=='yes') {
							$output.='
							<span class="pl-meta-item">
								<i class="fa fa-comments"></i>
								<a href="'.$comment_link.'">'.$comment_num.'</a>
							</span>
							';
						}
					$output.='  
					</div>
					';
				}//if show_meta==yes
				if ($slider_show_title=='yes') {
					$output.=' 
					<div class="pl-title">
						<a href="'. $post->link .'">'.$post->title .'</a>
					</div>
					';
				}//end if
				if ($slider_show_excerpt=='yes') {
					$output.=' 
					<div class="pl-excerpt">
						'.$this->excerpt($excerpt_c,$slider_excerpt_len).'
					</div>
					';
				}//end if
				if ($slider_show_button=='yes') {
					$output.=' 
					<div class="pl-buttons">
						<a class="pl-btn pl-readmore-btn" href="'. $post->link .'">'.$slider_btn_txt.'</a>
					</div>
					';
				}//end if
		  $output.='</div>';
		  
		  $output.='
		  <div class="pl-slider-thumbnail " style="background-image:url('.$slider_img[0].');">
			  <a href="'. $post->link .'"><div class="pl-thumbnail-overlay"></div></a>
		  </div>
		  ';
	$output.='</div><!--pl-item-mini -->';
	
	$output.='</div><!--pl-slider-slide -->';
	$is_last_item='true';
	$post_counter=0;
} //if post_counter==5
	
?>
