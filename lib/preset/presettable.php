<?php

namespace Gtd\VueEditor\Preset;


use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ArrayField;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

class PresetTable extends DataManager{

    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configureAutocomplete()
                ->configurePrimary(),
            new StringField('TITLE'),
            new StringField('BLOCK'),
            new TextField('DATA'),
            new DatetimeField('CREATED'),
            new IntegerField('USER_ID'),
            new BooleanField('SHARE', ['values' => ['N', 'Y']]),
            (new Reference(
                'USER',
                UserTable::class,
                Join::on('this.USER_ID', 'ref.ID')
            ))->configureJoinType('inner')
        ];
    }

    public static function getTableName()
    {
        return 'gtd_vueeditor_preset_block';
    }
}