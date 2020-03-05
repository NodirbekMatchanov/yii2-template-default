<?php

namespace frontend\controllers;

use frontend\CustomHelper\CustomHelpers;
use Yii;
use frontend\models\Data;
use frontend\models\DataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionController implements the CRUD actions for Data model.
 */
class TransactionController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Data models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $infoTransactionsData = $dataProvider->query->asArray()->all();
        $infoTransaction = [];
        if (!empty($infoTransactionsData)) {
            $infoTransaction = $this->getCountTransactions($infoTransactionsData);
        }
        $customHelper = new CustomHelpers();
        $items = $customHelper->getItemsMenu($infoTransaction);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'items' => $items,
        ]);
    }
    // обработка транзакций
    public function getCountTransactions($infoTransactionsData)
    {
        $infoTransaction = [];
        $customHelper = new CustomHelpers();
        foreach ($infoTransactionsData as $item) {
            try {
                $year = date("Y", strtotime($item['date']));
                $monthDigit = date("m", strtotime($item['date']));
                $month = $customHelper->getMonth($monthDigit);
                $infoTransaction[$year]['month'][$monthDigit]['count']++;
                $infoTransaction[$year]['month'][$monthDigit]['title'] = $month;
                $infoTransaction[$year]['count']++;
            } catch (\Exception $exception) {
                Yii::info('Ошибка при обработке данных:');
                Yii::info($exception);
            }
        }
        return $infoTransaction;
    }

    /**
     * Displays a single Data model.
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
     * Creates a new Data model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Data();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Data model.
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
     * Deletes an existing Data model.
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
     * Finds the Data model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Data the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Data::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
