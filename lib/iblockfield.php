<?php
namespace Gtd\VueEditor;

use Bitrix\Iblock\PropertyTable;


//@todo отрефачить класс , добавить шаблоны и тд

/**
 * Class IBlockField
 * @package Gtd\VueEditor
 */
class IBlockField {

    const USER_TYPE = 'BlockEditor';

    public function GetUserTypeDescription(){
        return [
            "PROPERTY_TYPE" => PropertyTable::TYPE_STRING,
            "USER_TYPE" => self::USER_TYPE,
            "DESCRIPTION" => "Блочный редактор",
            "GetPublicViewHTML" => array(__CLASS__, "GetPublicViewHTML"),
            "GetPublicEditHTML" => array(__CLASS__, "GetPublicEditHTML"),
            "GetAdminListViewHTML" => array(__CLASS__, "GetAdminListViewHTML"),
            "GetPropertyFieldHtml" => array(__CLASS__, "GetPropertyFieldHtml"),
            "ConvertToDB" => array(__CLASS__, "ConvertToDB"),
            "ConvertFromDB" => array(__CLASS__, "ConvertFromDB"),
            "GetLength" =>array(__CLASS__, "GetLength"),
            "PrepareSettings" =>array(__CLASS__, "PrepareSettings"),
            "GetUIFilterProperty" => array(__CLASS__, "GetUIFilterProperty"),
            "GetSettingsHTML" => array(__CLASS__, "GetSettingsHTML"),
        ];
    }

    public static function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
        if (!is_array($value["VALUE"]))
            $value = static::ConvertFromDB($arProperty, $value);
        $ar = $value["VALUE"];
        if (!empty($ar) && is_array($ar))
        {
            if (isset($strHTMLControlName['MODE']) && $strHTMLControlName['MODE'] == 'CSV_EXPORT')
                return '['.$ar["TYPE"].']'.$ar["TEXT"];
            elseif (isset($strHTMLControlName['MODE']) && $strHTMLControlName['MODE'] == 'SIMPLE_TEXT')
                return ($ar["TYPE"] == 'HTML' ? strip_tags($ar["TEXT"]) : $ar["TEXT"]);
            else
                return FormatText($ar["TEXT"], $ar["TYPE"]);
        }

        return '';
    }

    public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        if(!is_array($value["VALUE"]))
            $value = static::ConvertFromDB($arProperty, $value);
        $ar = $value["VALUE"];
        if($ar)
            return htmlspecialcharsEx($ar["TYPE"].":".$ar["TEXT"]);
        else
            return "&nbsp;";
    }

    public static function GetPublicEditHTML($arProperty, $value, $strHTMLControlName)
    {
        ob_start();
        echo 'я кастомное свойство!';
        $s = ob_get_contents();
        ob_end_clean();
        return  $s;
    }

    public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields) {
        $arPropertyFields = array(
            "HIDE" => array("FILTRABLE", "ROW_COUNT", "COL_COUNT", "DEFAULT_VALUE"), //will hide the field
            "SET" => array("FILTRABLE" => "N"), //if set then hidden field will get this value
            "USER_TYPE_SETTINGS_TITLE" => "Доступные блоки"
        );
        $configFinder = new \Gtd\VueEditor\Block\Finder();
        $blockConfig = $configFinder->find();
        $option = "";
        foreach ($blockConfig as $config){
            $selected = in_array($config->getType(), $arProperty['USER_TYPE_SETTINGS']['ALLOW_BLOCK']) ? "selected" : "";
            $option .= "<option ".$selected." value='".$config->getType()."'>".$config->getTitle()."</option>";
        }
        return '
        <tr>
            <td>Доступные блоки:</td>
            <td>
                <select size="10" multiple="multiple" name="'.$strHTMLControlName["NAME"].'[ALLOW_BLOCK][]">'.$option.'</select>
            </td>
        </tr>';
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        global $APPLICATION;
        $editor = new Editor();
        $editor->setPropertyId($arProperty['ID']);
        if($value['VALUE']){
            $editor->setValue($value['VALUE']);
        }
        if(!empty($arProperty['USER_TYPE_SETTINGS']['ALLOW_BLOCK'])){
            $editor->setAllowBlocks($arProperty['USER_TYPE_SETTINGS']['ALLOW_BLOCK']);
        }
        $editor
            ->setInputName($strHTMLControlName["VALUE"])
            ->initEditor();
    }

    public static function ConvertToDB($arProperty, $value)
    {
        $return['VALUE'] = $value['VALUE'];
        $return['DESCRIPTION'] = "";
        return $return;
    }


    public static function ConvertFromDB($arProperty, $value)
    {
        return $value;
    }

    /**
     * Check value.
     *
     * @param bool|array $arFields			Current value.
     * @param bool $defaultValue			Is default value.
     * @return array|bool
     */
    public static function CheckArray($arFields = false, $defaultValue = false)
    {
        $defaultValue = ($defaultValue === true);
        if (!is_array($arFields))
        {
            $return = false;
            if (CheckSerializedData($arFields))
                $return = unserialize($arFields);
        }
        else
        {
            $return = $arFields;
        }

        if ($return)
        {
            if (is_set($return, "TEXT") && ((strlen(trim($return["TEXT"])) > 0) || $defaultValue))
            {
                $return["TYPE"] = strtoupper($return["TYPE"]);
                if (($return["TYPE"] != "TEXT") && ($return["TYPE"] != "HTML"))
                    $return["TYPE"] = "HTML";
            }
            else
            {
                $return = false;
            }
        }
        return $return;
    }

    public static function GetLength($arProperty, $value)
    {
        if(is_array($value) && isset($value["VALUE"]["TEXT"]))
            return strlen(trim($value["VALUE"]["TEXT"]));
        else
            return 0;
    }

    public static function PrepareSettings($arProperty)
    {
//        $settings = $arProperty['USER_TYPE_SETTINGS'];
//        $newsettings = array();
//
//        foreach (array('DISABLE_CHANGE', 'ENABLE_SORT_BUTTONS','SETTINGS_NAME') as $val){
//            $newsettings[$val] = !empty($settings[$val]) ? $settings[$val] : '';
//        }
//
//        return array('USER_TYPE_SETTINGS' => $newsettings);
        return  $arProperty['USER_TYPE_SETTINGS'];
    }


    /**
     * @param array $property
     * @param array $strHTMLControlName
     * @param array &$fields
     * @return void
     */
    public static function GetUIFilterProperty($property, $strHTMLControlName, &$fields)
    {
        $fields["type"] = "string";
        $fields["operators"] = array(
            "default" => "%"
        );
        $fields["filterable"] = "?";
    }

    protected static function getValueFromString($value, $getFull = false)
    {
        $getFull = ($getFull === true);
        $valueType = 'HTML';
        $value = (string)$value;
        if ($value !== '')
        {
            $prefix = strtoupper(substr($value, 0, 6));
            $isText = $prefix == '[TEXT]';
            if ($prefix == '[HTML]' || $isText)
            {
                if ($isText)
                    $valueType = 'TEXT';
                $value = substr($value, 6);
            }
        }
        if ($getFull)
        {
            return array(
                'VALUE' => array(
                    'TEXT' => $value,
                    'TYPE' => $valueType
                )
            );
        }
        else
        {
            return array(
                'TEXT' => $value,
                'TYPE' => $valueType
            );
        }
    }
}