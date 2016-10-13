Symfony image gallery
=====================

A simple project which provides online access to the albums of
images. It based on Symfony 2.8 and MarionetteJS 3.0.

How to start:
-------------
1. Prepare database
    ```php app/console doctrine:database:create```
    ```php app/console doctrine:schema:update --force```
    
2. Specify your web-server user name at const WEBSERVER_USER 
into <ROOT_DIR>/src/AppBundle/DataFixtures/ORM/LoadAlbumData.php
   
3. Load fixtures
   ```php app/console doctrine:fixtures:load```

4. Install npm modules
    ```cd src/AppBundle/Resource```
    ```npm install```

5. If you are using Apache as web-server you will rename .htacess to 
apply directives.

6. Start symfony app
    ```php app/console server:run```
    
7. Try into your browser
    ```http://127.0.0.1:8000/```