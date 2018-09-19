<?php

namespace app\controllers;

use app\helper\PseudoCrypt;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Rental;
use app\models\RentalGarage;
use app\models\Condition;
use app\models\Bikes;
use app\models\Zakaz;
use DateTimeImmutable;

class SiteController extends Controller
{

    public $date_from;
    public $date_to;
    public $bike_id;
    public $condition_id;
    public $helmets_count;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->open();
        if (($session->has('date_from')) and ($session->has('date_to')) and ($session->has('location_from_map')) and ($session->has('name_from_map'))) {
            return $this->render('index', [
                'date_from' => $session->get('date_from'),
                'date_to' => $session->get('date_to'),
                'location_from_map' => $session->get('location_from_map'),
                'name_from_map' => $session->get('name_from_map'),
            ]);

        } else {
            return $this->render('index', [
                'date_from' => '',
                'date_to' => '',
                'location_from_map' => '',
                'name_from_map' => '',
            ]);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays second page.
     *
     * @return string
     */
    public function actionSecond()
    {
        $session = Yii::$app->session;
        $session->open();
        $request = Yii::$app->request;
        $post = $request->post();
        if ($request->isPost) {
            $this->date_from = $request->post('date-from', '');
            $this->date_to = $request->post('date-to', '');
            $session->set('date_from', $this->date_from);
            $session->set('date_to', $this->date_to);
            $session->set('location_from_map', $request->post('location_from_map', ''));
            $session->set('name_from_map', $request->post('name_from_map', ''));
            $location_from_map = $request->post('location_from_map', '');
        } else {
            $location_from_map = $session->get('location_from_map');
        }

        if(empty($this->date_to) && empty($this->date_from)){
            return $this->redirect('index');
        }

        //rental filter begin
        $u_coord = explode("|", $location_from_map);
        $rentals = Rental::find()->asArray()->all();
        $rent_idsp = [];



        foreach ($rentals as $rental) {
            /* @var $rental Rental */
            /*            $query_place = urlencode($valr['adress']);
                        $location = urlencode("-8.3693355,115.0350728");
                        $key = "AIzaSyDqY4QbBgvy2yBZTbdRUja6fjOTnO4m1vM";
                        $url = "https://maps.googleapis.com/maps/api/place/textsearch/xml?location=" . $location . "&radius=50000&query=" . $query_place . "&key=" . $key;
                        $args = array(
                            'headers' => array(
                                'Content-type: application/xml; charset=UTF-8'
                            )
                        );
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $args['headers']);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        $response = curl_exec($curl);
                        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);
                        $adress = simplexml_load_string($response);

                        if ($adress->status == 'OK') {
                            $cn = 0;
                            foreach ($adress->result as $adr) {

                                if (strpos((string)$adr->formatted_address, 'Bali')) {
                                    $adrr[$valr['id']][$cn]['coord'] = (string)$adr->geometry->location->lat . "|" . (string)$adr->geometry->location->lng;
                                    $adrr[$valr['id']][$cn]['name'] = (string)$adr->formatted_address;
                                    //calculate begin
                                    // Convert degrees to radians.
                                    $lat1 = deg2rad((string)$adr->geometry->location->lat);
                                    $lng1 = deg2rad((string)$adr->geometry->location->lng);
                                    $lat2 = deg2rad($latlng2[0]);
                                    $lng2 = deg2rad($latlng2[1]);

                                    // Calculate delta longitude and latitude.
                                    $delta_lat = ($lat2 - $lat1);
                                    $delta_lng = ($lng2 - $lng1);

                                    if ((round(6378137 * acos(cos($lat1) * cos($lat2) * cos($lng1 - $lng2) + sin($lat1) * sin($lat2))) / 1000) <= $valr['radius']) {
                                        $rent_idsp[] = $valr['id'];
                                    }
                                    //calculate	end
                                    $cn = $cn + 1;
                                }
                            }

                        }*/
            $r_coord = explode('|', $rental['coord']);
            $r_radius = $rental['radius'];
            $u_radius = PseudoCrypt::latlng2distance($u_coord[0], $u_coord[1], $r_coord[0], $r_coord[1]);
            if ($u_radius <= $r_radius) {
                $rent_idsp[] = $rental['id'];
            }

        }

        if (isset($rent_idsp) && is_array($rent_idsp)) {
            $rent_idsp = array_unique($rent_idsp);
        } else {
            $rent_idsp = 0;
        }

        //rental filter end
        //echo "<pre>";
        //print_r($rent_idsp);
        // $sel="[".implode(",",$rent_idsp)."]";

        $model = RentalGarage::find()->with('condition', 'bike', 'bikeprice')->asArray()->where(['status' => 1, 'rental_id' => $rent_idsp])->all();
        //$model = RentalGarage::find()->with('condition', 'bike', 'bikeprice')->asArray()->where(['status' => 1])->all();

        //print_r($model);
        //echo implode(',',$rent_idsp);
        //echo "</pre>";
        /*
         echo "<pre>";
         var_dump($model);
         echo "</pre>";
         die();
         */
        $photos = [];
        if (!empty($model)) {
            foreach ($model as $key => $value) {
                if (count($value['bikeprice']) != 0) {
                    foreach ($value as $key1 => $value1) {

                        if ($key1 == 'bikeprice') {
                            $price_arr = array();

                            foreach ($value1 as $kv => $val) {
                                $price_arr[$kv] = $val;
                                //$price_arr[$kv]=$val['price'];
                            }
                            arsort($price_arr);
                            reset($price_arr);

                            $key_price = 0;
                            foreach ($price_arr as $kv1 => $val1) {
                                $key_price = $kv1;
                                break;
                            }
                            //$value1 = $value1[$key_price];

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

            unset($photos);
//       echo "<pre>";
//       echo print_r($bikes);
//       echo "</pre>";
//        die();

            $session->set('bikes', $bikes);
            return $this->render('second', [
                'model' => $bikes,
            ]);
        } else {
            $u_coord[0] = -8.624073;
            $u_coord[1] = 115.169085;
            $session->set('location_from_map', implode('|',$u_coord));
            $session->set('name_from_map', 'Kuta, Bali');

            foreach ($rentals as $rental) {
                /* @var $rental Rental */
                /*            $query_place = urlencode($valr['adress']);
                            $location = urlencode("-8.3693355,115.0350728");
                            $key = "AIzaSyDqY4QbBgvy2yBZTbdRUja6fjOTnO4m1vM";
                            $url = "https://maps.googleapis.com/maps/api/place/textsearch/xml?location=" . $location . "&radius=50000&query=" . $query_place . "&key=" . $key;
                            $args = array(
                                'headers' => array(
                                    'Content-type: application/xml; charset=UTF-8'
                                )
                            );
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $url);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, $args['headers']);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            $response = curl_exec($curl);
                            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                            curl_close($curl);
                            $adress = simplexml_load_string($response);

                            if ($adress->status == 'OK') {
                                $cn = 0;
                                foreach ($adress->result as $adr) {

                                    if (strpos((string)$adr->formatted_address, 'Bali')) {
                                        $adrr[$valr['id']][$cn]['coord'] = (string)$adr->geometry->location->lat . "|" . (string)$adr->geometry->location->lng;
                                        $adrr[$valr['id']][$cn]['name'] = (string)$adr->formatted_address;
                                        //calculate begin
                                        // Convert degrees to radians.
                                        $lat1 = deg2rad((string)$adr->geometry->location->lat);
                                        $lng1 = deg2rad((string)$adr->geometry->location->lng);
                                        $lat2 = deg2rad($latlng2[0]);
                                        $lng2 = deg2rad($latlng2[1]);

                                        // Calculate delta longitude and latitude.
                                        $delta_lat = ($lat2 - $lat1);
                                        $delta_lng = ($lng2 - $lng1);

                                        if ((round(6378137 * acos(cos($lat1) * cos($lat2) * cos($lng1 - $lng2) + sin($lat1) * sin($lat2))) / 1000) <= $valr['radius']) {
                                            $rent_idsp[] = $valr['id'];
                                        }
                                        //calculate	end
                                        $cn = $cn + 1;
                                    }
                                }

                            }*/
                $r_coord = explode('|', $rental['coord']);
                $r_radius = $rental['radius'];
                $u_radius = PseudoCrypt::latlng2distance($u_coord[0], $u_coord[1], $r_coord[0], $r_coord[1]);

                if ($u_radius <= $r_radius) {
                    $rent_idsp[] = $rental['id'];
                }
            }
            if (isset($rent_idsp) && is_array($rent_idsp)) {
                $rent_idsp = array_unique($rent_idsp);
            } else {
                $rent_idsp = 0;
            }
            $model = RentalGarage::find()->with('condition', 'bike', 'bikeprice')->asArray()->where(['status' => 1, 'rental_id' => $rent_idsp])->all();
            $photos = [];
            if (!empty($model)) {
                foreach ($model as $key => $value) {
                    if (count($value['bikeprice']) != 0) {
                        foreach ($value as $key1 => $value1) {

                            if ($key1 == 'bikeprice') {
                                $price_arr = array();

                                foreach ($value1 as $kv => $val) {
                                    $price_arr[$kv] = $val;
                                    //$price_arr[$kv]=$val['price'];
                                }
                                arsort($price_arr);
                                reset($price_arr);

                                $key_price = 0;
                                foreach ($price_arr as $kv1 => $val1) {
                                    $key_price = $kv1;
                                    break;
                                }
                                //$value1 = $value1[$key_price];

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
                unset($photos);

               if(!empty($bikes)){
                   $session->set('bikes', $bikes);
                   return $this->render('second-no', [
                       'model' => $bikes,
                   ]);
               }else{
                   return $this->render('nobikes');
               }
        }
        }

    }

    /**
     * Displays third page.
     *
     * @return string
     */
    public function actionThird()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $bikes = $session->get('bikes');
        $post = $request->post();
        if ($request->isPost) {
            $this->bike_id = $request->post('bike_id', '');
            $this->condition_id = $request->post('condition_id', '');
            $this->helmets_count = $request->post('helmets_count', '');
            $session->set('bike_id', $this->bike_id);
            $session->set('condition_id', $this->condition_id);
            $session->set('helmets_count', $this->helmets_count);
            $data['helmets_count'] = $this->helmets_count;
            $bike_model = $bikes[$this->bike_id][$this->condition_id];
            $data['bike_model'] = $bike_model['bike']['model'];
        } else {
            $data['helmets_count'] = $session->get('helmets_count');
            $bike_model = $bikes[$session->get('bike_id')][$session->get('condition_id')];

        }
        $data['bike_model'] = $bike_model['bike']['model'];

        if ($data['helmets_count'] == 1) {
            $data['helmets_count'] = $data['helmets_count'] . " helmet";
        } elseif ($data['helmets_count'] == 2) {
            $data['helmets_count'] = $data['helmets_count'] . " helmets";
        }
        $date_from = new DateTimeImmutable($session->get('date_from'));
        $date_to = new DateTimeImmutable($session->get('date_to'));
        $diff = $date_to->diff($date_from);
        $data['days'] = $diff->days;
        $data['bike_price'] = $bike_model['bikeprice']['price'] * ($data['days'] + 1);
        $data['service_fee'] = ($data['bike_price'] / 100) * 15;
        $data['service_fee'] = round($data['service_fee'] / 1000) * 1000;
        $data['total'] = $data['bike_price'] + $data['service_fee'];
        $data['total'] = round($data['total'] / 1000) * 1000;
        $data['date_from'] = $date_from->format('j F');
        $data['date_to'] = $date_to->format('j F Y');
        $data['name_from_map'] = $session->get('name_from_map');

        $session->set('total', $data['total']);
        $session->set('service_fee', $data['service_fee']);
        $session->set('bike_model', $data['bike_model']);
        $session->set('bike_price', $data['bike_price']);
        $session->set('dates', ($data['days'] + 1) . 'days. ' . $data['date_from'] . ' - ' . $data['date_to']);
        //$session->set('bike_price',  $data['bike_price']);

        return $this->render('third', [
            'model' => $data,
        ]);
    }

    /**
     * Displays final page.
     *
     * @return string
     */
    public function actionFinal()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $session = Yii::$app->session;
            $zakaz = new Zakaz;
            $zakaz->user_name = strip_tags($request->post('name', ''));
            $zakaz->user_email = strip_tags($request->post('email', ''));
            $zakaz->user_phone = strip_tags($request->post('phone', ''));
            $date_from = new DateTimeImmutable($session->get('date_from'));
            $date_to = new DateTimeImmutable($session->get('date_to'));
            $timestamp = new DateTimeImmutable();
            $timestamp->setTimestamp(time());
            $zakaz->date_for = $date_from->format('Y-m-d');
            $zakaz->date_to = $date_to->format('Y-m-d');
            $zakaz->curr_date = $timestamp->format('Y-m-d H:i:s');
            $zakaz->price = $session->get('total');
            $u_bike_id = $session->get('bike_id');
            $u_condition_id = $session->get('condition_id');
            $u_condition = Condition::findOne(['id' => $u_condition_id]);
            $zakaz->zakaz_info = $session->get('bike_model') . $u_condition->text . ' ' . $session->get('helmets_count') . ' helmets';
            $zakaz->save();

            $u_coord = explode('|', $session->get('location_from_map'));
            $garages = RentalGarage::find()->where(['bike_id' => $u_bike_id, 'condition_id' => $u_condition_id, 'status' => 1])->all();

            if ($garages) {
                $rentals_email = [];
                foreach ($garages as $garage) {
                    /* @var $garage RentalGarage */
                    $r_coord = explode('|', $garage->rental->coord);
                    $u_radius = PseudoCrypt::latlng2distance($u_coord[0], $u_coord[1], $r_coord[0], $r_coord[1]);
                    if ($u_radius <= $garage->rental->radius)
                        $rentals_email[$garage->rental->id]['rental_id1'] = $garage->rental->id;
                    $rentals_email[$garage->rental->id]['rental_id'] = $garage->rental->id;
                    $rentals_email[$garage->rental->id]['garage_id'] = $garage->id;
                    $rentals_email[$garage->rental->id]['email'] = $garage->rental->mail;
                    $rentals_email[$garage->rental->id]['name'] = $garage->rental->name;
                    $rentals_email[$garage->rental->id]['number'] = $garage->number;
                    $rentals_email[$garage->rental->id]['region'] = $garage->rental->region->text;
                }
            }
//            echo '<pre>';
//            var_dump($rentals_email);
//            echo '</pre>';
//            die();
            if (isset($rentals_email) && !empty($rentals_email)) {
                Yii::$app->mailer->compose('views/rental_b_html', [
                    'o_name' => $zakaz->user_name,
                    'b_model' => $session->get('bike_model'),
                    'condition' => $u_condition->text,
                    'helmets_count' => $session->get('helmets_count'),
                    'adress' => $session->get('name_from_map'),
                    'date' => $session->get('dates'),
                    'summ' => $zakaz->price,
                    'price' => $session->get('bike_price'),
                    'comission' => $session->get('service_fee'),
                    'rental_list' => $rentals_email,
                ])// здесь устанавливается результат рендеринга вида в тело сообщения
                //->setFrom('getbike@leads24.info')
                //->setTo('mihalbl400@gmail.com')
                ->setTo('barkov@bureauit.ru')
                    ->setSubject('getbike.io - Order')
                    ->send();
            }

            //************************************************
            /*if(isset($rentals_email) && !empty($rentals_email)){
                foreach ($rentals_email as $val){
                    Yii::$app->mailer->compose('views/rental_html',[
                        'name' => $val['name'],
                        'o_name' => $zakaz->user_name,
                        'b_model' => $session->get('bike_model'),
                        'condition' => $u_condition->text,
                        'adress' => $session->get('name_from_map'),
                        'date' => $session->get('dates'),
                        'summ' => $zakaz->price,
                        'price' => $session->get('bike_price'),
                        'comission' => $session->get('service_fee'),
                        'number' => $val['number'],
                        'confirm_link' => '#',
                        'change_link' => '#',
                    ]) // здесь устанавливается результат рендеринга вида в тело сообщения
                    //->setFrom('getbike@leads24.info')
                        ->setTo('mihalbl400@gmail.com')
                        //->setTo('barkov@bureauit.ru')
                        ->setSubject('getbike.io - Order')
                        ->send();
                }
            }*/
            //************************************************

            $session->destroy();
        }

        return $this->render('final', [
            'model' => strip_tags($request->post('name', '')),
            'order' => $zakaz->id
        ]);
    }

    /**
     * Displays places page.
     *
     * @return json
     */
    public function actionFind()
    {

        $request = Yii::$app->request;
        $query_place = urlencode($request->get('term', ''));
        $location = urlencode("-8.3693355,115.0350728");
        $key = "AIzaSyDqY4QbBgvy2yBZTbdRUja6fjOTnO4m1vM";
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/xml?location=" . $location . "&radius=50000&query=" . $query_place . "&key=" . $key;
        $args = array(
            'headers' => array(
                'Content-type: application/xml; charset=UTF-8'
            )
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $args['headers']);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $adress = simplexml_load_string($response);

        if ($adress->status == 'OK') {
            $cn = 0;
            foreach ($adress->result as $adr) {

                if (strpos((string)$adr->formatted_address, 'Bali')) {
                    $adrr[$cn]['label'] = (string)$adr->formatted_address;
                    $adrr[$cn]['value'] = (string)$adr->geometry->location->lat . "|" . (string)$adr->geometry->location->lng;
                    $cn = $cn + 1;
                }
            }

            if ($cn != 0) {
                echo json_encode($adrr);
            } else {
                echo "";
            }
        } else {
            echo "";
        }
    }

    public function actionMailtest()
    {
        $result = Yii::$app->mailer->compose()// здесь устанавливается результат рендеринга вида в тело сообщения
        ->setFrom('getbike@leads24.info')
            ->setTo('mihalbl400@gmail.com')
            ->setSubject('getbike.io - Order')
            ->setTextBody('Текст сообщения')
            ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
            ->send();
        var_dump($result);
    }

}
