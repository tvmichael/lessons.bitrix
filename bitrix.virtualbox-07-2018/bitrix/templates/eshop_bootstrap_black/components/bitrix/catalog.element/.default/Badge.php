<?php
namespace Badge;

class Badge
{
    public $arResultB = null;

    public function setArResult(&$arr){
        $this->arResultB = $arr;
    }

    public function getId(){
        echo 'ID:'.$this->arResultB['ID'];
    }
}

$cl = new Badge();
$cl->setArResult($arResult);

$arResult['BADGE'] = $cl->getId();