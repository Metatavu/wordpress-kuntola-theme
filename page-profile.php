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
function hasMetaformAnswers($slug) {
  $metaforms = get_posts( [
    "post_type" => "metaform", 
    "name" => $slug,
    "post_status" => "publish"
  ]);
  $metaform = array_shift($metaforms);
  if ($metaform) {
    $metaformId = $metaform->ID;
    $values = get_user_meta(wp_get_current_user()->ID, "metaform-$metaformId-values", true);
    return !!$values;
  }
  return null;
}
function getMetaforms($categories) {
  $categoryIds = [];
  foreach ($categories as $category) {
    $categoryIds[] = $category->cat_ID;
  }
  return  get_posts( [
    "post_type" => "metaform", 
    "post_status" => "publish",
    "category" => implode(",", $categoryIds)
  ]);
}
function getCategories($categorySlugs) {
  $result = [];
  foreach ($categorySlugs as $categorySlug) {
    $result[] = get_category_by_slug($categorySlug);
  }
  return $result;
}

function getCoins() {
  $userId = wp_get_current_user()->ID;
  $result = intval(get_user_meta($userId, "hyvio_coins", true));
  return $result ? $result : 0;
}

add_filter( 'body_class', function( $classes ) {
  return array_merge( $classes, ['profile-page']);
});
add_action( 'wp_enqueue_scripts', function () {
  wp_register_style('jquery-ui', '//cdn.metatavu.io/libs/jquery-ui/1.12.1/jquery-ui.min.css');
  wp_enqueue_style('jquery-ui');
  wp_enqueue_script('profile-scripts', get_stylesheet_directory_uri() . '/profile-scripts.js', ['jquery-ui-dialog']);
} , 100);
// If user has not aswered "taustatiedot" -query, we will redirect him/her to the form
if (!hasMetaformAnswers("taustatiedot")) {
  wp_redirect("/metaform/taustatiedot/");
  exit;
}
$categories = getCategories(["ominaisuudet", "kayttaytyminen", "terveys", "palvelut", "mina"]);
$metaforms = getMetaforms($categories);
$queryAnsweredCount = 0;
$queryTotalCount = count($metaforms);
foreach ($metaforms as $metaform) {
  $postSlug = $metaform->post_name;
  if (hasMetaformAnswers($postSlug)) {
    $queryAnsweredCount++;
  } else {
    if ($_GET['next-query'] === 'true') {
      wp_redirect("/metaform/$postSlug/");
      exit;
    }
  }
}
get_header(); ?>

	<section id="primary" class="content-area col">
          
                <?php
                  $coins = getCoins();
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
                  
                  if ($motivationUserPoints > 0) {
                    echo '<main id="main" class="site-main" role="main">';
                  } else {
                    echo '<main id="main" class="site-main not-answered" role="main">';
                  }
                ?>
                <div class="row">
                  <div class="col">
                    <h4 class="text-center">Sinulla on <?php echo "$coins"; ?> <img class="profile-coin-image" src="<?php bloginfo('stylesheet_url'); ?>../../gfx/hyviocoin.png" /> hyviökolikkoa</h4>
                  </div>
                </div>

                  <div class="row">
                    <div class="col">
                      <a href="/queries?form_category=ominaisuudet">
                        <h3>Ominaisuuteni</h3>
                        <p class="profile-points profile-points-properties"><?php echo $propertiesUserPoints .' / ' . $propertiesMaxPoints ?></p>
                      </a>
                    </div>
                    <div class="col">
                      <a href="/queries?form_category=käyttäytyminen">
                        <h3>Käyttäytymiseni</h3>
                        <p class="profile-points profile-points-behaviour"><?php echo $behaviourUserPoints .' / ' . $behaviourMaxPoints ?></p>
                      </a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <a href="/queries?form_category=minä">
                        <h3 class="me-title">Minä</h3>
                        <img class="me-profile-btn" src="<?php bloginfo('stylesheet_url'); ?>../../gfx/profile-center-btn.png" />
                        <p class="profile-points profile-points-motivation"><?php echo $motivationUserPoints .' / ' . $motivationMaxPoints ?></p>
                      </a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <a href="/queries?form_category=terveys">
                        <h3>Terveyteni</h3>
                        <p class="profile-points profile-points-health"><?php echo $healthUserPoints .' / ' . $healthMaxPoints ?></p>
                      </a>
                    </div>
                    <div class="col">
                      <a href="/queries?form_category=palvelut">
                        <h3>Palveluni</h3>
                        <p class="profile-points profile-points-services"><?php echo $servicesUserPoints .' / ' . $servicesMaxPoints ?></p>
                      </a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <h4 class="text-center">Olet vastannut <?php echo "$queryAnsweredCount / $queryTotalCount"; ?> kyselyyn</h4>
                    </div>
                  </div>
		</main><!-- #main -->
	</section><!-- #primary -->
      </div><!-- .container -->
    </div><!-- #content -->
  </div><!-- #page -->
  <?php wp_footer(); ?>
</body>
</html>
