<?php
    /**
     * 把微信获得的坐标转为百度地图的坐标
     * 如果转换失败则不转换
     * @param $latitude
     * @param $longitude
     */
    private function convertCoordinate($latitude,$longitude){
        $ak = config("wxconf.baidu_map_ak");
        $url = 'http://api.map.baidu.com/geoconv/v1/?coords='.$longitude.','.$latitude.'&from=1&to=5&ak='.$ak;
        $httpUtil = HttpUtil::getInstance();
        $ret = json_decode($httpUtil->doGet($url),true);
        if($ret['status'] == 0){
            $la = $ret['result'][0]['y'];
            $lo = $ret['result'][0]['x'];
            return ['latitude'=>$la,'longitude'=>$lo];
        }
        return ['latitude'=>$latitude,'longitude'=>$longitude];
    }
