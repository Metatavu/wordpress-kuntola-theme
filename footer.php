<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?>
<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->

  <?php if (is_front_page()) { ?>
    <?php $footerPage = get_post(get_theme_mod('footer_banner_page')); ?>

    <footer id="footer-banner" style="background-image: url(<?php echo esc_attr(get_theme_mod('footer_banner_background' )); ?>)">
      <div class="container"> 
        <div class="row">
          <div class="col-lg footer-image-container">
            <img src="<?php echo esc_attr(get_theme_mod( 'footer_banner_image_left' )); ?>"/>
          </div>

          <div class="col-lg footer-text-container">
            <h2><?php echo $footerPage->post_title?></h2>
            <?php echo apply_filters( 'the_content', $footerPage->post_content ); ?>
          </div>

        </div>
      </div>
    </footer>
  <?php } ?>
  
  <?php get_template_part( 'footer-widget' ); ?>

  <footer id="colophon" class="site-footer <?php echo wp_bootstrap_starter_bg_class(); ?>" role="contentinfo">
    <div class="text-right">
      <a href="/rekisteriseloste">Rekisteriseloste</a>
    </div>
	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>