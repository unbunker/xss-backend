# XSS backend
backend for the XSS attack


# Setting up 
Install the composer packages

````shell script
composer install
````

## Setting up local database
Create a database
You can follow the README.md from the db folder to create a database with docker.

To perform the migrations perform:
````shell script
php bin/console doctrine:migrations:migrate
````

Do not forget to run this, to generate keypair, for jwt 
````shell script
php bin/console lexik:jwt:generate-keypair
````


## Start the backend

````shell script
symfony server:start
````

if everything works you can do in a new terminal:
````shell script
curl http://127.0.0.1:8000
````

This should give something like:
{"status":"running","environment":"dev"}