<?php

namespace app\controllers;

use app\helper\PseudoCrypt;
use app\models\Article;
use app\models\BikesPrice;
use app\models\Country;
use app\models\Message;
use app\models\Operations;
use app\models\Pages;
use app\models\RegionList;
use app\models\Regions;
use app\models\RentalSearch;
use app\models\SourceMessage;
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


    public function beforeAction($action)
    {
        /*if ($_SERVER['REMOTE_ADDR'] == '91.228.64.18')
            Yii::$app->language = 'ru-RU';*/
        /*        $laguage = Yii::$app->session->get('language');
                if ($laguage && !empty($laguage)) {
                    Yii::$app->language = $laguage;
                }*/


        if ('second' === $action->id) {
            $this->enableCsrfValidation = false;
        }
        if ($action->controller->id == 'widget' && Yii::$app->getModule('debug'))
            Yii::$app->getModule('debug')->instance->allowedIPs = [];
        return parent::beforeAction($action);
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

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'We help our clients rent scooters which they like. Large selection of bikes. Good price only 4$ rent per day for one scooter.'
        ]);

        foreach ($rentals as $rental) {
            /* @var $rental Rental */
            //$r_radius = $rental['radius'];
            //$r_coord = explode('|', $rental['coord']);
            //$u_radius = PseudoCrypt::latlng2distance($u_coord[0], $u_coord[1], $r_coord[0], $r_coord[1]);
            //if ($u_radius <= $r_radius) {
            $rent_idsp[] = $rental['id'];
            //}
        }
        //var_dump($rent_idsp);
        if (isset($rent_idsp) && is_array($rent_idsp)) {
            $model = RentalGarage::find()->with(['condition', 'bike', 'bikeprice'])->asArray()->where(['status' => 1, 'rental_id' => $rent_idsp])->all();
            $photos = [];
            if (!empty($model)) {
                $bikes = PseudoCrypt::getBikes($model);
                $session->set('bikes', $bikes);
                Yii::trace($bikes);
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

    public function actionWidget($type = null)
    {
        header("Access-Control-Allow-Origin: *");
        $session = Yii::$app->session;
        $rentals = Rental::find()->asArray()->all();
        $u_coord = [-8.624073, 115.169085];
        $session->set('location_from_map', implode('|', $u_coord));
        $session->set('name_from_map', 'Kuta, Bali');

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'We help our clients rent scooters which they like. Large selection of bikes. Good price only 4$ rent per day for one scooter.'
        ]);

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
            $model = RentalGarage::find()->with(['condition', 'bike', 'bikeprice'])->asArray()->where(['status' => 1, 'rental_id' => $rent_idsp])->all();
            $photos = [];
            if (!empty($model)) {
                $bikes = PseudoCrypt::getBikes($model);
                $session->set('bikes', $bikes);
                if ($type == null) {
                    return $this->renderPartial('index_w', [
                        'model' => $bikes,
                    ]);
                } else {
                    $this->layout = 'widget';
                    return $this->render('index_w', [
                        'model' => $bikes
                    ]);
                }


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
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'We need this information for free delivery your bike to your point on the Bali.'
        ]);

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
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'We need this information to contact you to complete your order'
        ]);

        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $bikes = $session->get('bikes');
        $post = $request->post();
        if ($request->isPost) {
            $this->date_from = $request->post('date-from', '');
            $this->date_to = $request->post('date-to', '');
            $session->set('date_from', $request->post('date-from', ''));
            $session->set('date_to', $request->post('date-to', ''));
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

        if (stristr($session->get('helmets_count'), 'helmet') === false) {
            if ($data['helmets_count'] == 1) {
                $data['helmets_count'] = $data['helmets_count'] . " helmet";
            } elseif ($data['helmets_count'] == 2) {
                $data['helmets_count'] = $data['helmets_count'] . " helmets";
            }
        }

        $session->set('helmets_count', $data['helmets_count']);

        $date_from = new DateTimeImmutable($session->get('date_from'));
        $date_to = new DateTimeImmutable($session->get('date_to'));
        $diff = $date_to->diff($date_from);
        $data['days'] = $diff->days;

       /* if (isset($bike_price) && !empty($bike_price)) {
            $data['bike_price'] = $bike_price->price * ($data['days'] + 1);
            $session->set('region_id', $rent_idsp[0]['region_id']);
        } else {
            $data['bike_price'] = $bike_model['bikeprice']['price'] * ($data['days'] + 1);
        }*/

        if (isset($bike_price) && !empty($bike_price)) {
            $bike_price_day = $bike_price->price;
            $bike_price_pm = $bike_price->pricepm;
            $session->set('region_id', $rent_idsp[0]['region_id']);
        }else{
            $bike_price_day = $bike_model['bikeprice']['price'];
            $bike_price_pm = $bike_model['bikeprice']['pricepm'];
        }


        $days = $data['days'] + 1;
        if($days > 30 && !empty($bike_price_pm) && $bike_price_pm > 0) {
            $days_count = $days % 30;
            $month_count = intdiv($days, 30);
            $data['bike_price'] = $bike_price_pm * $month_count + $bike_price_day * $days_count;
        }else{
            $data['bike_price'] = $bike_price_day * $days;
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


            $u_coord = explode('|', $session->get('location_from_map'));
            if ($session->get('region_id')) {
                $garages = RentalGarage::find()->where([
                    'bike_id' => $u_bike_id,
                    'condition_id' => $u_condition_id,
                    'region_id' => $session->get('region_id'),
                    'status' => 1
                ])->all();
                $zakaz->region_id = $session->get('region_id');
            } else {
                $garages = RentalGarage::find()->where(['bike_id' => $u_bike_id, 'condition_id' => $u_condition_id, 'status' => 1])->all();
            }
            $zakaz->save();

            /*$operations = new Operations();
            $operations->order_id = $zakaz->id;
            $operations->sum = -$zakaz->service_tax;
            $operations->operations = "Order #$zakaz->id commission";
            //$operations->rental_id = 0;
            $operations->save();*/

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
                'date' => $session->get('dates'),
                'adress' => $session->get('name_from_map'),
                'summ' => $zakaz->price,
                'price' => $session->get('bike_price'),
                'comission' => $session->get('service_fee'),
                'rental_list' => isset($rentals_email) ? $rentals_email : ''
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
            'order' => $zakaz->id,
            'price' => $zakaz->price
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

    public function actionTtest()
    {
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
        $region = Regions::findOne(['text' => $region]);
        $language = Yii::$app->language;
        if (Article::find()->where(['language' => $language, 'en_title' => $title, 'country_id' => $country->id, 'region_id' => $region->id])->exists()) {
            $article = Article::findOne(['language' => $language, 'en_title' => $title, 'country_id' => $country->id, 'region_id' => $region->id]);
        } else {
            $article = Article::findOne(['en_title' => $title, 'country_id' => $country->id, 'region_id' => $region->id]);
        }
        if ($article) {
            if ($article->page_desc)
                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => $article->page_desc
                ]);

            return $this->render('article', ['article' => $article]);
        } else {
            throw new HttpException(404, 'Article not found');
        }

    }

    public function actionPages($page)
    {

        $language = Yii::$app->language;
        if (Pages::find()->where(['language' => $language, 'alias' => $page])->exists()) {
            $page = Pages::findOne(['alias' => $page, 'language' => $language]);
        } else {
            $page = Pages::findOne(['alias' => $page]);
        }
        if ($page) {
            if ($page->page_desc) {
                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => $page->page_desc
                ]);
            }
            return $this->render('page', ['model' => $page]);
        } else {
            throw new HttpException(404, Yii::t('main', 'Page not found'));

        }
    }

    public function actionSitemap()
    {
        Yii::$app->response->format = Response::FORMAT_XML;
        $host = Yii::$app->request->hostInfo;
        $url_list = Pages::find()->all();
        $article_list = Article::find()->all();


        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        echo '<url>
                <loc>' . $host . '/</loc>
                <changefreq>daily</changefreq>
                <priority>0.5</priority>
            </url>';
        foreach ($url_list as $url) {
            echo '<url>
                <loc>' . $host . '/page/' . $url->alias . '</loc>
                <changefreq>daily</changefreq>
                <priority>0.5</priority>
            </url>';
        }
        foreach ($article_list as $article) {
            echo '<url>
                <loc>' . $host . '/' . $article->country->iso . '/' . $article->region->text . '/' . $article->en_title . '</loc>
                <changefreq>daily</changefreq>
                <priority>0.5</priority>
            </url>';
        }
        echo '</urlset>';


        Yii::$app->end();
    }

    public function actionLanguage($lng)
    {
        Yii::$app->session->set('language', $lng);
        return $this->redirect('/');
    }

    public function actionUploadlng()
    {
        $data = [
            'Contacts' => 'Контакты',
            'Delivery' => 'Доставка',
            'How it works' => 'Как это работает',
            'About Us' => 'О нас',

            'Choose bike' => 'Выбрать байк',

            'Book a bike in 2 minutes! Free delivery to hotel or your villa.' => 'Арендуй байк за 2 минуты! Бесплатная доставка к вашему отелю или вилле.',
            'STEP {0} OF {1}' => 'ШАГ {0} ИЗ {1}',
            'day' => 'день',
            'month' => 'месяц',

            'Low mileage' => 'Маленький пробег',
            'New bike' => 'Новый',
            'Big mileage' => 'Большой пробег',

            'Helmets' => 'Шлемы',
            'Your scooter or motorbike will be delivered with' => 'Ваш скутер или мотоцикл, будет доставлен',
            'either 1 or 2 clean and sanitized helmets.' => 'с 1 или 2 чистыми шлемами.',

            'Rent a perfect bike in Bali' => 'Арендуй лучший байк на Бали',
            'choose date' => 'выбрать дату',
            'From' => 'С',
            'from' => 'с',
            'To' => 'По',
            'to' => 'по',

            'Pickup and drop at' => 'Выбрать и доставить:',
            'back' => 'назад',
            'Booking' => 'Арендовать',

            'Confirm' => 'Выбрать',

            'Please choose date.' => 'Пожалуйста укажите дату!',
            'The date of return should be after the date of the lease.' => 'Конечная дата, должна быть позднее начальной!',


            'Rent bike for' => 'Аренда на',
            'days' => 'дней',
            'free' => 'бесплатно',
            'Pick and drop at' => 'Выбрать и доставить',
            '{0} helmet' => '{0} шлем',
            '{0} helmets' => '{0] шлема',
            'Service fee' => 'Комиссия',
            'Reservation Total' => 'Общая стоймость',

            'Delivery the bike throughout the day.' => 'Доставка байка в течении дня',
            'Payment on delivery, we only accept cash.' => 'Оплата после доставки, только наличные',
            'If you have any question please' => 'Если у вас остались вопросы',
            'contact us' => 'свяжитесь с нами',
            'Personal info' => 'Персональная информация',
            'Name' => 'Имя',
            'type your name' => 'введите ваше имя',

            'Phone Number' => 'Телефон',
            'type your WhatsApp (with country code)' => 'введите ваш WhatsApp',

            'E-mail' => 'E-mail',
            'type your email' => 'введите ваш E-mail',

            'Please enter a name' => 'Введите ваше имя!',
            'Please enter a phone' => 'Введите ваш телефон!',
            'Please enter a email' => 'Введите ваш E-mail',

            'Thank you' => 'Спасибо',
            'Your order has been processed. We will contact you shortly!' => 'Ваш заказ в обработке. Мы свяжемся с вами в ближайшее время',
        ];
        $messages = SourceMessage::find()->where(['category' => 'app'])->all();
        foreach ($messages as $message) {
            /* @var $message \app\models\SourceMessage */
            if (isset($data[$message->message])) {
                $mdata = Message::find()->where(['id' => $message->id])->all();
                if ($mdata) {
                    foreach ($mdata as $val) {
                        if ($val->language == 'ru') {
                            $val->translation = $data[$message->message];
                        }
                        if ($val->language == 'en') {
                            $val->translation = $message->message;
                        }
                        $val->save();

                    }
                }
            }
        }
    }

    public function actionBooking($country, $region, $step = null)
    {
        $session = Yii::$app->session;
        if($country == 'vietnam')
            $session->set('vietnam', 'Book a bike in Muine Vietnam in 2 minutes! Free delivery to hotel or your villa.');

        if ($step == null || $step == 'index')
            $step = 'first';

        $session->set('base_url', "/".Yii::$app->language."/$country/$region");


        $regions = RegionList::findAll(['alias' => $region]);
        if ($regions) {
            $session->set('currency', $regions[0]->country->currency);
            foreach ($regions as $rgn){
                $region_list[] = $rgn->id;
            }
            if ($step == 'first') {
                $rentals = Rental::find()->where(['region_id' => $region_list])->asArray()->all();
                $u_coord = explode('|',$regions[0]->coord);
                $session->set('location_from_map', $regions[0]->coord);
                $session->set('name_from_map', $regions[0]->adress);
                $session->set('tag_line', $regions[0]->tag_line);
                $session->set('region', $region);


                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => $regions[0]->description ? Yii::t('main', $regions[0]->description) : Yii::t('main', 'We help our clients rent scooters which they like. Large selection of bikes. Good price only 4$ rent per day for one scooter.')
                ]);

                foreach ($rentals as $rental){
                    $rent_idsp[] = $rental['id'];
                }
                if (isset($rent_idsp) && is_array($rent_idsp)) {
                    $model = RentalGarage::find()->with(['condition', 'bike', 'bikeprice'])->asArray()->where(['status' => 1, 'rental_id' => $rent_idsp])->all();
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

            if($step == 'second'){
                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => Yii::t('main','We need this information for free delivery your bike to your point on the '.ucfirst($region))
                ]);
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

            if($step == 'third'){

                Yii::$app->view->registerMetaTag([
                    'name' => 'description',
                    'content' => Yii::t('main','We need this information to contact you to complete your order')
                ]);

                $session = Yii::$app->session;
                $request = Yii::$app->request;
                $bikes = $session->get('bikes');
                $post = $request->post();

                if ($request->isPost) {
                    $this->date_from = $request->post('date-from', '');
                    $this->date_to = $request->post('date-to', '');
                    $session->set('date_from', $request->post('date-from', ''));
                    $session->set('date_to', $request->post('date-to', ''));
                    $session->set('location_from_map', $request->post('location_from_map', ''));
                    $session->set('name_from_map', $request->post('name_from_map', ''));
                    $location_from_map = $request->post('location_from_map', '');
                } else {
                    $location_from_map = $session->get('location_from_map');
                }

                $rentals = Rental::find()->where(['region_id' => $region_list])->asArray()->all();
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

                if (stristr($session->get('helmets_count'), 'helmet') === false) {
                    if ($data['helmets_count'] == 1) {
                        $data['helmets_count'] = $data['helmets_count'] . " helmet";
                    } elseif ($data['helmets_count'] == 2) {
                        $data['helmets_count'] = $data['helmets_count'] . " helmets";
                    }
                }

                $session->set('helmets_count', $data['helmets_count']);

                $date_from = new DateTimeImmutable($session->get('date_from'));
                $date_to = new DateTimeImmutable($session->get('date_to'));
                $diff = $date_to->diff($date_from);
                $data['days'] = $diff->days;



                if (isset($bike_price) && !empty($bike_price)) {
                    $bike_price_day = $bike_price->price;
                    $bike_price_pm = $bike_price->pricepm;
                    $session->set('region_id', $rent_idsp[0]['region_id']);
                }else{
                    $bike_price_day = $bike_model['bikeprice']['price'];
                    $bike_price_pm = $bike_model['bikeprice']['pricepm'];
                }


                $days = $data['days'] + 1;
                if($days > 30 && !empty($bike_price_pm) && $bike_price_pm > 0) {
                    $days_count = $days % 30;
                    $month_count = intdiv($days, 30);
                    $data['bike_price'] = $bike_price_pm * $month_count + $bike_price_day * $days_count;
                }else{
                    $data['bike_price'] = $bike_price_day * $days;
                }

              /*if (isset($bike_price) && !empty($bike_price)) {
                    $data['bike_price'] = $bike_price->price * ($data['days'] + 1);
                    $session->set('region_id', $rent_idsp[0]['region_id']);
                } else {
                    $data['bike_price'] = $bike_model['bikeprice']['price'] * ($data['days'] + 1);
                }*/


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

            if($step == 'final'){
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


                    $u_coord = explode('|', $session->get('location_from_map'));
                    if ($session->get('region_id')) {
                        $garages = RentalGarage::find()->where([
                            'bike_id' => $u_bike_id,
                            'condition_id' => $u_condition_id,
                            'region_id' => $session->get('region_id'),
                            'status' => 1
                        ])->all();
                        $zakaz->region_id = $session->get('region_id');
                    } else {
                        $garages = RentalGarage::find()->where(['bike_id' => $u_bike_id, 'condition_id' => $u_condition_id, 'status' => 1])->all();
                    }
                    $zakaz->save();


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
                        'date' => $session->get('dates'),
                        'adress' => $session->get('name_from_map'),
                        'summ' => $zakaz->price,
                        'price' => $session->get('bike_price'),
                        'comission' => $session->get('service_fee'),
                        'rental_list' => isset($rentals_email) ? $rentals_email : ''
                    ];


                    Yii::$app->mailer->compose('views/rental_b_html', $data)
                        ->setTo(['barkov@bureauit.ru', 'mihalbl400@gmail.com'])
                        ->setSubject('getbike.io - Order')
                        ->send();
                    PseudoCrypt::sendtoTelegram($data);

                    $session->destroy();
                    if($country == 'vietnam')
                        $session->set('vietnam', 'Book a bike in Muine Vietnam in 2 minutes! Free delivery to hotel or your villa.');
                }

                return $this->render('final', [
                    'model' => strip_tags($request->post('name', '')),
                    'order' => $zakaz->id,
                    'price' => $zakaz->price
                ]);
            }

            return $this->redirect($session->get('base_url'));
        }
        return $this->redirect(['/']);
    }

}
