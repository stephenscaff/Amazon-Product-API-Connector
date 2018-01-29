<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Util for getting product info from api
 * You better cache this shit, hard.
 * @useage: $product = get_product_by_asin($asin); $product->fprice;
 */
function get_product_by_asin($asin) {

  $amzn_product = new AmznApi;
	$url = $amzn_product->create_signed_url($asin);

	// parse XML from Amazon API
	$xml = $amzn_product->parse_xml($url);

	$product = array(
		'isEligibleForPrime' => (boolean)$xml->Items->Item[0]->Offers->Offer->OfferListing->IsEligibleForPrime,
		'name' => (string)$xml->Items->Item[0]->ItemAttributes->Title,
		'fprice' => (string)$xml->Items->Item[0]->Offers->Offer->OfferListing->Price->FormattedPrice,
		'url' => (string)$xml->Items->Item[0]->DetailPageURL
		);
	return (object)$product;
}
