# DRY Sendcloud
Dry PHP Client for Sendcloud API (unofficial client).

Full docs of the Sendcloud API can be found on https://docs.sendcloud.sc/api/v2/shipping/

#### Index

* [Installation](#installation)
* [Usage](#usage)

#### Installation
```ssh
composer require dietervyncke/dry-sendcloud

php oak migration migrate -m sendcloud
```

##### Config options
Name					      | Default
------------------- | ---------------------------------------------------------
api_url             | 
public_api          |
secret_api          |

#### Usage

##### Register the service provider
```php
<?php

$app = new \Oak\Application();

$app->register([
    \Tnt\Sendcloud\SendcloudServiceProvider::class,
]);

$app->bootstrap();
```

##### Implementation example

###### Create parcel

More info 

```php
<?php

try {
  $scParcel = $sendcloudApi->createParcel([
    'parcel' => [
      'order_numer' => '123',
      'name' => 'John Doe', // required
      'address' => 'Main St', // required
      'house_number' => '123', // required
      'postal_code' => '92520', // required
      'city' => 'Anytown', // required
      'country' => 'USA', // required
      'telephone' => '07552255',
      'email' => 'john-doe@acme.com',
      'request_label' => true,
      'shipment' => [ // required if request_label = true
          'id' => 1,
      ],
    ]
  ]);
  
} catch (Tnt\Sendcloud\Exception\SendCloudException $exception) {
  echo $exception->getMessage();
}

/**
 * Create Parcel
 */
$parcel = new Tnt\Sendcloud\Model\Parcel();
$parcel->created = time();
$parcel->updated = time();
$parcel->sendcloud_id = $scParcel['id'];
$parcel->name = $scParcel['name'];
$parcel->address = $scParcel['address'];
$parcel->city = $scParcel['city'];
$parcel->postal_code = $scParcel['postal_code'];
$parcel->email = $scParcel['email'];
$parcel->tracking_number = $scParcel['tracking_number'];
$parcel->status = $scParcel['status']['id'];
$parcel->country = 'USA';
$parcel->is_return = $scParcel['is_return'];
$parcel->shipment_method = 1;
$parcel->save();

/**
* Create Label
*/
$label = new Tnt\Sendcloud\Model\Label();
$label->created = time();
$label->updated = time();
$label->label_printer = isset($scParcel['label']) ? $scParcel['label']['label_printer'] : '';
$label->normal_printer = isset($scParcel['label']) ? $scParcel['label']['normal_printer'] : [];
$label->save();

$parcel->label = $label;
$parcel->save();

```
