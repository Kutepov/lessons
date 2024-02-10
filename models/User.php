<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['username', 'email'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['username', 'password_hash', 'auth_key'], 'required'],
        ];
    }

    public static function findIdentity($id): static
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null): void
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username): static|null
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId(): mixed
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getCompleteLessons(): ActiveQuery
    {
        return $this
            ->hasMany(Lesson::class, ['id' => 'lesson_id'])
            ->viaTable('complete_lessons_users', ['user_id' => 'id']);
    }

    public function isCompleteCourse(): bool
    {
        return $this->getCompleteLessons()->count() === Lesson::find()->count();
    }

    public function isCompleteLesson(int $lessonId): bool
    {
        return (bool)CompleteLessonsUsers::find()->where(['user_id' => $this->id, 'lesson_id' => $lessonId])->count();
    }

    public function isNextLesson(int $lessonId): bool
    {
        if (!$this->getCompleteLessons()->count() && $lessonId === 1) {
            return true;
        }
        return $this->getCompleteLessons()->max('id') + 1 === $lessonId;
    }
}
