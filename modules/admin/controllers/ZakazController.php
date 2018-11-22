<?php

namespace app\modules\admin\controllers;

use app\models\Operations;
use app\models\OperationsSearch;
use app\models\Rental;
use Yii;
use app\models\Zakaz;
use app\models\ZakazSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ZakazController implements the CRUD actions for Zakaz model.
 */
class ZakazController extends Controller
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
     * Lists all Zakaz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ZakazSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $summ = Zakaz::find()->where(['status' => [1, 2]])->sum('price');
        $tax_summ = Zakaz::find()->where(['status' => [1, 2]])->sum('service_tax');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'summ' => $summ,
            'tax_summ' => $tax_summ
        ]);
    }

    /**
     * Displays a single Zakaz model.
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
     * Finds the Zakaz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Zakaz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Zakaz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Zakaz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Zakaz();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Zakaz model.
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
     * Deletes an existing Zakaz model.
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

    public function actionChangestatus($id)
    {
        $model = $this->findModel($id);
        $status_id = Yii::$app->request->post('lead_status');
        $model->status = $status_id;

        if($status_id == 1){
            $operations = Operations::find()->where(['order_id' => $model->id])->one();

            if($operations){
                if(is_int($model->rental_id))
                    $operations->rental_id = $model->rental_id;
            }else{
                $operations = new Operations();
                if(is_int($model->rental_id))
                    $operations->rental_id = $model->rental_id;
                $operations->order_id = $model->id;
                $operations->sum = -$model->service_tax;
                $operations->operations = "Order #$model->id commission";
            }
            $operations->save();
        }

        if($status_id == 3){
            $operations = Operations::find()->where(['order_id' => $model->id])->one();
            if($operations){
                $operations->rental_id = null;
            }
            $operations->save();
        }


        if ($model->save()) {
            $result = ['status' => 'success', 'text' => 'Статус успешно изменён!'];
        } else {
            $result = ['status' => 'false', 'text' => 'Возникла проблема!'];
        }
        return json_encode($result);
    }

    public function actionChangerental($id)
    {
        $model = $this->findModel($id);
        $rental_id = Yii::$app->request->post('rental_id');

/*        $operations = Operations::find()->where(['order_id' => $id])->one();
        if($operations){
            if($rental_id == 0){
                $operations->delete();
                unset($operations);
            }else{
                $operations->rental_id = $rental_id;
            }
        }else{
            $operations = new Operations();
            $operations->rental_id = $rental_id;
            $operations->order_id = $id;
            $operations->operations = "Order #$id commission";
            $operations->sum = -$model->service_tax;
        }

        if($operations)
            $operations->save();*/



/*        if ($model->rental_id !== null) {
            $cur_rental = $model->rental_id;
            if ($cur_rental != $rental_id) {
                $cur_rental = Rental::findOne(['id' => $cur_rental]);
                $cur_rental->balance = $cur_rental->balance + $model->service_tax;

                $operations = Operations::find()->where(['order_id' => $id, 'rental_id' => $cur_rental->id])->one();

                $rental = Rental::findOne(['id' => $rental_id]);
                if ($rental){
                    $rental->balance = $rental->balance - $model->service_tax;
                    if($operations){
                        $operations->rental_id = $rental->id;
                    }else{
                        $operations = new Operations();
                        $operations->rental_id = $rental->id;
                        $operations->order_id = $id;
                        $operations->operations = "Order #$id commission";
                        $operations->sum = $model->service_tax;
                    }
                }else{

                    if($operations){
                        $operations->delete();
                        unset($operations);
                    }
                }
            }
        } else {
            $rental = Rental::findOne(['id' => $rental_id]);
            $rental->balance = $rental->balance - $model->service_tax;

            $operations = new Operations();
            $operations->rental_id = $rental->id;
            $operations->order_id = $id;
            $operations->operations = "Order #$id commission";
            $operations->sum = $model->service_tax;
        }


        if (isset($cur_rental))
            $cur_rental->save();

        if (isset($rental))
            $rental->save();

        if(isset($operations))
            $operations->save();*/


        $model->rental_id = ($rental_id != 0) ? $rental_id : null;

        if ($model->save()) {
            $result = ['status' => 'success', 'text' => 'Rental change success!'];
        } else {
            $result = ['status' => 'false', 'text' => 'Error!'];
        }
        return json_encode($result);
    }

    public function actionUpdateorders(){
        $orders = Zakaz::find()->where(['status' => 1])->andFilterWhere(['<>', 'rental_id', 'NULL'])->all();
        foreach ($orders as $order){
            /* @var $order \app\models\Zakaz */
            $operations = Operations::find()->where(['order_id' => $order->id])->one();
            if($operations){
                $operations->rental_id = $order->rental_id;
                $operations->operations = "Order #$order->id commission";
                $operations->sum = -$order->service_tax;
            }else{
                $operations = new Operations();
                $operations->order_id = $order->id;
                $operations->rental_id = $order->rental_id;
                $operations->operations = "Order #$order->id commission";
                $operations->sum = -$order->service_tax;
            }
            $operations->save();
            unset($operations);
        }

        Yii::$app->session->setFlash('success', "Operations updated!");
        //return $this->redirect('index');
    }
    public function actionUpdaterentalbalance(){
        $rentals = Rental::find()->all();
        foreach ($rentals as $rental){
            $sum = Operations::find()->where(['rental_id' => $rental->id])->sum('sum');
            var_dump($sum);
            $rental->balance = $sum;
            $rental->save();
        }
    }

}
