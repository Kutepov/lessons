<?php

namespace app\controllers;

use app\models\CompleteLessonsUsers;
use app\models\Lesson;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;

class LessonsController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['view', 'finish'],
                'rules' => [
                    [
                        'actions' => ['view', 'finish'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionView(string $id): string
    {
        $model = $this->findModel((int)$id);
        if (Yii::$app->user->identity->isNextLesson((int)$id) || Yii::$app->user->identity->isCompleteLesson((int)$id)) {
            return $this->render('view', compact('model'));
        }
        throw new ForbiddenHttpException('Урок недоступен');
    }

    public function actionFinish(string $id): Response
    {
        if (Yii::$app->request->isAjax) {
            $lesson = $this->findModel((int)$id);
            $user = Yii::$app->user->identity;
            if ($user->isCompleteLesson((int)$id)) {
                return $this->asJson(['result' => 'ok']);
            } elseif ($user->isNextLesson((int)$id)) {
                $completeLesson = new CompleteLessonsUsers();
                $completeLesson->user_id = $user->getId();
                $completeLesson->lesson_id = $lesson->id;
                if (!$completeLesson->save()) {
                    return $this->asJson(['result' => 'error']);
                }

                return $this->asJson(['result' => 'ok']);
            }
        }
        throw new ForbiddenHttpException('Only ajax query');
    }

    private function findModel(int $id): Lesson
    {
        if ($model = Lesson::findOne($id)) {
            return $model;
        }
        throw new NotFoundHttpException('Урок не найден');
    }
}