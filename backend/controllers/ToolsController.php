<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;

use backend\models\User;
use common\components\AccessRule;
use common\components\FHtml;
use backend\modules\ecommerce\models\Product;
use yii\base\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * ActiveController implements a common set of actions for supporting RESTful access to ActiveRecord.
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], which must implement [[\yii\db\ActiveRecordInterface]].
 * By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: return the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 * - `options`: return the allowed HTTP methods
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * To add a new action, either override [[actions()]] by appending a new action class or write a new action method.
 * Make sure you also override [[verbs()]] to properly declare what HTTP methods are allowed by the new action.
 *
 * You should usually override [[checkAccess()]] to check whether the current user has the privilege to perform
 * the specified action against the specified model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ToolsController extends AdminController
{
    public $defaultAction = 'index';

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['create', 'update', 'delete', 'view', 'index'],
                'rules' => [
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_USER,
                            User::ROLE_MODERATOR,
                            User::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['update', 'create', 'delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_MODERATOR,
                            User::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ];
    }


    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess()
    {
        // need to be modified for security
    }


    public function actionCache($action = '', $key = '')
    {
        $model = [];
        if ($action == 'refresh') {
            if (empty($key)) {
                FHtml::Cache()->flush();
            }
            else {
                if (FHtml::Cache()->exists($key))
                    FHtml::Cache()->delete($key);
            }
        } else if ($action == 'view') {
            if (empty($key))
                $model = FHtml::Cache();
            else {
                $model[] = [$key => FHtml::getCachedData($key)];
            }
        }

        return $this->render('cache.php', ['model' => $model]);
    }

    public function actionApi($object = '', $params = '', $orderby = '', $limit = '', $page = '', $fields = '', $action = '', $url = '')
    {
        $model = null;

        if (!empty($object))
            $url = FHtml::currentBaseURL() . 'backend/web/index.php/api/list?object=' . trim($object);

        if (!empty($params))
            $url .= '&' . $params;

        if (!empty($orderby))
            $url .= '&orderby=' . $orderby;

        if (!empty($limit))
            $url .= '&limit=' . $limit;

        if (!empty($fields))
            $url .= '&fields=' . $fields;

        if ($action == 'view' || $action == 'run')
        {
            //\Yii::$app->runAction($key);
            $model = FHtml::loadHtmlFromUrl($url);
            $model = FHtml::format_json($model);
        }
        return $this->render('api.php', ['model' => $model, 'url' => $url]);
    }

    public function actionIndex($url = '')
    {
        $model = null;
        return $this->render('index.php', ['model' => $model]);
    }
}
