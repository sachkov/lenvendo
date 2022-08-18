Тестовое задание на позицию Backend PHP Developer
=================================================

Тестовое задание предоставлено компанией Slotegrator [текст](/test_task/Slotegrator/test%20task%20PHP%20Developer%20GA.pdf)  

### Что удалось сделать:

Реализованы 2 страницы: первая страница для авторизации и кпопки "Принять участие в розыгрыше", вторая страница для демонстрации приза и выбора действия, которое можно выполнить над данным призом. Шаблоны страниц выболнены в видах на основе библиотеки twig.  
Реализовано HTTP 4 метода: Показ главной страници с формой авторизации и кнопкой розыгрыша, показ страницы розыгрыша с кнопками выбора действия с призом, Авторизация, Участие в розыгрыше, Действите с призом. HTTP методы обрабатываются котроллерами.  
Созданы миграции для создания и первичного наполнения таблиц:
* prize_drawing - наименование розыгрыша призов
* prizes - возможные призы (проигрыш представлен как отдельный приз)
* prize_types - типы призов (для создания обработчиков показа призов и действий с призом)
* winners - победители розыгрыша
* prize_actions - возможные действия с призами
* prize_actions_log - запись действия с призом, выбранных пользователем
* users - таблица пользователей
* settings - таблица общих настроек
* user_points - таблица храннения пользовательских баллов  

Для таблиц созданы соответствующие модели. Работа с БД построена на библиотеке doctrine/dbal.  
Для записка миграций используется консольная команда  

    $ php concole make:migrations  

Если приложение запущено локально, то миграции лучше запускать из контейнера или командой  

    $ make migration

Консольные команды работают на библиотеке symfony/console.  
Бизнес логика реализована в сервисах. PrizeDrawing - для проведения розыгрыша, случайный выбор призов на основе мультипликаторов ценности каждого приза и остатка не выданных призов. PrizeAction/Common - сервис для отображения возможных действий с призом и обработки команд на выбор действия, а так же сохраннения результатов действий. Обработка различных действий реализована в соответствующих типам призов обработчиках.  
Приложение работает с использованием существующей библиотеки-минифреймворка для самообучения.  
Проект разворачивается в docker-compose. Перед разворачиванием необходимо переименовать файл **docker/env_example** в **.env**. В данном файле храняться базовые настройки приложения, необъодимо их поправить при необходимости.  
Приложение можно развернуть командой ./start в папке docker.  
Проведено нагрузочное тестирование главной страницы. [результаты](/test_task/Slotegrator/ApacheBench%2002-08-22.txt)

### Что не удалось сделать (не хватило времени):

1. До конца реализовать логику, описанную в задании, в которой денежные призы и призы баллами выдаются как случайное число и денежные баллы ограничены.
2. Не реализована консольная команда на выполнение заданий для отправки запросов по зачислениям денежных средств на счета пользователей
3. Показать использование исключений, DTO, центролизованный валидации входящих данных.
