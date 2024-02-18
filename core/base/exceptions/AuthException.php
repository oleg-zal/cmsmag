<?php

namespace core\base\exceptions;

use core\base\settings\Settings;

class AuthException extends \Exception
{
    protected $messages;
    public function __construct($message='', $code=0){
        parent::__construct($message, $code);
        $this->messages = $message;
        $error = $this->getMessage() ?? $this->messages[$this->getCode()];
        $adminPath = PATH . Settings::get('routes')['admin']['alias'] . '/login';
        header("Location: $adminPath");
        exit();
    }
}