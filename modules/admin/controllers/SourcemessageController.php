<?php

namespace app\modules\admin\controllers;

use app\models\Message;
use Yii;
use app\models\SourceMessage;
use app\models\SourceMessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SourcemessageController implements the CRUD actions for SourceMessage model.
 */
class SourcemessageController extends Controller
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
     * Lists all SourceMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SourceMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new SourceMessage();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Message add!");
            return $this->redirect(['index']);
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
    public function actionUpdatelocale(){
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $id = $post['locale_id'];
            if ($id) {
                $language = $post['language'];
                if ($language && is_array($language)) {
                    foreach ($language as $key => $value) {
                        $model = Message::findOne(['id' => $id, 'language' => $key]);
                        if ($model) {
                            $model->translation = $value;
                            if ($model->save()) {
                                $error[$key] = 0;
                            } else {
                                $error[$key] = 1;
                            }
                        }
                    }
                }
                $data = [];
                $data['status'] = 'success';
                $data['text'] = '';
                if ($error) {
                    foreach ($error as $key => $value) {
                        if ($value == 0) {
                            $data['text'] .= "Language $key: save success! <br>";
                        } else {
                            $data['text'] .= "Language $key: save error! <br>";
                        }
                    }
                }

                exit(json_encode($data));
            }
        }
    }

    /**
     * Displays a single SourceMessage model.
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
     * Finds the SourceMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SourceMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SourceMessage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('admin/sourcemessage', 'The requested page does not exist.'));
    }

    /**
     * Creates a new SourceMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SourceMessage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SourceMessage model.
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
     * Deletes an existing SourceMessage model.
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
    public function actionTest(){
        exit(var_dump(Yii::$app->params));
    }
}
