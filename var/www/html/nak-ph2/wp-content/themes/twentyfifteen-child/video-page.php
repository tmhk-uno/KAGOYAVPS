<?php
/*
Template Name: video-page
*/
?>

<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
$image_rand = array(
    "http://133.18.208.167/nak-ph2/wp-content/uploads/2021/10/production-ID_4228659.mp4" ,
    "http://133.18.208.167/nak-ph2/wp-content/uploads/2021/10/pexels-mikhail-nilov-7441154.mp4",
    "http://133.18.208.167/nak-ph2/wp-content/uploads/2021/10/Pexels-Videos-2099568.mp4",
);
 
$image_rand = $image_rand[mt_rand(0, count($image_rand)-1)];
echo '<video src="'.$image_rand.'"  loop autoplay muted></video>';
?>

		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();

			// Include the page content template.
			get_template_part( 'content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
