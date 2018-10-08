<?php

namespace app\modules\admin\controllers;

use app\models\Regions;
use Yii;
use app\models\Country;
use app\models\CountrySearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $country = new Country();
        $country_list = Country::find()->asArray()->all();
        $country_list = ArrayHelper::map($country_list, 'id', 'text');
        $region = new Regions();

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $model = ($post['type'] == 'Country') ? new Country() : new Regions();
            $type = $post['type'];
            unset($post['type']);
            if($model->load($post) && $model->save()){
                Yii::$app->session->setFlash('success', "$type save success");
            }else{
                Yii::$app->session->setFlash('error', "$type save error");
            }
            return $this->redirect('index');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'country' => $country,
            'region' => $region,
            'country_list' => $country_list,
        ]);
    }

    /**
     * Displays a single Country model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Country();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionEditregion($id){
        $region = Regions::findOne(['id'=>$id]);
        $result = ['status'=>'false','text'=>'Error!'];
        if($region){
            $post = Yii::$app->request->post();
            if($post['act'] == 'dell'){
                $region->delete();
                $result = ['status'=>'success','text'=>'Region dell success'];
            }
            if($post['act'] == 'save'){
                $region->text = $post['val'];
                if($region->save())
                    $result = ['status'=>'success','text'=>'Region save success'];
            }
        }
        return json_encode($result);
    }
    public function actionGetregions($id){
        $regions = Regions::findAll(['country_id' => $id]);
        if($regions){
            $html = "<option value>Значение не выбрано</option>";
            foreach ($regions as $region){
                $html.="<option value='$region->id'>$region->text</option>";
            }
            return $html;
        }else{
            return 0;
        }
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
