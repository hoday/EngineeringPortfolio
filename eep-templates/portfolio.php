<div class="eep-portfolio">
		<?php echo "shortcode rendered"; ?>


</div>
<?php
	$args = array(
		'post_type' 	=> 'project',
		'order' 		=> 'DESC',
		'orderby' 		=> 'date',
		'meta_key' 		=> 'featured',
		'meta_value' 	=> '1'
    );
    $the_query = new WP_Query($args);

     if ($the_query->have_posts()) {
    	include 'portfolio-section-projects.php';
    }
?>

<?php
	$args = array(
		'post_type' 	=> 'portfolio_item',
		'order' 		=> 'DESC',
		'orderby' 		=> 'date',
		'meta_key' 		=> 'featured',
		'meta_value' 	=> '1',
	);
	$the_query = new WP_Query( $args );
    if ($the_query->have_posts()) {
       include 'portfolio-section-portfolioitems.php';
   }
?>


<?php
    $term_list = array('paper', 'patent');


    $args = array(
        'post_type' 	=> 'publication',
        'order' 		=> 'ASC',
        'orderby' 		=> 'date',
        'meta_key' 		=> 'featured',
        'meta_value' 	=> '1',
        'tax_query' 	=> array(
            array(
                'taxonomy' => 'publication_type',
                'field'    => 'name',
                'terms'    => $term_list,
            ),
        ),
    );
    $the_test_query = new WP_Query( $args );
    if ( $the_test_query->have_posts() ) {
        include 'portfolio-section-publications.php';
    }

?>
