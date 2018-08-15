<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CBadgesCheck extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,
            "TEMPLATE_FOR_BADGE" => intval($arParams["TEMPLATE_FOR_BADGE"]),
            "TEMPLATE_FOR_ARRESULT" => $arParams["TEMPLATE_FOR_ARRESULT"],
        );
        return $result;
    }

    public function sqr($x)
    {
        return $x * $x;
    }

    public function executeComponent(){
        if($this->startResultCache())
        {
            $this->arResult["Y"] = $this->sqr($this->arParams["X"]);
            $this->arResult['SOME_VAR'] = $this->arParams;
            $this->includeComponentTemplate();
        }
        return $this->arResult["Y"];
    }
}?>