<?php
/**
 * Created by PhpStorm.
 * User: Нодирбек
 * Date: 05.03.2020
 * Time: 1:03
 */

namespace frontend\CustomHelper;

use Yii;
use yii\helpers\Html;

class CustomHelpers
{
    public $month = [
        '01' => 'Январь',
        '02' => 'Февраль',
        '03' => 'Март',
        '04' => 'Апрель',
        '05' => 'Май',
        '06' => 'Июнь',
        '07' => 'Июль',
        '08' => 'Август',
        '09' => 'Сентябрь',
        '10' => 'Октябрь',
        '11' => 'Ноябрь',
        '12' => 'Декабрь',
    ];

    // передаем месяц в текстовом виде
    public function getMonth($m)
    {
        return $this->month[$m];
    }

    // обработка транзакций для меню информации
    public function getItemsMenu($data)
    {
        $items = [];
        if (!empty($data)) {
            foreach ($data as $year => $yearsData) {
                $items[$year] = [
                    'label' => $year . '(' . $yearsData['count'] . ')',
                    'url' => Yii::$app->request->get() ? Yii::$app->request->url . '&DataSearch[date]=' . $year : '?DataSearch[date]=' . $year,
                ];
                foreach ($yearsData['month'] as $month => $monthData) {
                    $items[$year]['items'][] = ['label' => $monthData['title'] . '(' . $monthData['count'] . ')',
                        'url' => Yii::$app->request->get() ? Yii::$app->request->url . '&DataSearch[date]=' . $year . '-' . $month : '?DataSearch[date]=' . $year . '-' . $month
                    ];
                }
            }
        }
        return $items;
    }
}