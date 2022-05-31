
# About
This is a start project with some demos implemented.

## Features
The project is a habit tracker, to help me to keep track of the habits I want to develop.

User registration can be made from the [register page](http://127.0.0.1:3000/register/).

You can log in using the [login page](http://127.0.0.1:3000/login/).

After login you can access the [habits page](http://127.0.0.1:3000/habits/) to list existing habits or add new ones. 
This can also be done using the [api](http://127.0.0.1:8000/api).

The habits and the events can be inserted only by a logged-in user.
One user can have many habits and each habit can have just an event per day.
The user will receive an email at each event.

##Roadmap
Preferences for events frequency, targets and notification will be released in the next update.

## Architecture 

### Stack
#### Frontend 
The frontend is build using React.
#### Backend
The backend is build using Symfony and ApiPlatform.

### Infrastructure
The project contains the following containers managed by docker-compose file (./docker-compose.yml):
- mySQL:8.0.26 [database/Dockerfile](./database/Dockerfile), 
- redis:6.2.5-alpine [redis/Dockerfile](./redis/Dockerfile),
- php:8.1.3 [web/Dockerfile](./web/Dockerfile)
- node:17 [node/Dockerfile](./node/Dockerfile)

Start the project with `docker-compose up` from the habits-tracker folder.