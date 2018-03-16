<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

	<section id="primary" class="content-area col">
		<main id="main" class="site-main" role="main">
    
    <?php 
      if (is_front_page()) {
        $marketingPage1 = get_post(get_theme_mod('marketing_page_1')); 
        $marketingPage2 = get_post(get_theme_mod('marketing_page_2')); 
        
        if ($marketingPage1 && $marketingPage2) { 
          $pageThumbnail1 = get_the_post_thumbnail($marketingPage1, 'full');
          $pageThumbnail2 = get_the_post_thumbnail($marketingPage2, 'full');

          ?>
          <div class="row">
            <div class="col-md-6">
              <?php if ($pageThumbnail1) echo $pageThumbnail1 ?>
              <h2 class="marketing-page-header"><?php echo $marketingPage1->post_title?></h2>
              <?php echo apply_filters( 'the_content', $marketingPage1->post_content ); ?>
            </div>

            <div class="col-md-6">
              <?php if ($pageThumbnail2) echo $pageThumbnail2 ?>
              <h2 class="marketing-page-header"><?php echo $marketingPage2->post_title?></h2>
              <?php echo apply_filters( 'the_content', $marketingPage2->post_content ); ?>
            </div>
          </div>

        <?php 
        }
      }
      ?>

      <div class="row">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
      endif;
      
      echo '<h2 class="news-title"><div class="line"></div><span class="text">Ajankohtaista</span></h2>';

			/* Start the Loop */
			while ( have_posts() ) : the_post();
        /*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;
    else :
      
		endif; ?>
      </div>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
