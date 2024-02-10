<?php

/** @var View $this */
/** @var ActiveDataProvider $dataProvider */

use yii\grid\GridView;
use app\models\Lesson;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\data\ActiveDataProvider;

$this->title = 'Уроки';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Уроки</h1>
    </div>

    <?php

    if (Yii::$app->user->identity->isCompleteCourse()) { ?>
        <div class="alert alert-success" role="alert">
            Курс пройден!
        </div>
    <?php } ?>

    <div class="body-content">
        <div class="row">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => false,
                'tableOptions' => [
                    'class' => 'table table-bordered',
                ],
                'rowOptions' => function (Lesson $model, $key, $index, $grid) {
                    $class = '';
                    if (!Yii::$app->user->identity->isCompleteLesson($model->id) && !Yii::$app->user->identity->isNextLesson($model->id)) {
                        $class = 'table-secondary';
                    } elseif (Yii::$app->user->identity->isCompleteLesson($model->id)) {
                        $class = 'table-success';
                    }

                    return ['class' => $class];
                },
                'columns' => [
                    'order',
                    'title',
                    [
                        'label' => '',
                        'value' => function (Lesson $model) {
                            if (!Yii::$app->user->identity->isCompleteLesson($model->id) && !Yii::$app->user->identity->isNextLesson($model->id)) {
                                $text = 'Начать';
                                $class = 'secondary disabled';
                            } elseif (Yii::$app->user->identity->isCompleteLesson($model->id)) {
                                $text = 'Повторить';
                                $class = 'primary';
                            } else  {
                                $text = 'Начать';
                                $class = 'success';
                            }
                            return Html::a($text, Url::to(['lessons/view', 'id' => $model->id]), ['class' => 'form-control btn btn-' . $class]);
                        },
                        'format' => 'raw',
                    ]
                ]
            ]);
            ?>
        </div>
    </div>
</div>
