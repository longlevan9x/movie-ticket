<?php

namespace backend\modules\app\models;

/**
 * This is the ActiveQuery class for [[AppUserDevice]].
 *
 * @see AppUserDevice
 */
class AppUserDeviceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AppUserDevice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppUserDevice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
