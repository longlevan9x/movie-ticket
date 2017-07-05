<?php
namespace backend\controllers;

use backend\controllers\AdminController;
use common\components\FHtml;
use common\components\FSecurity;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'log'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'about', 'log'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLog()
    {
        $action = FHtml::getRequestParam('action');
        if ($action == 'clear')
            FHtml::clearLog();

        return $this->render('log');
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if (FSecurity::logInBackend($model)) {
            return $this->goBack();
        } else {
            return $this->render('login2', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        FSecurity::logOut();
        return $this->goHome();
    }

    public function goHome()
    {
        $this->redirect(['/']);
    }
}
