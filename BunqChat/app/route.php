<?php

declare(strict_types=1);


use Slim\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;


// include database and object files
include_once 'database/database.php';
include_once 'models/user.php';
include_once 'models/message.php';
include_once 'config/token.php';

return function (App $app) {
    //create new user
    $app->post('/user', function (RequestInterface $request, ResponseInterface $response, $args) {

        //get params
        $params = (array)$request->getParsedBody();

        //check if params exist
        if (!isset($params['name']) || !isset($params['password'])) {
            return is400Response($response, "missing name or password");
        }

        //get db connection
        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);
        // set user property values
        $user->name =  $params['name'];
        $user->password = password_hash($params['password'], PASSWORD_DEFAULT);


        //check if username is taken by selecting user from db
        $stmt = $user->get_user();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row != false) {
            return is400Response($response, "username is taken");
        }

        // create the user
        if ($user->create()) {
            return is200Response($response, "user created");
        } else {
            return is400Response($response, "error");
        }

        return $response;
    });

    //send message
    $app->post('/message', function (RequestInterface $request, ResponseInterface $response, $args) {
        //chek if authenticated 
        //get token from header
        if (!isLogedin($request)) {
            return is401Response($response, "unauthorized");
        }

        //get db connection
        $database = new Database();
        $db = $database->getConnection();

        //get params and chek if not empty
        $params = (array)$request->getParsedBody();
        if (!isset($params['message']) || !isset($params['to_user_id'])) {
            return is400Response($response, "missing values");
        }

        // set message property values
        $message = new Message($db);
        //take sender id from token
        $message->from_user_id =  Token::is_valid($request->getHeaderLine('Authorization'))['data']['id'];
        $message->to_user_id = $params['to_user_id'];
        $message->message = $params['message'];


        if (!userExist($params['to_user_id'])) {
            return is400Response($response, "error1");
        }

        // create the user
        if ($message->create()) {
            return is200Response($response, "message sent");
        } else {
            return is400Response($response, "error2");
        }

        return $response;
    });
    //read messages with user {id}
    $app->get('/message/{id}', function (RequestInterface $request, ResponseInterface $response, $args) {

        if (!isLogedin($request)) {
            return is401Response($response, "unauthorized");
        }

        $id = $args['id'];
        if (!userExist($id)) {
            return is400Response($response, "error");
        }
        $database = new Database();
        $db = $database->getConnection();

        $message = new Message($db);
        // set message property values
        $message->to_user_id = $id;
        $message->from_user_id = Token::is_valid($request->getHeaderLine('Authorization'))['data']['id'];; ///get user id from token

        //querry messages
        $stmt = $message->get_messages();
        $messages = [];
        while ($row = $stmt->fetchObject()) {
            array_push($messages, $row);
        }
        return is200Response($response, $messages);
    });

    //get chats of user 
    $app->get('/message', function (RequestInterface $request, ResponseInterface $response, $args) {

        if (!isLogedin($request)) {
            return is401Response($response, "unauthorized");
        }

        $database = new Database();
        $db = $database->getConnection();

        $message = new Message($db);
        // set message property values
        $message->from_user_id = Token::is_valid($request->getHeaderLine('Authorization'))['data']['id'];; ///get user id from token

        //querry messages
        $stmt = $message->get_all_chats();
        $users = [];
        while ($row = $stmt->fetchObject()) {
            array_push($users, $row->to_user_id);
            array_push($users, $row->from_user_id);
        }
        $users = array_values(array_unique($users));
        return is200Response($response, $users);
    });

    $app->post('/login', function (RequestInterface $request, ResponseInterface $response, $args) {

        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);

        $params = (array)$request->getParsedBody();

        $user->name = isset($params['name']) ? $params['name'] : "";
        $user->password = isset($params['password']) ? $params['password'] : "";

        //check if user exist
        $stmt = $user->get_user();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row != false) {
            extract($row);

            //verfy password
            if (password_verify($user->password, $row['password'])) {
                $token = Token::create_token($row['id'], $row['name']);
                return is200Response($response, $token);
            } else {
                $responseMessage = "invalid username or password";

                return is401Response($response, $responseMessage);
            }
        } else {
            return is401Response($response, "Unauthorised");
        }
    });
};


function userExist($userId)
{
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $user->id = $userId;
    $stmt = $user->get_user_by_id();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row == false) {
        return false;
    }
    return true;
}
function isLogedin($request)
{

    if ($request->getHeaderLine('Authorization') !== null && Token::is_valid($request->getHeaderLine('Authorization'))) {
        return true;
    }
    return false;
}

function is401Response($response, $responseMessage)
{
    $responseMessage = json_encode(["success" => false, "response" => $responseMessage]);
    $response->getBody()->write($responseMessage);
    return $response->withHeader("Content-Type", "application/json")
        ->withStatus(401);
}

function is200Response($response, $responseMessage)
{
    $responseMessage = json_encode(["success" => true, "response" => $responseMessage]);
    $response->getBody()->write($responseMessage);
    return $response->withHeader("Content-Type", "application/json")
        ->withStatus(200);
}

function is400Response($response, $responseMessage)
{
    $responseMessage = json_encode(["success" => false, "response" => $responseMessage]);
    $response->getBody()->write($responseMessage);
    return $response->withHeader("Content-Type", "application/json")
        ->withStatus(400);
}
