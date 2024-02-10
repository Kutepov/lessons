'use strict';

var Lesson = function () {
    this.finish = function (lessonId) {
        $.ajax({
            'url': '/lessons/finish',
            'method': 'get',
            'data': {'id': lessonId},
            'success': function (data) {
                location.href = '/';
            },
        });
    };
};

var lesson = new Lesson();