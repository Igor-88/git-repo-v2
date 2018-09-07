<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\UserLog]].
 *
 * @see \app\models\UserLog
 */
class UserLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\UserLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\UserLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
