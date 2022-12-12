<?php

namespace Models;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class EmailModel {

    protected $_destinationEmail;
    protected $_destinationName;
    protected $_subject;
    protected $_body;
    private $_host;
    private $_port;
    private $_pass_account;
    private $_user_account;
    private $_encryption;
    private $_email_from;
    private $_name_from;
    private $_pass_from;

    function __construct() {

        $email = parse_ini_file("./app.config/email.ini");
        
        $this->_host = $email['host'];
        $this->_port = $email['port'];
        $this->_pass_account = $email['pass_account'];
        $this->_user_account = $email['user_account'];
        $this->_encryption = $email['encryption'];
        $this->_email_from = $email['email_from'];
        $this->_name_from = $email['name_from'];
        $this->_pass_from = $email['pass_from']; 
    }

    private function loadParams($posts = []) {
        $params = [
            'email_from' => $posts['email_from'],
            'name_from' => $posts['name_from'],
            'destination_email' => $posts['destination_email'],
            'destination_name' => $posts['destination_name'],
            'subject' => $posts['subject'],
            'body_email' => $posts['body_email'],
        ];
        return $params;
    }

    public function send($params = '') {
        $params = $this->loadParams($params);
        $transport = $this->setParamsEmailTransport();
        $mailer = new Swift_Mailer($transport);
        $message = $this->setParamsMessageEmail($params);
        return $mailer->send($message);
    }

    public function sendTest($params = '') {
        // Create the Transport
        $transport = $this->setParamsEmailTransport();
        $mailer = new Swift_Mailer($transport);
        $message = $this->setParamsMessageEmail($params);
        //0 if failure		
        return $mailer->send($message);
    }

    function checkPassword($password) {
        if (base64_decode($password) == base64_decode($this->_pass_from)) {
            return true;
        }
        return false;
    }

    function setParamsEmailTransport() {
        $transport = (new Swift_SmtpTransport($this->_host, $this->_port, $this->_encryption))
                ->setUsername($this->_user_account)
                ->setPassword($this->_pass_account);
        return $transport;
    }

    function setParamsMessageEmail($params = '') {
    $email = parse_ini_file("./app.config/email.ini");
    
    $message = (new Swift_Message($email['test_subject']))
                ->setFrom($email['test_email_from'])
                ->setTo([$email['test_destination_email'] => $email['test_destination_name']])
                ->setBody($email['test_body_email']);

        return $message;
    }

}
