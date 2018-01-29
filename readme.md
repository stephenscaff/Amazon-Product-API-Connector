# Amazon Product API

A simple, PHP and curl connector to the Amazon Product API.


### amazon-api.php

Main class connecting to apai.

### amazon-functions.php

Helper function to access/call api class, using the product's ASIN number.

Example using a custom field (wp) for product asin:

```
$asin = get_post_meta( $post->ID, 'product_asin', true );
$product = get_product_by_asin($asin);

echo $product->fprice;
echo $product->url;

```

### amazon-options.php

Registers Wp admin page / fields to store / access the Amazon API - Access Key, Secret Key, Associate Tag.

## Cache

So, the Amazon API will kill your load times. You better cache. Not sure what Amazon's policies are on the matter.
