<?php

namespace backend\modules\app\models;

/**
 * This is the ActiveQuery class for [[AppUserPro]].
 *
 * @see AppUserPro
 */
class AppUserProQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AppUserPro[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppUserPro|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
