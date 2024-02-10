<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "complete_lessons_users".
 *
 * @property int|null $lesson_id
 * @property int|null $user_id
 *
 * @property Lesson $lesson
 * @property User $user
 */
class CompleteLessonsUsers extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'complete_lessons_users';
    }

    public function rules(): array
    {
        return [
            [['lesson_id', 'user_id'], 'integer'],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lesson::class, 'targetAttribute' => ['lesson_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'lesson_id' => 'Lesson ID',
            'user_id' => 'User ID',
        ];
    }

    public function getLesson(): ActiveQuery
    {
        return $this->hasOne(Lesson::class, ['id' => 'lesson_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
