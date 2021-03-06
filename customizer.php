<?php
  function kuntolaCustomizeHeaderImage($wp_customize) {
    $section = 'header_image';
    
    $wp_customize->add_setting( 'kuntola_header_image_right', [
      'sanitize_callback' => 'esc_url'
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control( $wp_customize, 'kuntola_header_image_right', [
      'label'    => __( 'Right image', 'kuntola'),
      'section'  => $section,
      'settings' => 'kuntola_header_image_right'
    ]));
  }

  function kuntolaCustomizeCallToAction($wp_customize) {
    $section = 'kuntola_call_to_action';

    $wp_customize->add_section($section, [
      'title' => __("Call to action", 'kuntola'),
      'priority' => 30
    ]);

    $wp_customize->add_setting( 'header_calltoaction_title', array(
      'sanitize_callback' => 'wp_filter_nohtml_kses',
    ));

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'header_calltoaction_title', [
      'label' => __( 'Title', 'kuntola' ),
      'section' => $section,
      'settings' => 'header_calltoaction_title',
      'type' => 'text'
    ]));

    $wp_customize->add_setting( 'header_calltoaction_text', []);

    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'header_calltoaction_text', [
      'label' => __( 'Text', 'kuntola' ),
      'section' => $section,
      'settings' => 'header_calltoaction_text',
      'type' => 'text'
    ]));
    
    $wp_customize->add_setting( 'header_calltoaction_link', []);
    
    $wp_customize->add_control( new WP_Customize_Control($wp_customize, 'header_calltoaction_link', [
      'label' => __( 'Link href', 'kuntola' ),
      'section' => $section,
      'settings' => 'header_calltoaction_link',
      'type' => 'textarea'
    ]));
  }

  function kuntolaCustomizeProfileBanner($wp_customize) {
    $section = 'kuntola_profile_banner';

    $wp_customize->add_section($section, [
      'title' => __("Profile banner", 'kuntola'),
      'priority' => 30
    ]);

    $wp_customize->add_setting('profile_banner_page', [
      'default' => '0'
    ]);

    $wp_customize->add_control(	'profile_banner_page', [
      'type' => 'dropdown-pages',
      'label' =>  __( 'Page', 'kuntola' ),
      'section' => $section
    ]);

    $wp_customize->add_setting( 'profile_banner_background', [
      'sanitize_callback' => 'esc_url'
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'profile_banner_background', [
      'label'    => __( 'Background image', 'kuntola' ),
      'section'  => $section,
      'settings' => 'profile_banner_background'
    ]));
  }

  function kuntolaCustomizeMarketingPages($wp_customize) {
    $section = 'kuntola_marketing_pages';

    $wp_customize->add_section($section, [
      'title' => __("Marketing pages", 'kuntola'),
      'priority' => 30
    ]);

    $wp_customize->add_setting('marketing_page_1', [
      'default' => '0'
    ]);

    $wp_customize->add_control(	'marketing_page_1', [
      'type' => 'dropdown-pages',
      'label' =>  __( 'Page', 'kuntola' ),
      'section' => $section
    ]);

    $wp_customize->add_setting('marketing_page_2', [
      'default' => '0'
    ]);

    $wp_customize->add_control(	'marketing_page_2', [
      'type' => 'dropdown-pages',
      'label' =>  __( 'Page', 'kuntola' ),
      'section' => $section
    ]);
  }

  function kuntolaCustomizeFooterBanner($wp_customize) {
    $section = 'kuntola_footer_banner';

    $wp_customize->add_section($section, [
      'title' => __("Footer banner", 'kuntola'),
      'priority' => 30
    ]);
    
    $wp_customize->add_setting('footer_banner_page', [
      'default' => '0'
    ]);

    $wp_customize->add_control(	'footer_banner_page', [
      'type' => 'dropdown-pages',
      'label' =>  __( 'Page', 'kuntola' ),
      'section' => $section
    ]);

    $wp_customize->add_setting( 'footer_banner_background', [
      'sanitize_callback' => 'esc_url'
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_banner_background', [
      'label'    => __( 'Background image', 'kuntola' ),
      'section'  => $section,
      'settings' => 'footer_banner_background'
    ]));

    $wp_customize->add_setting( 'footer_banner_image_left', [
      'sanitize_callback' => 'esc_url'
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_banner_image_left', [
      'label'    => __( 'Banner image left', 'kuntola' ),
      'section'  => $section,
      'settings' => 'footer_banner_image_left'
    ]));
  }

  add_action('customize_register', function ($wp_customize) {
    kuntolaCustomizeHeaderImage($wp_customize);
    kuntolaCustomizeCallToAction($wp_customize);
    kuntolaCustomizeProfileBanner($wp_customize);
    kuntolaCustomizeMarketingPages($wp_customize);
    kuntolaCustomizeFooterBanner($wp_customize);
  });
?>