<?php

namespace Tnt\Sendcloud\Model;

use dry\orm\Model;

class Parcel extends Model
{
    const TABLE = 'sendcloud_parcel';

    public static $special_fields = [
      'label' => Label::class,
      'shipment_method' => ShipmentMethod::class,
    ];

    public function getLabels()
    {
        return $this->has_many(Label::class, 'parcel');
    }

    public function delete()
    {
        foreach ($this->getLabels() as $label) {
            $label->delete;
        }

        parent::delete();
    }
}