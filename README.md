symfony_images
==============

A simple Symfony 2.8 project which provides online access to the albums of
images.

How start:
----------

1. Specify your web-server user name at const WEBSERVER_USER 
into <ROOT_DIR>/src/AppBundle/DataFixtures/ORM/LoadAlbumData.php
   
2. Load fixtures
   ```php app/console doctrine:fixtures:load```

3. If you are using Apache as web-server you will rename .htacess to 
apply directives.