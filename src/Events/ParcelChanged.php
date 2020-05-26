<?php

namespace Tnt\Sendcloud\Events;

use dry\db\FetchException;
use Oak\Dispatcher\Event;
use Tnt\Sendcloud\Model\Parcel;

class ParcelChanged extends Event
{
    /**
     * @var \dry\orm\Model|null
     */
    private $parcel;

    /**
     * ParcelChanged constructor.
     * @param array $parcelData
     */
    public function __construct(array $parcelData)
    {
        try {
            $parcel = Parcel::load_by('sendcloud_id', $parcelData['parcel']['id']);
            $parcel->updated = $parcelData['timestamp'];
            $parcel->status = $parcelData['parcel']['status']['id'];
            $parcel->save();

        } catch (FetchException $e) {

            $parcel = null;
        }

        $this->parcel = $parcel;
    }

    /**
     * @return null|Parcel
     */
    public function getParcel():? Parcel
    {
        return $this->parcel;
    }
}