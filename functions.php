<?php

  require_once( __DIR__ . '/shortcodes.php');
  require_once( __DIR__ . '/customizer.php');

  add_action('wp', function () {
    if (!is_front_page() && !is_user_logged_in()) {
      global $wp;
      $path = $wp->request;
      foreach (["profile", "metaform"] as $protectedPath) {
        $prefix = substr($path, 0, strlen($protectedPath));
        if ($prefix === $protectedPath) {
          auth_redirect();
          return;
        }
      }
    }
  });

  add_filter('show_admin_bar', function () {
    return false;
  });

  add_filter( 'wp_nav_menu_items', function ($items, $args) {
    if (is_user_logged_in() && $args->theme_location == 'primary') {
      $items .= '<li class="nav-item"><a class="btn btn-primary" href="'. wp_logout_url() .'">Kirjaudu ulos</a></li>';
    } elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
      $items .= '<li class="nav-item"><a class="btn btn-primary" href="/profile">Kirjaudu sisään</a></li>';
    }

    return $items;
  }, 10, 2);

  add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css' );
    wp_dequeue_style('wp-bootstrap-starter-style');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style']);
    wp_enqueue_script('metaform-scripts', get_stylesheet_directory_uri() . '/metaform-scripts.js', ['jquery-effects-core', 'jquery-effects-slide', 'jquery-ui-slider']);
  } , 99);

?>