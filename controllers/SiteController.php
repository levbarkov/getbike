<?php

namespace app\controllers;

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
		if (($session->has('date_from')) and ($session->has('date_to')) and ($session->has('location_from_map')) and ($session->has('name_from_map'))){
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
        $location_from_map=$request->post('location_from_map', '');
        } else {
		$location_from_map=$session->get('location_from_map');	
		}
        
        //rental filter begin
        $latlng2=explode("|",$location_from_map);
        $rentals = Rental::find()->asArray()->all();
       
        foreach ($rentals as $keyr=>$valr){	
        $query_place=urlencode($valr['adress']);
        $location=urlencode("-8.3693355,115.0350728");
        $key="AIzaSyDqY4QbBgvy2yBZTbdRUja6fjOTnO4m1vM";
	    $url="https://maps.googleapis.com/maps/api/place/textsearch/xml?location=".$location."&radius=50000&query=".$query_place."&key=".$key;
		$args 		= array(
			'headers'	=> array(
				'Content-type: application/xml; charset=UTF-8'
			)
		);	    
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $args['headers'] );
		curl_setopt( $curl, CURLOPT_HEADER, 0 );
		$response = curl_exec( $curl );
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close( $curl );	
        $adress=simplexml_load_string($response);
      
        if ($adress->status=='OK'){		
		$cn=0;
		foreach ($adress->result as $adr){
		
		if (strpos((string)$adr->formatted_address, 'Bali')){
		$adrr[$valr['id']][$cn]['coord']=(string)$adr->geometry->location->lat."|".(string)$adr->geometry->location->lng;
		$adrr[$valr['id']][$cn]['name']=(string)$adr->formatted_address;
        //calculate begin 
         // Convert degrees to radians.
        $lat1=deg2rad((string)$adr->geometry->location->lat);
        $lng1=deg2rad((string)$adr->geometry->location->lng);
        $lat2=deg2rad($latlng2[0]);
        $lng2=deg2rad($latlng2[1]);
     
        // Calculate delta longitude and latitude.
        $delta_lat=($lat2 - $lat1);
        $delta_lng=($lng2 - $lng1);
     
        if ((round( 6378137 * acos( cos( $lat1 ) * cos( $lat2 ) * cos( $lng1 - $lng2 ) + sin( $lat1 ) * sin( $lat2 ) ) )/1000)<=$valr['radius']){
        $rent_idsp[]=$valr['id'];      
	    }
        //calculate	end
		$cn=$cn+1;
	    }
		}

	    }       
	    }
	    $rent_idsp=array_unique($rent_idsp);
        //rental filter end
        //echo "<pre>";
        //print_r($rent_idsp);
       // $sel="[".implode(",",$rent_idsp)."]";
        $model = RentalGarage::find()->with('condition', 'bike', 'bikeprice')->asArray()->where(['status' => 1, 'rental_id'=>$rent_idsp])->all();
        //print_r($model);
        //echo implode(',',$rent_idsp);
        //echo "</pre>";
        
        foreach($model as $key=>$value){
			if (count($value['bikeprice'])!=0){
			foreach($value as $key1=>$value1){
				
			if ($key1=='bikeprice'){
				$price_arr=array();	
				
				 foreach($value1 as $kv=>$val){
					 $price_arr[$kv]=$val['price'];
					 }
				arsort($price_arr);
                reset($price_arr);	 

				$key_price=0;
				foreach($price_arr as $kv1=>$val1){
					$key_price=$kv1;
					break;
					}
				$value1=$value1[$key_price];	
				
				}	
		    		
			   $bikes[$value['bike_id']][$value['condition_id']][$key1]=$value1;
			}
		     $photos[$value['bike_id']][$value['condition_id']]=$value['bikeprice'][$key_price]['photo'];
		 }
		     
			}	

			foreach ($photos as $pk=>$pv){
				ksort($photos[$pk]);
                reset($photos[$pk]);
                foreach ($photos[$pk] as $ppk=>$ppv){
					$bikes[$pk]['first_img']=$ppv;
					$bikes[$pk]['first_condition']=$ppk;
					break;
					}
				}

			unset($photos);
		//echo "<pre>";	
        //echo print_r($bikes);
        //echo "</pre>";		
        //die();	
      $session->set('bikes', $bikes);
            return $this->render('second', [
            'model' => $bikes,
            ]);
            
					
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
        $bikes=$session->get('bikes');		
		$post = $request->post();
		if ($request->isPost) {
		$this->bike_id = $request->post('bike_id', '');   
        $this->condition_id = $request->post('condition_id', '');
        $this->helmets_count = $request->post('helmets_count', '');    
        $session->set('bike_id', $this->bike_id);
        $session->set('condition_id', $this->condition_id);	
        $session->set('helmets_count', $this->helmets_count);
	    $data['helmets_count']=$this->helmets_count;
	    $bike_model=$bikes[$this->bike_id][$this->condition_id];
	    $data['bike_model']=$bike_model['bike']['model'];	    
	    } else {
		$data['helmets_count']=$session->get('helmets_count');
	    $bike_model=$bikes[$session->get('bike_id')][$session->get('condition_id')];
	    			
		}
		$data['bike_model']=$bike_model['bike']['model'];
		
		if ($data['helmets_count']==1){
			$data['helmets_count']=$data['helmets_count']." helmet";
			} elseif ($data['helmets_count']==2) {
			$data['helmets_count']=$data['helmets_count']." helmets";	
			}
        $date_from = new DateTimeImmutable($session->get('date_from'));
        $date_to= new DateTimeImmutable($session->get('date_to'));	
        $diff = $date_to->diff($date_from);
        $data['days']=$diff->days;
        $data['bike_price']=$bike_model['bikeprice']['price']*($data['days']+1);
        $data['service_fee']=($data['bike_price']/100)*15;
        $data['service_fee']=round($data['service_fee']/1000)*1000;
        $data['total']=$data['bike_price']+$data['service_fee'];
        $data['total']=round($data['total']/1000)*1000;
        $session->set('total', $data['total']);
        $data['date_from']=$date_from->format('j F');
        $data['date_to']=$date_to->format('j F Y');
        $data['name_from_map']=$session->get('name_from_map');
        
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
$zakaz=new Zakaz;
$zakaz->user_name=strip_tags($request->post('name', ''));
$zakaz->user_email=strip_tags($request->post('phone', ''));
$zakaz->user_phone=strip_tags($request->post('email', ''));
$date_from=new DateTimeImmutable($session->get('date_from'));
$date_to=new DateTimeImmutable($session->get('date_to'));
$timestamp=new DateTimeImmutable();
$timestamp->setTimestamp(time());
$zakaz->date_for=$date_from->format('Y-m-d');
$zakaz->date_to=$date_to->format('Y-m-d');
$zakaz->curr_date=$timestamp->format('Y-m-d H:i:s');
$zakaz->price=$session->get('total');
$zakaz->save();		
		$session->destroy();
}		
        return $this->render('final',[
            'model' => strip_tags($request->post('name', '')),
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
        $query_place=urlencode($request->get('term', ''));
        $location=urlencode("-8.3693355,115.0350728");
        $key="AIzaSyDqY4QbBgvy2yBZTbdRUja6fjOTnO4m1vM";
	    $url="https://maps.googleapis.com/maps/api/place/textsearch/xml?location=".$location."&radius=50000&query=".$query_place."&key=".$key;
		$args 		= array(
			'headers'	=> array(
				'Content-type: application/xml; charset=UTF-8'
			)
		);	    
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $args['headers'] );
		curl_setopt( $curl, CURLOPT_HEADER, 0 );
		$response = curl_exec( $curl );
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close( $curl );	
        $adress=simplexml_load_string($response);
      
        if ($adress->status=='OK'){		
		$cn=0;
		foreach ($adress->result as $adr){
		
		if (strpos((string)$adr->formatted_address, 'Bali')){
		$adrr[$cn]['label']=(string)$adr->formatted_address;
		$adrr[$cn]['value']=(string)$adr->geometry->location->lat."|".(string)$adr->geometry->location->lng;
		$cn=$cn+1;
	    }
		}
		
		if ($cn!=0){
		echo json_encode($adrr);
	    } else {
		echo "";
		}
	    } else {
		echo "";		
		}	
	}	      
                
}
