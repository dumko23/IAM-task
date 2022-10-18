# IAM-task
## Identity and Access Management Page (simplified)


To run this project as it is, the latest version of Docker and Composer should be installed on your machine.


If you want to run this project on a different environment - the actual project files located in "```www/```" folder.


Preview: ```https://softsprint-task.000webhostapp.com/```


## Docker
Crete ```.env``` files in project root and "www" folders, then copy content of the ```sample.example.env``` according to its location to ```.env``` files. 
Here you can manage your DB credentials or leave it as it is.


Using terminal, ```cd``` to the "```www/```" folder and run ```composer install```. This will load necessary packages from ```composer.json``` and install them to the ```vendor``` folder.


In terminal ```cd``` back to the project root and paste this commands:
```
docker-compose build
docker-compose up -d
```
to build ```Apache+PHP``` container and start this project in detached mode.


Finally, you can go to ```http://localhost/``` to enter project main page.


## Other environments
Copy content of the "```www```" folder to your server root.


Crete ```.env``` file in "www" folder, then copy content of the ```sample.example.env``` to ```.env``` file.


Now, you can manage your DB credentials and  server settings to run this project.
