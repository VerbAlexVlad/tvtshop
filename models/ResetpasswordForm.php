<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.08.2015
 * Time: 15:46
 */
namespace app\models;
use yii\base\Model;
use yii\base\InvalidParamException;
class ResetpasswordForm extends Model
{
    public $password;
    private $_user;
    public function rules()
    {
        return [
            ['password', 'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль'
        ];
    }
    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            throw new InvalidParamException('Ключ не может быть пустым.');
        $this->_user = (new User())::findBySecretKey($key);
        if(!$this->_user)
            throw new InvalidParamException('Не верный ключ.');
        parent::__construct($config);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function resetpassword(): bool
    {
        /* @var $user User */
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeSecretKey();
        return $user->save();
    }
}