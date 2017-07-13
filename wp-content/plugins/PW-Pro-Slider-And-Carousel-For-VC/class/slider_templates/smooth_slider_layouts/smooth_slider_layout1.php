<?php 
	$alt_output.='<div class="pl-slider-content">';
		if ($slider_show_meta=='yes'){
			$alt_output.='
			<div class="pl-meta">
			';
				if ($slider_show_date=='yes') { 
					$alt_output.='
					<span class="pl-meta-item">
						<i class="fa fa-clock-o"></i>
						<span class="meta-text">'. get_the_date($slider_date_format) .'</span>
					</span>
					';
				}
				if ($slider_show_category=='yes') {
					$alt_output.='
					<div class="pl-meta-item">
					<i class="fa fa-folder-o"></i>';
					for ($i=0;$i < $slider_cat_number ; $i++){
						 if (isset($cat_tax_array[$i]))
							 $alt_output.=$cat_tax_array[$i].'<div class="pl-meta-spil">,</div>';
					 }//end for
					 $alt_output.='
					 </div>';
				}//end if
				if ($slider_show_author=='yes') {
					$alt_output.=' 
					<span class="pl-meta-item">
						<i class="fa fa-user"></i><a href="#">'.$post->author.'</a>
					</span>
					';
				}
				if ($slider_show_author=='yes') {
					$alt_output.='
					<span class="pl-meta-item">
						<i class="fa fa-comments"></i>
						<a href="'.$comment_link.'">'.$comment_num.'</a>
					</span>
					';
				}
			$alt_output.='  
			</div>
			';
		}//if show_meta==yes
		if ($slider_show_title=='yes') {
			$alt_output.=' 
			<div class="pl-title">
				<a href="'. $post->link .'">'.$post->title .'</a>
			</div>
			';
		}//end if
		if ($slider_show_excerpt=='yes') {
			$alt_output.=' 
			<div class="pl-excerpt">
				'.$this->excerpt($excerpt_c,$slider_excerpt_len).'
			</div>
			';
		}//end if
		if ($slider_show_button=='yes') {
				$alt_output.=' 
				<div class="pl-buttons">
					<a class="pl-btn pl-readmore-btn" href="'. $post->link .'">'.$slider_btn_txt.'</a>
				</div>
				';
			}//end if
	$alt_output.='</div>';
?>
