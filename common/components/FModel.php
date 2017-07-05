<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */
namespace common\components;

use applications\ecommerce\Ecommerce;
use backend\models;
use backend\modules\ecommerce\models\Product;
use backend\modules\system\models\ObjectSetting;
use backend\modules\system\models\SettingsSchema;
use common\config\FSettings;
use common\models\BaseDataList;
use common\models\BaseModel;
use frontend\components\Helper;
use frontend\models\ViewModel;
use Imagine\Exception\InvalidArgumentException;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\cubrid\Schema;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use common\components\FConstant;
use common\widgets\FUploadedFile;

class FModel extends FConfig
{
    public static function getTableName($model)
    {
        $result = '';

        if (is_object($model) && method_exists($model, 'getTableName')) {
            $result = $model->getTableName();
        } else if (is_string($model))
            $result = $model;

        if (StringHelper::endsWith($result, '_search}}'))
            $result = str_replace('_search}}', '}}', $result);

        $result = str_replace('-', '_', BaseInflector::camel2id($result));
        $result = str_replace('{{%', '', $result);
        $result = str_replace('}}', '', $result);

        return $result;
    }

    public static function getModelNamespace($table, $namespace = 'backend\\models\\')
    {
        $table = FHtml::getTableName($table);

        $module = self::getModelModule(strtolower($table));
        if (!empty($module))
            return 'backend\\modules\\' . $module . '\\models\\';

        return $namespace;
    }

    public static function getControllerNamespace($table, $namespace = 'backend\\controllers\\')
    {
        $table = FHtml::getTableName($table);

        $module = self::getModelModule(strtolower($table));
        if (!empty($module))
            return 'backend\\modules\\' . $module . '\\controllers\\';

        return $namespace;
    }

    public static function getControllerObject($tableName, $zone = 'backend')
    {
        $tableName = self::getTableName($tableName);
        $namespace = self::getControllerNamespace($tableName);
        $module = self::getModelModule($tableName);
        $className =  $namespace . BaseInflector::camelize($tableName) . 'Controller';
        if (class_exists($className)) {
            return Yii::createObject(['class' => $className::className()], [$tableName, $module]);
        }
        return null;
    }

    // 2017/3/7
    public static function getModelModule($table)
    {
        $table = strtolower($table);

        $modules = self::MODULES;

        foreach ($modules as $module => $tables) {
            if (FHtml::isInArray($table, $tables)) {
                return $module;
            }
        }

        try {
            $result = explode('_', $table);
            return reset($result);
        } catch (Exception $e) {
            echo $table;
            die;
        }
    }


    public static function createMetaModel($table, $type = '', $id = null, $params = null)
    {
        if (empty($type)) {
            return null;
        } else {
            return self::getModel($table, $type, $id, $params);
        }
    }

    public static function getDataProvider($table, $params = [])
    {
        $searchModel = self::createModel($table . '_search');
        if (isset($searchModel))
            return $searchModel->search($params);

        $searchModel = self::createModel($table);

        if (isset($searchModel)) {
            $query = self::buildQueryFromModel($searchModel, $params);
            $provider = new \yii\data\ActiveDataProvider([
                'query' => $query,
            ]);
            return $provider;
        }

        return null;
    }

    //2017.05.03
    public static function delete($table, $condition = []) {
        $model = self::findOne($table, $condition);
        if (isset($model))
            return $model->delete();
        return false;
    }

    public static function deleteAll($table, $condition = []) {
        $model = self::createModel($table);
        if (isset($model))
            return $model::deleteAll($condition);
        return false;
    }

    public static function findOne($table, $condition) {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModel($table, '', $condition, null, false);
    }

    public static function findAll($table, $condition = [], $order_by = [], $page_size = -1, $page = -1) {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModels($table, $condition, $order_by, $page_size, $page, false);
    }

    public static function getCachedModels($table, $condition = [], $order_by = [], $page_size = -1, $page = -1) {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModels($table, $condition, $order_by, $page_size, $page, true);
    }

    public static function findCachedModels($table, $condition = [], $order_by = [], $page_size = -1, $page = -1) {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModels($table, $condition, $order_by, $page_size, $page, true);
    }

    public static function getModelObject($table, $type) {
        if (empty($table))
            return null;

        $className = self::getModelNamespace($table) . str_replace('-','_', BaseInflector::camelize($table)) . BaseInflector::camelize($type);

        if (class_exists($className)) {

            $model = Yii::createObject(['class' => $className::className()]);
            return $model;
        }
        return null;
    }

    public static function getModel($table, $type = '', $id = null, $default_fields = null, $autoCreateNew = true)
    {
        if (empty($table))
            return null;

        $className = self::getModelNamespace($table) . str_replace('-','_', BaseInflector::camelize($table)) . BaseInflector::camelize($type);

        if (class_exists($className)) {

            $model = Yii::createObject(['class' => $className::className()]);
            if (isset($id) && !empty($id) && isset($model))
                $model = $model::findOne($id);

            if (!isset($model)) {
                if ($autoCreateNew) {
                    $model = Yii::createObject(['class' => $className::className()]);
                } else
                    return null;
            }

            return $model = self::setFieldValues($model, $default_fields);

        } else {
            //echo $className; die;
            return null;
        }
    }

    public static function getViewModel($table, $type = '', $id = null, $params = null, $autoCreateNew = true)
    {
        $model = self::getModel($table, $type, $id, $params, $autoCreateNew);
        if (isset($model))
            return $model->toViewModel();
        return null;
    }

    public static function getPageViewModel($object_type = '', $id = '') {
        if (empty($id))
            $id = \common\components\FHtml::getRequestParam(['id']);

        $model = null;
        if (!empty($id))
            $model = FHtml::getViewModel($object_type, '', $id);

        return $model;
    }

    public static function setFieldValues($model, $arrays)
    {
        if (!isset($arrays) || empty($arrays))
            return $model;

        foreach ($arrays as $field => $value) {
            if (self::field_exists($model, $field))
                FHtml::setFieldValue($model, $field, $value);
        }

        return $model;
    }

    public static function copyFieldValues($model, $arrays, $rootModel = null, $overrideIfEmpty = false)
    {
        if (!isset($arrays) || empty($arrays))
            return $model;

        foreach ($arrays as $field) {
            if (self::field_exists($model, $field) && self::field_exists($rootModel, $field)) {
                $value = FHtml::getFieldValue($rootModel, $field);
                if ($overrideIfEmpty || !empty($value))
                    FHtml::setFieldValue($model, $field, $value);
            }
        }

        return $model;
    }

    public static function field_exists($model, $field)
    {
        if (is_string($model))
            $model = FHtml::createModel($model);

        try {
            if ((is_object($model) && property_exists($model, $field))
                || (is_array($model) && key_exists($field, $model))
                || (is_object($model) && method_exists($model, 'hasAttribute') && $model->hasAttribute($field))
                || (is_object($model) && method_exists($model, $field))
                || (is_object($model) && defined($model::className() . '::' . $field)) // constant
            )
                return true;
            else
                return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getCloneModel($table, $id = '', $params = null)
    {
        $model = FHtml::createModel($table);
        if (!empty($id)) {
            $model = $model::findOne($id);
            if (isset($model)) {
                $model->id = null;
                $model->setIsNewRecord(true);
            } else {
                $model = FHtml::createModel($table);
            }
        }

        $model = self::setFieldValues($model, $params);

        return $model;
    }

    public static function createModel($table)
    {
        return self::getModel($table);
    }

    public static function getModuleObject($module, $zone = 'backend')
    {
        $module = strtolower($module);
        $className =  $zone . '\\modules\\' . $module . '\\' . BaseInflector::camelize($module);
        if (class_exists($className)) {
            return Yii::$app->getModule(strtolower($module));
        }
        return null;
    }

    public static function getApplicationHelper($module = '')
    {
        if (empty($module))
            $module = FHtml::currentApplicationId();

        $module = strtolower($module);
        $className =  'applications\\' . $module . '\\' . BaseInflector::camelize($module);
        if (class_exists($className)) {
            return Yii::createObject(['class' => $className::className()]);
        }
        return new FApplication();
    }

    public static function getModelForAPI($table, $type = '', $id = null, $params = null, $autoCreateNew = true)
    {
        $model = self::getModel($table, $type, $id, $params, $autoCreateNew);
        return self::prepareDataForAPI($model);
    }

    public static function prepareDataForAPI($models, $folder = '', $displayFields = [], $file_fields = ['image', 'icon', 'thumbnail', 'avatar', 'banner', 'file'])
    {
        if (!is_array($models) && is_object($models))
            $arr[] = $models;
        else if (is_array($models))
            $arr = $models;
        if (!empty($arr)) {
            foreach ($arr as $model) {
                if (!is_object($model)) {
                    continue;
                }

                if (!empty($displayFields) && method_exists($model, 'setFields')) {
                    $model->setFields($displayFields);
                } else if (!empty($displayFields) && self::field_exists($model, 'columnsMode')) {
                    $model->columnsMode = $displayFields;
                } else if (empty($displayFields) && self::field_exists($model, 'columnsMode')) {
                    $model->columnsMode =  empty($model->columnsMode) ? 'api' : $model->columnsMode;
                }
                if (method_exists($model, 'prepareCustomFields'))
                    $model->prepareCustomFields();

                if (empty($folder) && method_exists($model, 'getTableName'))
                    $folder = str_replace('_', '-', $model->getTableName());

                foreach ($file_fields as $field) {
                    if (self::field_exists($model, $field))
                        FHtml::setFieldValue($model, $field, self::getFileURLForAPI(FHtml::getFieldValue($model, $field), $folder));
                }
            }
        }

        return $models;
    }

    //2017.5.4
    public static function setFieldValue($model, $field, $value, $overrideIfEmpty = true)
    {
        if (!$overrideIfEmpty && empty($value)) // does not set value if $value is empty
            return $model;

        $array = [];
        if (is_array($field))
            $array = $field;
        else
            $array[] = $field;

        foreach ($array as $field1) {
            if (self::field_exists($model, $field1)) {
                if (is_object($model))
                    $model->$field1 = $value;
                else if (is_array($model)) {
                    $model[$field1] = $value;
                }
            }
            else {
                if (FHtml::isDynamicObjectEnabled()) { // auto add column to table if not existed
                    self::addColumn($model, $field1);
                    $model->refresh();
                    if (is_object($model)) {
                        $model->$field1 = $value;
                        $model->save();
                    }
                    else if (is_array($model)) {
                        $model[$field1] = $value;
                    }
                }
            }
        }

        return $model;
    }

    public static function createTable($table, $columns, $tableOptions = []) {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->createTable($table, $columns, $tableOptions);
    }

    public static function dropTable($table) {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->dropTable($table);
    }

    public static function truncateTable($table) {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->truncateTable($table);
    }

    public static function addColumn($table, $column, $type = Schema::TYPE_STRING . '(1000)') {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->addColumn($table, $column, $type);
    }

    public static function alterColumn($table, $column, $type = Schema::TYPE_STRING . '(1000)') {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->alterColumn($table, $column, $type);
    }

    public static function renameColumn($table, $column, $newName) {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->renameColumn($table, $column, $newName);
    }

    public static function getFileURLForAPI($file, $folder = '')
    {

        $file = FHtml::getFileURL($file, $folder);

        if (!StringHelper::startsWith($file, 'http'))
            $file = FHtml::currentHost() . $file;

        return $file;
    }

    public static function getFieldValue($model, $field, $empty_value = '')
    {
        try {
            if (isset($model)) {
                if (is_string($field)) {
                    if (strpos(',', $field) === false)
                        $arr[] = $field;
                    else
                        $arr = explode(',', $field);
                } else if (is_array($field)) {
                    $arr = $field;
                }
                $result = '';
                foreach ($arr as $field1) {

                    //field1 as property
                    if (self::field_exists($model, $field1)) {
                        if (is_object($model))
                            $result = $model->$field1;
                        else if (is_array($model))
                            $result = $model[$field1];
                        if (!empty($result))
                            return $result;
                    }
                    //field1 as function like getName(), getDescription()..
                    if (method_exists($model, 'get' . ucfirst($field1))) {
                        $result = $model->{'get' . ucfirst($field1)}();
                        if (isset($result) && !empty($result))
                            return $result;
                    }
//                    else {
//                        if (FHtml::field_exists($model, 'getObjectAttributes')) {
//                            $customAttrs = $model::getObjectAttributes();
//                            foreach ($customAttrs as $attr) {
//                                if ($attr->meta_key == $field1 && $attr->is_active)
//                                    return $attr->meta_value;
//                            }
//                        }
//                    }
                }

                return empty($result) ? self::getValue($empty_value) : $result;
            } else
                return self::getValue($empty_value);
        } catch (Exception $e) {
            return self::getValue($empty_value);
        }
    }

    public static function getFieldLabel($model, $field, $isDisplaySetting = false) {
        if (is_string($model))
            $model = FHtml::getModel($model);

        if (isset($model) && FHtml::field_exists($model, 'attributeLabels()') && key_exists($field, $model->attributeLabels()))
            $result = $model->attributeLabels()[$field];
        else
            $result = FHtml::t('common', BaseInflector::camel2words($field));

        if ($isDisplaySetting && FHtml::isRoleModerator()) {
            $result .= FHtml::createLink('system/object-setting', ['object_type' => FHtml::getTableName($model), 'meta_key' => $field], BACKEND, ' <span class="glyphicon glyphicon-cog text-default small"></span>', '_blank', '');
        }

        return $result;
    }

    public static function getValue($value, $empty_value = '')
    {
        if (is_array($value))
            return $value[rand(0, count($value) - 1)]['id'];
        else if (isset($value))
            return $value;

        if (is_array($empty_value))
            return $empty_value[rand(0, count($empty_value) - 1)]['id'];
        else
            return $empty_value;
    }

    public static function getCategoriesList($object_type = '', $params = [], $isCached = false)
    {
        return self::getCategoriesByType($object_type, $params, $isCached);
    }

    public static function getCategoriesByType($object_type = '', $params = [], $isCached = false)
    {
        return self::getCategories($object_type, $params, $isCached);
    }

    public static function getCategories($object_type = '', $object_id = -1, $isCached = false)
    {
        $data = [];

        if ($object_id === -1) {// pass id or id array as first param
            $arr = [];
            if (is_string($object_type))
                $arr = explode(',', $object_type);
            else if (is_array($object_type))
                $arr = $object_type;
            else
                $arr[] = $object_type;
            $model = self::createModel(self::TABLE_CATEGORIES);
            $data = $model::find()->andWhere(['is_active' => 1])->andWhere(['in', 'id', $arr])->all();

            return $data;
        } else {
            if ($isCached) {
                $data = self::getCachedData(self::TABLE_CATEGORIES, $object_type, $object_id);
                if (isset($data))
                    return $data;
            }

            if (is_string($object_id))
                $arr = explode(',', $object_id);
            else if (is_array($object_id))
                $arr = $object_id;
            else
                $arr[] = $object_id;
            $model = self::createModel(self::TABLE_CATEGORIES);
            $default_object_type = FHtml::OBJECT_TYPE_DEFAULT;
            if (!empty($arr)) {
                if (ArrayHelper::isIndexed($arr)) { // if $arr is [id1, id2, id3..]
                    $data = $model::find()->andWhere(['is_active' => 1])->andWhere("object_type = '$object_type' OR object_type = '$default_object_type' OR object_type = '' ")->andWhere(['in', 'id', $arr])->orderby('sort_order asc, name asc')->all();
                } else { // if $arr is [field1 => value1, field2 => value2, field3 => value3..]

                    $data = $model::find()->andWhere(['is_active' => 1])->andWhere("object_type = '$object_type' OR object_type = '$default_object_type' OR object_type = '' ")->andWhere($arr)->orderby('sort_order asc, name asc')->all();
                }
            } else {
                $data = $model::find()->andWhere(['is_active' => 1])->andWhere("object_type = '$object_type' OR object_type = '$default_object_type' OR object_type = '' ")->orderby('sort_order asc, name asc')->all();
            }

            if ($isCached)
                self::saveCachedData($data, 'object-category\\' . $object_type, $object_type);

            return $data;
        }
    }

    // auto assign full path to Image, File
    public static function getProductCategories($params = [], $isCached = false)
    {
        return self::getCategoriesByType(self::TABLE_PRODUCT, $params, $isCached);
    }

    public static function getNewsCategories($params = [], $isCached = false)
    {
        return self::getCategoriesByType(self::TABLE_BLOGS, $params, $isCached);
    }

    public static function getArticleCategories($params = [], $isCached = false)
    {
        return self::getCategoriesByType(self::TABLE_ARTICLE, $params, $isCached);
    }

    public static function getAboutCategories($params = [], $isCached = false)
    {
        return self::getCategoriesByType(self::TABLE_ABOUT, $params, $isCached);
    }

    // build active query with complex Search Params, automatically remove $fields that are not model properties
    public static function getServiceCategories($params = [], $isCached = false)
    {
        return self::getCategoriesByType(self::TABLE_SERVICE, $params, $isCached);
    }

    // build active query with complex Search Params
    public static function getGalleries($object_type, $object_id = false, $isCached = false)
    {
        if ($object_id === false) {// pass id or id array as first param
            if (is_string($object_type))
                $arr = explode(',', $object_type);
            else if (is_array($object_type))
                $arr = $object_type;
            $data = [];
            $model = self::createModel(self::TABLE_OBJECT_FILES);
            $data = $model::find()->where(['in', 'object_id', $arr])->all();

        } else {
            if ($isCached) {
                $data = self::getCachedData(self::TABLE_OBJECT_FILES, $object_type, $object_id);
                if (isset($data))
                    return $data;
            }
            $model = self::createModel(self::TABLE_OBJECT_FILES);
            $data = $model::findAll(['object_type' => $object_type, 'object_id' => $object_id, 'file_type' => 'image']);
            if ($isCached) {
                self::saveCachedData($data, self::TABLE_OBJECT_FILES, $object_type, $object_id);
            }
            return $data;
        }
    }

    public static function getOutputForAPI($models, $type = '', $message = '', $dataParam = 'data', $totalPage = 1, $pageSize = 0,  $pageIndex = 0)
    {
        // From Cuong Hy
        if (is_array($dataParam)) {
            $status = $type;
            $data = $models;
            $others = $dataParam;
            $out = array();
            $out['status'] = $status;
            $out['data'] = $data;

            foreach ($others as $key=> $value){
                $out[$key] = $value;
            }
            $out['message'] = $message;

            return $out;
        }

        if (is_string($models)) {
            $out['status'] = 'ERROR';
            $out['message'] = $models . '. ' . $message;
            $out['name'] = $type;
            $out['code'] = 0;
            $out['type'] = '400';
            $out['total_page'] = $totalPage;
            $out['page_size'] = $pageSize;
            $out['page_index'] = $pageIndex;
            $out['total_items'] = -1;

            $out[$dataParam] = null;
        } else if (!isset($models) || (is_array($models) && count($models) == 0 || empty($models))) {
            $out['status'] = 'WARNING';
            $out['message'] = 'No items found';
            $out['total_page'] = $totalPage;
            $out['total_page'] = $totalPage;
            $out['page_size'] = $pageSize;
            $out['page_index'] = $pageIndex;
            $out['total_items'] = 0;

            $out['name'] = $type;
            $out['code'] = 0;
            $out['type'] = '400';
            $out[$dataParam] = null;
        } else {
            $out['status'] = 'SUCCESS';
            $out['message'] = $message;
            $out['total_page'] = $totalPage;
            $out['page_size'] = $pageSize;
            $out['page_index'] = $pageIndex;
            $out['name'] = $type;
            $out['code'] = 0;
            $out['number'] = is_array($models) ? count($models) : 1;
            $out['total_items'] = is_array($models) ? count($models) : 1;
            $out['type'] = is_array($models) ? 'list' : 'detail';
            $out[$dataParam] = $models; //is_array($models) ? $models : [$models];
        }
        return $out;
    }


    public static function getOutputForLookup($search_object, $search_field = 'name', $q = null, $id = null)
    {
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q) || !empty($q)) {
            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', ['like', $search_field, $q]);
            $out['results'] = array_values($data);
        } else if (is_null($q) || empty($q)) {
            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', []);
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $model = self::getModel($search_object, '', $id);
            $out['results'] = ['id' => $id, 'text' => FHtml::getFieldValue($model, ['name', 'title'])];
        }
    }

    public static function getKeyValueArray($data) {
        $result = [];
        if (is_array($data)) {
            $result = [];
            $associate = ArrayHelper::isAssociative($data);
            foreach ($data as $id => $item) {
                if (is_array($item)) {

                    if (key_exists('id', $item) && key_exists('name', $item)) {
                        $result[] = ['id' => FHtml::getFieldValue($item, ['id']), 'name' => FHtml::getFieldValue($item, ['name'])];
                    }
                    else {
                        $result[] = ['id' => $item[0], 'name' => $item[1]];
                    }
                }
                else {
                    if ($associate || (is_string($id) && !is_numeric($id)))
                        $result[] = ['id' => $id, 'name' => $item];
                    else
                        $result[] = ['id' => $item, 'name' => $item];
                }
            }

            $data = $result;
        }

        return $data;
    }

    public static function getComboArray($key, $table = '', $column = '', $isCache = true, $id_field = 'id', $name_field = 'name', $hasNull = true, $search_params = [], $limit = 0)
    {
        $data = self::getArray($key, $table, $column, $isCache, '', $search_params, $limit);
        $data = self::getKeyValueArray($data);

        if ($data != null) {
            if ($hasNull)
                return ArrayHelper::merge([null => FHtml::NULL_VALUE], ArrayHelper::map($data, $id_field, $name_field));
            else
                return ArrayHelper::map($data, $id_field, $name_field);
        }
        return [];
    }

    // getRelatedModels('album', 1, 'song'); getRelatedModels('product', 1, 'galleries');
    public static function getArray($key, $table = '', $column = '', $isCache = false, $select = '', $search_params = [], $limit = 0)
    {
        $isCache = false;

        if ($isCache) {
            $dataCached = self::getCachedData($key, $table, $column);
            if (!empty($dataCached))
                return $dataCached;
        }

        if (is_array($key)) {
            return $key;
        }

        // Select Distinct
        if (strpos($key, '#') !== false) {
            $arr = explode('#', $key);
            if (!empty($arr[0]))
                $table = $arr[0];

            if (!empty($arr[1]) && count($arr) > 0)
                $column = $arr[1];

            $sql = "SELECT DISTINCT `$column` As id, `$column` as name FROM $table ORDER BY id desc";

            $list = FHtml::findBySql($sql);
            return $list;
        } else if (!empty($key) && strpos(strtolower($key), ':') != false) {
            $arr = explode(':', $key);
            if (!empty($arr[0]))
                $table = $arr[0];

            if (!empty($arr[1]) && count($arr) > 0)
                $column = $arr[1];

        } else if (!empty($key) && strpos(strtolower($key), '.') != false && empty($table) & empty($column)) {
            $arr = explode('.', $key);
            if (!empty($arr[0]))
                $table = $arr[0];

            if (!empty($arr[1]) && count($arr) > 0)
                $column = $arr[1];
        } else if (!empty($key) && StringHelper::startsWith(strtolower($key), '@') != false) {
            $table = str_replace('@', '', $key);
            $column = '';
        } else if (!empty($key) && StringHelper::startsWith(strtolower($key), 'select') != false) {
            $list = FHtml::findBySql($key);
            return $list;
        }

        //echo $key . ' - ';
        if ($column == 'lang' || $key == 'lang') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $column = 'lang';
            $data = self::ARRAY_LANG;
        } else if ($column == 'city' || $key == 'city') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $column = 'city';
            $data = [];
        }  else if ($column == 'country' || $key == 'country') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $column = 'country';
            $data = [];
        } else if ($column == 'role' || $key == 'role') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $currentRole = FHtml::getCurrentRole();
            $currentAction = FHtml::currentAction();
            if ($currentRole == \common\models\User::ROLE_ADMIN || !in_array($currentAction, ['create', 'update'])) {
                $data = [
                    ['id' => \common\models\User::ROLE_ADMIN, 'name' => FHtml::t('common', 'Admin')],
                    ['id' => \common\models\User::ROLE_MODERATOR, 'name' => FHtml::t('common', 'Manager')],
                    ['id' => \common\models\User::ROLE_USER, 'name' => FHtml::t('common', 'User')]];
            } else if ($currentRole == \common\models\User::ROLE_MODERATOR) {
                $data = [
                    ['id' => \common\models\User::ROLE_MODERATOR, 'name' => FHtml::t('common', 'Manager')],
                    ['id' => \common\models\User::ROLE_USER, 'name' => FHtml::t('common', 'User')]];
            } else {
                $data = [
                    ['id' => \common\models\User::ROLE_USER, 'name' => FHtml::t('common', 'User')]];
            }
        } else if (($key == 'app_user' || $key == '@app_user') & $column == 'user_id') {
            $table = 'app_user';
            $key = '@app_user';
            $data = [];
        } else if ($column == 'user_id' || $key == 'user_id') {
            $table = 'user';
            $key = '@user';
            $data = [];
        } else if ($key == 'user.status') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $data = [
                ['id' => \common\models\User::STATUS_DELETED, 'name' => FHtml::t('common', 'Blocked')],
                ['id' => \common\models\User::STATUS_ACTIVE, 'name' => FHtml::t('common', 'Activated')]];

            return $data;

        } else if ($column == 'editor' || $key == 'editor') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $data = self::ARRAY_EDITOR;
        } else if ($column == 'is_active' || (StringHelper::startsWith($column, 'is_'))) {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $arr = explode('_', $column);
            $text = BaseInflector::camel2words($arr[1]);
            $data = [
                ['id' => '1', 'name' => FHtml::t('common',  $text)],
                ['id' => '0', 'name' => FHtml::t('common', 'Not ' . $text)],
                ['id' => null, 'name' => FHtml::t('common', FHtml::NULL_VALUE)]];
        } else if ($key == 'color' || $column == 'color') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $data = self::ARRAY_COLOR;
        } else if ($key == 'gender' || $column == 'gender') {
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
            $data = self::ARRAY_GENDER;
        } else if ($key == 'field_layout' || $column == 'field_layout') {
            return $data = self::ARRAY_FIELD_LAYOUT;
        } else if ($key == 'alignment' || $column == 'alignment') {

            return $data = self::ARRAY_ALIGNMENT;
        } else if ($key == 'grid_buttons' || $column == 'grid_buttons') {

            return $data = self::ARRAY_GRID_BUTTONS;
        } else if ($key == 'transition_speed' || $column == 'transition_speed') {

            return $data =
                self::ARRAY_TRANSITION_SPEED;
        } else if ($key == 'transition_type' || $column == 'transition_type') {

            return $data =
                self::ARRAY_TRANSITION_TYPE;
        } else if ($key == 'theme_color' || $key == '@theme_color' || $column == 'theme_color') {

            return $data = self::ARRAY_ADMIN_THEME;
        } else if ($key == 'theme_style' || $key == '@theme_style' || $column == 'theme_style') {

            return $data = self::ARRAY_THEME_STYLE;
        } else if ($key == 'controls_alignment' || $key == '@controls_alignment' || $column == 'controls_alignment') {

            return $data = self::ARRAY_CONTROLS_ALIGNMENT;
        } else if ($key == 'buttons_style' || $key == '@buttons_style' || $column == 'buttons_style') {

            return $data = self::ARRAY_BUTTONS_STYLE;
        } else if ($column == 'dataType') {
            $data = self::ARRAY_DBTYPE;
            $table = FHtml::OBJECT_TYPE_DEFAULT;
            $key = '';
        } else {
            $model = self::getModel($table);

            if (isset($model) && method_exists($model, 'getLookupArray')) {
                $data = $model::getLookupArray($column); // get pre-defined constants of Lookup Array in object class
            } else {
                $data = [];
            }
        }

        //2017/3/7 - Get Data from Settings table: object_settings
        $data = self::getKeyValueArray($data);

        if (FHtml::isDBSettingsEnabled()) {
            //then merge with custom data from 'object-setting' table
            $query = self::getQuery($key, $table, $column, $isCache, $select, $search_params);

            if ($query != null) {
                if ($limit > 0)
                    $query->limit($limit);

                $arr = $query->all();
                if (count($arr) > 0 && is_object($arr[0])) { // if return ActiveQuery::find(), not Query::find()
                    $arr1 = [];
                    foreach ($arr as $arr_item) {
                        $arr1[] = ['id' => FHtml::getFieldValue($arr_item, ['key', 'id']), 'name' => FHtml::getFieldValue($arr_item, ['value', 'name', 'text'])];
                    }
                    $arr = $arr1;
                }

                $data = ArrayHelper::merge($data, $arr);
            }
        }

        //2017/3/7 - Get data from Global setting
        $lookup = self::LOOKUP;
        foreach ($lookup as $id => $value) {
            if ($id == $key || $id == ($table . '.' . $column)) {
                $arr = self::getKeyValueArray($value);
                $data = ArrayHelper::merge($data, $arr);
                break;
            }
        }

        //2017/3/7 - Get data from Module setting
        $module = FHtml::getModuleObject(FHtml::getModelModule($table));
        if (isset($module)) {
            if (method_exists($module, 'getLookupArray')) {
                $arr = $module::getLookupArray($table . '.' . $column);
                if (!empty($arr))
                    $data = ArrayHelper::merge($data, $arr);
            } else if (self::field_exists($module, 'LOOKUP')) {
                if ($module::LOOKUP !== null)
                    $lookup = $module::LOOKUP;

                if (!empty($lookup)) {
                    foreach ($lookup as $id => $value) {
                        if ($id == $key || $id == ($table . '.' . $column)) {
                            $arr = self::getKeyValueArray($value);
                            $data = ArrayHelper::merge($data, $arr);
                            break;
                        }
                    }
                }
            }
        }


        if ($isCache)
            self::saveCachedData($data, $key, $table, $column);

        return $data;
    }

    public static function getQuery($key, $table = '', $column = '', $isCache = false, $select = '', $search_params = [], $order_by = [], $limit = 0)
    {
        $sql_select = '';
        $sql_table = '';
        //$table = str_replace('@', '', $table);
        if (StringHelper::startsWith($key, '@')) {
            $key = substr($key, 1);
            $arr = explode(',', $key);
            $sql_table = $arr[0];
            if ($key == 'user') {
                $id_column = 'id';
                $name_column = 'username';
            } else if ($key == 'app_user') {
                $id_column = 'id';
                $name_column = 'username';
                $table = 'app_user';
            } else {
                $id_column = isset($arr[1]) ? $arr[1] : 'id';
                $name_column = isset($arr[2]) ? $arr[2] : 'name';
            }
            $sql_select = !empty($select) ? $select : '*, ' . $id_column . ' as id, ' . $name_column . ' as name' . ', ' . $name_column . ' as text';
            $query = new Query;

            $query->select($sql_select)
                ->from($sql_table);

            if (!empty($search_params))
                $query->where($search_params);

            $model = FHtml::createModel($sql_table);
            if (isset($model) && FHtml::field_exists($model, 'application_id') && FHtml::isApplicationsEnabled())
                $query = $query->andWhere(['OR', ['application_id' => FHtml::APPLICATION_NONE], ['application_id' => FHtml::currentApplicationCode()]]);

            if (isset($model) && FHtml::field_exists($model, 'is_active'))
                $query->andWhere(['is_active' => 1]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                $id_column => SORT_ASC,
            ]);

            return $data = $query;
        }
        if (StringHelper::endsWith($column, '_userid')) {
            $sql_select = !empty($select) ? $select : 'id, username AS name';
            $sql_table = self::TABLE_USER;
            $query = new Query;
            $query->select($sql_select)
                ->from($sql_table);

            $query->where(!empty($search_params) ? $search_params : ['status' => FHtml::USER_STATUS_ACTIVE]);
            if (FHtml::isApplicationsEnabled())
                $query = $query->andWhere(['OR', ['application_id' => FHtml::APPLICATION_NONE], ['application_id' => FHtml::currentApplicationCode()]]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'username' => SORT_ASC,
            ]);
            return $data = $query;
        } else if (StringHelper::endsWith($column, '_user')) {
            $sql_select = !empty($select) ? $select : 'username as id, username AS name';
            $sql_table = self::TABLE_USER;
            $query = new Query;
            $query->select($sql_select)
                ->from($sql_table);

            $query->where(!empty($search_params) ? $search_params : ['status' => FHtml::USER_STATUS_ACTIVE]);
            if (FHtml::isApplicationsEnabled())
                $query = $query->andWhere(['OR', ['application_id' => FHtml::APPLICATION_NONE], ['application_id' => FHtml::currentApplicationCode()]]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'username' => SORT_ASC,
            ]);
            return $data = $query;
        } else if (($table == 'product' && $column == '') || ($key == '@product')) {
            $sql_select = !empty($select) ? $select : 'id as id, name AS text';
            $sql_table = 'product';

            $query = new Query;
            $query->select($sql_select)
                ->from($sql_table);

            $query->where(!empty($search_params) ? $search_params : ['is_active' => true]);
            if (FHtml::isApplicationsEnabled())
                $query = $query->andWhere(['application_id' => FHtml::currentApplicationCode()]);

            if ($limit > 0)
                $query->limit($limit);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'id' => SORT_ASC,
            ]);
            return $data = $query;
        } else if (in_array($table, ['object-category', 'category', 'object_category']) || in_array($key, ['object-category', 'category', 'object_category']) || in_array($column, ['categoryid', 'category_id']) || strpos($key, 'category_id') !== false) {
            $query = models\ObjectCategory::find();

            if (empty($table) || $table == self::TABLE_CATEGORIES)
                $query = $query->where(!empty($search_params) ? $search_params : ['OR', ['object_type' => $table], ['object_type' => ''], ['object_type' => FHtml::OBJECT_TYPE_DEFAULT]]);
            else
                $query = $query->where(!empty($search_params) ? $search_params : ['OR', ['object_type' => $table], ['object_type' => ''], ['object_type' => FHtml::OBJECT_TYPE_DEFAULT]]);

            if (FHtml::isApplicationsEnabled())
                $query = $query->andWhere(['application_id' => FHtml::currentApplicationCode()]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'sort_order' => SORT_ASC, 'name' => SORT_ASC
            ]);
            return $query;

        } else if ($key == 'object_type' || $table == 'object_type' || $column == 'object_type') {

            $sql_select = !empty($select) ? $select : 'object_type As id, name AS name';
            $sql_table = self::TABLE_OBJECT_TYPE;
            $query = new Query;
            $query->select($sql_select)
                ->from($sql_table);

            $query->where(!empty($search_params) ? $search_params : ['is_active' => true]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'sort_order' => SORT_ASC,
                'name' => SORT_ASC,
            ]);
            return $data = $query;
        } else if ($column == 'parent_id') {
            $model = FHtml::createModel($table);
            if (isset($model)) {
                $query = $model::find();
                $query->where(!empty($search_params) ? $search_params : ['is_active' => 1]);

                if (FHtml::isApplicationsEnabled())
                    $query = $query->andWhere(['application_id' => FHtml::currentApplicationCode()]);

                $query->orderBy(!empty($order_by) ? $order_by : [
                    'name' => SORT_ASC
                ]);
                return $query;
            }

        } else { // Get from Meta Setting table

            $query = ObjectSetting::find();
            $query->where(!empty($search_params) ? $search_params : ['OR', ['object_type' => $table, 'meta_key' => $column, 'is_active' => true], ['meta_key' => "$table.$column", 'is_active' => true]]);

            if (FHtml::isApplicationsEnabled())
                $query = $query->andWhere(['application_id' => FHtml::currentApplicationCode()]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'meta_key' => SORT_ASC,
                'sort_order' => SORT_ASC,
            ]);

            return $data = $query;
        }

        return null;
    }

    //2017/3/19
    public static function buildQuery($query, $search_params)
    {
        if (is_string($search_params)) {
            $query = $query->where($search_params);
        } else if (is_array($search_params)) {
            foreach ($search_params as $field => $value) {
                $query = $query->andWhere(self::buildQueryParams($field, $value));
            }
        }

        return $query;
    }

    //2017/3/19
    public static function buildQueryParams($fields, $value = '', $operator = '=', $connector = 'or')
    {
        $item = [];
        if (is_string($fields) && strpos($fields, ',') !== false) {
            $fields = explode(',', $fields);
        }

        //2017/3/19: [$operator, $field, $value]
        if (is_numeric($fields) && is_array($value) && count($value) == 3) {
            $operator = $value[0];
            $fields = $value[1];
            $value = $value[2];
        }

        // 2016-10-19: if keyword contains '%' then it is LIKE
        if (is_string($value) && strpos($value, '%') !== false) {
            $value = str_replace('%', '', $value);
            $operator = 'like';
        }

        if ((is_string($value) && strpos($value, ',') !== false) || is_array($value)) {
            if (is_string($value))
                $value = explode(',', $value);
            $operator = 'in';
            $item = [$operator, $fields, $value];
            return $item;
        }

        // 2017-3-17:
        if (StringHelper::startsWith($value, '-') || StringHelper::startsWith($value, '!')) {
            $value = substr($value, 1, strlen($value) - 1);
            $operator = '!=';
        }

        if (is_array($fields)) {
            // If there are more than 2 fields than auto merge conditions of the fields
            $item[] = $connector;
            foreach ($fields as $field1) {
                if (FHtml::isInArray($field1, ['category_id', 'categoryid'])) {
                    $operator = $operator == '!=' ? 'not like' : 'like';
                    $value1 = ',' . $value . ',';
                    if ($operator == 'like')
                        $item[] = ['OR', [$field1 => $value], [$operator, $field1, $value1]];
                    else
                        $item[] = ['AND', ['<>', $field1 , $value], [$operator, $field1, $value1]];

                } else if (!is_numeric($value) && is_string($value) && !FHtml::isInArray($field1, FHtml::getFIELDS_GROUP())) {
                    $operator = $operator == '!=' ? 'not like' : 'like';
                    $item[] = [$operator, $field1, $value];
                }
            }
        } else if (is_string($fields)) {
            $field1 = $fields;
            if (FHtml::isInArray($field1, ['category_id', 'categoryid'])) {
                $operator = $operator == '!=' ? 'not like' : 'like';
                $value1 = ',' . $value . ',';
                if ($operator == 'like')
                    $item = ['OR', [$field1 => $value], [$operator, $field1, $value1]];
                else
                    $item = ['AND', ['<>', $field1 , $value], [$operator, $field1, $value1]];
                return $item;
            } else if (!is_numeric($value) && is_string($value) && !FHtml::isInArray($field1, FHtml::getFIELDS_GROUP())) {
                $operator = $operator == '!=' ? 'not like' : 'like';
            }

            if ($operator != '=' && !empty($operator))
                $item = [$operator, $field1, $value];
            else if (in_array($operator, ['=', '<>', 'not like']))
                $item = "`{$field1}` is null or `$field1` {$operator} '$value'";
            else
                $item = [$field1 => $value];
        }

        return $item;
    }

    public static function getRelatedModels($object_type, $object_id, $object2_type, $relation_type = FHtml::RELATION_MANY_MANY)
    {
        $arr = [];
        if (is_string($object_id))
            $arr = explode(',', $object_id);
        else if (is_array($object_id))
            $arr = $object_id;

        $source = self::createModel($object_type);
        $destination = self::createModel($object2_type);
        $result = [];
        if ($relation_type == FHtml::RELATION_FOREIGN_KEY) {
            $result = $destination::find()->where(['in', $destination::primaryKey(), $arr])->all();
        } else if ($relation_type == FHtml::RELATION_ONE_MANY) {
            $result = $destination::find()->where(['in', 'object_id', $arr])->where(['object_type' => $object_type])->all();
        } else if (!empty($relation_type)) {
            $arr = models\ObjectRelation::find()->where(['object_type' => $object_type, 'object_id' => $object_id, 'object2_type' => $object2_type, 'relation_type' => $relation_type])->select('object2_id')->orderBy('sort_order asc, created_date desc')->asArray()->all();
            $arr = ArrayHelper::getColumn($arr, 'object2_id');
            $result = $destination::find()->where(['in', $destination::primaryKey(), $arr])->all();
        } else {
            $list = models\ObjectRelation::find()->where(['object_type' => $object_type, 'object_id' => $object_id, 'object2_type' => $object2_type])->select('object2_id')->orderBy('sort_order asc, created_date desc')->asArray()->all();
            $arr1 = ArrayHelper::getColumn($list, 'object2_id');
            $list = models\ObjectRelation::find()->where(['object2_type' => $object_type, 'object2_id' => $object_id, 'object_type' => $object2_type])->select('object_id')->orderBy('sort_order asc, created_date desc')->asArray()->all();
            $arr2 = ArrayHelper::getColumn($list, 'object_id');
            $arr = ArrayHelper::merge($arr1, $arr2);
            $result = $destination::find()->where(['in', $destination::primaryKey(), $arr])->all();
        }
        return $result;
    }

    public static function getRelatedViewModels($object_type, $object_id, $object2_type, $relation_type = FHtml::RELATION_MANY_MANY)
    {
        $models = self::getRelatedModels($object_type, $object_id, $object2_type, $relation_type);
        return self::toViewModel($models);
    }

    public static function toViewModel($models)
    {
        if (is_array($models)) {
            $viewModels = [];
            foreach ($models as $dataitem) {
                $viewModels[] = $dataitem->toViewModel();
            }

            return $viewModels;
        } else {

            return $models->toViewModel();
        }
    }

    public static function findbySql($sql, $params = []) {
        return self::currentDb()->createCommand($sql)->queryAll();
    }

    public static function executeSql($sql, $params = []) {
        return self::currentDb()->createCommand($sql, $params)->execute();
    }

    public static function queryScalar($sql, $params = []) {
        return self::currentDb()->createCommand($sql, $params)->queryScalar();
    }

    public static function getModels($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $load_active_only = true, $selectedFields = [])
    {
        $list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached, $load_active_only, $selectedFields);

        $models = isset($list) ? $list->models : null;

        //get Translated content if any
        $models = self::getTranslatedModels($models);

        return $models;
    }

    public static function getTranslatedModel($model) {
        if (!isset($model) || !FHtml::isDBLanguagesEnabled($model) || !method_exists($model, 'getTranslatedModel'))
            return $model;

        $translated = $model->translatedModel;
        if (isset($translated)) {
            $arr = FHtml::decode($translated->content);
            foreach ($arr as $field => $value) {
                $model = FHtml::setFieldValue($model, $field, $value);
            }
        }

        return $model;
    }

    public static function getTranslatedModels($models) {
        if (!isset($models) || !FHtml::isDBLanguagesEnabled($models))
            return $models;

        //get Translated content if any
        $result = [];
        foreach ($models as $model) {
            $translated = self::getTranslatedModel($model);
            $result[] = $translated;
        }
        $models = $result;
        return $models;
    }

    public static function getSettingLookup($name) {
        $model = FHtml::getModel('settings_lookup', '', ['name' => $name]); // models\SettingsLookup::findOne(['name' => $name]);
        return $model;
    }

    public static function buildSearchParams($model, $search_params = '', $load_active_only = false) {
        $category_id = FHtml::getRequestParam(['category_id', 'category']);
        $keyword = FHtml::getRequestParam(['keyword', 'k']);
        $search_params = empty($search_params) ? '1 = 1' : $search_params;

        if (!empty($category_id)) {
            if (self::field_exists($model, 'category_id')) {
                if (empty($search_params)) {
                    $search_params = ['category_id' => $category_id];
                } else {
                    if (is_array($search_params))
                        $search_params = ArrayHelper::merge($search_params, ['category_id' => $category_id]);
                    else if (is_string($search_params))
                        $search_params .= " AND (category_id LIKE '%,$category_id,%' OR category_id=$category_id)";
                }
            }
        }

        if (!empty($keyword)) {
            if (is_string($search_params)) {
                $search_params1 = "";
                $search_params1 .= self::field_exists($model, 'name') ? "name LIKE '%$keyword% OR " : "";
                $search_params1 .= self::field_exists($model, 'overview') ? "overview LIKE '%$keyword% OR " : "";
                $search_params1 .= self::field_exists($model, 'content') ? "content LIKE '%$keyword% OR " : "";
                $search_params1 .= self::field_exists($model, 'description') ? "description LIKE '%$keyword% OR " : "";

                if (!empty($search_params1))
                    $search_params .= " AND (" . $search_params1 . " 1=0)";
            }
        }

        if ($load_active_only) {
            if (self::field_exists($model, 'is_active')) {
                if (empty($search_params)) {
                    $search_params = ['is_active' => 1];
                } else {
                    if (is_array($search_params))
                        $search_params = ArrayHelper::merge($search_params, ['is_active' => 1]);
                    else if (is_string($search_params))
                        $search_params = $search_params . ' AND is_active = 1';
                }
            }
        }

        return $search_params;
    }

    public static function getPageModelsList($object_type = '', $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        $page_size1 = FHtml::getRequestParam(['per-page', 'page_size']);
        $page1 = FHtml::getRequestParam('page');

        if (!empty($page_size1))
            $page_size = $page_size1;
        if (!empty($page1))
            $page = $page1;

        $model = self::createModel($object_type);

        $search_params = FHtml::buildSearchParams($model, $search_params);
        return self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached, true);
    }

    public static function getModelsList($object_type = '', $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $load_active_only = true, $selected_fields = [])
    {
        $lookupModel = self::getSettingLookup($object_type); // Get defined Params in setting_query table
        if (isset($lookupModel)) {
            if ($lookupModel->is_active === 1) {
                $object_type = $lookupModel->object_type;
                $search_params = $lookupModel->params;
                $order_by = $lookupModel->orderby;
                $page_size = $lookupModel->limit;
                $isCached = $lookupModel->is_cached;
                $selected_fields = $lookupModel->fields;
            }
        }

        if ($isCached) {
            $data = self::getCachedData('', $object_type, 'LIST');
            if (isset($data))
                return $data;
        }
        $model = self::createModel($object_type);

        if ($load_active_only) {
            if (self::field_exists($model, 'is_active')) {
                if (empty($search_params)) {
                    $search_params = ['is_active' => 1];
                } else {
                    if (is_array($search_params))
                        $search_params = ArrayHelper::merge($search_params, ['is_active' => 1]);
                    else if (is_string($search_params))
                        $search_params = $search_params . ' AND is_active = 1';
                }
            }
        }

        $query = self::buildQueryFromModel($model, $search_params);

        if (!isset($query) || is_string($query))
            return $query;

        $total = $query->count();
        $order_by = empty($order_by) ? (method_exists($model, 'getOrderBy') ? $model->getOrderBy() : []) : $order_by;

        if ($page_size < 1) {
            $query = $query->orderBy($order_by);
        } else {
            if ($page * $page_size - $page_size >= $total) {
                return $provider = new BaseDataList([
                    'query' => $query,
                    'models' => FError::PAGE_INDEX_INVALID,
                    'pagination' => [
                        'pageSize' => $page_size,
                        'totalCount' => $total,
                        'page' => $page
                    ],
                ]);
                //$page = ceil($total/$page_size);
            } else if ($page < 1)
                $page = 1;

            $start_index = $page * $page_size - $page_size;
            $query = $query->orderBy($order_by)->limit($page_size)->offset($start_index);
        }

        $list = $query->all();
        $list1 = [];
        if (!empty($selected_fields))
        {
            foreach ($list as $model) {
                if (method_exists($model, 'setFields')) {
                    $model->setFields($selected_fields);
                }
                $list1[] = $model;
            }
        } else {
            $list1 = $list;
        }

        $provider = new BaseDataList([
            'query' => $query,
            'models' => $list1,
            'pagination' => [
                'pageSize' => $page_size,
                'totalCount' => $total,
            ],
        ]);

        return $provider;
    }

    //2017/3/21
    public static function buildQueryFromModel($model, $search_params)
    {
        if (is_array($model))
            return null;

        if (!is_object($model) && is_string($model))
            $model = FHtml::createModel($model);

        if (!isset($model))
            return null;

        $query = $model::find();

        if (is_string($search_params)) {
            $query = $query->where($search_params);
        } else if (is_array($search_params)) {
            foreach ($search_params as $field => $value) {
                if ($value == self::NULL_VALUE || !isset($value) || empty($value))
                    continue;

                if (self::field_exists($model, $field)) {
                    $query = $query->andWhere(self::buildQueryParams($field, $value));
                }
            }
        }

        //2017/3/21: Auto add application_id and lang params
        $applicationid = FHtml::currentApplicationCode(); // Auto filter by ApplicationId
        if (!empty($applicationid) && self::field_exists($model, 'application_id') && self::isApplicationsEnabled()) {
            $query = $query->andWhere(['application_id' => $applicationid]);
        }

//        $lang = self::currentLang(); // Auto filter by ApplicationId
//        if (!empty($lang) && self::field_exists($model, 'lang') && self::isLanguagesEnabled()) {
//            $query = $query->andWhere(['lang' => $lang]);
//        }

        $query = $query->orderBy(FHtml::getOrderBy($model));

        return $query;
    }

    // Get Products
    public static function getModelsForAPI($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $folder = '', $displayFields = [])
    {
        if (empty($folder))
            $folder = str_replace('_', '-', $object_type);

        $list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached);

        return isset($list) ? FHtml::prepareDataForAPI($list->models, $folder, $displayFields) : null;
    }

    public static function getObjectSettings($object_type, $key, $isCached = false, $displayFields = [])
    {
        $list = self::getModelsList(self::TABLE_OBJECT_SETTING, ['is_active' => 1, 'object_type' => $object_type, 'meta_key' => $key], 'sort_order asc');
        return isset($list) ? $list->models : null;
    }

    public static function getViewModels($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $displayFields = [])
    {
        $list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached);
        return isset($list) ? $list->viewModels : null;
    }

    public static function getViewModelsForAPI($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $folder = '', $displayFields = [])
    {
        $list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached);
        return isset($list) ? FHtml::prepareDataForAPI($list->viewModels, $folder, $displayFields) : null;
    }

    // Get About
    public static function getBlogsModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->models;
    }

    public static function getBlogsList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0)
    {
        return self::getModelsList(self::TABLE_BLOGS, $search_params, $order_by, $page_size, $page, $isCached, $limit);
    }

    public static function getBlogsViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
    }

    // Get Articles
    public static function getProductsModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getProductsList($search_params, $order_by, $page_size, $page, $isCached)->models;
    }

    public static function getProductsList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0)
    {
        return self::getModelsList(self::TABLE_PRODUCT, $search_params, $order_by, $page_size, $page, $isCached, $limit);
    }

    public static function getProducts($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getProductsList($search_params, $order_by, $page_size, $page, $isCached)->models;
    }

    // Get Articles

    public static function getProductsViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getProductsList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
    }

    public static function getAboutList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0)
    {
        return self::getModelsList(self::TABLE_ABOUT, $search_params, $order_by, $page_size, $page, $isCached, $limit);
    }

    public static function getAboutModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->models;
    }

    //HungHX: 20160801
    public static function getAboutViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getBlogsList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
    }

    public static function getArticleModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0)
    {
        return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->models;
    }

    public static function getArticleList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0)
    {
        return self::getModelsList(self::TABLE_ARTICLE, $search_params, $order_by, $page_size, $page, $isCached, $limit);
    }

    public static function getArticleViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
    }

    public static function getPromotionList($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0)
    {
        return self::getModelsList(self::TABLE_PROMOTION, $search_params, $order_by, $page_size, $page, $isCached, $limit);
    }

    public static function getPromotionModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $limit = 0)
    {
        return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->models;
    }

    // Increase Field Values (used for updating statistic values)

    public static function getPromotionViewModels($search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false)
    {
        return self::getArticleList($search_params, $order_by, $page_size, $page, $isCached)->viewModels;
    }

    // Assign value to model field

    public static function getFieldArray($model, $field)
    {
        try {
            if (isset($model)) {
                if (is_string($field)) {
                    if (strpos(',', $field) === false)
                        $arr[] = $field;
                    else
                        $arr = explode(',', $field);
                } else if (is_array($field)) {
                    $arr = $field;
                }
                $result = [];
                foreach ($arr as $field1) {
                    if (self::field_exists($model, $field1))
                        $result = ArrayHelper::merge($result, [$field1 => $model[$field1]]);
                }
                return $result;
            } else
                return [];
        } catch (Exception $e) {
            return [];
        }
    }

    public static function increaseFieldValues($model, $arrays, $value = 1)
    {
        if (!isset($arrays) || empty($arrays))
            return $model;

        if (is_string($arrays))
            $arrays = explode(',', $arrays);

        if (ArrayHelper::isIndexed($arrays)) {
            foreach ($arrays as $field) {
                if (self::field_exists($model, $field))
                    $model[$field] = $model[$field] + $value;
            }
        } else if (ArrayHelper::isAssociative($arrays)) {
            foreach ($arrays as $field => $value1) {
                if (self::field_exists($model, $field))
                    $model[$field] = $model[$field] + $value1;
            }
        }

        return $model;
    }

    //HungHX: 20160814

    public static function Db()
    {
        return \Yii::$app->db;
    }

    //HungHX: 20160814

    public static function refreshCache($key = '')
    {
        FHtml::Cache()->flush();
    }

    public static function getTableNames()
    {
        return self::currentDb()->getSchema()->getTableNames();
    }

    public static function populateObjectTypes()
    {
        $tables = self::getTableNames();
        foreach ($tables as $table) {
            $model = models\ObjectType::findOne(['object_type' => $table]);
            $module = self::getModelModule($table);
            if (empty($module))
                $module = FHtml::OBJECT_TYPE_DEFAULT;

            if (!isset($model))
            {
                $model = self::createModel('object_type');
                $model->object_type = $table;
                $model->name = BaseInflector::camel2words($table);
                $model->is_active = 0;
                FHtml::setFieldValue($model, 'is_system', 1);
                FHtml::setFieldValue($model, 'group', $module);
                $model->save();
            }

            FHtml::populateObjectSchema($table); //Populate schema for each $table
        }
    }

    //HungHX: 20160814
    public static function currentDb($db_config = '')
    {
        $arr = [FHtml::currentApplicationDatabase(), FHtml::CONFIG_DB];
        foreach ($arr as $arr_item) {
            $db = Yii::$app->get($arr_item, false);
            if (isset($db))
                return $db;
        }

        return Yii::$app->get(FHtml::CONFIG_DB, false);
    }

    //2017/3/13
    public static function currentDbName() {
        $arr = [FHtml::currentApplicationDatabase(), FHtml::CONFIG_DB];
        foreach ($arr as $arr_item) {
            $db = Yii::$app->get($arr_item, false);
            if (isset($db))
                return $arr_item;
        }
        return FHtml::CONFIG_DB;
    }

    public static function getTableColumns($tableName)
    {
        return self::getTableSchema($tableName)->columns;
    }

    public static function getObjectColumns($object_type) {
        $key = $object_type . '::Columns';
        $items = self::getCachedData($key);
        if (isset($items))
            return $items;

        $items = FHtml::getModels('settings_schema', ['object_type' => $object_type], 'sort_order asc', -1, 1, true, false);
        self::saveCachedData($items, $key);

        return $items;
    }

    public static function getObjectColumn($object_type, $column) {
        $items = self::getObjectColumns($object_type);
        if (isset($items)) {
            foreach ($items as $item) {
                if (strtolower($item->name) == strtolower($column))
                    return $item;
            }
        }
        return null;
    }

    public static function getTableSchema($tableName)
    {
        return self::currentDb()->getTableSchema($tableName);
    }

    public static function deleteObjectSchema($object_type)
    {
        models\SettingsSchema::deleteAll(['object_type' => $object_type]);
    }

    public static function populateObjectSchema($object_type) {
        $object_type = empty($object_type) ? FHtml::getRequestParam('object_type') : $object_type;
        if (!empty($object_type))
        {
            $schema = FHtml::Db()->getTableSchema($object_type);
            if (isset($schema))
            {
                $i = 0;
                foreach ($schema->columns as $column) {
                    $i = $i + 1;
                    $dbSchema = FHtml::getModel('settings_schema', '', ['object_type' => $object_type, 'name' => $column->name], [], false);
                    if (!isset($dbSchema) || empty($dbSchema)) {
                        $dbSchema = new SettingsSchema();
                        $dbSchema->is_active = 1;
                    } else {

                    }

                    $dbSchema->object_type = $object_type;
                    $dbSchema->is_column = 1;
                    $dbSchema->name = $column->name;
                    $dbSchema->dbType = $column->dbType;
                    $dbSchema->is_system = 1;

                    if (empty($dbSchema->sort_order))
                        $dbSchema->sort_order = $i;

                    if (!isset($dbSchema->is_readonly) && FHtml::isInArray($column->name, FHtml::COLUMNS_FORM_READONLY))
                        $dbSchema->is_readonly = 1;

                    $commentArray = FHtml::toArrayFromDbComment($column->comment, $column->name);

                    if (empty($dbSchema->editor))
                        $dbSchema->editor = isset($commentArray['editor']) ? $commentArray['editor'] : '';
                    if (empty($dbSchema->lookup))
                        $dbSchema->lookup = isset($commentArray['lookup']) ? $commentArray['lookup'] : '';

                    $dbSchema->save();
                }
            }
        }
    }

    public static function saveModel($model, $post = null, $arrays = [])
    {
        $post = isset($post) ? $post : Yii::$app->request->post();
        $model = is_object($model) ? $model : self::createModel($model);
        if (isset($model)) {
            self::prepareModel($model, $post, $arrays);

            if ($model->validate()) {
                $model->save();
            } else {
                $model = null;
                return false;
            }
        }

        return $model;
    }

    public static function prepareModel($model, $post = null, $arrays = [])
    {
        $post = isset($post) ? $post : Yii::$app->request->post();

        if (isset($model)) {
            $model->load($post);

            foreach ($arrays as $key => $value) {
                if (self::field_exists($model, $key))
                    $model[$key] = $value;
            }
        }

        return $model;
    }

    public static function getValueKey($module, $type, $key)
    {
        $record = Setting::findOne(['module' => $module, 'type' => $type, 'key' => $key]);
        if (isset($record)) {
            return $record->value;
        } else {
            return $key;
        }
    }

    public static function saveObjectItems($model, $object_type, $object_id, $object_related = [])
    {
        FHtml::saveObjectAttributes($model, $object_type, $object_id);
        FHtml::saveObjectFile($model, $object_type, $object_id);
        FHtml::saveCategory($model, $object_type, $object_id);

        if (!is_array($object_related))
            $arr[] = $object_related;
        else
            $arr = $object_related;

        foreach ($arr as $object_related) {
            FHtml::saveObjectRelation($model, $object_type, $object_id, $object_related, FHtml::RELATION_MANY_MANY);
        }
    }

    // Get Value of a key in table setting

    public static function saveObjectAttributes($model, $object_type, $object_id, $arrays = [])
    {
        $object_module = BaseInflector::camelize($object_type);
        if (empty($arrays)) {
            if (key_exists($object_module, $_POST) && key_exists('ObjectAttributes', $_POST[$object_module]))
                $arrays = $_POST[$object_module]['ObjectAttributes'];
        }

        models\ObjectAttributes::deleteAll(['object_type' => $object_type, 'object_id' => $object_id]);
        if (is_array($arrays) && !empty($arrays)) {
            foreach ($arrays as $array) {
                $item = new models\ObjectAttributes();
                $item->object_id = $object_id;
                $item->object_type = $object_type;
                $item->is_active = 1;
                $item->created_date = date('Y-m-d');
                $item->created_by = FHtml::currentUserId();
                $item->application_id = FHtml::currentApplicationCode();
                $item->meta_key = $array['meta_key'];
                $item->meta_value = $array['meta_value'];
                $item->save();
            }
        }
    }

    public static function saveUploadedFiles($model, $files = [], $folder = '', $baseFolder = '') {
        if (FHtml::field_exists($model, 'COLUMNS_UPLOAD')) {
            $folder = empty($folder) ? FHtml::getImageFolder($model) : $folder;
            $baseFolder = empty($baseFolder) ? FHtml::getFullUploadFolder() : $baseFolder;
            $files = empty($files) ? FHtml::getUploadedFiles($model, $model::COLUMNS_UPLOAD, $folder . FHtml::getAttribute($model, 'id')) : $files;
            FHtml::saveFiles($files, $baseFolder . "/$folder/", $model, false);
        }
    }

    public static function saveObjectFile($model, $object_type, $object_id, $arrays = [], $folder = [])
    {
        if (empty($folder))
            $folder = FHtml::getFullUploadFolder('object-file');

        $object_module = BaseInflector::camelize($object_type);

        if (empty($arrays)) {
            if (key_exists($object_module, $_POST) && key_exists('ObjectFile', $_POST[$object_module])) {
                $arrays = $_POST[$object_module]['ObjectFile'];
            } else {
                models\ObjectFile::deleteAll('object_type = "' . $object_type . '" AND object_id = "' . $object_id . '"');
                return $model;
            }
        }

        if (is_array($arrays) && !empty($arrays)) {
            $i = 0;
            foreach ($arrays as $array) {
                if (!empty($array['id']))
                    $ids[] = $array['id'];
            }

            if (!empty($ids))
                models\ObjectFile::deleteAll('object_type = "' . $object_type . '" AND object_id = "' . $object_id . '" AND id NOT IN (' . implode(',', $ids) . ')');
            foreach ($arrays as $array) {
                $id = $array['id'];
                if (empty($id)) {
                    $file = FUploadedFile::getInstance($model, 'ObjectFile[' . $i . '][file_upload]');

                    $item = new models\ObjectFile();
                    $item->object_id = $object_id;
                    $item->object_type = $object_type;
                    $item->is_active = 1;
                    $item->sort_order = $i;

                    $item->created_date = date('Y-m-d');
                    $item->created_user = FHtml::currentUserId();
                    $item->application_id = FHtml::currentApplicationCode();
                    $item->title = $array['title'];
                    $item->file = $array['file'];
                    $item->description = '';

                    if (self::field_exists($item, 'file_duration') && key_exists('file_duration', $array))
                        $item->file_duration = $array['file_duration'];

                    $item->save();
                    if (isset($file)) {
                        $file->fieldName = 'file';
                        $file->name = $object_type . '_' . $object_id . '_file_' . $item->id . '.' . $file->extension;
                        $item->file_type = $file->type;
                        $item->file_size = $file->size;
                        $item->file = $file->name;
                        FHtml::saveFiles($file, $folder, $item);
                    }
                } else {
                    $item = models\ObjectFile::findOne($id);
                    if (isset($item)) {
                        $item->object_id = $object_id;
                        $item->object_type = $object_type;
                        $item->is_active = 1;
                        $item->sort_order = $i;
                        $item->title = $array['title'];
                        $item->file = $array['file'];
                        $item->file_duration = $array['file_duration'];
                        $item->save();
                        $file = FUploadedFile::getInstance($model, 'ObjectFile[' . $i . '][file_upload]');
                        if (isset($file)) {
                            $file->fieldName = 'file';
                            $file->oldName = $item->file;
                            $file->name = $object_type . '_' . $object_id . '_file_' . $item->id . '.' . $file->extension;
                            $item->file_type = $file->type;
                            $item->file_size = $file->size;
                            $item->file = $file->name;
                            FHtml::saveFiles($file, $folder, $item);
                        }
                    }
                }
                $i += 1;
            }
        }
    }

    public static function saveCategory($model, $object_type = '', $object_id = '', $arrays = [])
    {
        $object_module = BaseInflector::camelize($object_type);
        $object2_type = 'object-category';
        $object2_module = BaseInflector::camelize(str_replace('\\', '_', $object2_type . '_array'));
        $relation_type = '';

        if (empty($arrays)) {
            if (FHtml::field_exists($model, 'category_id_array'))
                $arrays = FHtml::getFieldValue($model, 'category_id_array');
            else if (FHtml::field_exists($model, 'category_id'))
                $arrays = explode(',', FHtml::getFieldValue($model, 'category_id'));
            else if (key_exists($object_module, $_POST) && key_exists($object2_module, $_POST[$object_module]))
                $arrays = $_POST[$object_module][$object2_module];
        }

        models\ObjectRelation::deleteAll(['object_type' => $object_type, 'object_id' => $object_id, 'object2_type' => $object2_type]);

        if (is_array($arrays) && !empty($arrays)) {
            $i = 0;
            foreach ($arrays as $array) {
                   $i += 1;
                $item = new models\ObjectRelation();
                $item->object_id = $object_id;
                $item->object_type = $object_type;
                $item->created_date = date('Y-m-d');
                $item->sort_order = $i;
                $item->created_user = FHtml::currentUserId();
                $item->object2_type = $object2_type;
                $item->object2_id = (!is_array($array)) ? $array : $array['id'];
                $item->relation_type = $relation_type;
                $item->save();
            }
        }
    }

    public static function saveObjectRelation($model, $object_type, $object_id, $object2_type, $relation_type, $arrays = [])
    {
        $object_module = BaseInflector::camelize($object_type);
        $object2_module = BaseInflector::camelize(str_replace('\\', '_', $object2_type));
        $arr = explode('\\', $object2_type);
        if (count($arr) > 1) {
            $object2_type = $arr[0];
            $relation_type = $arr[1];
        }

        if (empty($arrays)) {
            if (key_exists($object_module, $_POST) && key_exists($object2_module, $_POST[$object_module])) {
                $arrays = $_POST[$object_module][$object2_module];
                models\ObjectRelation::deleteAll(['object_type' => $object_type, 'object_id' => $object_id, 'object2_type' => $object2_type, 'relation_type' => $relation_type]);
                if (is_array($arrays) && !empty($arrays)) {
                    $i = 0;
                    foreach ($arrays as $array) {
                        $i += 1;
                        $item = new models\ObjectRelation();
                        $item->object_id = $object_id;
                        $item->object_type = $object_type;
                        $item->created_date = date('Y-m-d');
                        $item->sort_order = $i;
                        $item->created_user = FHtml::currentUserId();
                        $item->object2_type = $object2_type;
                        $item->object2_id = (!is_array($array)) ? $array : $array['id'];
                        $item->relation_type = $relation_type;
                        $item->save();
                    }
                }
            }
            //new
            $object2_module = '_' . $object2_module;
            if (key_exists($object_module, $_POST) && key_exists($object2_module, $_POST[$object_module])) {
                $arrays = $_POST[$object_module][$object2_module];
                if (is_array($arrays) && !empty($arrays)) {
                    $i = 0;
                    foreach ($arrays as $array) {
                        $object2_id = is_numeric($array) ? $array : $array['id'];
                        $i += 1;
                        $item = FHtml::getModel('object_relation', '', ['object_type' => $object_type, 'object_id' => $object_id, 'object2_id' => $object2_id, 'object2_type' => $object2_type, 'relation_type' => $relation_type]);
                        $item->object_id = $object_id;
                        $item->object_type = $object_type;
                        $item->created_date = date('Y-m-d');
                        $item->sort_order = $i;
                        $item->created_user = FHtml::currentUserId();
                        $item->object2_type = $object2_type;
                        $item->object2_id = $object2_id;
                        $item->relation_type = $relation_type;
                        $item->save();
                    }
                }
            }
        }

    }

    //HungHX: 20160801
    public static function prepareDefaultValues($model, $fields = ['category_id_array', 'created_date', 'created_user', 'is_active', 'application_id'], $whenAction = FHtml::ACTION_ADD)
    {
        if ((FHtml::currentAction() == 'create' && $whenAction == FHtml::ACTION_ADD) && self::field_exists($model, 'id')) {
            unset($model->id);
        }

        $modelName = StringHelper::basename($model::className());

        foreach ($fields as $field) {
            if (!self::field_exists($model, $field) && !StringHelper::endsWith($field, '_array')) //non existed field
                continue;
            if (in_array($field, ['created_date', 'modified_date', 'updated_date']) && self::field_exists($model, $field) && empty($model[$field])) {
                $model[$field] = FHtml::Now();
            } else if (in_array($field, ['created_at', 'modified_at']) && self::field_exists($model, $field) && empty($model[$field])) {
                $model[$field] = time();
            } else if (self::field_exists($model, $field) && strpos($field, 'is_') !== false && !isset($model[$field]) && self::isRoleModerator() ) {
                $model[$field] = 1;
            } else if (self::field_exists($model, $field) && empty($model[$field]) && strpos($field, 'application_id') !== false || strpos($field, 'application_id') !== false) {
                $model[$field] = FHtml::currentApplicationCode();
            } else if (self::field_exists($model, $field) && empty($model[$field]) && StringHelper::endsWith($field, 'user')) {
                $model[$field] = FHtml::currentUserId();
            } else if (StringHelper::endsWith($field, '_array')) {

                $field1 = str_replace('_array', '', $field);
                if (!self::field_exists($model, $field1)) //non existed field
                    continue;

                if (in_array($field1, ['category_id'])) { // if field is category_id
                    if ($whenAction == self::ACTION_LOAD) {
                        $result = self::getFieldValue($model, $field1);
                        $result = trim($result, ',[];|\t\n');

                        $model[$field] = explode(',', $result);
                        $model[$field1] = explode(',', $result);

                    } else {
                        if (isset($_POST[$modelName][$field])) {
                            $arr = $_POST[$modelName][$field];
                            $model[$field] = $arr;
                            if (is_array($arr) && FHtml::field_exists($model, $field1) && !empty($arr)) {
                                $model[$field1] = ',' . implode(',', $arr) . ',';
                            }
                            else {
                                $model[$field1] = null;
                            }
                        }
                    }
                } else {
                    if ($whenAction == self::ACTION_LOAD) {
                        $result = self::getFieldValue($model, $field1);
                        $model[$field1] = FHtml::decode($result);
                    } else {
                        if (isset($_POST[$modelName][$field])) {
                            $arr = $_POST[$modelName][$field];
                            $model[$field] = $arr;
                        }

                        if (isset($_POST[$modelName][$field1])) {

                            $arr = $_POST[$modelName][$field1];

                            $model[$field1] = FHtml::encode($arr);

                        }
                    }
                }
            } else {

            }
        }

    }

    //HungHX: 20160814
    public static function selectDistinctArray($table, $column, $isCache = true)
    {
        $sql_select = '';
        $sql_table = '';

        $sql_select = $column . ' AS Name';
        $sql_table = $table;
        $query = new Query;
        $query->select($sql_select)
            ->from($sql_table)->distinct();

        $query->orderBy([
            $column => SORT_ASC,
        ]);
        return $data = ArrayHelper::getColumn($query->all(), 'Name');
    }

    public static function Request()
    {
        return \Yii::$app->request;
    }

    public static function RequestParams($excluded_keys = [])
    {
        if (is_string($excluded_keys))
            $excluded_keys = explode(',', $excluded_keys);

        $params = \Yii::$app->request->getQueryParams();
        if (!empty($excluded_keys)) {
            foreach ($excluded_keys as $key) {
                ArrayHelper::remove($params, $key);
            }
        }
        return $params;
    }

    public static function merge($param, $extra = [])
    {
        return self::mergeRequestParams($param, $extra);
    }

    public static function mergeRequestParams($param, $extra = [])
    {
        if (empty($param))
            return $extra;

        if (is_array($param)) {
            $result = [];
            foreach ($param as $key => $value) {
                if (isset($value) && !empty($value)) {
                    $result = array_merge($result, [$key => $value]);
                }
            }
            if (is_array($extra)) {
                foreach ($extra as $key => $value) {
                    if (isset($value) && !empty($value)) {
                        $result = array_merge($result, [$key => $value]);
                    }
                }
            }
            return $result;
        } else if (is_string($param)) {
            $result = $param;
            if (is_string($extra)) {
                $result = $param . $extra;
            } else if (is_array($extra)) {
                foreach ($extra as $key => $value) {
                    $result .= ((strpos($result, '?') > 0) ? '&' : '?') . $key . '=' . $value;
                }
            }
        }
        return $result;
    }

    public static function getArrayParam($array, $param, $defaultvalue = '')
    {
        if (isset($array[$param]))
            return $array[$param];
        else
            return $defaultvalue;
    }

    //2017/3/21
    public static function loadParams($model, $params)
    {
        if (is_string($params))
            return $model;

        //$params from ajax search of kartik grid 'ModelSearch' => [
        $object_type = BaseInflector::camelize(FHtml::getTableName($model)) . 'Search';
        if (key_exists($object_type, $params)) {
            $params = $params[$object_type];
        }

        foreach ($params as $key => $value) {

            //2017/3/17 if $params like ['<>', $field, $value]
            if (is_numeric($key) && is_array($value)) {
                if (count($value) == 3)
                    FHtml::setFieldValue($model, $value[1], in_array($value[0], ['!=', '<>']) ? ('-' . $value[2]) : $value[2]);
            } else if (self::field_exists($model, $key)) {
                $model[$key] = isset($params[$key]) ? $params[$key] : null;
                if (!empty(FHtml::getRequestParam($key)) && !isset($model[$key]))
                    $model[$key] = FHtml::getRequestParam($key);
            }
        }

        $applicationid = FHtml::currentApplicationCode(); // Auto filter by ApplicationId
        if (!empty($applicationid) && self::field_exists($model, 'application_id') && self::isApplicationsEnabled()) {
            $model->application_id = $applicationid;
        }

        return $model;
    }


    public static function getDummyViewModels($count = 1, $object_type = '')
    {
        $arr = [];
        $i = 0;
        for ($i = 0; $i < $count; $i++) {
            $arr[] = self::getDummyViewModel($object_type);
        }

        return $arr;
    }

    public static function getDummyViewModel($object_type = '')
    {
        return ViewModel::dummy();
    }

    public static function saveEditableData($object_type = '') {
        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $Id = Yii::$app->request->post('editableKey');

            if (empty($object_type))
                $object_type = FHtml::currentController();

            $model = FHtml::getModel($object_type, '', $Id);

            // store a default json response as desired by editable
            $out = Json::encode(['output' => '', 'message' => '']);

            // fetch the first entry in posted data (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $controllerPost = BaseInflector::camelize($object_type);
            $posted = current($_POST[$controllerPost]);
            $post[$controllerPost] = $posted;

            // load model like any single model validation
            if ($model->load($post)) {
                // can save model or do something before saving model
                $model->save();

                // custom output to return to be displayed as the editable grid cell
                // data. Normally this is empty - whereby whatever value is edited by
                // in the input by user is updated automatically.
                $output = '';
                // similarly you can check if the name attribute was posted as well
                // if (isset($posted['name'])) {
                //   $output =  ''; // process as you need
                // }
                $out = Json::encode(['output' => $output, 'message' => '']);
            }
            // return ajax json encoded response and exit
            //echo $out;
            return $out;
        }
    }

    public static function getOrderBy($model) {
        $sort = FHtml::getRequestParam(['sort', 'order_by', 'sort_by']);

        if (!empty($sort) && FHtml::field_exists($model, $sort))
            return $sort;

        $s = '';
        if (FHtml::field_exists($model, 'sort_order'))
            $s .= 'sort_order asc, ';
        if (FHtml::field_exists($model, 'id'))
            $s .= 'id desc, ';
        if (FHtml::field_exists($model, 'is_active'))
            $s .= 'is_active desc, ';
        if (FHtml::field_exists($model, 'is_hot'))
            $s .= 'is_hot desc, ';
        if (FHtml::field_exists($model, 'modified_date'))
            $s .= 'modified_date desc, ';
        if (FHtml::field_exists($model, 'created_date'))
            $s .= 'created_date desc, ';
        if (FHtml::field_exists($model, 'name'))
            $s .= 'name asc, ';
        return $s;
    }

    //The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"
    public static function parseAttribute($attribute) {
        if (is_string($attribute)) {
            if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
                throw new \yii\console\Exception('The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
            }
            $attribute = [
                'attribute' => $matches[1],
                'format' => isset($matches[3]) ? $matches[3] : 'raw',
                'label' => isset($matches[5]) ? $matches[5] : null,
            ];
        }

        return $attribute;
    }

    //2017/3/15
    public static function getFIELDS_GROUP() {

        $group = FSettings::FIELDS_GROUP;
        $modules = self::getModules();
        foreach ($modules as $moduleName) {
            $module = self::getModuleObject($moduleName);
            if (isset($module)) {
                if (defined($module::className() . '::FIELDS_GROUP')) {
                    $group = array_merge($group, $module::FIELDS_GROUP);
                }
            }
        }

        return $group;
    }

    public static function getModules () {
        return array_keys(\Yii::$app->getModules());
    }

    //2017/3/21: Return Counting for all values
    public static function getCounters($table, $column) {

        $arr = FHtml::getComboArray('', $table, $column);
        $result = [];
        foreach ($arr as $key => $value) {
            $result = array_merge($result, [$key => FHtml::countModels($table, [$column => $key])]);
        }

        return $result;
    }

    //2017/3/21: Count $model base on specific params
    public static function countModels($model, $params) {
        $query = self::buildQueryFromModel($model, $params);
        if (isset($query) && is_object($query))
            return $query->count();
        else
            return 0;
    }

    //2017/4/11
    public static function getModelFields($model, $excluded_fields = ['id']) {

        $fields = FHtml::getTableColumns($model);
        $result = [];
        foreach ($fields as $field) {
            if (!FHtml::isInArray($field->name, $excluded_fields))
                $result[] = $field->name;
        }
        return $result;
    }

    public static function is_numeric($value) {
        return is_numeric($value);
    }

    public static function is_timestamp($timestamp)
    {
        $check = (is_int($timestamp) OR is_float($timestamp))
            ? $timestamp
            : (string) (int) $timestamp;
        return  ($check === $timestamp)
            AND ( (int) $timestamp <=  PHP_INT_MAX)
            AND ( (int) $timestamp >= ~PHP_INT_MAX);
    }

    public static function getClassName($object, $full = true)
    {
        if (!is_object($object)) {
            return '';
        } else {
            $result = $object::className();
            if ($full)
                return $result;

            return StringHelper::basename($result);
        }
    }
}