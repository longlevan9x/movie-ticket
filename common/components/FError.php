<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use common\components\FConstant;
use common\config\FSettings;
use yii\base\Exception;
use Yii;
use yii\helpers\VarDumper;


class FError extends FSettings
{
    const MISSING_PARAMS = 'Missing parameters';
    const
        EMAIL_OR_USERNAME_EXIST = 'Email or username existed',
        PAGE_INDEX_INVALID = 'Invalid Page Index',
        EMAIL_EXIST = 'Email exist',
        EMAIL_DOES_NOT_EXIST = 'Email does not exist',
        EMAIL_OR_USERNAME_DOES_NOT_EXIST = 'Email or username does not exist',
        WRONG_PASSWORD = 'Wrong password',
        USER_NOT_FOUND = 'User not found',
        DEAL_NOT_FOUND = 'Deal not found',
        DATA_NOT_FOUND = 'Data not found',
        TOKEN_MISMATCH = 'Token mismatch',
        CAN_NOT_PERFORM = 'You can not perform this action';

    public static function getErrorMessage($errors)
    {
        $error_message = 'FAIL';
        $error_array = array();
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $error_array[] = $message;
            }
        }
        if (count($error_array) != 0) {
            $error_message = implode('. ', $error_array);
        }
        return $error_message;
    }

    public static function getErrorMsg($code)
    {
        $str = array(

            200 => 'Success',
            201 => 'Fail',
            202 =>  FError::MISSING_PARAMS,
            203 => 'Invalid input',
            204 => 'Token missing',
            205 =>  FError::TOKEN_MISMATCH,
            206 =>  FError::CAN_NOT_PERFORM,
            207 => 'Paid fail',
            221 => 'User not found',
            222 => 'Wrong password',
            223 => 'Email does not exist',
            224 => 'Email or username does not exist',
            225 => 'Email existed',
            226 => 'Email or username existed',
            227 => 'Current password mismatch',
            228 => 'Your account is not activated',
            229 => 'Fail to send email, please check your email address',
            230 => 'Your account balance is not enough to do this action',
            231 => 'Please update your business profile first',
            232 => 'You another request is pending, please try again later',
            233 => 'Please login by your social network account and change password',

            241 => 'Deal not found',
            242 => 'Reservation not found',

            261 => 'Trip not found',
            262 => 'Your driver role is not activated',
            263 => 'Please update your driver profile first',
            264 => 'You need to charge your online duration',

            300 => FError::PAGE_INDEX_INVALID

        );
        if($code == 'all')
            return $str;
        else
            return isset($str[$code]) ? $str[$code] : '';
    }

    public static function showMessage($message = null, $type = 'message')
    {
        $result = [];
        if (!isset($message))
            $result = [];

        if (is_string($message))
            $result[] = $message;

        if (is_array($message))
            $result = array_merge($result, $message);

        if (is_object($message))
            $result[] = FHtml::encode($message);

        if (!empty(FHtml::getRequestParam($type)))
            $result[] = FHtml::getRequestParam($type);

        if (Yii::$app->session->hasFlash($type))
            $message = Yii::$app->session->getFlash($type);

        if (is_array($message))
            $result = array_merge($result, $message);

        if (is_object($message))
            $result[] = FHtml::encode($message);

        if (empty($result))
            return '';

        if ($type == 'message' || $type == 'messages')
            $type = 'success';
        else if ($type == 'error' || $type == 'errors')
            $type = 'danger';
        else
            $type = 'info';

        $message = implode('<br/>', $result);

        return FHtml::showAlert($message, 'alert-' . $type, true);
        //return "<div class='alert alert-$type' style='margin-top:20px;margin-bottom:20px'>$message</div>";
    }

    public static function showErrorMessage($message = null)
    {
        return self::showMessage($message, 'error');
    }

    public static function showCurrentMessages() {

        $result = '';

        $result .= self::showMessage(null, 'message');
        $result .= self::showMessage(null, 'error');

        FError::clearMessages();
        echo $result;
        return $result;
    }

    public static function addError($errors) {
        if (empty($errors))
            return;
        $result = '';

        if (is_array($errors))
        {
            foreach ($errors as $key => $message) {
                if (is_array($message))
                    $message = implode(',', $message);
                $result .= $message . '; ';
            }
        } else
            $result = $errors;

        FHtml::Session()->addFlash('error', $result);
    }

    public static function log($content, $type = 'message')
    {
        self::addLog($content);
    }

    public static function addLog($content, $type = 'message') {
        $logs = self::currentLog();
        $title = date('Y-m-d h:i') . ' / module: ' . FHtml::currentModule() . ' / controller: '. FHtml::currentController() . ' / action: ' . FHtml::currentAction() . ' / object: ' .(is_object($content)? FHtml::getTableName($content) : '');
        $content = VarDumper::dumpAsString($content);

        $newlog = "<small style='color:grey'>$title</small> <br/> <pre>$content</pre><br/>";
        $logs = $newlog . $logs;
        FHtml::Session()['logs'] = $logs;
    }

    public static function clearLog() {
        FHtml::Session()->remove('logs');
    }

    public static function currentLog() {
        $logs = '';
        if (FHtml::Session()->has('logs'))
            $logs = FHtml::Session()->get('logs');
        return $logs;
    }

    public static function addMessage($errors) {
        if (empty($errors))
            return;
        $result = '';

        if (is_array($errors))
        {
            foreach ($errors as $key => $message) {
                $result .= $message . '; ';
            }
        } else
            $result = $errors;

        FHtml::Session()->addFlash('message', $result);
    }

    public static function clearMessages() {
        FHtml::Session()->removeFlash('error');
        FHtml::Session()->removeFlash('message');
    }

    public static function logObjectActions($model, $action, $oldContent = [], $attributes = [], $name = '') {
        $object_model = FHtml::createModel('object_actions');
        $changedContent = [];
        foreach ($attributes as $field => $value) {
            if (key_exists($field, $oldContent))
                $old_value = $oldContent[$field];
            else
                $old_value = '';

            if ($old_value != $value) {
                $changedContent[] = [$field, $old_value, $value];
                //$changedContent = array_merge($changedContent, [$field => ['old' => $old_value, 'new' => $value]]);
            }
        }

        if (isset($object_model)) {
            if (empty($name)) {
                $name = strtoupper($action) . '. Changed fields: ';
                if (!empty($changedContent)) {
                    foreach ($changedContent as $item) {
                        $name .= $item[0] . ', ';
                    }
                }
            }

            $object_model->application_id = FHtml::currentApplicationCode();
            $object_model->created_date = FHtml::Now();
            $object_model->created_user = FHtml::currentUserId();
            $object_model->action = $action;
            $object_model->is_active = 1;
            $object_model->old_content = FHtml::encode($oldContent);
            $object_model->content = FHtml::encode($changedContent); //FHtml::encode($attributes);
            $object_model->name = $name;
            $object_model->object_type = $model->tableName;
            $object_model->object_id = FHtml::getFieldValue($model, ['id']);
            return $object_model->save();
        }

        return false;
    }

    public static function showObjectActions($changedItems, $columns = [], $fieldName = ['content']) {
        if (is_object($changedItems))
            $changedItems = FHtml::getFieldValue($changedItems, $fieldName);

        if (is_string($changedItems))
            $changedItems = FHtml::decode($changedItems);

        $result = '';
        foreach ($changedItems as $item) {
            $field = $item[0];
            $old_value = $item[1];
            $new_value = $item[2];
            $new_value = str_replace($old_value, "<span style='color:grey'>$old_value</span>", $new_value);
            $result .= "<tr><td class='col-md-2'>$field</td><td class='col-md-5'>$old_value</td><td class='col-md-5'>$new_value</td></tr>";
        }
        if (!empty($result))
            $result = "<table class='table table-bordered table-condensed'>$result</table>";
        return $result;
    }
}