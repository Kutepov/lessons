<?php

use yii\db\Migration;

/**
 * Class m240209_092730_init
 */
class m240209_092730_init extends Migration
{

    public function safeUp(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->insert('{{%users}}', [
            'username' => 'user',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('user'),
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->createTable('{{%lessons}}', [
            'id' => $this->primaryKey(),
            'order' => $this->integer(),
            'title' => $this->string(),
            'description' => $this->text(),
            'video_url' => $this->string(),
        ]);

        $this->createTable('{{%complete_lessons_users}}', [
            'id' => $this->primaryKey(),
            'lesson_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        $this->addForeignKey('kf-complete-lessons-users_lesson_id', '{{%complete_lessons_users}}', 'lesson_id', '{{%lessons}}', 'id');
        $this->addForeignKey('kf-complete-lessons-users_user_id', '{{%complete_lessons_users}}', 'user_id', '{{%users}}', 'id');

        $this->batchInsert('{{%lessons}}', ['order', 'title', 'description', 'video_url'], [
            [
                1,
                'Урок 1. Фреймворк Yii2. Быстрый старт. Установка фреймворка Yii2',
                "В этом уроке будет показана установка фреймворка Yii2 на сервер. В качестве варианта установки будет выбран рекомендуемый вариант – установка при помощи Composer. Из урока вы узнаете о необходимом окружении для работы (веб-сервер, редактор кода), а также о минимальных требованиях к окружению.
Установив фреймворк, мы получим не просто голый каркас, но простое приложение, с которого можно начать знакомство с фреймворком и его возможностями",
                'https://www.youtube.com/watch?v=znr5u0t44-E',
            ],
            [
                2,
                'Урок 2. Фреймворк Yii2. Быстрый старт. Паттерн MVC',
                "Все современные PHP фреймворки реализуют архитектурный паттерн MVC, предполагающий разделение приложения на составные логические части: контроллеры, модели и виды. По сути это простейший архитектурный паттерн, понять который не составит особого труда. Однако, у начинающих разработчиков его понимание, порой, вызывает определенные трудности.
В предлагаемом видео на простых и понятных примерах будет показана суть паттерна MVC.",
                'https://www.youtube.com/watch?v=zIMOtP6jDVA',
            ],
            [
                3,
                'Урок 3. Фреймворк Yii2. Быстрый старт. Перенос шаблона',
                "Для дальнейшей работы и знакомства с возможностями фреймворка Yii2 был выбран бесплатный HTML-шаблон, интеграция которого и будет показана в данном видео. Из этого видео вы узнаете о том, как создать свой шаблон, как подключить стили и скрипты к шаблону, как разбить шаблон на части и выделить из него контентную часть. Также из видео вы узнаете о базовых моментах работы с файлом конфигурации фреймворка.",
                'https://www.youtube.com/watch?v=yD_jwKQSAdU',
            ],
            [
                4,
                'Урок 4. Фреймворк Yii2. Быстрый старт. Контроллер, модель и вид приложения',
                "Данный урок будет посвящен чуть более подробному освещению темы компонентов паттерна MVC: контроллерам, моделям и видам. В уроке будет показано создание контроллера для работы со статьями блога, а также экшена, отвечающего за работу со стартовой страницей приложения.
Также из урока вы узнаете, как создать модель, получить первые данные из БД и передать полученные данные в представление.",
                'https://www.youtube.com/watch?v=tOVDS41nROM',
            ],
            [
                5,
                'Урок 5. Фреймворк Yii2. Быстрый старт. Вывод и форматирование данных',
                "В этом уроке будет показан вывод данных, полученных из БД в предыдущем уроке. Это будут статьи. Буквально за считанные минуты статичный вывод шаблонных данных будет преобразован в динамичный и на странице будут показаны последние статьи блога. Кроме вывода данных, в уроке также будут показаны основы работы с компонентом для форматирования данных, который можно использовать для получения нужного формата даты.",
                'https://www.youtube.com/watch?v=R89PmMK9r0o',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%complete_lessons_users}}');
        $this->dropTable('{{%lessons}}');
        $this->dropTable('{{%users}}');
    }
}
