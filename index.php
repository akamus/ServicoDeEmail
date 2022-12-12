<?php

require __DIR__ . '/vendor/autoload.php';

use Bramus\Router\Router;
use Helpers\Helper;
use Models\EmailModel;

/**
 * @author Aureo Souto <akamus.souto@gmail.com>
 */
$router = new Router();

/**
 * envio de e-mail
 * 
 * parâmetros:
 * 
 * destination_name - nome destino
 * subject - assunto
 * body_email - corpo do email
 * email_from - email de origem
 * name_from - nome origem
 * password - senha interna do sistema
 * 
 */
$router->post('/send', function () {

    $email = new EmailModel();

    $input_post = $_POST;

    if (!Helper::filtrarCampos($input_post)) {
        echo  json_encode(['status' => false, 'msg' => 'input post error!'], JSON_PRETTY_PRINT);
    }

    if (!$email->checkPassword($input_post['password'])) {
        echo json_encode(['status' => false, 'msg' => 'password error!'], JSON_PRETTY_PRINT);
    }

    if ($email->send($_POST)) {
        echo json_encode(['status' => true, 'msg' => 'success!'], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['status' => false, 'msg' => 'error!'], JSON_PRETTY_PRINT);
    }
});
/** 
 * teste de envio
 * basta preencher os dados no arquivo email.ini para test
 */
$router->post('/send/test', function () {

    $email = new EmailModel();

    if ($email->sendTest()) {
        echo json_encode(['status' => true, 'msg' => 'test success'], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['status' => false, 'msg' => 'test error'], JSON_PRETTY_PRINT);
    }
});

$router->get('/', function () {
   echo  json_encode(['status' => true,'msg' => 'E-mail Service!'], JSON_PRETTY_PRINT);

});

$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo 'Not found!';
});

$router->run();
