<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wp-bootstrap-starter' ); ?></a>
    <?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
	<header id="masthead" class="site-header navbar-static-top <?php echo wp_bootstrap_starter_bg_class(); ?>" role="banner">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0">
                <div class="navbar-brand">
                    <?php if ( get_theme_mod( 'wp_bootstrap_starter_logo' ) ): ?>
                        <a href="<?php echo esc_url( home_url( '/' )); ?>">
                            <img src="<?php echo esc_attr(get_theme_mod( 'wp_bootstrap_starter_logo' )); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                        </a>
                    <?php else : ?>
                        <a class="site-title" href="<?php echo esc_url( home_url( '/' )); ?>"><?php esc_url(bloginfo('name')); ?></a>
                    <?php endif; ?>

                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fa fa-bars"></i>
                </button>

                <?php
                wp_nav_menu(array(
                'theme_location'    => 'primary',
                'container'       => 'div',
                'container_id'    => '',
                'container_class' => 'collapse navbar-collapse justify-content-end',
                'menu_id'         => false,
                'menu_class'      => 'navbar-nav',
                'depth'           => 3,
                'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
                'walker'          => new wp_bootstrap_navwalker()
                ));
                ?>
            </nav>
        </div>
  </header><!-- #masthead -->
    <?php 
    if (is_front_page()):?>
      <div class="container-flush text-center logoset-container">
        <img src="<?php bloginfo('stylesheet_url'); ?>../../gfx/footer-logos.jpg"/>
      </div>
    <?php endif; ?>
    
    
    <?php if(is_front_page() && !get_theme_mod( 'header_banner_visibility' )): ?>
        <div id="page-sub-header" <?php if(has_header_image()) { ?>style="background-image: url('<?php header_image(); ?>');" <?php } ?>>
            <div class="container">
              <div class="row">
                <div class="col-lg text-container">
                  <?php if (!empty(get_theme_mod( 'header_banner_title_setting' ))) { ?>
                  <div class="row  header-banner-container">
                    <div class="col">
                      <h1 class="header-banner-heading"><?php echo get_theme_mod( 'header_banner_title_setting' );?></h1>
                    </div>
                    <div class="container calltoaction-container">
                      <div class="row">
                        <div class="col-lg">
                          <h2 class="call-to-action-heading"><?php echo get_theme_mod( 'header_calltoaction_title' );?></h2>
                          <p><?php echo get_theme_mod( 'header_calltoaction_text' );?></p>
                        </div>
                        <div class="col-lg login-btn-container">
                          <a class="btn btn-primary header-banner-button" href="
                            <?php 
                              if (!empty(get_theme_mod( 'header_calltoaction_link' ))) {
                                echo get_theme_mod( 'header_calltoaction_link' );
                              } else {
                                echo '/profile';
                              }
                            ?>
                          ">
                            Rekisteröidy
                          </a> 
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                  <?php if (!empty(get_theme_mod( 'header_banner_tagline_setting' ))) { ?>
                  <div class="row">
                    <div class="col">
                      <h1><?php echo get_theme_mod( 'header_banner_tagline_setting' );?></h1>
                    </div>
                  </div>
                  <?php } ?>
                </div>
                <div class="col-lg image-container">
                  <img src="<?php echo esc_attr(get_theme_mod( 'kuntola_header_image_right' )); ?>"/>
                </div>                
              </div>
            </div>
        </div>
    <?php endif; ?>


  <?php if (is_front_page()) { ?>
    <?php $profilePage = get_post(get_theme_mod('profile_banner_page')); ?>

    <div class="profile-container" style="background-image: url(<?php echo esc_attr(get_theme_mod('profile_banner_background' )); ?>)">
      <div class="container"> 
        <div class="row"> 
          <div class="col text-center">
            <h2><?php echo $profilePage->post_title?></h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg">  
            <?php echo apply_filters( 'the_content', $profilePage->post_content ); ?>
          </div>

          <?php $postThumbnail = get_the_post_thumbnail($profilePage, 'full'); ?>
          <?php if ($postThumbnail) { ?>
          <div class="col profile-image-container d-none d-lg-block">  
            <?php echo get_the_post_thumbnail($profilePage, 'full');?>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>

	<div id="content" class="site-content">
		<div class="container">
			<div class="row">
                <?php endif; ?>