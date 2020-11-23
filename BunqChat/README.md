# Bunq Chat Application API

This is a simple chat application API that uses the Slim framework and an SQLite database. GUI is not implemented, so the only API exist. You can access it using Postman or some other tool. 

## Install the Application

start by using: ( or some other server like xampp)
```bash
 php -S localhost:8080 -t public public/index.php
```
The database will init by itself on first entry.


User needs to be logged in so put your token into Authorization heated.
API:
POST localhost:8080/user (name, password)
registers new user

POST localhost:8080/login (name, password)
to login user, returns authorization token.

POST localhost:8080/message(message, to_user_id)
sends a new message, to_user_id should be the id of the user you are sending a message.

GET: localhost:8080/message/{id} 
returns your conversation with user {id}

GET: localhost:8080/message {id} 
returns user ids with whom you previously had conversations
