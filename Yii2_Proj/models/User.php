<?php
namespace app\models;

use app\models\query\UserQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $surname
 * @property string $name
 * @property string $password
 * @property string $salt
 * @property string $access_token
 * @property string $authKey
 * @property string $create_date
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Validate constant
     */
    const MIN_LENGTH_PASS = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public static function find()
    {
        return new UserQuery(__CLASS__);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'surname', 'name', 'password', 'salt'], 'required'],
            [['create_date'], 'safe'],
            [['username'], 'string', 'max' => 128],
            [['surname', 'name'], 'string', 'max' => 45],
            [['password', 'salt', 'access_token'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['access_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'password' => 'Пароль',
            'salt' => 'Соль',
            'access_token' => 'Ключ авторизации',
            'create_date' => 'Дата создания',
        ];
    }

    /**
     * Before save event handler
     *
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->getIsNewRecord() && !empty($this->password)) {
                $this->salt = $this->saltGenerator();
            }
            if (!empty($this->password)) {
                $this->password = $this->passWithSalt($this->password, $this->salt);
            } else {
                unset($this->password);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generate the salt
     *
     * @return string
     */
    public function saltGenerator()
    {
        return hash("sha512", uniqid('salt_', true));
    }

    /**
     * Return pass with the salt
     *
     * @param $password
     * @param $salt
     *
     * @return string
     */
    public function passWithSalt($password, $salt)
    {
        return hash("sha512", $password . $salt);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $this->passWithSalt($password, $this->salt);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $this->passWithSalt($password, $this->saltGenerator());
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }
}
