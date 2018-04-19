<?php

namespace app\modules\rental\controllers;

use app\models\Bikes;
use app\models\Condition;
use app\models\Rental;
use app\models\RentalGarage;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class RentalController extends Controller
{
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
        $garage = RentalGarage::find()->where(['rental_id'=>Yii::$app->session->get('rental_id')])->all();
        return $this->render('index',[
            'garage' => $garage
        ]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionAccount()
    {
        $rental = Rental::findOne(Yii::$app->session->get('rental_id'));
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($rental->load($post) && $rental->validate() && $rental->save()){
                Yii::$app->session->setFlash('success', 'Information updated!');
            }else{
                Yii::$app->session->setFlash('error', 'Information update error!');

            }
        }
        return $this->render('account',[
            'model' => $rental
        ]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionAdd()
    {
        $garage = new RentalGarage();
        $bikes = Bikes::find()->all();
        $conditions = Condition::find()->all();

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            Yii::trace($post);
            if($garage->load($post) && $garage->validate() && $garage->save()){
                return $this->redirect('index');
            }else{
                $html = '';
                foreach ($garage->getErrors() as $key => $value) {
                    $html.= $key.': '.$value[0] . '<br>';
                }
                Yii::$app->session->setFlash('error', $html);

            }
        }

        return $this->render('add', [
            'bikes' => $bikes,
            'conditions' => $conditions
        ]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionEdit($id)
    {
        $bikes = Bikes::find()->all();
        $conditions = Condition::find()->all();
        $garage = RentalGarage::findOne($id);

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            Yii::trace($post);
            if($garage->load($post) && $garage->validate() && $garage->save()){
                return $this->redirect('index');
            }else{
                $html = '';
                foreach ($garage->getErrors() as $key => $value) {
                    $html.= $key.': '.$value[0] . '<br>';
                }
                Yii::$app->session->setFlash('error', $html);

            }
        }

        return $this->render('edit', [
            'garage' => $garage,
            'bikes' => $bikes,
            'conditions' => $conditions
        ]);
    }

    public function actionChangestatus($id){
        if(Yii::$app->request->isAjax){
            $garage = RentalGarage::findOne($id);
            if($garage->status == 0){
                $garage->status = 1;
            }else{
                $garage->status = 0;
            }
            if($garage->save())
                return 1;
            return 0;
        }else{
            return false;
        }
    }
    /**
     * Deletes an existing Bikes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        RentalGarage::findOne($id)->delete();
        return $this->redirect(['index']);
    }
}
