<div class="eep-portfolio">
	<section id="eep-portfolio-featured-projects" class="eep-portfolio-section">
		<?php echo "shortcode rendered"; ?>

	</section>
	
</div>
<?php 
			$args = array( 
				'post_type' 	=> 'project', 
				'order' 			=> 'ASC', 
				'orderby' 		=> 'date', 
				'meta_key' 		=> 'featured', 
				'meta_value' 	=> '1' );
			$the_query = new WP_Query( $args ); 
		?>
		<?php if ( $the_query->have_posts() ) : ?>	
	  
			<section id="projectssection" class="bg-inverse p-y-3" >
			
				<div class="container">
				
					<h1 class="display-5 text-xs-center text-uppercase p-y-3">
						Projects
					</h1>
				
					<?php $loop_counter = 0; ?>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

					<?php if (($loop_counter % 3) == 0): ?>
					<div class="row">
					<?php endif; ?>
				

						<div class="col-sm-4 ">
						
							<div class="thumbnail">
								<?php the_post_thumbnail('medium_large', ['class' => 'img-fluid img-responsive responsive--full', 'title' => 'Feature image']); ?> <!-- want to add img-fluid class -->
								<div class="caption p-t-1  full-height">
									<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
									<p><?php if (!empty(get_the_excerpt())) {the_excerpt(); } else {echo get_post_meta(get_the_ID(),"project_subtitle", true); } ?></p>
								</div>
								<div>
									<p class="pull-bottom"><a href="<?php the_permalink(); ?>" class="btn btn-primary" role="button">Learn more</a></p>
								</div>
							</div>
						</div>					
					
					<?php if (($loop_counter % 3) == (3 -1) || $loop_counter == $the_query->post_count - 1): ?>
					</div>
					<?php endif; ?>
					
					<?php $loop_counter++; ?>
					
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>		

					<div class="text-xs-right p-t-2">
						<p><a href="<?php echo get_post_type_archive_link('project'); ?>">See more projects &raquo;</a></p>
					</div>

				</div>
				
			</section>
			
		<?php endif; ?>
		
		<?php 
			$args = array( 
				'post_type' 	=> 'portfolio_item',
				'order' 			=> 'ASC',
				'orderby' 		=> 'date',
				'meta_key' 		=> 'featured',
				'meta_value' 	=> '1',
			);
			$the_query = new WP_Query( $args ); 
		?>
		<?php if ( $the_query->have_posts() ) : ?>		

			<section id="portfoliossection" class="p-y-3" >
			
				<div class="container">
				
					<h1 class="display-5 text-xs-center text-uppercase p-y-3">
						Portfolio
					</h1>
			
					<?php $loop_counter = 0; ?>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

					<?php if (($loop_counter % 3) == 0): ?>
					<div class="row">
					<?php endif; ?>

						<div class="col-sm-4">
							<div class="thumbnail">
								<?php the_post_thumbnail('medium_large', ['class' => 'img-fluid img-responsive responsive--full', 'title' => 'Feature image']); ?> <!-- want to add img-fluid class -->
								<div class="caption p-y-2 full-height-2">
									<h3><?php the_title(); ?></h3>
									<p><?php echo get_post_meta(get_the_ID(),"portfolio_item_details", true); ?></p>
								</div>
								<div>
									<p class="pull-bottom"><a href="<?php echo get_post_meta(get_the_ID(),"portfolio_item_link", true); ?>" class="btn btn-primary" type="button" role="button">View on Github</a></p>								
								</div>
							</div>
						</div>				

					<?php if (($loop_counter % 3) == (3 -1) || $loop_counter == $the_query->post_count - 1): ?>
					</div>
					<?php endif; ?>
					
					<?php $loop_counter++; ?>
					
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>		

					<div class="text-xs-right p-t-2">
						<p><a href="<?php echo get_post_type_archive_link('portfolio_item'); ?>">See more projects &raquo;</a></p>
					</div>
					
				</div>
				
			</section>
			
		<?php endif; ?>			
			
		<section id="publiationssection" class="p-y-2 bg-inverse">
		
			<div class="container">

			<?php 
				$term_list = array('paper', 'patent');
				
				foreach($term_list as $term) :
			
					$args = array( 
						'post_type' 	=> 'publication',
						'order' 			=> 'ASC',
						'orderby' 		=> 'date',
						'meta_key' 		=> 'featured',
						'meta_value' 	=> '1',
						'tax_query' 	=> array(
							array(
								'taxonomy' => 'publication_type',
								'field'    => 'name',
								'terms'    => $term,
							),
						),
					);
					$the_query = new WP_Query( $args ); 
			?>
			<?php if ( $the_query->have_posts() ) : ?>	
			
				<div class="row">
				
					<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 p-y-2">
					
						<h3 class="display-5 text-xs-center text-uppercase p-a-1">
							<?php echo get_term_by('name', $term, 'publication_type')->description; ?>
						</h3>
						
						<div class="text-xs-left p-y-1">
								  
								<hr class="bg-inverse"/>

								<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
								<?php list($has_href, $href, $href_type) = get_publication_href(get_the_ID()); ?>

								<div class="newslink">
								<?php if ($has_href) : ?>
								  <a class="row vertical-center"  href="<?php echo $href; ?>" >
								<?php endif; ?>
									<div class="col-xs-10 newslinkcontainer">
									  <div class="linktitle"><?php the_title(); ?></div>
									  <div class="linksource"><?php echo get_post_meta(get_the_ID(), "publication_authors", true); ?></div>
									  <div class="conferenceloc text-muted"><?php echo get_post_meta(get_the_ID(), "publication_details", true); ?></div>
									</div>
									<div class="col-xs-2">
										<i class="fa fa-chevron-right"></i>					  
									</div>
								  </a>
								</div> 

								<hr/ class="bg-inverse">	

								<?php endwhile; ?>																
							
							</div>
						
						
					</div>
				
				
				</div>
				
				<?php endif; ?>
				
				<?php endforeach; ?>

			</div>
			
		</section>	