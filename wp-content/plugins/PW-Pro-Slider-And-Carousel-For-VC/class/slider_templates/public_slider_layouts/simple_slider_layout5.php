<?php 

$output.='<div class="pl-slider-slide item" data-dot="<img src=\''.$thumb_img[0].'\' title=\''.$post->title.'\' class=\'pl-thumb-tooltip-'.$this->rand_id.'\' />"  >';
          $output.='<a href="'. $post->link .'"><img src="'. $slider_img[0] .'" class="slide-img" /> </a>';
				$output.='<div class="pl-slider-content">';
					$output .='<div class="pl-slide-table-wrap">';
						$output .='<div class="pl-slide-table-cell">';
							$output .='<div class="pl-slide-center-cnt">';
								if ( ($slider_show_meta=='yes') && ($slider_show_category=='yes')){
									$output.='
									<div class="pl-top-meta">';
											$output.='
											<div class="pl-meta-item">';
											for ($i=0;$i < $slider_cat_number ; $i++){
												 if (isset($cat_tax_array[$i]))
													 $output.=$cat_tax_array[$i].'<div class="pl-meta-spil">,</div>';
											 }//end for
											 $output.='
											 </div>';
										
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
							$output .='</div>';
						$output .='</div>';
					$output .='</div>';		
               $output.='</div>';
$output.='</div>';
?>
