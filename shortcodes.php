<?php

  /**
   * Returns all answer to a metaform
   * 
   * @param int $id metaform id
   * 
   * @return array all answer to a metaform
   */
  function getMetaformValues($id) {
    global $wpdb;
    $select = "SELECT $wpdb->usermeta.meta_value FROM $wpdb->usermeta WHERE $wpdb->usermeta.meta_key = 'metaform-${id}-values'";
    $results = $wpdb->get_results($select);

    $values = [];

    foreach ($results as $result) {
      $values[] = json_decode($result->meta_value, true);
    }

    return $values;
  }

  /**
   * Returns category averages for metaform answers
   * 
   * @param array $categoryMap category map
   * @param array $values array of user value arrays
   * 
   * @return array value averages
   */
  function getMetaformCategoryAverageValues($categoryMap, $values) {
    $skipKeys = ['page', 'page-count'];

    foreach ($values as $userValues) {
      foreach ($userValues as $key => $value) {
        $intvalue = intval($value);

        if (in_array ($key , $skipKeys)) {
          continue;
        }

        $category = $categoryMap[$key];

        if ($averageArrays[$category]) {
          $averageArrays[$category][] = $intvalue;
        } else {
          $averageArrays[$category] = [$intvalue];
        }

      }
    }

    foreach ($averageArrays as $key => $averageArray) {
      $averages[$key] = array_sum($averageArray) / count($averageArray);
    }

    return $averages;
  }

  add_filter( 'query_vars', function ( $vars ){
    $vars[] = "form_category";
    $vars[] = "query_name";
    $vars[] = "query_id";
    return $vars;
  });
  
  add_action( 'wp_enqueue_scripts', function () {

    add_shortcode('metaform_list', function ($tagAttrs) {
      $category = get_query_var('form_category', 'ominaisuudet');

      $metaformsByCategory = get_posts( ["post_type" => "metaform", "category_name" => $category] );
      ?>
      <div class="card">
        <ul class="list-group list-group-flush">
            <?php
            foreach ($metaformsByCategory as $metaform) {
              $metaformId = $metaform->ID;
              $answered = !empty(get_user_meta(wp_get_current_user()->ID, "metaform-$metaformId-values", true));
              ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $metaform->post_title ?>
                <div class="btn-container">
                  <a class="btn btn-primary" href="<?php the_permalink($metaform)?>">Vastaa</a>
                  <a class="btn btn-info <?php if (!$answered) { echo 'disabled'; } ?>"  href="<?php echo "/results?query_name=" . $metaform->post_name ?>">Tulokset</a>
                </div>
              </li>
              <?php
            }
            ?>
        </ul>
      </div>
    <?php
    });
    
    add_shortcode('metaform_averages', function ($tagAttrs) {
      wp_enqueue_script('chartjs', '//cdn.metatavu.io/libs/chart-js/2.5.0/Chart.min.js');

      $categoryMap = [
        "ansaitakseni-elantoni" => "Muiden odotukset",
        "koska-se-auttaaminua-rentoutumaan" => "Henkinen hyvinvointi",
        "koska-se-on-mielenkiintoista" => "Huvi ja nautinto",
        "jotta-voisin-liikkua-yhdessa-muiden-kanssa" => "Yhteenkuuluvuus",
        "kehittyakseni" => "Osaaminen ja taitojen hallitseminen",
        "ollakseni-paremmassa-kunnossa-kuin-muut" => "Kilpailullisuus/ego",
        "koska-minulle-maksetaan-siita" => "Muiden odotukset",
        "koska-nautin-ajanvietosta-muiden-seurassa-liikkuen" => "Yhteenkuuluvuus",
        "koska-haluan-hallita-stressia-paremmin" => "Henkinen hyvinvointi",
        "koska-sen-avulla-keho-pysyy-terveena" => "Fyysinen hyvinvointi",
        "koska-se-saa-lihakset-nayttamaan-paremmilta" => "Ulkonäkö",
        "ollakseni-fyysisesti-hyvassa-kunnossa" => "Fyysinen hyvinvointi",
        "koska-se-tekee-minut-onnelliseksi" => "Huvi ja nautinto",
        "unohtaakseni-tyo-arkihuolet" => "Henkinen hyvinvointi",
        "yllapitaakseni-fyysista-terveyttani" => "Fyysinen hyvinvointi",
        "parantaakseni-nykyisia-taitojani" => "Osaaminen ja taitojen hallitseminen",
        "ollakseni-ryhman-paras" => "Kilpailullisuus/ego",
        "hoitaakseni-sairautta-tai-vaivaa" => "Muiden odotukset",
        "parantaakseni-suorituskykyani-verrattuna-aikaisempiin-suorituksiini" => "Osaaminen ja taitojen hallitseminen",
        "koska-se-on-yhteista-minulle-ja-ystavilleni" => "Yhteenkuuluvuus",
        "koska-muiden-mielesta-tarvitsen-liikuntaa" => "Muiden odotukset",
        "koska-se-toimii-stressin-laukaisijana" => "Henkinen hyvinvointi",
        "parantaakseni-kehoni-muotoa" => "Ulkonäkö",
        "oppiakseni-uusia-taitoja-tai-kokeillakseni-uusia-liikuntamuotoja" => "Osaaminen ja taitojen hallitseminen",
        "koska-se-on-hauskaa" => "Huvi ja nautinto",
        "laakarin-fysioterapeutin-tms-maarayksesta" => "Muiden odotukset",
        "tehdakseni-kuntoni-eteen-enemman-kuin-muut-ihmiset" => "Kilpailullisuus/ego",
        "koska-se-pitaa-minut-terveena" => "Fyysinen hyvinvointi",
        "kilpaillakseni-muiden-kanssa" => "Kilpailullisuus",
        "koska-voin-samalla-keskustella-ystavieni-kanssa" => "Yhteenkuuluvuus",
        "pitaakseni-ylla-nykyista-taitotasoani" => "Osaaminen ja taitojen hallitseminen",
        "parantaakseni-ulkonakoani" => "Ulkonäkö",
        "parantaakseni-kestavyyskuntoani" => "Fyysinen hyvinvointi",
        "koska-nautin-liikkumisesta" => "Huvi ja nautinto",
        "koska-liikunta-saa-ajatukset-muualle" => "Henkinen hyvinvointi",
        "pudottaakseni-painoa-jotta-nayttaisin-paremmalta" => "Ulkonäkö",
        "koska-koen-sen-hauskana" => "Huvi ja nautinto",
        "ollakseni-ystavieni-kanssa" => "Yhteenkuuluvuus",
        "ollakseni-paremmassa-kunnossa-kuin-muut" => "Kilpailullisuus/ego",
        "koska-se-auttaa-minua-yllapitamaan-kehoni-jantevana" => "Ulkonäkö"
      ];

      $categories = [];
      
      foreach ($categoryMap as $key => $value) {
        if (!in_array ($value , $categories)) {
          $categories[] = $value;
        }
      }
      
      $metaform = null;
      $metaformName = get_query_var('query_name');
      
      if (empty($metaformName)) {
        $metaformId = get_query_var('query_id');
        $metaform = get_post($metaformId);
      } else {
        $metaforms = get_posts([
          'name' => $metaformName,
          'post_type' => 'metaform',
          'numberposts' => 1
        ]);
        
        $metaform = $metaforms[0];
      }
      
      if (!empty($metaform)) {
        $id = $metaform->ID;

        $values = getMetaformValues($id);

        $userValues = json_decode(get_user_meta(wp_get_current_user()->ID, "metaform-$id-values", true), true);

        $averages = getMetaformCategoryAverageValues($categoryMap, $values);
        $userAverages = getMetaformCategoryAverageValues($categoryMap, [$userValues]);

        $highestCategoryName = '';
        $highestCategoryValue = 0;
        foreach ($userAverages as $categoryName => $categoryValue) {
          if ($categoryValue > $highestCategoryValue) {
            $highestCategoryName = $categoryName;
            $highestCategoryValue = $categoryValue;
          }
        }
        ?>
        <div class="jumbotron jumbotron-fluid result-header-container" style="background-image: url(<?php echo esc_attr(get_theme_mod('profile_banner_background' )); ?>)">
          <div class="container">
            <div class="row">
              <div class="col-lg-5">
                <img src="<?php bloginfo('stylesheet_url'); ?>../../gfx/<?php echo sanitize_title($highestCategoryName) ?>.png" />
              </div>
              <div class="col-lg-7">
                <div class="row">
                  <h2>Kiitos vastauksista!<br/>Hyvinvointimoottorisi on:</h2>
                </div>
                <div class="row">
                  <h1 class="result-header"><?php echo $highestCategoryName ?></h1>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <?php
        echo '<div class="container">';
        echo '<h1 class="center-block text-center">Tulokset</h1>';
        echo sprintf('<canvas id="metaform-averages" width="400" height="400" data-values="%s"/>', htmlspecialchars(json_encode([
          userAverages => $userAverages,
          averages => $averages,
          categories => $categories
        ])));
        echo '</div>';
      }
    });
  } , 99);
  
?>