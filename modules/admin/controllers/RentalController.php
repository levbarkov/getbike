<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Rental;
use app\models\RentalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RegionList;
use yii\helpers\ArrayHelper;
use app\models\Operations;
use app\models\OperationsSearch;
/**
 * RentalController implements the CRUD actions for Rental model.
 */
class RentalController extends Controller
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
     * Lists all Rental models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Rental();
        $searchModel = new RentalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data['region_list'] = ArrayHelper::map(RegionList::find()->asArray()->all(), 'id', 'text');

/*        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', "Rental save success");
                return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error', "Rental save error");
            }
        }*/


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'data' => $data
        ]);
    }

    /**
     * Displays a single Rental model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $searchModel = new OperationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams + ['OperationsSearch' => ['=', 'rental_id' => $id]]);


        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Rental model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rental();
        $data['region_list'] = ArrayHelper::map(RegionList::find()->asArray()->all(), 'id', 'text');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'data' => $data

        ]);
    }

    /**
     * Updates an existing Rental model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data['region_list'] = ArrayHelper::map(RegionList::find()->asArray()->all(), 'id', 'text');

        $searchModel = new OperationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams + ['OperationsSearch' => ['=', 'rental_id' => $id]]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'data' => $data,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Rental model.
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

    /**
     * Finds the Rental model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rental the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rental::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
