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

<body <?php body_class('metaform-profiili'); ?>>
<div id="page" class="site">
  
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wp-bootstrap-starter' ); ?></a>
    <?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
	
    <?php if(is_front_page() && !get_theme_mod( 'header_banner_visibility' )): ?>
        <div id="page-sub-header" <?php if(has_header_image()) { ?>style="background-image: url('<?php header_image(); ?>');" <?php } ?>>
            <div class="container">
              <div class="row">
                <div class="col-lg text-container">
                  <?php if (!empty(get_theme_mod( 'header_banner_title_setting' ))) { ?>
                  <div class="row">
                    <div class="col">
                      <h1><?php echo get_theme_mod( 'header_banner_title_setting' );?></h1>
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
    <div class="container calltoaction-container">
      <div class="row">
        <div class="col-lg">
          <h2><?php echo get_theme_mod( 'header_calltoaction_title' );?></h2>
          <p><?php echo get_theme_mod( 'header_calltoaction_text' );?></p>
        </div>
        <div class="col-lg login-btn-container">
          <a class="btn btn-primary" href="/profile">Rekisteröidy</a>
        </div>
      </div>
    </div>
  <?php } ?>

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

          <div class="col profile-image-container d-none d-lg-block">  
            <?php echo get_the_post_thumbnail($profilePage, 'full');?>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

	<div id="content" class="site-content">
		<div class="container">
			<div class="row">
                <?php endif; ?>