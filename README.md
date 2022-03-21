This is a start project with some demos implemented.

The project is a habit tracker, to help me to keep track of the habits I want to develop.

User registration can be made, momentarily, by the API (User->POST at http://127.0.0.1:8000/api).
You can log in using the form from http://127.0.0.1:8000/login.

After login the app can be used just from the api **http://127.0.0.1:8000/api** as the frontend is not yet ready

The habits and the events can be inserted using the api only by a logged-in user.
One user can have many habits and each habit can have just an event per day.
The user will receive an email at each event.

Preferences for events frequency, targets and notification will be released in the next update.

The project contains the following containers managed by docker-compose file (./docker-compose.yml):
- mySQL:8.0.26 (./database/Dockerfile), 
- redis:6.2.5-alpine (./redis/Dockerfile),
- php:8.1.3 (./web/Dockerfile)

Start the project with `docker-compose up` from the habits-tracker folder.