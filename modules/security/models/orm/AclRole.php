<?php
namespace app\modules\security\models\orm;

use app\common\acl\role\RoleInterface;
use app\common\db\ActiveRecord;
use yii\db\ActiveQueryInterface;

/**
 * Class AccessRole
 * @package Module\Security
 * @copyright 2012-2019 Medkey
 */
class AclRole extends ActiveRecord implements RoleInterface
{
    /**
     * @inheritdoc
     */
    public function getRoleId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public static function modelIdentity()
    {
        return ['name'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ 'name', 'unique', 'filter' => function (ActiveQueryInterface $query) {
                return $query
                    ->notDeleted();
            }, ],
            [ 'short_name', 'unique', 'filter' => function (ActiveQueryInterface $query) {
                return $query
                    ->notDeleted();
            }, ],
            [
                ['name', 'short_name', 'description'],
                'string',
                'on' => [
                    ActiveRecord::SCENARIO_CREATE,
                    ActiveRecord::SCENARIO_UPDATE
                ]
            ]
        ];
    }

    public function attributeLabelsOverride()
    {
        return [
            'name' => 'Наименование роли',
            'short_name' => 'Короткое наименование',
            'description' => 'Описание',
        ];
    }
}
