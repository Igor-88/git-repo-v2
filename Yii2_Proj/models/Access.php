<?php

namespace app\models;

use app\models\query\AccessQuery;

/**
 * This is the model class for table "access".
 *
 * @property int $id
 * @property int $note_id
 * @property int $user_id
 * @property string $since
 */
class Access extends \yii\db\ActiveRecord
{
    public const LEVEL_DENIED = 0;
    public const LEVEL_VIEW = 1;
    public const LEVEL_EDIT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['note_id', 'user_id'], 'required'],
            ['since', 'date', 'format' => 'Y-m-d'],
            [['note_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'note_id' => 'Note ID',
            'user_id' => 'User ID',
            'since' => 'Доступно с',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\AccessQuery the active query used by this AR class.
     */
    public static function find(): AccessQuery
    {
        return new AccessQuery(__CLASS__);
    }
}
