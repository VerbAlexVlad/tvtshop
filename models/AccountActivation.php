<?php
namespace app\models;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * @property-read string $username
 */
class AccountActivation extends Model
{
    private $_user;
    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            throw new InvalidArgumentException('Ключ не может быть пустым!');
        $this->_user = User::findBySecretKey($key);
        if(!$this->_user)
            throw new InvalidArgumentException('Не верный ключ!');
        parent::__construct($config);
    }
    public function activateAccount(): bool
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;

        $user->removeSecretKey();
        return $user->save();
    }
    public function getUsername(): string
    {
        $user = $this->_user;
        return $user->username;
    }
}