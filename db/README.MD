
Creating the docker image:
This example uses the environment variables used in the sample.
````shell script
docker build -t xxs-db-image --build-arg db_root_pw=1234 --build-arg db_name=xxs-db --build-arg db_username=xxs-db --build-arg db_pw=1234 .
````

To start the dockerized SQL server, run the following command from the db folder of this project in the terminal:
````shell script
docker run -p 3306:3306 -d xxs-db-image
````
This will run the previously created image and forward the port 3306 to the local 3306
The database however still needs to be created.
