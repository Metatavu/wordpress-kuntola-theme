<?php
/**
 * The template for displaying all profile page
 *
 */

/**
 * Gets total points that user has answered to given metaform
 * 
 * @param object $metaform metaform post object
 * @return int total points answered by user
 */
function getMetaformUserPoints($metaform) {
  $skipKeys = ['page', 'page-count'];
  $metaformId = $metaform->ID;
  $json = get_user_meta(wp_get_current_user()->ID, "metaform-$metaformId-values", true);
  $userValues = json_decode($json, true);
  $result = 0;
  
  
  foreach ($userValues as $key => $value) {
    $intvalue = intval($value);
    
    if (in_array ($key , $skipKeys)) {
      continue;
    }

    $result += $intvalue;
  }
  
   return $result;
}

/**
 * Gets total possible points from give metaform
 * 
 * @param object $metaform metaform post object
 * @return int total possible points from give metaform
 */
function getMetaformMaxPoints($metaform) {
  $json = get_post_meta($metaform->ID, "metaform-json", true);
  if (!$json) {
    error_log("Could not read metaform $metaform->ID json");
    return 0;
  }
  
  $object = json_decode($json);
  if (!$object) {
    error_log("Could not parse metaform $metaform->ID json");
    return 0;
  }

  $result = 0;

  $sections = $object->sections;
  foreach ($sections as $section) {
    foreach ($section->fields as $field) {
      if ($field->type === 'radio') {
        $result += count($field->options);
      }
    }
  }

  return $result;
}

/**
 * Gets  total possible points from given category
 * 
 * @param string $categoryName name of the category
 * @return int total points from given category
 */
function getMetaformMaxPointsByCategory($categoryName) {
  $result = 0;
  $metaformsByCategory = get_posts( ["post_type" => "metaform", "category_name" => $categoryName] );
  foreach ($metaformsByCategory as $metaform) {
    $result += getMetaformMaxPoints($metaform);
  }
  
  return $result;
}


/**
 * Gets total points from given category by user
 * 
 * @param string $categoryName name of the category
 * @return int total points from given category
 */
function getMetaformUserPointsByCategory($categoryName) {
  $result = 0;
  $metaformsByCategory = get_posts( ["post_type" => "metaform", "category_name" => $categoryName] );
  foreach ($metaformsByCategory as $metaform) {
    $result += getMetaformUserPoints($metaform);
  }
  
  return $result;
}

add_filter( 'body_class', function( $classes ) {
  return array_merge( $classes, ['profile-page']);
});

get_header(); ?>

	<section id="primary" class="content-area col">
		<main id="main" class="site-main" role="main">
                  <?php
                    $propertiesMaxPoints = getMetaformMaxPointsByCategory("ominaisuudet");
                    $behaviourMaxPoints = getMetaformMaxPointsByCategory("käyttäytyminen");
                    $healthMaxPoints = getMetaformMaxPointsByCategory("terveys");
                    $servicesMaxPoints = getMetaformMaxPointsByCategory("palvelut");
                    $motivationMaxPoints = getMetaformMaxPointsByCategory("minä");
                    
                    $propertiesUserPoints = getMetaformUserPointsByCategory("ominaisuudet");
                    $behaviourUserPoints = getMetaformUserPointsByCategory("käyttäytyminen");
                    $healthUserPoints = getMetaformUserPointsByCategory("terveys");
                    $servicesUserPoints = getMetaformUserPointsByCategory("palvelut");
                    $motivationUserPoints = getMetaformUserPointsByCategory("minä");
                  ?>
                  
                  <div class="row">
                    <div class="col">
                      <a href="/queries?form_category=ominaisuudet">
                        <h3>Ominaisuuteni</h3>
                        <p><?php echo $propertiesUserPoints .' / ' . $propertiesMaxPoints ?></p>
                      </a>
                    </div>
                    <div class="col">
                      <a href="/queries?form_category=käyttäytyminen">
                        <h3>Käyttäytymiseni</h3>
                        <p><?php echo $behaviourUserPoints .' / ' . $behaviourMaxPoints ?></p>
                      </a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <a href="/queries?form_category=minä">
                        <img class="me-profile-btn" src="<?php bloginfo('stylesheet_url'); ?>../../gfx/profile-center-btn.png" />
                      </a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <a href="/queries?form_category=terveys">
                        <h3>Terveyteni</h3>
                        <p><?php echo $healthUserPoints .' / ' . $healthMaxPoints ?></p>
                      </a>
                    </div>
                    <div class="col">
                      <a href="/queries?form_category=palvelut">
                        <h3>Palveluni</h3>
                        <p><?php echo $servicesUserPoints .' / ' . $servicesMaxPoints ?></p>
                      </a>
                    </div>
                  </div>
		</main><!-- #main -->
	</section><!-- #primary -->
      </div><!-- .container -->
    </div><!-- #content -->
  </div><!-- #page -->
</body>
</html>
