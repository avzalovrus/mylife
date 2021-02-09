<?php
use Component\Controller;
use Component\App;

/**
 * Контроллер SiteController
 */
class SiteController extends Controller
{
    /**
     * @return array
     * Поведение для контроллера
     */
    protected function behaviors()
    {
        return [
            'access' => [
                0 => ['index', 'about', 'login', 'test-action'],
                1 => ['index', 'about', 'logout', 'test-action'],
            ]
        ];
    }

    /**
     * Action для Ошибок
     */
    public function action404()
    {
        header('Location: /');
        exit();
        return true;
    }

    /**
     * Action для главной страницы
     */
    public function actionIndex()
    {
        $user = App::$user;
        $this->render('index', ['user' => $user]);
        return true;
    }

    /**
     * Action для страницы "О компании"
     */
    public function actionAbout()
    {
        $this->render('about', []);
        return true;
    }

    /**
     * Action для страницы "О компании"
     */
    public function actionTestAction()
    {
        $this->render('test-action', []);
        return true;
    }

    /**
     * Action для страницы авторизации
     */
    public function actionLogin()
    {
        if(!empty($_POST) && App::$user->login()){
            header('Location: /');
            exit();
        }
        $this->render('login', []);
        return true;
    }

    /**
     * Action для страницы выхода из аккаунта
     */
    public function actionLogout()
    {
        if(App::$user->logout()){
            header('Location: /');
            exit();
        }
        return true;
    }

}
