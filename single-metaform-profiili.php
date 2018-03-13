<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WP_Bootstrap_Starter
 */

get_header('metaform-profiili'); ?>

	<section id="primary" class="content-area col">
		<main id="main" class="site-main" role="main">         
		<?php
		while ( have_posts() ) : the_post();
                ?>
                  <div class="text-center">
                      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>
                            <div class="entry-content">
                    <?php
                          if (is_single()):
                            the_content();
                          else:
                            the_content('');
                          endif;
                    ?>
                  </div><!-- .entry-content -->
                  <footer class="entry-footer">
                          <?php wp_bootstrap_starter_entry_footer(); ?>
                  </footer><!-- .entry-footer -->
                  </article><!-- #post-## -->
                  </div>         
              <?php
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer('metaform-profiili');
