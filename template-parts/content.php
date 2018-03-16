<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<?php if (is_single()) { ?>
<div class="col-lg">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div>
	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<?php
		endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
      if (is_single()):
			  the_content();
      else:
        the_content('');
      endif;

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wp-bootstrap-starter' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php wp_bootstrap_starter_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
</div>
<?php } elseif ('post' === get_post_type())  { ?>
  <div class="col-12 post">
    <article class="row" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="col-6 pl-0">
        <div class="post-thumbnail" style="background-image:url(<?php echo has_post_thumbnail() ? the_post_thumbnail_url() : "" ?>)"></div>
      </div>
      <div class="col-6">
        <header class="entry-header"><?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink()) . '" rel="bookmark">', '</a></h2>' ); ?></header>
        <div class="entry-content">
          <?php
            the_content('');
            echo '<a class="btn btn-primary" href="' . esc_url( get_permalink()) . '">Lue lisää</a>';
          ?>
        </div>
      </div>
    </article>
  </div>
<?php } ?>