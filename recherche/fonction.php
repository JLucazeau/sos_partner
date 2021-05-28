
<?php
 function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
 {
      $pi80 = M_PI / 180;
      $lat1 *= $pi80;
      $lng1 *= $pi80;
      $lat2 *= $pi80;
      $lng2 *= $pi80;

      $r = 6372.797; // rayon moyen de la Terre en km
      $dlat = $lat2 - $lat1;
      $dlng = $lng2 - $lng1;
      $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      $km = $r * $c;
   
      return ($miles ? ($km * 0.621371192) : $km);
 }

function getCoordinates($address) {
    // replace all the white space with "+" sign to match with google search pattern
    if (isset($address)){
    $address = str_replace(" ", "+", $address);

    $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&key=AIzaSyD7EAkgAdAXIFv1IYeaUZILi8IYFya3CFA&address=$address";
    $response = file_get_contents($url);
   
    // generate array object from the response from the web
    $json = json_decode($response,TRUE);
    // Latitude
    $latitude = ($json['results'][0]['geometry']['location']['lat']) ? $json['results'][0]['geometry']['location']['lat'] : '--';
    // Longitude
    $longitude = ($json['results'][0]['geometry']['location']['lng']) ? $json['results'][0]['geometry']['location']['lng'] : '--';

    return $latitude . "," . $longitude;}
}

function age($bday)
{

$date = date("d-m-Y", strtotime($bday));
$newDate = new DateTime($date);

$today = new Datetime(date('d-m-Y'));
$diff = $today->diff($newDate);
$diff = $diff->y;

return $diff;
}


?>