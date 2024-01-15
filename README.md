## Установка

Следуйте этим шагам, чтобы установить и запустить проект локально на вашем компьютере.

1. **Клонировать репозиторий**

   ```bash
   git clone https://github.com/Odilbukh/toplist.git
2. **Перейти в директорию проекта**

   ```bash
   cd path-to-project

3. **Собирать Докер**
    ```bash
   docker compose build
   docker compose up -d
После успешный сборке докер контейнера, все следующие команды запускаем внутри контейнара

4. **Установить зависимости**
    ```bash
   composer install
    
5. **Настроить файл окружения**
   <br>Копируйте файл <b>.env.example</b> в <b>.env</b> и настройте его с вашими параметрами, такими как подключение к базе данных и другие настройки.
<br><b>Обратите внимание на эти поля и заполните их правильно!</b><br>
  DB_CONNECTION=mysql<br>
   DB_HOST=127.0.0.1<br>
   DB_PORT=3306<br>
   DB_DATABASE=<br>
   DB_USERNAME=<br>
   DB_PASSWORD=<br><br>
   
6. **Генерировать ключ приложения**
    ```bash
   php artisan key:generate
   
7. **Запустить миграции и наполнение базы данных**
    ```bash
    php artisan migrate
    php artisan db:seed --class=GameSeeder
   

## Список API

**localhost:8091/api/top-results**
![image](https://github.com/Odilbukh/toplist/assets/22895615/160341a5-bb78-4227-b593-fa16435d555f)


**localhost:8091/api/save-result**
![image](https://github.com/Odilbukh/toplist/assets/22895615/0b2d1d3b-5b75-4878-a618-356c53daf163)


