<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;


use common\components\FHtml;
use common\controllers\BaseAdminController;
use yii\base\Exception;
use yii\helpers\BaseInflector;


/**
 * Controller is the base class for RESTful API controller classes.
 *
 * Controller implements the following steps in a RESTful API request handling cycle:
 *
 * 1. Resolving response format (see [[ContentNegotiator]]);
 * 2. Validating request method (see [[verbs()]]).
 * 3. Authenticating user (see [[\yii\filters\auth\AuthInterface]]);
 * 4. Rate limiting (see [[RateLimiter]]);
 * 5. Formatting response data (see [[serializeData()]]).
 *
 * @author Hung Ho (Steve) | www.apptemplate.co, wwww.moza-tech.com, www.codeyii.com | skype: hung.hoxuan  <hung.hoxuan@gmail.com>
 * @since 2.0
 */
class AdminController extends BaseAdminController
{
    public $enableCsrfValidation = false;

    // Return json data to fill in Combo box for Lookup api in other controls
    public function actionListLookup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = FHtml::getRequestParam(['q', 'data']);
        $id = FHtml::getRequestParam(['id']);
        $search_object = FHtml::getRequestParam(['object', 'search_object', 'name'], 'product');
        $search_field = FHtml::getRequestParam(['field', 'search_field'], 'name');

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q) || !empty($q)) {
            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', ['like', $search_field, $q]);

            $out['results'] = array_values($data);
        } else if (is_null($q) || empty($q)) {
            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', []);
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => FHtml::getFieldValue(FHtml::getModel($search_object, '', $id, null, false), ['name', 'title', 'username'])];
        }
        return $out;
    }

    // Return json data of detail object for Lookup api in other controls
    public function actionDetailLookup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $search_object = FHtml::getRequestParam(['object', 'search_object', 'name'], 'product');
        $search_field = FHtml::getRequestParam(['field', 'search_field'], 'id');

        $post = $_POST['keys'];

        $data = FHtml::getModel($search_object, '', ['=', $search_field, $post], null, false);

        return $data;
    }

    // Update Sort Orders of Object_Type
    public function actionSortOrder()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object_type');
        $sort_orders = FHtml::getRequestParam('sort_orders');
        $sort_order_field = FHtml::getRequestParam('sort_order_field', 'sort_order');

        if (empty($sort_orders) || empty($object_type))
            return 'Empty data';

        if (is_array($sort_orders))
            $arr = $sort_orders;
        else if (is_string($sort_orders))
            $arr = explode(',', $sort_orders);

        $sort_orders = $object_type . ': ';
        for ($i = 0; $i < count($arr); $i++) {
            $model = FHtml::getModel($object_type, '', $arr[$i], null, false);
            if (isset($model) && method_exists($model, 'setSortOrder')) {
                $model->setSortOrder($i);
                $model->save();
                $sort_orders .= $arr[$i] . ', ';

            } else if (isset($model) && FHtml::field_exists($model, 'sort_order')) {
                FHtml::setFieldValue($model, 'sort_order', $i);
                $model->save();
                $sort_orders .= $arr[$i] . ', ';
            }
        }
        return $sort_orders;
    }

    // Change Value  of Object_Type
    public function actionChange()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $field = FHtml::getRequestParam('field');
        $value = FHtml::getRequestParam('data');

        $model = FHtml::getModel($object_type, '', $object_id, null, false);
        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        if (!FHtml::field_exists($model, $field))
            return 'Error ! Field name [' . $field . '] does not exist.  ';

        if (isset($model) && FHtml::field_exists($model, $field)) {
            FHtml::setFieldValue($model, $field, $value);
            if ($model->save())
                return '';
            else
                return 'Error saving ' . $object_type . ': ' . implode('<br/>', $model->errors);
        } else {
            return 'Error saving ' . $object_type . ', field: ' . $field . ', value: ' . $value;
        }
    }

    //2017/3/18: Reset some fields to null
    public function actionReset()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $field = FHtml::getRequestParam('field');
        if (!empty($field))
            $arr = explode(',', $field);
        else
            $arr = [];

        $value = FHtml::getRequestParam('data');

        $model = FHtml::getModel($object_type, '', $object_id, null, false);

        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        foreach ($arr as $field) {
            if (isset($model) && FHtml::field_exists($model, $field)) {
                FHtml::setFieldValue($model, $field, null);
            }
        }
        $model->save();
        return '';
    }

    // Change Value  of Object_Type
    public function actionActive()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $field = FHtml::getRequestParam('field');
        $value = FHtml::getRequestParam('data');

        $model = FHtml::getModel($object_type, '', $object_id, null, false);
        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        if (!FHtml::field_exists($model, $field))
            return 'Error ! Field name [' . $field . '] does not exist.  ';

        if (isset($model) && FHtml::field_exists($model, $field)) {
            if ($value == 1)
                $value = 0;
            else
                $value = 1;

            FHtml::setFieldValue($model, $field, $value);
            $model->save();
            return '';
        } else {
            return 'Error saving ' . $object_type . ', field: ' . $field . ', value: ' . $value;
        }
    }

    // Add new Object_Type
    public function actionPlus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $model = FHtml::createModel($object_type);

        $object_type = BaseInflector::camel2words($object_type);

        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        try {
            $model = FHtml::loadParams($model, $_REQUEST);
            FHtml::setFieldValue($model, 'is_active', 1);

            $model->save();
            if (!empty($model->getErrors()))
                return 'Error: ' . FHtml::encode($model->getErrors());
            return '';
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    // Add new Object_Type
    public function actionRemove()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $model = FHtml::getModel($object_type, '', $object_id, [], false);

        $object_type = BaseInflector::camel2words($object_type);

        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        try {
            $model->delete();

            if (!empty($model->getErrors()))
                return 'Error: ' . FHtml::encode($model->getErrors());
            return '';
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function actionViewDetail($id)
    {
        $request = \Yii::$app->request;

        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }
}
