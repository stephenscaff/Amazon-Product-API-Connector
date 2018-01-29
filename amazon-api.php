<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Amzn API
 * Class that builds our connection to the amazon product api
 */
class AmznApi {

  /**
   * AWS Access Key
   */
  protected $aws_access_key = '';

  /**
   * AWS Secret Key
   */
  protected $aws_secret_key = '';

  /**
   * AWS Associate Tag
   */
  protected $aws_associate_tag = '';

  /**
   * Endpoint / Region (US)
   */
   public $endpoint = "";

  /**
   * Constructor
   */
  function __construct() {
    $this->aws_access_key = 'XXXXXXXXXXXX'; // or, use fields: esc_attr( get_option("amzn_apai-aws-access-key") );
    $this->aws_secret_key = 'xxxxxxxxxxxx'; // or, use fields: esc_attr( get_option("amzn_apai-aws-secret-key") );
    $this->aws_associate_tag = 'yomoms-20'; // or, use fields: esc_attr( get_option("amzn_apai-associate-tag") );
    $this->endpoint = "webservices.amazon.com";
  }

  /**
   * Created Signed URL
   */
  public function create_signed_url($asin) {

    $uri = "/onca/xml";

    $params = array(
      "Service" => "AWSECommerceService",
      "Operation" => "ItemLookup",
      "AWSAccessKeyId" => $this->aws_access_key,
      "AssociateTag" => $this->aws_associate_tag,
      "ItemId" => $asin,
      "IdType" => "ASIN",
      "ResponseGroup" => "ItemAttributes,Offers"
    );

    // Set current timestamp
    if (!isset($params["Timestamp"])) {
      $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
    }

    // Sort the params by key
    ksort($params);

    $pairs = array();

    foreach ($params as $key => $value) {
      array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
    }

    $canonical_query_string = join("&", $pairs);
    $string_to_sign = "GET\n".$this->endpoint."\n".$uri."\n".$canonical_query_string;
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->aws_secret_key, true));
    $apai_signed_url = 'http://'.$this->endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

    return $apai_signed_url;
  }

  /**
   * Parse XML
   * Parses the ml response from Amazon.
   */
  public function parse_xml($url) {
    // $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
    // $xml = @file_get_contents($url, false, $context);

    $ch = curl_init($url);
    $timeout = 5;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $xml = curl_exec($ch);
    curl_close($ch);

    if ($xml === FALSE) {
      //echo 'Connection Offline';
    } else {
      $xml = simplexml_load_string($xml);
    }
    return $xml;
  }
}
