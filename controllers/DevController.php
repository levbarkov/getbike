<?php

namespace app\controllers;

use app\helper\PseudoCrypt;
use app\models\Article;
use app\models\BikesPrice;
use app\models\Country;
use app\models\Pages;
use app\models\Regions;
use app\models\RentalSearch;
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
use yii\web\HttpException;
use yii\httpclient\Client;
use yii\base\Action;

class DevController extends Controller
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

//    public function beforeAction($action)
//    {
//        /* @var $action Action */
//        if($action->id == 'error'){
//           var_dump($action);
//            return false;
//        }
//        return parent::beforeAction($action);
//    }
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
        return $this->render('insurance');
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex_()
    {
        $session = Yii::$app->session;
        $session->open();
        if (($session->has('date_from')) and ($session->has('date_to')) and ($session->has('location_from_map')) and ($session->has('name_from_map'))) {
            return $this->render('second', [
                'date_from' => $session->get('date_from'),
                'date_to' => $session->get('date_to'),
                'location_from_map' => $session->get('location_from_map'),
                'name_from_map' => $session->get('name_from_map'),
            ]);

        } else {
            return $this->render('second', [
                'date_from' => '',
                'date_to' => '',
                'location_from_map' => '',
                'name_from_map' => '',
            ]);
        }
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $rentals = Rental::find()->asArray()->all();
        $u_coord = [-8.624073, 115.169085];
        $session->set('location_from_map', implode('|', $u_coord));
        $session->set('name_from_map', 'Kuta, Bali');

        foreach ($rentals as $rental) {
            /* @var $rental Rental */
            $r_radius = $rental['radius'];
            $r_coord = explode('|', $rental['coord']);
            $u_radius = PseudoCrypt::latlng2distance($u_coord[0], $u_coord[1], $r_coord[0], $r_coord[1]);
            if ($u_radius <= $r_radius) {
                $rent_idsp[] = $rental['id'];
            }
        }
        //var_dump($rent_idsp);
        if (isset($rent_idsp) && is_array($rent_idsp)) {
            $model = RentalGarage::find()->with('condition', 'bike', 'bikeprice')->asArray()->where(['status' => 1, 'rental_id' => $rent_idsp])->all();
            $photos = [];
            if (!empty($model)) {
                $bikes = PseudoCrypt::getBikes($model);
                $session->set('bikes', $bikes);
                return $this->render('index', [
                    'model' => $bikes,
                ]);

            } else {
                return $this->render('nobikes');

            }
        } else {
            return $this->render('nobikes');
        }
    }

    /**
     * Displays second page.
     *
     * @return string
     */
    public function actionSecond_()
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

        if (empty($this->date_to) && empty($this->date_from)) {
            return $this->redirect('index');
        }

        //rental filter begin
        $u_coord = explode("|", $location_from_map);
        $rentals = Rental::find()->asArray()->all();
        $rent_idsp = [];


        foreach ($rentals as $rental) {
            /* @var $rental Rental */
            $r_coord = explode('|', $rental['coord']);
            $r_radius = $rental['radius'];
            $u_alt_coord = [-8.624073, 115.169085];
            $u_radius = PseudoCrypt::latlng2distance($u_coord[0], $u_coord[1], $r_coord[0], $r_coord[1]);
            $u_alt_radius = PseudoCrypt::latlng2distance($u_alt_coord[0], $u_alt_coord[1], $r_coord[0], $r_coord[1]);
            if ($u_radius <= $r_radius) {
                $rent_idsp[] = $rental['id'];
            }
            if ($u_alt_radius <= $r_radius) {
                $rent_alt_idsp[] = $rental['id'];
            }
        }

        if (isset($rent_idsp) && is_array($rent_idsp)) {
            $rent_idsp = array_unique($rent_idsp);
            $list = 1;
        } else {
            if (isset($rent_alt_idsp) && is_array($rent_alt_idsp)) {
                $rent_alt_idsp = $rent_idsp = array_unique($rent_alt_idsp);
                $list = 0;
            } else {
                return $this->render('nobikes');
            }
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

            unset($photos);

            $session->set('bikes', $bikes);
            if ($list == 1) {
                return $this->render('index', [
                    'model' => $bikes,
                ]);
            } else {
                return $this->render('second-no', [
                    'model' => $bikes,
                ]);
            }
        } else {
            return $this->render('nobikes');

        }

    }


    /**
     * Displays second page.
     *
     * @return string
     */
    public
    function actionSecond()
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
        if (($session->has('date_from')) and ($session->has('date_to')) and ($session->has('location_from_map')) and ($session->has('name_from_map'))) {
            return $this->render('second', [
                'date_from' => $session->get('date_from'),
                'date_to' => $session->get('date_to'),
                'location_from_map' => $session->get('location_from_map'),
                'name_from_map' => $session->get('name_from_map'),
            ]);

        } else {
            return $this->render('second', [
                'date_from' => '',
                'date_to' => '',
                'location_from_map' => '',
                'name_from_map' => '',
            ]);
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

        $rentals = Rental::find()->asArray()->all();
        $i = 0;
        foreach ($rentals as $rental) {
            /* @var $rental Rental */
            $r_radius = $rental['radius'];
            $r_coord = explode('|', $rental['coord']);
            $u_coord = explode('|', $location_from_map);
            $u_radius = PseudoCrypt::latlng2distance($u_coord[0], $u_coord[1], $r_coord[0], $r_coord[1]);
            if ($u_radius <= $r_radius) {
                $rent_idsp[$i]['id'] = $rental['id'];
                $rent_idsp[$i]['region_id'] = $rental['region_id'];
                $i++;
            }
        }

        if (isset($rent_idsp) && !empty($rent_idsp)) {
            $bike_price = BikesPrice::find()
                ->where(
                    [
                        'bike_id' => $session->get('bike_id'),
                        'condition_id' => $session->get('condition_id'),
                        'region_id' => $rent_idsp[0]['region_id']
                    ])->one();
        }

        $data['helmets_count'] = $session->get('helmets_count');
        $bike_model = $bikes[$session->get('bike_id')][$session->get('condition_id')];


        $data['bike_model'] = $bike_model['bike']['model'];

        if ($data['helmets_count'] == 1) {
            $data['helmets_count'] = $data['helmets_count'] . " helmet";
        } elseif ($data['helmets_count'] == 2) {
            $data['helmets_count'] = $data['helmets_count'] . " helmets";
        }

        $session->set('helmets_count', $data['helmets_count']);

        $date_from = new DateTimeImmutable($session->get('date_from'));
        $date_to = new DateTimeImmutable($session->get('date_to'));
        $diff = $date_to->diff($date_from);
        $data['days'] = $diff->days;

        if (isset($bike_price) && !empty($bike_price)) {
            $data['bike_price'] = $bike_price->price * ($data['days'] + 1);
            $session->set('region_id', $rent_idsp[0]['region_id']);
        } else {
            $data['bike_price'] = $bike_model['bikeprice']['price'] * ($data['days'] + 1);
        }

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
    public
    function actionFinal()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $session = Yii::$app->session;
            $zakaz = new Zakaz;
            $zakaz->user_name = strip_tags($request->post('name', ''));
            $zakaz->user_email = strip_tags($request->post('email', ''));
            $zakaz->user_phone = strip_tags($request->post('phone', ''));
            //$zakaz->user_phone = PseudoCrypt::phoneClear($request->post('phone', ''));
            $date_from = new DateTimeImmutable($session->get('date_from'));
            $date_to = new DateTimeImmutable($session->get('date_to'));
            $timestamp = new DateTimeImmutable();
            $timestamp->setTimestamp(time());
            $zakaz->date_for = $date_from->format('Y-m-d');
            $zakaz->date_to = $date_to->format('Y-m-d');
            $zakaz->curr_date = $timestamp->format('Y-m-d H:i:s');
            $zakaz->coord = $session->get('location_from_map');
            $zakaz->price = $session->get('total');
            $zakaz->service_tax = $session->get('service_fee');
            $zakaz->status = 0;
            $u_bike_id = $session->get('bike_id');
            $u_condition_id = $session->get('condition_id');
            $u_condition = Condition::findOne(['id' => $u_condition_id]);
            $zakaz->zakaz_info = $session->get('bike_model') . $u_condition->text . ' ' . $session->get('helmets_count') . ' helmets';
            $zakaz->save();

            $u_coord = explode('|', $session->get('location_from_map'));
            if ($session->get('region_id')) {
                $garages = RentalGarage::find()->where([
                    'bike_id' => $u_bike_id,
                    'condition_id' => $u_condition_id,
                    'region_id' => $session->get('region_id'),
                    'status' => 1
                ])->all();
            } else {
                $garages = RentalGarage::find()->where(['bike_id' => $u_bike_id, 'condition_id' => $u_condition_id, 'status' => 1])->all();
            }

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

            $data = [
                'o_name' => $zakaz->user_name,
                'phone' => $zakaz->user_phone,
                'bike_model' => $session->get('bike_model'),
                'condition' => $u_condition->text,
                'helmets' => $session->get('helmets_count'),
                'date'=> $session->get('dates'),
                'adress'=> $session->get('name_from_map'),
                'summ'=> $zakaz->price,
                'price'=> $session->get('bike_price'),
                'comission'=> $session->get('service_fee'),
                'rental_list'=> isset($rentals_email) ? $rentals_email : ''
            ];


            Yii::$app->mailer->compose('views/rental_b_html', $data)
            ->setTo(['barkov@bureauit.ru', 'mihalbl400@gmail.com'])
                ->setSubject('getbike.io - Order')
                ->send();
            PseudoCrypt::sendtoTelegram($data);





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
    public
    function actionFind()
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

    public
    function actionMailtest()
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

    public function actionTtest(){
        /*$client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $headers = [
            'Domain' => 'getbike.io'
        ];


        $t_message = "<a href='ya.ru'>Link</a>";

        $data = [
            'event'=>'new_order',
            'telegram_id' => [209979583,209979583],
            'message' => $t_message];


        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl('https://tgl.website/getbike')
            ->setHeaders($headers)
            ->setData($data)
            ->send();
        return $response->content;*/
        return 0;
    }

    public function actionHiw()
    {

        return $this->render('hiw');
    }

    public function actionContacts()
    {
        return $this->render('contacts');
    }

    public function actionDelivery()
    {
        return $this->render('delivery');
    }

    public function actionWtest()
    {
        return $this->render('test');
    }

    public function actionArticles()
    {

    }

    public function actionArticle($iso, $region, $title)
    {
        $country = Country::findOne(['iso' => $iso]);
        $region = Regions::findOne(['text'=>$region]);

        $article = Article::find()->where(['en_title' => $title, 'country_id' => $country->id, 'region_id' => $region->id])->one();
        if ($article) {
            if($article->page_desc)
                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => $article->page_desc
                ]);

           return $this->render('article', ['article' => $article]);
        }else{
            throw new HttpException(404 ,'Article not found');
        }

    }

    public function actionPages($page){
        $page = Pages::findOne(['alias'=>$page]);
        if($page){
            if($page->page_desc){
                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => $page->page_desc
                ]);
            }
            return $this->render('page', ['model'=>$page]);
        }else{
            throw new HttpException(404 ,'Article not found');

        }
    }

}
