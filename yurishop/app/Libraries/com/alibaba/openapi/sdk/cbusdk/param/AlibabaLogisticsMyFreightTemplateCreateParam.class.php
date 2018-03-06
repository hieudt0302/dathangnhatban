<?php

include_once ('app/Libraries/com/alibaba/openapi/client/entity/SDKDomain.class.php');
include_once ('app/Libraries/com/alibaba/openapi/client/entity/ByteArray.class.php');
include_once ('app/Libraries/com/alibaba/openapi/sdk/cbusdk/param/AlibabaopenplatformlogisticsDeliveryTemplateDTO.class.php');
include_once ('app/Libraries/com/alibaba/openapi/sdk/cbusdk/param/AlibabaopenplatformlogisticsDeliverySubTemplateDTO.class.php');
include_once ('app/Libraries/com/alibaba/openapi/sdk/cbusdk/param/AlibabaopenplatformlogisticsDeliveryRateDetailDTO.class.php');
include_once ('app/Libraries/com/alibaba/openapi/sdk/cbusdk/param/AlibabaopenplatformlogisticsDeliverySubTemplateDTO.class.php');
include_once ('app/Libraries/com/alibaba/openapi/sdk/cbusdk/param/AlibabaopenplatformlogisticsDeliveryRateDetailDTO.class.php');

class AlibabaLogisticsMyFreightTemplateCreateParam {

        
        /**
    * @return 主运费模板，必填。必填字段：name（模板名称），remark（备注），fromAreaCode（发货区编码），addressCodeText（发货区编码对应文本，以空格分割）
    */
        public function getMainTemplate() {
        $tempResult = $this->sdkStdResult["mainTemplate"];
        return $tempResult;
    }
    
    /**
     * 设置主运费模板，必填。必填字段：name（模板名称），remark（备注），fromAreaCode（发货区编码），addressCodeText（发货区编码对应文本，以空格分割）     
     * @param AlibabaopenplatformlogisticsDeliveryTemplateDTO $mainTemplate     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setMainTemplate(AlibabaopenplatformlogisticsDeliveryTemplateDTO $mainTemplate) {
        $this->sdkStdResult["mainTemplate"] = $mainTemplate;
    }
    
        
        /**
    * @return 快递子模板基本信息（必填）。必填字段：chargeType（1:按重量计价，1-按件数，2-按体积），serviceChargeType（0-卖家承担运费，1-买家承担运费）
    */
        public function getExpressSubTemplate() {
        $tempResult = $this->sdkStdResult["expressSubTemplate"];
        return $tempResult;
    }
    
    /**
     * 设置快递子模板基本信息（必填）。必填字段：chargeType（1:按重量计价，1-按件数，2-按体积），serviceChargeType（0-卖家承担运费，1-买家承担运费）     
     * @param AlibabaopenplatformlogisticsDeliverySubTemplateDTO $expressSubTemplate     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setExpressSubTemplate(AlibabaopenplatformlogisticsDeliverySubTemplateDTO $expressSubTemplate) {
        $this->sdkStdResult["expressSubTemplate"] = $expressSubTemplate;
    }
    
        
        /**
    * @return 快递子模板的费率设置（必填）。第一个设置针对全国的费率，后面的看情况针对个别省份。必填字段见示例。
    */
        public function getExpressSubRateList() {
        $tempResult = $this->sdkStdResult["expressSubRateList"];
        return $tempResult;
    }
    
    /**
     * 设置快递子模板的费率设置（必填）。第一个设置针对全国的费率，后面的看情况针对个别省份。必填字段见示例。     
     * @param array include @see AlibabaopenplatformlogisticsDeliveryRateDetailDTO[] $expressSubRateList     
     * 参数示例：<pre>[{ "toAreaCodeText": "全国", "rateDTO": { "firstUnit": 10, "firstUnitFee": 20, "nextUnit": 15, "nextUnitFee": 25, "toAreaCodeList": [ "1" ] } }, { "toAreaCodeText": "上海、福建省、广东省", "rateDTO": { "firstUnit": 10, "firstUnitFee": 20, "nextUnit": 15, "nextUnitFee": 25, "toAreaCodeList": [ "310000", "350000", "440000" ] } }]</pre>     
     * 此参数必填     */
    public function setExpressSubRateList(AlibabaopenplatformlogisticsDeliveryRateDetailDTO $expressSubRateList) {
        $this->sdkStdResult["expressSubRateList"] = $expressSubRateList;
    }
    
        
        /**
    * @return 货到付款子模板基本信息（可不填）。若需要则必填字段：chargeType（1:按重量计价，1-按件数，2-按体积），serviceChargeType（0-卖家承担运费，1-买家承担运费）
    */
        public function getCashSubTemplate() {
        $tempResult = $this->sdkStdResult["cashSubTemplate"];
        return $tempResult;
    }
    
    /**
     * 设置货到付款子模板基本信息（可不填）。若需要则必填字段：chargeType（1:按重量计价，1-按件数，2-按体积），serviceChargeType（0-卖家承担运费，1-买家承担运费）     
     * @param AlibabaopenplatformlogisticsDeliverySubTemplateDTO $cashSubTemplate     
     * 参数示例：<pre></pre>     
     * 此参数必填     */
    public function setCashSubTemplate(AlibabaopenplatformlogisticsDeliverySubTemplateDTO $cashSubTemplate) {
        $this->sdkStdResult["cashSubTemplate"] = $cashSubTemplate;
    }
    
        
        /**
    * @return 货到付款子模板的费率设置（若cashSubTemplate为空，则此字段亦无效）。第一个设置针对全国的费率，后面的看情况针对个别省份。必填字段见示例。
    */
        public function getCashSubRateList() {
        $tempResult = $this->sdkStdResult["cashSubRateList"];
        return $tempResult;
    }
    
    /**
     * 设置货到付款子模板的费率设置（若cashSubTemplate为空，则此字段亦无效）。第一个设置针对全国的费率，后面的看情况针对个别省份。必填字段见示例。     
     * @param array include @see AlibabaopenplatformlogisticsDeliveryRateDetailDTO[] $cashSubRateList     
     * 参数示例：<pre>[{ "toAreaCodeText": "全国", "rateDTO": { "firstUnit": 10, "firstUnitFee": 20, "nextUnit": 15, "nextUnitFee": 25, "toAreaCodeList": [ "1" ] } }, { "toAreaCodeText": "上海、福建省、广东省", "rateDTO": { "firstUnit": 10, "firstUnitFee": 20, "nextUnit": 15, "nextUnitFee": 25, "toAreaCodeList": [ "310000", "350000", "440000" ] } }]</pre>     
     * 此参数必填     */
    public function setCashSubRateList(AlibabaopenplatformlogisticsDeliveryRateDetailDTO $cashSubRateList) {
        $this->sdkStdResult["cashSubRateList"] = $cashSubRateList;
    }
    
        
    private $sdkStdResult=array();
    
    public function getSdkStdResult(){
    	return $this->sdkStdResult;
    }

}
?>