<?php

namespace core\admin\controllers;

use core\base\controllers\BaseController;
use core\base\models\UserModel;
use core\base\settings\Settings;

class LoginController extends BaseController
{
    protected $model;
    protected function inputData() {
        $this->model = UserModel::instance();
        $this->model->setData();
        if (isset($this->parameters['logout'])) {
            $this->checkAuth(true);
            $userLog = "Выход пользователя {$this->userId['name']}";
            $this->writeLog($userLog, 'user_log.txt', 'ACCESS USER');
            $this->model->logout();
            $this->redirect(PATH);
        }
        $timeClean = (new \DateTime())->modify('-' . BLOCK_TIME . ' hour')->format('Y-m-d H:i:s');
        $this->model->delete($this->model->getBlockedTable(), [
            'where' => ['time' => $timeClean],
            'operand' => ['<']
        ]);
        if ($this->isPost()) {
            if (empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
                exit('КУКУ ОХИБКА');
            }
            $ipUser = filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP) ?:
                filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP) ?:
                    @$_SERVER['REMOTE_ADDR'];
            $trying = $this->model->delete($this->model->getBlockedTable(), [
                'fields' => ['trying'],
                'where' => ['ip' => $ipUser]
            ]);
            $trying = !empty($trying) ? $this->clearNum($trying) : 0;
            $success = 0;
            if ( !empty($_POST['login']) && !empty($_POST['password']) && $trying < 3) {
                $login = $this->clearStr($_POST['login']);
                $password = md5( $this->clearStr($_POST['password']) );
                $userData = $this->model->get($this->model->getAdminTable(), [
                    'fields' => ['id', 'name'],
                    'where' => ['login' => $login, 'password' => $password]
                ]);
                if (!$userData) {
                    $method = 'add';
                    $where = [];
                    if ($trying) {
                        $method = 'edit';
                        $where['id'] = $ipUser;
                    }
                    $this->model->$method($this->model->getAdminTable(), [
                        'fields' => ['login' => $login, 'ip' => $ipUser, 'time' => 'NOW()', 'trying' => ++$trying],
                        'where' => $where
                    ]);
                    $error = "Неверные имя пользователя или пароль - {$ipUser} Логин - {$login}";
                }
                else {
                    if (!$this->model->checkUser($userData[0]['id'])) {
                        $error = $this->model->getLastError();
                    }
                    else {
                        $error = "Вход пользователя - $login";
                        $success = 1;
                    }
                }

            }
            elseif ($trying >= 3) {
                $error = 'Превышено максимальное количество ввода попыток пароля - ' . $ipUser;
            }
            else {
                $error = 'Заполните обязательные поля';
            }
            $_SESSION['res']['answer'] = $success ?
                "<div class=\"success\">Добро пожаловать {$userData['name']}</div>" :
                preg_split('/\s*\-/', $error, 2, PREG_SPLIT_NO_EMPTY)[0];
            $this->writeLog($error, 'user_log.txt', 'ACCESS USER');
            $path = null;
            if ($success) {
                $path = PATH . Settings::get('routes')['admin']['alias'];
            }
            $this->redirect($path);
                
        }
        return $this->render('', ['adminPath' => Settings::get('routes')['admin']['alias']]);
    }
}