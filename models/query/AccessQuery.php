<?php

namespace app\models\query;

use app\models\Note;

/**
 * This is the ActiveQuery class for [[\app\models\Access]].
 *
 * @see \app\models\Access
 */
class AccessQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\Access[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Access|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param Note $note
     * @return self
     */
    public function forNote(Note $note): self
    {
        $this->andWhere(['note_id' => $note->id]);

        return $this;
    }

    /**
     * @param int $userId
     * @return self
     */
    public function forUserId(int $userId): self
    {
        $this->andWhere(['user_id' => $userId]);

        return $this;
    }

    /**
     * @return self
     */
    public function forCurrentDate(): self
    {
        $date = date('Y-m-d');
        $this->andWhere([
            'or',
            ['<=', 'since', $date],
            ['since' => null],
        ]);

        return $this;
    }
}
