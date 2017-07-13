<?php 
$output.='<div class="pl-slider-slide item " >';
	$output .='<div class="pl-post-cnt pl-outerdesc-cnt pl-outerdesc-layout3">';
	   $output .='
				<div class="pl-thumbnail">
					<a href="'. $post->link .'">
						<div class="pl-thumbnail-overlay"></div>
					</a>
					<div class="pl-triangle"></div>
					<div class="pl-thumbnail-img '.$image_hover.'">
						<img src="'. $slider_img[0] .'" class="slide-img" />
					</div>
				</div>		  	
			';
	   $output.='<div class="pl-outer-details">';
	   			if ($slider_show_meta=='yes'){
					$output.='
					<div class="pl-cat-meta">
					';
						if ($slider_show_category=='yes') {
							$output.='
							<div class="pl-meta-item">';
							for ($i=0;$i < $slider_cat_number ; $i++){
								 if (isset($cat_tax_array[$i]))
									 $output.=$cat_tax_array[$i];
							 }//end for
							 $output.='
							 </div>';
						}//end if
					$output.='</div>';
				}
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
				if ($slider_show_meta=='yes'){
					$output.='
					<div class="pl-post-footer-cnt">
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
							
							if ($slider_show_author=='yes') {
								$output.=' 
								<span class="pl-meta-item">
									<i class="fa fa-user"></i><a href="'.$author_link.'">'.$post->author.'</a>
								</span>
								';
							}
							if ($slider_show_comments=='yes') {
								$output.='
								<span class="pl-meta-item">
									<i class="fa fa-comments"></i>
									<a href="'.$comment_link.'">'.$comment_num.'</a>
								</span>
								';
							}
						$output.='  
						</div>
					</div>
					';
				}//if show_meta==yes
		  $output.='</div>';
	$output.='</div>';
$output.='</div>';
?>
