<?php

namespace app\models;

use app\models\query\NoteQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "note".
 *
 * @property int $id
 * @property string $text
 * @property int $creator
 * @property string $date_create
 *
 * @property User $author
 * @property Access[] $access
 */
class Note extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
            [['creator'], 'integer'],
            [['date_create'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'creator' => 'Creator',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\NoteQuery the active query used by this AR class.
     */
    public static function find(): NoteQuery
    {
        return new NoteQuery(static::class);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'creator']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAccess(): ActiveQuery
    {
        return $this->hasMany(Access::class, ['note_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);

        if (!$this->creator) {
            $this->creator = \Yii::$app->user->id;
        }
        if (!$this->date_create) {
            $this->date_create = \date('Y-m-d H:i:s');
        }

        return $result;
    }

}
