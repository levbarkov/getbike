<?php

namespace app\modules\admin\controllers;

use app\models\Bikes;
use app\models\Condition;
use app\models\RegionList;
use Yii;
use app\models\BikesPrice;
use app\models\BikesPriceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * BikespriceController implements the CRUD actions for BikesPrice model.
 */
class BikespriceController extends Controller
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
     * Lists all BikesPrice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new BikesPrice();
        $bikes = new Bikes();
        $data['bike_list'] = ArrayHelper::map(Bikes::find()->asArray()->all(), 'id', 'model');
        $data['condition_list'] = ArrayHelper::map(Condition::find()->asArray()->all(), 'id', 'text');
        $data['region_list'] = ArrayHelper::map(RegionList::find()->asArray()->all(), 'id', 'text');

        $searchModel = new BikesPriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post())) {
                $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Price save success");
                    return $this->redirect(['index']);
                }
            }
            if ($bikes->load(Yii::$app->request->post()) && $bikes->save()) {
                Yii::$app->session->setFlash('success', "Bike save success");
                return $this->redirect(['index']);
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data' => $data,
            'model' => $model,
            'bikes' => $bikes,
        ]);
    }

    /**
     * Displays a single BikesPrice model.
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
     * Finds the BikesPrice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BikesPrice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BikesPrice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new BikesPrice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BikesPrice();
        $bikes = new Bikes();
        $data['bike_list'] = ArrayHelper::map(Bikes::find()->asArray()->all(), 'id', 'model');
        $data['condition_list'] = ArrayHelper::map(Condition::find()->asArray()->all(), 'id', 'text');
        $data['region_list'] = ArrayHelper::map(RegionList::find()->asArray()->all(), 'id', 'text');


        if ($model->load(Yii::$app->request->post())) {
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'data' => $data,
            'bikes' => $bikes,
        ]);
    }

    /**
     * Updates an existing BikesPrice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $bikes = new Bikes();
        $data['bike_list'] = ArrayHelper::map(Bikes::find()->asArray()->all(), 'id', 'model');
        $data['condition_list'] = ArrayHelper::map(Condition::find()->asArray()->all(), 'id', 'text');
        $data['region_list'] = ArrayHelper::map(RegionList::find()->asArray()->all(), 'id', 'text');

        if ($model->load(Yii::$app->request->post())) {
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'data' => $data,
            'bikes' => $bikes,
        ]);
    }

    /**
     * Deletes an existing BikesPrice model.
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
}
