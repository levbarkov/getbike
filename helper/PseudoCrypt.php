<?php
/**
 * PseudoCrypt by KevBurns (http://blog.kevburnsjr.com/php-unique-hash)
 * Reference/source: http://stackoverflow.com/a/1464155/933782
 *
 * I want a short alphanumeric hash that’s unique and who’s sequence is difficult to deduce.
 * I could run it out to md5 and trim the first n chars but that’s not going to be very unique.
 * Storing a truncated checksum in a unique field means that the frequency of collisions will increase
 * geometrically as the number of unique keys for a base 62 encoded integer approaches 62^n.
 * I’d rather do it right than code myself a timebomb. So I came up with this.
 *
 * Sample Code:
 *
 * echo "<pre>";
 * foreach(range(1, 10) as $n) {
 *     echo $n." - ";
 *     $hash = PseudoCrypt::hash($n, 6);
 *     echo $hash." - ";
 *     echo PseudoCrypt::unhash($hash)."<br/>";
 * }
 *
 * Sample Results:
 * 1 - cJinsP - 1
 * 2 - EdRbko - 2
 * 3 - qxAPdD - 3
 * 4 - TGtDVc - 4
 * 5 - 5ac1O1 - 5
 * 6 - huKpGQ - 6
 * 7 - KE3d8p - 7
 * 8 - wXmR1E - 8
 * 9 - YrVEtd - 9
 * 10 - BBE2m2 - 10
 */
namespace app\helper;
use yii\httpclient\Client;

class PseudoCrypt{

    /* Key: Next prime greater than 62 ^ n / 1.618033988749894848 */
    /* Value: modular multiplicative inverse */
    private static $golden_primes = array(
        '1'                  => '1',
        '41'                 => '59',
        '2377'               => '1677',
        '147299'             => '187507',
        '9132313'            => '5952585',
        '566201239'          => '643566407',
        '35104476161'        => '22071637057',
        '2176477521929'      => '294289236153',
        '134941606358731'    => '88879354792675',
        '8366379594239857'   => '7275288500431249',
        '518715534842869223' => '280042546585394647'
    );

    /* Ascii :                    0  9,         A  Z,         a  z     */
    /* $chars = array_merge(range(48,57), range(65,90), range(97,122)) */
    private static $chars62 = array(
        0=>48,1=>49,2=>50,3=>51,4=>52,5=>53,6=>54,7=>55,8=>56,9=>57,10=>65,
        11=>66,12=>67,13=>68,14=>69,15=>70,16=>71,17=>72,18=>73,19=>74,20=>75,
        21=>76,22=>77,23=>78,24=>79,25=>80,26=>81,27=>82,28=>83,29=>84,30=>85,
        31=>86,32=>87,33=>88,34=>89,35=>90,36=>97,37=>98,38=>99,39=>100,40=>101,
        41=>102,42=>103,43=>104,44=>105,45=>106,46=>107,47=>108,48=>109,49=>110,
        50=>111,51=>112,52=>113,53=>114,54=>115,55=>116,56=>117,57=>118,58=>119,
        59=>120,60=>121,61=>122
    );

    public static function base62($int) {
        $key = "";
        while(bccomp($int, 0) > 0) {
            $mod = bcmod($int, 62);
            $key .= chr(self::$chars62[$mod]);
            $int = bcdiv($int, 62);
        }
        return strrev($key);
    }

    public static function hash($num, $len = 5) {
        $ceil = bcpow(62, $len);

        $primes = array_keys(self::$golden_primes);
        $prime = $primes[$len];
        $dec = bcmod(bcmul($num, $prime), $ceil);
        $hash = self::base62($dec);
        return str_pad($hash, $len, "0", STR_PAD_LEFT);
    }

    public static function unbase62($key) {
        $int = 0;
        foreach(str_split(strrev($key)) as $i => $char) {
            $dec = array_search(ord($char), self::$chars62);
            $int = bcadd(bcmul($dec, bcpow(62, $i)), $int);
        }
        return $int;
    }

    public static function unhash($hash) {
        $len = strlen($hash);
        $ceil = bcpow(62, $len);
        $mmiprimes = array_values(self::$golden_primes);
        $mmi = $mmiprimes[$len];
        $num = self::unbase62($hash);
        $dec = bcmod(bcmul($num, $mmi), $ceil);
        return $dec;
    }

    public static function latlng2distance($lat1, $long1, $lat2, $long2)  {
        //радиус Земли
        $R = 6372795;
        //перевод коордитат в радианы
        $lat1 *= pi() / 180;
        $lat2 *= pi() / 180;
        $long1 *= pi() / 180;
        $long2 *= pi() / 180;
        //вычисление косинусов и синусов широт и разницы долгот
        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);
        //вычисления длины большого круга
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;
        $ad = atan2($y, $x);
        $dist = $ad * $R;
        //расстояние между двумя координатами в метрах
        return $dist/1000;
    }

    public static function getBikes($model){
        foreach ($model as $key => $value) {
            if (count($value['bikeprice']) != 0) {
                foreach ($value as $key1 => $value1) {

                    if ($key1 == 'bikeprice') {
                        $price_arr = array();

                        foreach ($value1 as $kv => $val) {
                            $price_arr[$kv] = $val;
                        }
                        arsort($price_arr);
                        reset($price_arr);

                        $key_price = 0;
                        foreach ($price_arr as $kv1 => $val1) {
                            $key_price = $kv1;
                            break;
                        }
                    }

                    $bikes[$value['bike_id']][$value['condition_id']][$key1] = $value1;
                }
                $photos[$value['bike_id']][$value['condition_id']] = $value['bikeprice']['photo'];
            }

        }

        foreach ($photos as $pk => $pv) {
            ksort($photos[$pk]);
            reset($photos[$pk]);
            foreach ($photos[$pk] as $ppk => $ppv) {
                $bikes[$pk]['first_img'] = $ppv;
                $bikes[$pk]['first_condition'] = $ppk;
                break;
            }
        }

        foreach ($bikes as $k=>$v){
            //$price_aaray[$k] = $bikes[$k][0]['bikeprice']['price'];
            foreach ($v as $kk => $vv){
                $price_aaray[$k] = $vv['bikeprice']['price'];
                break;
            }
        }
        asort($price_aaray);

        foreach ($price_aaray as $key => $value){
            $sort_bikes[$key] = $bikes[$key];
        }

        unset($photos);
        return $sort_bikes;
    }
    public static function sendtoTelegram($data){
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);

        $t_message = "We have new order.

    Customer: ".$data['o_name']."
    Whatsapp number: ".$data['phone']."
    <a href='https://api.whatsapp.com/send?phone=".PseudoCrypt::phoneClear($data['phone'])."'>WhatsApp:".PseudoCrypt::phoneClear($data['phone'])."</a>
    ".$data['bike_model']."
    ".$data['condition']."  ".$data['helmets']."
    Dates: ".$data['date']."
    Delivery now to: ".$data['adress']."
    Price in agreement: ".$data['summ']." Rp
    Your part is ".$data['price']."  Rp
    Please keep ".$data['comission']." Rp for me, thank you";

        $data = [
            'event'=>'new_order',
            'telegram_id' => [190756392,109733868],
            'message' => $t_message];


        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl('https://tgl.website/getbike')
            ->setData($data)
            ->send();
    return true;
    }
    public static function phoneClear($phone){
        $phone = str_split(preg_replace('~\D+~', '', strip_tags($phone)));
        foreach ($phone as $k=>$v){
            if($v == 0){
                unset($phone[$k]);
            }else{
                break;
            }
        }
        $phone = implode('',$phone);
        return $phone;

    }

}