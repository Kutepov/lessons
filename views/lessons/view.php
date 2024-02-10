<?php

/** @var View $this */
/** @var Lesson $model */

use yii\widgets\DetailView;
use yii\web\View;
use app\models\Lesson;
use yii\helpers\Html;
use app\assets\LessonAsset;

LessonAsset::register($this);

$this->title = $model->title;

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'video_url',
            'value' => function (Lesson $model) {
                $urlArray = parse_url($model->video_url);
                parse_str($urlArray['query'], $params);
                return '<iframe 
                            width="560"
                            height="315"
                            src="https://www.youtube.com/embed/' . $params['v'] . '"
                            title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>';
            },
            'format' => 'raw',
        ],
        'title',
        'description',
        [
            'label' => '',
            'value' => function (Lesson $model) {
                return Html::a('Завершить', '#', ['class' => 'form-control btn btn-success', 'onclick' => 'lesson.finish( ' . $model->id . ' )']);
            },
            'format' => 'raw',
        ],
    ]
]);