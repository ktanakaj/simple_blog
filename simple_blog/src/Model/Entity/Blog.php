<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Blog Entity
 *
 * @property int $id
 * @property string $title
 * @property string $mail_address
 * @property string $password
 * @property \Cake\I18n\Time $last_login
 *
 * @property \App\Model\Entity\Content[] $contents
 * @property \App\Model\Entity\Oauth[] $oauth
 */
class Blog extends Entity
{
    use LazyLoadEntityTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }
}
