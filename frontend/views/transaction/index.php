<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cписка транзакций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">

        <div class="col-md-3 col-lg-3 col-sm-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Информация
                </div>
                <div class="panel-body">
                    <?= (!empty($items)) ? \yii\widgets\Menu::widget([
                        'options' => ['class' => 'nav'],
                        'items' => $items,

                    ]) : 'пусто' ?>
                    <br>
                    <?= Html::a('Очистить фильтр', '/transaction', ['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-lg-9 col-sm-9">
            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'card_number',
                    'date',
                    'volume',
                    'service',
                    'address_id',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

            <?php Pjax::end(); ?>

        </div>

    </div>
</div>
