<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "lessons".
 *
 * @property int $id
 * @property int|null $order
 * @property string|null $title
 * @property string|null $description
 * @property string|null $video_url
 *
 */
class Lesson extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'lessons';
    }

    public function rules(): array
    {
        return [
            [['order'], 'integer'],
            [['description'], 'string'],
            [['title', 'video_url'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'order' => 'Урок №',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'video_url' => 'Видео',
        ];
    }
}
