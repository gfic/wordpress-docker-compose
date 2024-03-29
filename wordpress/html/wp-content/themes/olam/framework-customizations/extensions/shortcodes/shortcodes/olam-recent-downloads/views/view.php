 <?php if (!defined('FW')) die('Forbidden');
 if(!olam_check_edd_exists()){
 	return;
 }
/**
 * @var $atts The shortcode attributes
 */
?>
<?php $noposts=(isset($atts['noposts']))?$atts['noposts']:-1; ?>
<?php
$taxCat=($atts['category']=='all')?null:$atts['category'];
$taxQuery=null;
if(isset($taxCat)){
	$taxQuery=	
	array(
		array(
			'taxonomy' => 'download_category',
			'field'    => 'id',
			'terms'    => $taxCat
			),
		) ;
}
$args = array(
	'post_type' => 'download',
	'status'	=> 'publish',
	'showposts' => $noposts,
	'orderby'	=> 'date',
	'order'		=>'DESC',
	'tax_query'=>$taxQuery
	);
		//print_r($args); die;
	$the_query = new WP_Query( $args ); ?> 
	<?php		if($atts['thumbordetail']=='thumb'){ ?>
	<div class="product_gallery_slider_wrapper">
																
		<div id="gallery" class="product_gallery_slider frame">
			<ul class="slides product-gallery">
				<?php if ( $the_query->have_posts() ) : ?>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>      
						<?php 
						if($the_query->post_count>=20) {    
							if($the_query->current_post % 2==0) {    
								echo   "<li>"; }
							}
							else{echo   "<li>"; }
							?>
							<div class="gal-item">
								<div class="gal-thumb">
									<?php
									$featImage=null;
									$realfeatImage = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'olam-product-thumb-small' );
									$realfeatImage=$realfeatImage[0];

									$theDownloadImage=get_post_meta(get_the_ID(),'download_item_thumbnail_id'); 
									$square_img = get_post_meta(get_the_ID(),"download_item_square_img");
									if (!empty($square_img) && strlen($square_img[0])>0) {
										$featImage = $square_img[0];
									}
									else if(is_array($theDownloadImage) && (count($theDownloadImage)>0) ){
										$thumbID=$theDownloadImage[0];
										$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
										$featImage=$featImage[0];
									}
									else{
										$thumbID=get_post_thumbnail_id(get_the_ID());
										$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
										$featImage=$featImage[0];
									}						
									?>
									<?php
									if(isset($featImage)){
										$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true); ?>
										<a href="<?php the_permalink(); ?>" title="<?php esc_html_e('View','olam'); ?>">
											<img src="<?php echo esc_url($featImage); ?>" alt="<?php echo esc_attr($alt); ?>">
										</a>
										<?php } elseif (isset($realfeatImage)){ 
											$alt = get_post_meta($realfeatImage, '_wp_attachment_image_alt', true);?>
										<a href="<?php the_permalink(); ?>" title="<?php esc_html_e('View','olam'); ?>">
											<img src="<?php echo esc_url($realfeatImage); ?>" alt="<?php echo esc_attr($alt); ?>">
										</a>
											<?php } else { ?>
										<a href="<?php the_permalink(); ?>" title="<?php esc_html_e('View','olam'); ?>">
											<img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/product-thumb-small.jpg" alt="">
										</a>
										<?php }	?>
									</div>
								</div>
								<?php
								if($the_query->post_count>=20) { 
									if($the_query->current_post % 2!=0) { echo  "</li>"; } 
								}
								else{ echo  "</li>"; }
								?>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						<?php else : ?>
							<p><?php esc_html_e( 'Sorry, no posts matched your criteria.',"olam"); ?></p>
						<?php endif; ?>
					</ul>
				</div>
				<div class="container text-center">
					<div class="scrollbar">
						<div class="handle"></div>
					</div>
					<?php if(isset($atts['viewmoretext']) && (strlen($atts['viewmoretext'])>0) ) { 
						$viewMoreText=$atts['viewmoretext'];
						$viewmore=(isset($atts['viewmore']) && (strlen($atts['viewmore'])>0))?$atts['viewmore']:"#";
						?>
						<a href="<?php echo esc_url($atts['viewmore']); ?>" class="btn btn-primary"><?php echo esc_html($viewMoreText); ?></a>
						<?php } ?>
					</div>
					<?php } else{ 
						$listingClass=null;
						if(isset($atts['listingorslider']) && $atts['listingorslider']=='listing') {
							$listingClass="product-listing";
							$listingItemClass="product";
						}
						else{
							$listingClass="product-carousel";
							$listingItemClass="slider-item";
						}
						?>
						<div class="row">
							<div class="<?php echo $listingClass; ?>">
								<?php if ( $the_query->have_posts() ) : ?>
									<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
										<div class="<?php echo $listingItemClass; ?> <?php echo esc_html($atts['listingcolumn']); ?>">
											<div class="edd_download_inner">
												<div class="thumb">
													<?php
													$thumbID=get_post_thumbnail_id(get_the_ID());
													$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb');
													$featImage=$featImage[0]; 
													$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true);

													$square_img = get_post_meta(get_the_ID(),"download_item_square_img");

												// feat vid code start
													$videoCode=get_post_meta(get_the_ID(),"download_item_video_id"); 
													$audioCode=get_post_meta(get_the_ID(),"download_item_audio_id");		
													$itemSet=null;		
													$featFlag=null;	
													$videoFlag=null;				
													if(isset($videoCode[0]) && (strlen($videoCode[0])>0) ){
														$itemSet=1;	
														$videoUrl=$videoCode[0];
														//$videoUrl=wp_get_attachment_url($videoCode[0]); 
														
														$videoFlag=1; 

														if (strpos($videoUrl, 'vimeo') !== false) {
									                    	echo '<div class="media-thumb vimeovid">'.do_shortcode("[video src='".esc_url($videoUrl)."']").'</div>';
										                } else {
										                    echo '<div class="media-thumb othervid">'.do_shortcode("[video src='".esc_url($videoUrl)."']").'</div>';
										                }
				                
													}
													else if (!empty($square_img) && strlen($square_img[0])>0) {
														$featFlag=1; ?>
								               		    <a href="<?php the_permalink(); ?>">
															<span><i class="demo-icons icon-link"></i></span>
															<img src="<?php echo esc_url($square_img[0]); ?>" />
														</a> <?php
								                 	}
													else if((isset($featImage))&&(strlen($featImage)>0)){
														$featFlag=1;
														$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true); ?>
														<a href="<?php the_permalink(); ?>">
															<span><i class="demo-icons icon-link"></i></span>
															<img src="<?php echo esc_url($featImage); ?>" alt="<?php echo esc_attr($alt); ?>">
														</a><?php
													}
													if(!isset($videoFlag)){ 
														if(isset($audioCode[0]) && (strlen($audioCode[0])>0) ){
															$itemSet=1;
															$audioUrl=wp_get_attachment_url($audioCode[0]);
															?>
															<div class="media-thumb">
																<?php echo do_shortcode("[audio src='".$audioUrl."']"); ?>
															</div> <?php
														}

													} ?>
													<?php if(!(isset($featFlag))){ ?>
													<a href="<?php the_permalink(); ?>">
														<span><i class="demo-icons icon-link"></i></span>
														<img src="<?php echo get_template_directory_uri(); ?>/img/preview-image-default.jpg" alt="<?php echo esc_attr($alt); ?>">
													</a>
													<?php } ?>

												</div>
												<div class="product-details">
													<div class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
													<div class="product-price"><?php edd_price(get_the_ID()); ?></div>
													<?php if ( has_excerpt() ) : // Only show custom excerpts not autoexcerpts ?>
	               										<p class="olam-custom-excerpt"><?php echo get_the_excerpt(); ?></p>
	            									<?php endif; ?>
													<div class="details-bottom">
														<div class="product-options">	
															<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e('View','olam'); ?> "><i class="demo-icons icon-search"></i></a>  
															<?php if(!olam_check_if_added_to_cart(get_the_ID())){ 
																$eddOptionAddtocart=edd_get_option( 'add_to_cart_text' );
																$addCartText=(isset($eddOptionAddtocart) && $eddOptionAddtocart  != '') ?$eddOptionAddtocart:esc_html__("Add to cart","olam");
																if(edd_has_variable_prices(get_the_ID())){														
																	$defaultPriceID=edd_get_default_variable_price( get_the_ID() );
																	$downloadArray=array('edd_action'=>'add_to_cart','download_id'=>get_the_ID(),'edd_options[price_id]'=>$defaultPriceID);
																}
																else{
																	$downloadArray=array('edd_action'=>'add_to_cart','download_id'=>get_the_ID());
																}	
																?>
																<a href="<?php echo esc_url(add_query_arg($downloadArray,edd_get_checkout_uri())); ?>" title="<?php esc_attr_e('Buy Now','olam'); ?>"><i class="demo-icons icon-download"></i></a>													
																<?php
																$purchase_link_args = array(
																	'download_id' => get_the_ID(),
																	'price' => false,
																	'direct' => false,
																	'style' => 'plain',
																	'class' => 'demo-icons icon-cart cart-icon-btn',
																	'text' => '',
																);
																$purchase_link_args = apply_filters( 'edd_rp_purchase_link_args', $purchase_link_args );
																echo edd_get_purchase_link( $purchase_link_args );
																} else { ?>
																<a class="cart-added" href="<?php echo esc_url(edd_get_checkout_uri()); ?>" title="<?php esc_attr_e('Checkout','olam'); ?> "><i class="fa fa-check"></i></a>    
																<?php } ?>
															</div>
															<?php $olamct=get_theme_mod('olam_show_cats');
											        			if(isset($olamct)&& $olamct==1 ){
											                    $cat = wp_get_post_terms(get_the_ID(),'download_category');
												                   if(count($cat)>0){
												                    $mlink = get_term_link($cat[0]->slug,'download_category');
												                    ?><div class="product-author"><a href="<?php echo $mlink; ?>"><?php echo($cat[0]->name); ?></a></div><?php
												                } }
											                    else{
											                    ?> <div class="product-author"><a href="<?php echo esc_url(add_query_arg( 'author_downloads', 'true', get_author_posts_url( get_the_author_meta('ID')) )); ?>"><?php esc_html_e("By","olam"); ?>: <?php the_author(); ?></a></div><?php
											                    }
											                    ?>
														</div>
													</div>
												</div>
											</div>
										<?php endwhile; ?>
										<?php wp_reset_postdata(); ?>
									<?php else : ?>
										<p><?php esc_html_e( 'Sorry, no posts matched your criteria.','olam' ); ?></p>
									<?php endif; ?>
									<span class="clearfix"></span>
								</div>	
							</div>
							<?php 	} ?>
	</div>