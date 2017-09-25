<?php
namespace CodeandoMexico\Sismomx\Core\Traits\Base;

/**
 * Trait GoogleGeolocationTrait
 * @package CodeandoMexico\Sismomx\Core\Traits\Base
 */
trait GoogleGeolocationTrait
{
    /**
     * @param $url
     * @return mixed
     */
    protected function unshorten_url($url)
    {
        if (preg_match('/google.com/',$url)>0) {
            return $url;
        }
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_FOLLOWLOCATION => TRUE,  // the magic sauce
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYHOST => FALSE, // suppress certain SSL errors
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ));
        curl_exec($ch);
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        return $url;
    }

    /**
     * @param $url
     * @return string
     */
    public function getGeolocationFromUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return "";
        }
        $effectiveUrl = $this->unshorten_url($url);
        preg_match(
            "/@{1}([0-9]+)\.([0-9]+)\,(-)?([0-9]+)\.([0-9]+)/i",
            iconv("utf-8", "ascii//TRANSLIT//IGNORE",
                $effectiveUrl),
            $matches
        );
        if (count($matches)>1) {
            return str_replace("@","",$matches[0]);
        }
        return "";
    }

    /**
     * @param $address
     * @return bool|string
     */
    public function getGeolocationFromAddress($address)
    {
        if (empty($address) === true) {
            return "";
        }
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={address}&key=AIzaSyCywICcxAj5Lo3qXNSJjd6wr4eQOohn5Ic";
        $url = str_replace('{address}',$address,$url);
        $prepAddr = str_replace(' ','+',$url);
        $geocode = file_get_contents($prepAddr);
        $output = json_decode($geocode,true);
        if (!is_array($output)) {
            return "";
        }
        if (isset($output['results']) === false) {
            return "";
        }
        if (is_array($output['results']) === false) {
            return "";
        }
        if (count($output['results']) == 0) {
            return "";
        }
        $latitude = $output['results'][0]['geometry']['location']['lat'];
        $longitude = $output['results'][0]['geometry']['location']['lng'];
        return $latitude . "," . $longitude;
    }
}