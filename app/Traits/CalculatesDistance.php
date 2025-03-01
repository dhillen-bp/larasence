<?php

namespace App\Traits;

use Ballen\Distical\Calculator as DistanceCalculator;
use Ballen\Distical\Entities\LatLong;

trait CalculatesDistance
{
    /**
     * Menghitung jarak antara dua titik (latitude & longitude) dan mengembalikannya dalam meter.
     *
     * @param float $latitude Latitude titik pengguna
     * @param float $longitude Longitude titik pengguna
     * @return float Jarak dalam meter
     */
    public function calculateDistance($latitude, $longitude): float
    {
        $officeLatitude = env('OFFICE_LATITUDE');
        $officeLongitude =  env('OFFICE_LONGITUDE');

        $locationA = new LatLong($officeLatitude, $officeLongitude);
        $locationB = new LatLong($latitude, $longitude);

        $distanceCalculator = new DistanceCalculator($locationA, $locationB);
        $distanceInKm = $distanceCalculator->get()->asKilometres();

        return ($distanceInKm * 1000); // konversi ke meter
    }
}
