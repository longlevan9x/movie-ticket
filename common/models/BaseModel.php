<?php

namespace common\models;

use backend\models\ObjectCategory;
use backend\models\ObjectTranslation;
use common\components\FActiveQuery;
use common\components\FHtml;
use common\components\FSecurity;
use frontend\models\ViewModel;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class BaseModel extends ActiveRecord
{
    const COLUMNS = [
    ];

    public $id_array;
    private $category_id_array;
    public $objectAttributes;
    public $objectFile;
    public $objectCategories;
    public $tableName;
    public $model_object_type;
    public $order_by;
    public $objectFile_upload;
    public $columnsMode = '';
    public $columns = [];
    public $extraFields = [];
    public $oldContent = [];
    public $changedContent = [];

    public static function getLookupArray($column)
    {
        return [];
    }

    public static function getRelatedObjects()
    {
        return [];
    }

    public static function getMetaObjects()
    {
        return [];
    }

    public function addExtraField($field)
    {
        $this->extraFields[] = $field;
    }

    public function setFields($fields)
    {
        if (is_string($fields))
            $fields = FHtml::decode($fields);
        $result = [];
        foreach ($fields as $field) {
            if (FHtml::field_exists($this, $field))
                $result[] = $field;
        }
        $this->columnsMode = $result;
    }

    public function fields()
    {
        if (is_array($this->columnsMode) && !empty($this->columnsMode))
            return $this->columnsMode;

        $fields = parent::fields();
        if (!empty($this->extraFields))
            $fields = array_merge($fields, $this->extraFields);

        return $fields;
    }

    public function prepareCustomFields()
    {
        $this->objectAttributes = $this->getObjectAttributes();
        $this->objectFile = $this->getObjectFile();
        $this->objectCategories = $this->getObjectCategories();
    }

    public function getObjectAttributes()
    {
        if (empty($this->objectAttributes)) {
            $this->objectAttributes = FHtml::getModels('object_attributes', ['object_type' => $this->getObjectType(), 'object_id' => self::primaryKeyValue()]);
        }
        return $this->objectAttributes;
    }

    public function setObjectAttributes($value)
    {
        $this->objectAttributes = $value;
    }

    public function getObjectType()
    {
        if (!empty($this->model_object_type))
            return $this->model_object_type;
        else
            return self::getTableName();
    }

    /**
     * Declares the name of the database table associated with this AR class.
     * By default this method returns the class name as the table name by calling [[Inflector::camel2id()]]
     * with prefix [[Connection::tablePrefix]]. For example if [[Connection::tablePrefix]] is 'tbl_',
     * 'Customer' becomes 'tbl_customer', and 'OrderItem' becomes 'tbl_order_item'. You may override this method
     * if the table is not named after this convention.
     * @return string the table name
     */
    public static function tableName()
    {
        return Inflector::camel2id(StringHelper::basename(get_called_class()), '_');
    }

    public function getTableName()
    {
        if (!empty($this->tableName))
            return $this->tableName;
        else {
            $this->tableName = self::tableName();
            return $this->tableName;
        }
    }

    public function primaryKeyValue()
    {
        return FHtml::getFieldValue($this, self::primaryKey());
    }

    public function getObjectFile()
    {
        if (empty($this->objectFile)) {
            $this->objectFile = FHtml::getModels('object_file', ['object_type' => $this->getTableName(), 'object_id' => self::primaryKeyValue()]);
        }
        return $this->objectFile;
    }

    public function getRelatedModels($object_type, $relation_type = FHtml::RELATION_FOREIGN_KEY)
    {
        return FHtml::getRelatedModels($this->getTableName(), self::primaryKeyValue(), $object_type, $relation_type);
    }

    public function getColumns()
    {
        if (empty($this->columns)) {
            $this->columns = FHtml::getObjectColumns($this->getTableName());
        }
        return $this->columns;
    }

    public function getColumn($columnName)
    {
        $columns = $this->getColumns();
        if (!isset($columns) || empty($columns))
            return null;

        foreach ($columns as $column) {
            if (strtolower($column->name) == strtolower($columnName))
                return $column;
        }
        return null;
    }

    public function setObjectFile($value)
    {
        $this->objectFile = $value;
    }

    public function getObjectCategories()
    {
        return FHtml::getCategoriesList($this->getObjectType());
    }

    public function toBaseModel()
    {
        return self::toViewModel();
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        $model->name = FHtml::getFieldValue($this, ['name', 'title']);
        $model->overview = FHtml::getFieldValue($this, ['overview', 'description']);
        $model->content = FHtml::getFieldValue($this, ['content', 'text', 'comment']);
        $model->image = FHtml::getFieldValue($this, ['image', 'file', 'thumbnail', 'icon', 'avatar']);
        $model->is_active = FHtml::getFieldValue($this, ['is_active', 'active', 'isActive', 'status']);
        $model->is_hot = FHtml::getFieldValue($this, ['is_hot', 'is_popular', 'isHot']);
        $model->is_top = FHtml::getFieldValue($this, ['is_top', 'is_featured', 'isTop']);
        $model->is_featured = FHtml::getFieldValue($this, ['is_featured', 'is_top', 'isTop']);
        $model->category_id = trim(FHtml::getFieldValue($this, ['category_id', 'categoryid']), ',');
        $model->type = FHtml::getFieldValue($this, ['type']);
        $model->status = FHtml::getFieldValue($this, ['status']);
        $model->linkurl = FHtml::getFieldValue($this, ['linkurl']);
        $model->id = FHtml::getFieldValue($this, ['id', 'product_id']);
        $model->price = FHtml::getFieldValue($this, ['price', 'cost']);
        $model->created_date = FHtml::getFieldValue($this, ['created_date', 'created_at', 'createdDate', 'date_created']);
        $model->created_user = FHtml::getFieldValue($this, ['created_user', 'created_by', 'created_userid', 'createduser']);

        $model->tablename = Inflector::id2camel(StringHelper::basename(self::className()));
        return $model;
    }

    public function getCategory()
    {
        $arr = self::getCategories();
        if (count($arr) > 0)
            return $arr[0];
        return new ObjectCategory();
    }

    public function getCategories()
    {
        if (!isset($this->objectCategories)) {
            $category_id = FHtml::getFieldValue($this, ['category_id', 'categoryid']);
            if (!empty($category_id))
                $this->objectCategories = FHtml::getCategories($category_id);
            else
                $this->objectCategories = FHtml::getRelatedModels(self::getTableName(), $category_id, FHtml::TABLE_CATEGORIES);
        }
        return $this->objectCategories;
    }

    public function getGalleries()
    {
        return FHtml::getGalleries(self::tableName(), $this->id);
    }

    public function getCategory_id_array()
    {
        if (isset($this['category_id']) && empty($this->category_id_array))
            $this->category_id_array =  explode(',', trim($this['category_id'], ','));

        return $this->category_id_array;
    }

    public function setCategory_id_array($value)
    {
        $this->category_id_array = $value;
    }

    public function setSortOrder($value)
    {
        if (FHtml::field_exists($this, 'sort_order'))
            FHtml::setFieldValue($this, 'sort_order', $value);
    }

    public function getOrderBy()
    {
        if (!empty($this->order_by))
            return $this->order_by;

        return FHtml::getOrderBy($this);
    }

    public function beforeSave($insert)
    {
//        var_dump($_REQUEST['id']);
//        echo $this->id;echo $this->tableName; echo $this->name; die;
        FHtml::saveUploadedFiles($this);

        if ($insert)
            FHtml::prepareDefaultValues($this, ['category_id_array', 'is_active', 'category_id', 'created_user', 'created_date', 'application_id'], FHtml::ACTION_ADD);
        else
            FHtml::prepareDefaultValues($this, ['category_id_array', 'category_id', 'modified_user', 'modified_date'], FHtml::ACTION_SAVE);

        $result = parent::beforeSave($insert); // TODO: Change the autogenerated stub

        if ($result) {
            $lang = FHtml::currentLang();
            // Save languages data
            if (!$insert && $lang != DEFAULT_LANG && FHtml::isLanguagesEnabled($this->tableName)) {
                $translated = ObjectTranslation::findOne(['lang' => $lang, 'object_id' => $this->id, 'object_type' => $this->tableName, 'application_id' => FHtml::currentApplicationCode()]);
                if (!isset($translated))
                    $translated = new ObjectTranslation();
                $translated->object_type = $this->tableName;
                $translated->object_id = $this->id;
                $translated->application_id = FHtml::currentApplicationCode();
                $translated->lang = $lang;
                $translated->content = FHtml::encode($this->getAttributes());
                $translated->save();
                if (FHtml::isAjaxRequest()) {
                    die; // important, do not delete.
                }
                else
                    return false; // already save translated data into Object_Translation table, no need to save in real table -> return false;
            }

            // If Enabled Object Changes Log - Prepare Change data
            if (FHtml::isObjectActionsLogEnabled($this->tableName)) {
                foreach ($this->oldAttributes as $key => $value) {
                    $this->oldContent = array_merge($this->oldContent, [$key => $value]);
                }
            }

        } else {

        }
        return $result;
    }

    public function afterSave($insert, $changedAttributes)
    {
        $id = $this->primaryKeyValue();

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub

        FHtml::saveObjectItems($this, $this->getTableName(), $id, $this::getRelatedObjects());

        // If Enabled Object Changes Log - Log data changed into Object_actions table
        if (FHtml::isObjectActionsLogEnabled($this->tableName)) {
            FHtml::logObjectActions($this, $insert ? FHtml::ACTION_CREATE : FHtml::ACTION_EDIT, $this->oldContent, $this->attributes);
        }

        //Show success/ error message
        if (!empty($this->getErrors()))
            FHtml::addError($this->getErrors());
        else {
            //Show success/ error message
            $object = FHtml::t('common', BaseInflector::camel2words(self::getTableName()));
            $object_title = FHtml::getFieldValue($this, ['name', 'title']);
            $object_id = FHtml::getFieldValue($this, ['id']);

            if (FHtml::isObjectActionsLogEnabled($this->tableName)) {
                $message = FHtml::t('common', 'Saved successfully');
                FHtml::addMessage("$message. $object #$object_id - $object_title");
            }

            FHtml::refreshCache();
        }
    }

    public function afterValidate()
    {
        parent::afterValidate(); // TODO: Change the autogenerated stub
    }

    public
    function afterDelete()
    {
        // If Enabled Object Changes Log - Log data changed into Object_actions table
        if (FHtml::isObjectActionsLogEnabled($this->tableName)) {
            FHtml::logObjectActions($this, FHtml::ACTION_DELETE, $this->attributes, [], 'Deleted successful !');
        }

        parent::afterDelete(); // TODO: Change the autogenerated stub
        FHtml::refreshCache();
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public
    static function find()
    {
        $query = \Yii::createObject(FActiveQuery::className(), [get_called_class()]);
        if (FHtml::isApplicationsEnabled(static::tableName())) {
            $query = $query->where(['application_id' => FHtml::currentApplicationCode()]);
        }
        return $query;
    }

//2017/6/23
    public
    static function findOne($condition, $applications_enabled = true)
    {
        if ($applications_enabled && FHtml::isApplicationsEnabled() && FHtml::field_exists(self::tableName(), 'application_id')) {
            if (is_array($condition)) {
                if (FHtml::isInArray(self::tableName(), FHtml::EXCLUDED_TABLES_AS_APPLICATIONS))
                    $condition = array_merge($condition, ['OR', ['application_id' => FHtml::currentApplicationCode()], ['application_id' => FHtml::APPLICATION_NONE]]);
                else
                    $condition = array_merge($condition, ['application_id' => FHtml::currentApplicationCode()]);

            } else if (is_string($condition) && !is_numeric($condition)) {
                $application_id = FHtml::currentApplicationCode();
                $application_id_none = FHtml::APPLICATION_NONE;
                if (FHtml::isInArray(self::tableName(), FHtml::EXCLUDED_TABLES_AS_APPLICATIONS))
                    $condition = $condition . " and (application_id = '$application_id' or application_id = '$application_id_none')";
                else
                    $condition = $condition . " and (application_id = '$application_id')";
            }
        }

        $result = parent::findOne($condition);

        if (isset($result))
            return FHtml::getTranslatedModel($result);

        return null;
    }

    public
    static function getOne($condition, $applications_enabled = true)
    {
        $model = self::findOne($condition, $applications_enabled);
        if (!isset($model))
            return $model;

        $items = self::findAll($condition);
        if (count($items) > 0) {
            foreach ($items as $key => $result) {
                return FHtml::getTranslatedModel($result); // return first item
            }
        } else
            return null;

        return null;
    }

    public
    static function findAll($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        if (is_numeric($condition) && !empty($condition)) { // findOne
            return self::findOne($condition);
        }

        return FHtml::getModels(static::tableName(), $condition, $order_by, $page_size, $page, $isCached, $load_activeonly, $display_fields);
    }

    public
    static function findLimit($limit = -1, $condition = [], $order_by = [], $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        return FHtml::getModels(static::tableName(), $condition, $order_by, $limit, $page, $isCached, $load_activeonly, $display_fields);
    }

    public
    static function findHot($limit = -1, $condition = [], $order_by = [], $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        if (is_array($condition) && !key_exists('is_hot', $condition) && FHtml::field_exists(static::tableName(), 'is_hot'))
            $condition = array_merge($condition, ['is_hot' => 1]);
        return FHtml::getModels(static::tableName(), $condition, $order_by, $limit, $page, $isCached, $load_activeonly, $display_fields);
    }

    public
    static function findTop($limit = -1, $condition = [], $order_by = [], $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        if (is_array($condition) && !key_exists('is_top', $condition) && FHtml::field_exists(static::tableName(), 'is_top'))
            $condition = array_merge($condition, ['is_top' => 1]);
        return FHtml::getModels(static::tableName(), $condition, $order_by, $limit, $page, $isCached, $load_activeonly, $display_fields);
    }

    public
    static function getDataProvider($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
       return FHtml::getPageModelsList(static::tableName(), $condition, $order_by, $page_size, $page);
    }

    public
    static function findAllForAPI($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $folder = '', $display_fields = [], $load_activeonly = true)
    {
        return FHtml::getModelsForAPI(static::tableName(), $condition, $order_by, $page_size, $page, $isCached, $folder, $display_fields);
    }

    public
    static function findViewModelsForAPI($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $folder = '', $display_fields = [], $load_activeonly = true)
    {
        return FHtml::getViewModelsForAPI(static::tableName(), $condition, $order_by, $page_size, $page, $isCached, $folder, $display_fields);
    }

    public
    static function findViewModels($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        return FHtml::getViewModels(static::tableName(), $condition, $order_by, $page_size, $page, $isCached, $display_fields);
    }

    public
    static function findByCategory($categories = 0, $order_by = [], $page_size = -1, $page = 1, $field = 'category_id')
    {
        $list = [];
        if (is_string($categories)) {
            if (strpos($categories, ',') !== false)
                $list = explode(',', $categories);
            else
                $list[] = $categories;
        } else if (is_array($categories))
            $list = $categories;
        $result = [];
        if (!empty($list)) {
            foreach ($list as $listItem) {
                $result = array_merge($result, self::findAll([$field => $listItem], $order_by, $page_size, $page));
            }
        }
        return $result;
    }

    public
    function inActive()
    {
        FHtml::setFieldValue($this, ['is_active', 'isactive'], 0);
        FHtml::setFieldValue($this, ['is_deleted', 'deleted'], 1);

        $this->save();
    }

    public
    function disable()
    {
        self::inActive();
    }

    public
    function active()
    {
        FHtml::setFieldValue($this, ['is_active', 'isactive'], 1);
        FHtml::setFieldValue($this, ['is_deleted', 'deleted'], 0);

        $this->save();
    }

    public
    function enable()
    {
        self::active();
    }

    public
    function softDelete()
    {
        self::disable();
    }

    public
    function restoreDelete()
    {
        self::enable();
    }

    public
    function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        FHtml::loadParams($this, $data);
        return $result;
    }

    public
    static function create($data = [])
    {
        $model = FHtml::createModel(self::tableName());
        FHtml::loadParams($model, $data);
        return $model;
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public
    static function getDb()
    {
        return FHtml::currentDb();
    }

    public
    static function deleteAll($condition = '', $params = [])
    {
        $model = FHtml::createModel(self::tableName());
        if (FHtml::field_exists($model, 'application_id')) {
            $application_id = FHtml::currentApplicationCode();
            if (is_string($condition))
                $condition .= (!empty($condition) ? ' AND ' : '') . "application_id = '$application_id'";
            else
                $condition = array_merge($condition, ['application_id' => $application_id]);
        }
        return parent::deleteAll($condition, $params); // TODO: Change the autogenerated stub
    }

    public
    static function createNew($values = [])
    {
        $model = FHtml::createModel(self::tableName());
        foreach ($values as $field => $value) {
            FHtml::setFieldValue($model, $field, $value);
        }
        FHtml::setFieldValue($model, 'application_id', FHtml::currentApplicationCode());
        if ($model->save())
            return $model;
        else
            return $model->errors;
    }

    public
    static function createOrUpdate($condition, $values = [], $overrideIfExisted = true)
    {
        $model = self::findOne($condition);
        if (!isset($model)) {
            $model = FHtml::createModel(self::tableName());
            $overrideIfExisted = true;
        }
        if ($overrideIfExisted) {
            if (is_array($condition)) {
                foreach ($condition as $field => $value) {
                    FHtml::setFieldValue($model, $field, $value);
                }
            }
            foreach ($values as $field => $value) {
                FHtml::setFieldValue($model, $field, $value);
            }
            FHtml::setFieldValue($model, 'is_active', 1);
            FHtml::setFieldValue($model, 'application_id', FHtml::currentApplicationCode());

            if ($model->save())
                return $model;
            else
                return $model->errors;
        }
        return false;
    }

    public function getTranslatedModel()
    {
        return $this->hasOne(ObjectTranslation::className(), ['object_id' => 'id'])
            ->andOnCondition(['AND',
                ['object_type' => $this->tableName],
                ['lang' => FHtml::currentLang()]
            ]);
    }
}
