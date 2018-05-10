<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
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
		if (($session->has('date_from')) and ($session->has('date_to'))){
			
            return $this->render('index', [
            'date_from' => $session->get('date_from'),
            'date_to' => $session->get('date_to'),
            ]);
			
			} else {
            return $this->render('index', [
            'date_from' => '',
            'date_to' => '',
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
        }
        $model = RentalGarage::find()->with('condition', 'bike', 'bikeprice')->asArray()->where('status = :status', [':status' => 1])->all();

        foreach($model as $key=>$value){
			foreach($value as $key1=>$value1){
			   $bikes[$value['bike_id']][$value['condition_id']][$key1]=$value1;
		     }
       
		     if ($value['condition_id']==1){
		     $bikes[$value['bike_id']]['first_img']=$value['bikeprice']['photo'];
		     }
			}
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
}
