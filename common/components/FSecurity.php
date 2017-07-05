<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;

use backend\models\AppUser;
use backend\models\AuthGroup;
use backend\models\AuthPermission;
use backend\models\AuthRole;
use backend\modules\system\models\SettingsMenu;
use common\components\FConstant;
use common\models\LoginForm;
use common\models\User;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;

class FSecurity extends FConstant
{
    public static function currentUser($zone = '')
    {
        if (empty($zone))
            $zone = FHtml::currentZone();

        if ($zone === FRONTEND) {
            $appuser = \Yii::$app->appuser;
            $user = \Yii::$app->user;

            if (isset($user->identity))
                return $user;

            return $appuser;
        }

        $user = \Yii::$app->user;

        return $user;
    }

    public static function currentUserIdentity()
    {
        $user = self::currentUser();
        return isset($user->identity) ? $user->identity : null;
    }

    public static function currentBackendUser()
    {
        $user = self::currentUser(BACKEND);

//        $userModel = FHtml::Session()->get('currentBackendUser');
//        if (!isset($userModel) || FHtml::getFieldValue($userModel, 'username') != FHtml::getFieldValue($user, 'username')) {
//            $userModel = \common\models\User::findOne($user->id, false);
//            if (isset($userModel)) {
//                FHtml::Session()->set('currentBackendUser', $userModel);
//                return $userModel;
//            }
//        }

        return $user;
    }

    public static function currentUserId()
    {
        $identity = self::currentUserIdentity();
        if (isset($identity))
            return $identity->getId();
        else
            return '';
    }

    public static function currentUserModel($zone = '')
    {
        if (empty($zone))
            $zone = FHtml::currentZone();

        if ($zone === FRONTEND) {
            $appuser = \Yii::$app->appuser;
            $user = \Yii::$app->user;

            if (isset($user->identity))
                return self::currentBackendUser();

            return $appuser;
        }

        return self::currentBackendUser();
    }

    public static function addBackendUser($username, $email = '', $password = '123456', $role = User::ROLE_USER)
    {
        return self::addUser($username, $email, $password, $role);
    }

    public static function addUser($username, $email = '', $password = '123456', $role = User::ROLE_USER, $isBackend = BACKEND)
    {
        if ($isBackend == true || $isBackend == BACKEND) {
            $model = FHtml::getModel('user', '', ['username' => $username]);
        }
        else {
            $model = FHtml::getModel('app_user', '', ['username' => $username]);
        }

        $model->username = $username;
        $model->email = !empty($email) ? $email : (strpos('@', $username) ? $username : '');
        $model->status = FHtml::USER_STATUS_ACTIVE;
        $model->setPassword($password);
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        $model->role = $role;

        if ($model->save())
            return $model;
        else
        {
            FHtml::addError($model->errors);
            return false;
        }
    }

    public static function addFrontendUser($username, $email = '', $password = DEFAULT_PASSWORD, $role = User::ROLE_USER)
    {
        return self::addAppUser($username, $email, $password, $role);
    }

    public static function addAppUser($username, $email = '', $password = DEFAULT_PASSWORD, $role = User::ROLE_USER)
    {
        return self::addUser($username, $email, $password, $role, FRONTEND);
    }

    public static function getUser($username, $isBackend = BACKEND)
    {
        $zone = FHtml::currentZone();

        if ($isBackend === true || $isBackend === BACKEND || $zone === BACKEND) {
            return User::findByUsername($username);
        } else {
            return AppUser::findByUsername($username);
        }
    }


    public static function setUserPassword($model, $password_new = '')
    {
        if (empty($password_new))
            $password_new = DEFAULT_PASSWORD;

        $model->setPassword($password_new);
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        return $model;
    }

    public static function isRoleUser($userid = '')
    {
        if (empty($userid))
            $role = FHtml::getCurrentRole();
        else {
            $role = FHtml::getFieldValue($userid, 'role');
        }
        return $role == \common\models\User::ROLE_ADMIN || $role == \common\models\User::ROLE_MODERATOR || $role == \common\models\User::ROLE_USER;
    }

    public static function isRoleModerator($userid = '')
    {
        if (empty($userid))
            $role = FHtml::getCurrentRole();
        else {
            $role = FHtml::getFieldValue($userid, 'role');
        }
        return $role == \common\models\User::ROLE_ADMIN || $role == \common\models\User::ROLE_MODERATOR;
    }

    //HungHX: 20160801
    public static function isInRole($object_type, $action, $role = '', $userid = '', $field = '')
    {
        if ($action == 'update')
            $action = 'update';

        if ($action == 'add')
            $action = 'create';

        if ($action == 'view-detail') {
            $action = 'view';
        }

        if (empty($object_type))
            $object_type = str_replace('-', '_', FHtml::currentController());

        if (is_object($object_type))
            $object_type = FHtml::getTableName($object_type);

        $object_type = str_replace('-', '_', BaseInflector::camel2id($object_type));

        if (empty($role))
            $role = FHtml::getCurrentRole();

        if (empty($userid))
            $userid = FHtml::currentUserId();

        $user = self::currentUser();
        if (!isset($user)) {
            return false;
        }

        if ($user->isGuest && $role != 'guest') {
            return false;
        }

        if ($role == \common\models\User::ROLE_ADMIN) {
            return true; // can do any thing
        }

        $module = FHtml::getModelModule($object_type);
        $controller = str_replace('_', '-', $object_type);

        $rules = FHtml::getControllerRules($object_type);
        if (!empty($rules)) {
            foreach ($rules as $i => $rule) {
                $actions = FHtml::getFieldValue($rule, 'actions');
                if (is_array($actions) && in_array($action, $actions)) {
                    $rights = FHtml::getFieldValue($rule, 'roles');
                    return FHtml::getFieldValue($rule, 'allow', false) && self::isInRoles($rights, $module, $controller, $action);
                }
            }

            return false;
        }

        if ($role == \common\models\User::ROLE_MODERATOR) {
            return in_array($action, ['view', 'index', 'create', 'update', 'delete']);
        }

        if ($role == \common\models\User::ROLE_USER) {
            return in_array($action, ['view', 'index']);
        }

        if (strlen($role) > 0) {
            return $user->can($role);
        }

        if (strlen($action) > 0)
            return $user->can($action);

        return false;
    }

    public static function getUserRoles($user = null)
    {
        if (!isset($user))
            $user = \Yii::$app->user;

        $roles = array();
        if (isset($user->identity->groups)) {
            $groups = $user->identity->groups;
            /* @var $group AuthPermission */
            foreach ($groups as $group) {
                $group_roles = $group->group->roles;
                foreach ($group_roles as $role) {
                    $roles[] = $role->role->code;
                }
            }
        }

        if (isset($user->identity->rights)) {
            $rights = $user->identity->rights;
            foreach ($rights as $right) {
                $roles[] = $right->role->code;
            }
        }

        if (count($roles) != 0) {
            $rights = array_merge(array_unique($roles), [$user->identity->role]);
        } else {
            $rights = [$user->identity->role];
        }

        return $rights;
    }


    public static function isInRoles($roles, $module = '', $controller = '', $action = '', $user = null)
    {
        if (FHtml::isRoleAdmin())
            return true;

        if (\Yii::$app->user->isGuest) {
            return false;
        }

        if (is_string($roles))
            $roles = FHtml::decode($roles);

        $roles = self::getRoles($roles, $module, $controller, $action);

        $user_roles = self::getUserRoles($user);

        //echo !empty(array_intersect($user_roles, $roles));
//        if (empty(array_intersect($user_roles, $roles))) {
//            var_dump($user_roles); var_dump($roles); echo !empty(array_intersect($user_roles, $roles));
//        }

        return in_array(User::ROLE_ALL, $roles) || !empty(array_intersect($user_roles, $roles));
    }

    public static function getRoles($roles, $module = '', $controller = '', $action = '')
    {
        $arr = [];

        if (empty($roles) || !isset($roles)) {
            $roles = [];
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $item) {
            if (key_exists($item, FHtml::ROLE_CODE_GROUPS))
                $arr[] = FHtml::ROLE_CODE_GROUPS[$item];
            else
                $arr[] = $item;
        }

        $roles1 = [User::ROLE_ADMIN];

        $object_type = strtolower(str_replace('-', '_', $controller));

        if (in_array($action, ['view', 'index'])) {
            if (!empty($module)) {
                $roles1 = array_merge($roles1, [strtolower($module)]);
                $roles1 = array_merge($roles1, [strtolower($module) . '/view']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/manage']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/admin']);
            }
            if (!empty($controller)) {
                $roles1 = array_merge($roles1, [$object_type]);
                $roles1 = array_merge($roles1, [$object_type . '/view']);
                $roles1 = array_merge($roles1, [$object_type . '/manage']);
                $roles1 = array_merge($roles1, [$object_type . '/admin']);
            }
        } else if (in_array($action, ['add', 'update', 'create', 'update', 'delete'])) {
            if (!empty($module)) {
                $roles1 = array_merge($roles1, [strtolower($module) . '/manage']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/admin']);
            }
            if (!empty($controller)) {
                $roles1 = array_merge($roles1, [$object_type . '/manage']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/admin']);
                $roles1 = array_merge($roles1, [$object_type . '/' . strtolower($action)]);
            }
        } else {
            $roles1 = array_merge($roles1, [$object_type . '/' . strtolower($action)]);
        }

        $arr = array_merge($arr, $roles1);
        return $arr;
    }

    public static function isAuthorized($action, $object_type, $field, $form_name = '', $form_type = '', $role = '', $userid = '', $manualValue = false)
    {
        if (is_object($object_type))
            $object_type = FHtml::getTableName($object_type);

        $object_type = str_replace('-', '_', BaseInflector::camel2id($object_type));

        if (FHtml::isInArray($field, ['id', 'application_id']) && $action != self::ACTION_VIEW)
            return false;

        $user = self::currentUser();
        if (!isset($user)) {
            return false;
        }

        if (empty($role))
            $role = FHtml::getCurrentRole();

        if ($role == self::ROLE_ADMIN)
            return true;

        if (empty($userid))
            $userid = FHtml::currentUserId();

        return self::isInRole($object_type, $action) || $manualValue;
    }

    public static function getPermissions($object_type, $field, $form_name = '', $form_type = '', $role = '', $userid = '')
    {
        return null;
    }


    //HungHX: 20160801
    public static function getCurrentRole()
    {
        $identity = self::currentUserIdentity();

        if (isset($identity))
            return $identity->role;
        else
            return 'guest';
    }

    public static function logOut()
    {
        \Yii::$app->db->schema->refresh();
        FHtml::flushCache();
        FHtml::Session()->close();
        $user = FHtml::currentUserIdentity();
        FHtml::setFieldValue($user, 'is_online', 0);
        FHtml::setFieldValue($user, 'last_logout', FHtml::Now());
        $user->save();

        \Yii::$app->user->logout();
    }

    public static function logInBackend($model = null, $username = '', $password = '')
    {
        if (!isset($model))
            $model = new LoginForm();

        if (!empty($username) && !empty($password)) {
            $model->username = $username;
            $model->password = $password;
        } else {
            $model->load(\Yii::$app->request->post());
        }

        if ($model->login()) {
            $application_id = FHtml::getFieldValue(FHtml::currentBackendUser(), 'application_id');
            if (empty($application_id))
                $application_id = FHtml::getRequestParam('application_id', DEFAULT_APPLICATION_ID);

            $user = $model->getUser();
            FHtml::setFieldValue($user, 'is_online', 1);
            FHtml::setFieldValue($user, 'last_login', FHtml::Now());
            $user->save();

            FHtml::setApplicationId($application_id);
            return true;
        }

        return false;
    }

    public static function generateHash($arr, $secret_key = SECRET_KEY)
    {
        $arr = array_merge($arr, [$secret_key]);
        $arr_str = implode($arr, ',');
        $sha1 = sha1($arr_str, true);
        return bin2hex($sha1);
    }

    public static function checkHash($hash, $arr, $secret_key = SECRET_KEY)
    {
        $sha1 = self::generateHash($arr, $secret_key);
        if ($sha1 == $hash)
            return true;
        else
            return false;
    }

    public static function checkFootPrint($hash, $time, $arr, $max_duration = FOOTPRINT_TIME_LIMIT)
    {
        date_default_timezone_set(SERVER_TIME_ZONE);
        $time_value = strtotime($time);
        $duration = FHtml::time() - $time_value;

        if ($duration < 0 || $duration > $max_duration)
            return 'Fasle: Expired Footprint';

        if (!self::checkHash($hash, $arr))
            return 'False: Invalid Footprint';

        return '';
    }

    public static function getControllerBehaviours($rules = [], $controller = '')
    {
        return $rules;
    }

    public static function getApplicationUsers()
    {
        return FHtml::findAll('user');
    }

    public static function getApplicationRoles()
    {
        $result = FHtml::findAll('auth_role');

        return $result;
    }

    public static function getApplicationGroups()
    {
        $result = FHtml::findAll('auth_group');
        return $result;
    }

    public static function getApplicationUsersComboArray($displayName = 'username')
    {
        return ArrayHelper::map(self::getApplicationUsers(), 'id', $displayName);
    }

    public static function getApplicationRolesComboArray()
    {
        $arr = ArrayHelper::map(self::getApplicationRoles(), 'id', 'name');
        return $arr;

//        $object_types = ArrayHelper::getColumn(FHtml::findbySql('select distinct `object_type` from settings_menu'), 'object_type');
//        foreach ($object_types as $object_type) {
//            $object_type = str_replace('_', '-', strtolower($object_type));
//            $arr = array_merge($arr, [$object_type]);
//        }
//
//        $object_types = ArrayHelper::getColumn(FHtml::findbySql('select distinct `module` from settings_menu'), 'module');
//        foreach ($object_types as $object_type) {
//            $object_type = str_replace('_', '-', strtolower($object_type));
//            $arr = array_merge($arr, [$object_type]);
//        }

        return $arr;
    }

    public static function getRolesComboArray()
    {
        $arr = ['admin' => 'Admin', 'moderator' => 'Moderator', 'user' => 'User'];
        $arr = array_merge($arr, ArrayHelper::map(self::getApplicationRoles(), 'code', 'name'));

//        $object_types = ArrayHelper::getColumn(FHtml::findbySql('select distinct `object_type` from settings_menu'), 'object_type');
//        foreach ($object_types as $object_type) {
//            $object_type = str_replace('_', '-', $object_type);
//            $arr = array_merge($arr, [$object_type]);
//        }
        return $arr;
    }

    public static function getApplicationModulesComboArray()
    {
        $application_id = FHtml::currentApplicationCode();
        $result = \yii\helpers\ArrayHelper::map(FHtml::findbySql("select distinct module, module from settings_menu where application_id = '$application_id'"), 'module', 'module');
        return $result;
    }

    public static function populateAuthItems()
    {
        $items = FHtml::getModuleControllersFromUrls();
        foreach ($items as $module => $controllers) {
            $module = strtolower($module);
            $group_model1 = AuthGroup::createOrUpdate(['name' => BaseInflector::camel2words($module) . ' User'], [], false);
            $group_model2 = AuthGroup::createOrUpdate(['name' => BaseInflector::camel2words($module) . ' Admin'], [], false);

            $role_model1 = AuthRole::createOrUpdate(['code' => $module], ['name' => BaseInflector::camel2words($module) . ' - View'], false);
            $role_model2 = AuthRole::createOrUpdate(['code' => "$module/manage"], ['name' => BaseInflector::camel2words($module) . ' - Manage'], false);

            if (is_object($group_model1) && is_object($role_model1))
                $permission_model1 = AuthPermission::createOrUpdate(['object_type' => 'auth_group', 'object_id' => $group_model1->id, 'relation_type' => 'group-role', 'object2_type' => 'auth_role', 'object2_id' => $role_model1->id]);

            if (is_object($group_model2) && is_object($role_model2))
                $permission_model2 = AuthPermission::createOrUpdate(['object_type' => 'auth_group', 'object_id' => $group_model2->id, 'relation_type' => 'group-role', 'object2_type' => 'auth_role', 'object2_id' => $role_model2->id]);

            foreach ($controllers as $controller) {
                $controller = str_replace('-', '_', $controller);
                $role_model1 = AuthRole::createOrUpdate(['code' => $controller], ['name' => BaseInflector::camel2words($controller) . ' - View'], false);
                $role_model2 = AuthRole::createOrUpdate(['code' => "$controller/manage"], ['name' => BaseInflector::camel2words($controller) . ' - Manage'], false);
            }
        }
    }

    public static function getUserGroupModels($user)
    {
        $arr = [];
        $groups = $user->groups;
        foreach ($groups as $group) {
            $arr[] = $group->group;
        }

        return $arr;
    }

    public static function getUserGroupArray($user)
    {
        $groups = self::getUserGroupModels($user);
        $result = ArrayHelper::getColumn($groups, 'object_id');
        return $result;
    }

    public static function getUserRoleModels($user)
    {
        $models = $user->hasMany(AuthPermission::className(), ['object_id' => 'id'])
            ->andOnCondition(['AND',
                ['relation_type' => 'user-role'],
                ['object2_id' => 'auth_group'],
                ['object_type' => 'user']]);
        return $models;
    }

    public static function getUserRoleArray($user)
    {
        $groups = self::getUserRoleModels($user);
        $result = ArrayHelper::getColumn($groups, 'object_id2');
        return $result;
    }

    public static function getGroupRoleModels($group) {

        $arr = [];
        $roles = $group->roles;
        foreach ($roles as $role) {
            $arr[] = $role->role;
        }

        return $arr;
    }

    public static function getApplicationGroupsComboArray()
    {
        return ArrayHelper::map(self::getApplicationGroups(), 'id', 'name');
    }

    public static function saveAuthPermission($object_type, $id, $relation_type, $related_object_type, $related_objects = [])
    {
        if (!isset($id) || empty($id))
            return;

        $time_string = time();
        $today = date('Y-m-d H:i:s', $time_string);

        if (!is_array($related_objects))
            $related_objects = [$related_objects];

        if (count($related_objects) != 0) {
            AuthPermission::deleteAll("relation_type = '$relation_type' AND object_id = $id");
            foreach ($related_objects as $related_object) {
                $new_user = new AuthPermission();
                $new_user->object_id = $id;
                $new_user->object_type = $object_type;
                $new_user->object2_id = $related_object;
                $new_user->object2_type = $related_object_type;
                $new_user->relation_type = $relation_type;
                $new_user->sort_order = 0;
                $new_user->created_date = $today;
                $new_user->save();
            }
        }
    }

    public static function updateUserGroups($userModel, $groups = [])
    {
        $time_string = time();
        $today = date('Y-m-d H:i:s', $time_string);

        if (!is_array($groups))
            $groups = [$groups];

        AuthPermission::deleteAll("relation_type = 'group-user' AND object2_id = $userModel->id AND object2_type='user'");

        foreach ($groups as $group) {
            //$new_user = FHtml::getModel('auth_permission', '', ['relation_type' => 'group-user', 'object2_id' => $userModel->id, 'object2_type' => 'user', 'object_id' => $group, 'object_type' => 'auth_group']);
            $new_user = new AuthPermission();
            if ($new_user->isNewRecord) {
                $new_user->object_id = $group;
                $new_user->object_type = 'auth_group';
                $new_user->object2_id = $userModel->id;
                $new_user->object2_type = 'user';
                $new_user->relation_type = 'group-user';
                $new_user->sort_order = 0;
                $new_user->created_date = $today;
                $new_user->save();

            }
        }
    }

    public static function updateUserRoles($userModel, $roles = [])
    {
        $time_string = time();
        $today = date('Y-m-d H:i:s', $time_string);

        if (!is_array($roles))
            $roles = [$roles];

        AuthPermission::deleteAll("relation_type = 'user-role' AND object_id = $userModel->id AND object_type='user'");

        foreach ($roles as $role) {
            $new_user = new AuthPermission();
            if ($new_user->isNewRecord) {
                $new_user->object2_id = $role;
                $new_user->object2_type = 'auth_role';
                $new_user->object_id = $userModel->id;
                $new_user->object_type = 'user';
                $new_user->relation_type = 'user-role';
                $new_user->sort_order = 0;
                $new_user->created_date = $today;
                $new_user->save();

            }
        }
    }

    public static function getControllerRules($controller = null)
    {
        if (is_string($controller))
            $controller = FHtml::getControllerObject($controller);

        if (is_object($controller) && isset($controller)) {
            $arr = $controller->behaviors();
            if (key_exists('access', $arr))
                $arr = $arr['access'];
            else
                return null;
            if (key_exists('rules', $arr))
                $rules = $arr['rules'];
            else
                return null;
            return $rules;
        }
        return null;
    }

    public static function createAuthRole($controller, $action = '', $description = '')
    {
        if (empty($action)) {
            $name = $controller;
            $action = 'View';
            $description = empty($description) ? 'View, List' : $description;
        } else {
            $name = "$controller/$action";
            $description = empty($description) ? ($action == 'manage' ? 'Create, Update, Delete' : $action) : $description;

        }

        $role = AuthRole::findOne(['code' => $name]);
        if (!isset($role)) {
            $role = new AuthRole();
            $role->code = strtolower($name);
            $role->name = BaseInflector::camel2words($controller) . ' - ' . BaseInflector::camel2words($action);
            $role->description = $description;
            $role->is_active = 1;
            $role->application_id = FHtml::currentApplicationCode();
            $role->save();
        }

        return $role;
    }

    public static function createAuthGroup($controller, $name, $actions = [])
    {
        $name = BaseInflector::camel2words($controller) . ' ' . $name;
        $object_type = str_replace('-', '_', $controller);
        $group = AuthGroup::findOne(['name' => $name]);
        if (!isset($group)) {
            $group = new AuthGroup();
            $group->name = $name;
            $group->is_active = 1;
            $group->application_id = FHtml::currentApplicationCode();
            $group->save();
        }

        if (isset($group) && !empty($actions))
        {
            foreach ($actions as $action) {
                if ($action == 'view')
                    $action = '';

                $role_model = self::createAuthRole($controller, $action);
                if (isset($role_model))
                {
                    self::saveAuthPermission('auth_group', $group->id, 'group-role', 'auth_role', [$role_model->id]);
                }
            }
        }

        return $group;
    }

}