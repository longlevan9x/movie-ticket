<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;

/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "settings_text".
 *

 * @property string $id
 * @property string $group
 * @property string $name
 * @property string $description
 * @property string $description_en
 * @property string $description_es
 * @property string $description_pt
 * @property string $description_de
 * @property string $description_fr
 * @property string $description_it
 * @property string $description_ko
 * @property string $description_ja
 * @property string $description_vi
 * @property string $description_zh
 */
class SettingsTextBase extends BaseModel //\yii\db\ActiveRecord
{

// id, group, name, description, description_en, description_es, description_pt, description_de, description_fr, description_it, description_ko, description_ja, description_vi, description_zh
    const COLUMN_ID = 'id';
    const COLUMN_GROUP = 'group';
    const COLUMN_NAME = 'name';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_DESCRIPTION_EN = 'description_en';
    const COLUMN_DESCRIPTION_ES = 'description_es';
    const COLUMN_DESCRIPTION_PT = 'description_pt';
    const COLUMN_DESCRIPTION_DE = 'description_de';
    const COLUMN_DESCRIPTION_FR = 'description_fr';
    const COLUMN_DESCRIPTION_IT = 'description_it';
    const COLUMN_DESCRIPTION_KO = 'description_ko';
    const COLUMN_DESCRIPTION_JA = 'description_ja';
    const COLUMN_DESCRIPTION_VI = 'description_vi';
    const COLUMN_DESCRIPTION_ZH = 'description_zh';

    /**
    * @inheritdoc
    */
    public $tableName = 'settings_text';

    public static function tableName()
    {
        return 'settings_text';
    }



    /**
     * @inheritdoc
     * @return SettingsTextQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsTextQuery(get_called_class());
    }
}
