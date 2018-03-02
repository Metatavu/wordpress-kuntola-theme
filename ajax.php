<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  
  add_action( 'wp_ajax_kunta_api_search_codes', function () {
    try {
      $types = $_POST['types'];
      $search = $_POST['search'];
      $results = [];
      $codes = \KuntaAPI\Core\Api::getCodesApi()->listCodes($types, $search);
      
      foreach ($codes as $code) {
        $results[] = $code->__toString();
      }
      
      echo '[';
      echo join(',', $results);
      echo ']';
      
      wp_die();
    } catch (\KuntaAPI\ApiException $e) {
      $message = json_encode($e->getResponseBody());
      wp_die($message, null, [
        response => $e->getCode()
      ]);
    }
  });
  
  add_action( 'wp_ajax_kunta_api_find_organization', function () {
    try {
      $organizationId = $_POST['id'];
      $organization = \KuntaAPI\Core\Api::getOrganizationsApi()->findOrganization($organizationId);
      echo $organization->__toString();
      wp_die();
    } catch (\KuntaAPI\ApiException $e) {
      $message = json_encode($e->getResponseBody());
      wp_die($message, null, [
        response => $e->getCode()
      ]);
    }
  });
  
  add_action( 'wp_ajax_kunta_api_search_organizations', function () {
    try {
      $search = $_POST['search'];
      $results = [];
      $organizations = \KuntaAPI\Core\Api::getOrganizationsApi()->listOrganizations(null, null, $search, null, null, null, 10);
      
      foreach ($organizations as $organization) {
        $results[] = $organization->__toString();
      }
      
      echo '[';
      echo join(',', $results);
      echo ']';
      
      wp_die();
    } catch (\KuntaAPI\ApiException $e) {
      $message = json_encode($e->getResponseBody());
      wp_die($message, null, [
        response => $e->getCode()
      ]);
    }
  });
  

?>