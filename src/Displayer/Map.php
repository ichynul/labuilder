<?php

namespace Ichynul\Labuilder\Displayer;

class Map extends Text
{
    protected $view = 'map';

    protected $type = 'amap';

    protected $minify = false;

    protected $jsOptions = [];

    protected $height = '450px';

    protected $width = '100%';

    /**
     * Undocumented function
     *
     * @param string|int $val
     * @return $this
     */
    public function height($val)
    {
        if (is_numeric($val)) {
            $val .= 'px';
        }
        $this->height = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string|int $val
     * @return $this
     */
    public function width($val)
    {
        if (is_numeric($val)) {
            $val .= 'px';
        }
        $this->width = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param int $val
     * @return void
     */
    public function zoom($val)
    {
        $this->jsOptions['zoom'] = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $options
     * @return $this
     */
    public function jsOptions($options)
    {
        $this->jsOptions = array_merge($this->jsOptions, $options);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function amap()
    {
        $this->type = 'amap';
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function baidu()
    {
        $this->type = 'baidu';
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function google()
    {
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function tcent()
    {
        $this->type = 'tcent';
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function yandex()
    {
        $this->type = 'yandex';
        return $this;
    }

    public function beforRender()
    {
        $config = config('labuilder');

        if ($this->type == 'amap') {
            $this->amapScript($config['amap_js_key']);
        } else if ($this->type == 'baidu') {
            $this->js[] = $config['baidu_map_js_key'];
            $this->baiduScript();
        } else if ($this->type == 'tcent') {
            $this->tcentScript($config['tcent_map_js_key']);
        } else if ($this->type == 'yandex') {
            $this->js[] = $config['yandex_map_js_key'];
            $this->yandexScript();
        }

        $this->beforSymbol('<i class="mdi mdi-map"></i>');

        return parent::beforRender();
    }

    public function customVars()
    {
        return [
            'maptype' => $this->type,
            'mapStyle' => 'style="width: ' . $this->width . ';height: ' . $this->height . ';max-width: 100%;"',
        ];
    }

    public function tcentScript($jsKey)
    {
        $inputId = $this->getId();

        $value = $this->renderValue();

        $position = explode(',', $value);
        if (count($position) != 2) {
            $value = '24.847463,102.709629';
        } else {
            $value = $position[1] . ',' . $position[0];
        }

        $this->jsOptions = array_merge([
            'zoom' => 15,
            'panControl' => true,
            'zoomControl' => true,
            'scaleControl' => true,
        ], $this->jsOptions);

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT

        window.tcentInit = function(){
            var input = $('#{$inputId}');

            var center = new qq.maps.LatLng({$value});

            var map = new qq.maps.Map(document.getElementById("map-{$inputId}"), {
                center : center,
                {$configs}
            });

            var marker = new qq.maps.Marker({
                position: center,
                draggable: true,
                map: map
            });

            if(!input.val())
            {
                var citylocation = new qq.maps.CityService();
                citylocation.setComplete(function(result) {
                    map.setCenter(result.detail.latLng);
                    marker.setPosition(result.detail.latLng);
                    input.val(result.detail.latLng.getLng() + ',' + result.detail.latLng.getLat());
                });
                citylocation.searchLocalCity();
            }

            qq.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(event.latLng);
                input.val(event.latLng.getLng() + ',' + event.latLng.getLat());
            });

            qq.maps.event.addListener(marker, 'dragend', function() {
                var pp = marker.getPosition();
                input.val(pp.getLng() + ',' + pp.getLat());
            });
            var ap = new qq.maps.place.Autocomplete(document.getElementById('search-{$inputId}'));
            var searchService = new qq.maps.SearchService({
                map : map
            });

            qq.maps.event.addListener(ap, "confirm", function(res){
                searchService.search(res.value);
            });
        }

        var url = '$jsKey&callback=tcentInit';

        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = url;
        document.body.appendChild(script);
EOT;
        $this->script[] = $script;
    }

    protected function yandexScript()
    {
        //未做测试
        $inputId = $this->getId();
        $value = $this->renderValue();

        $position = explode(',', $value);
        if (count($position) != 2) {
            $position = [24.847463, 102.709629];
            $value = '24.847463,102.709629';
        } else {
            $position = [$position[1], $position[0]];
            $value = $position[1] . ',' . $position[0];
        }

        $this->jsOptions = array_merge([
            'center' => $position,
            'zoom' => 14,
        ], $this->jsOptions);

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT

        var input = $('#{$inputId}');

        ymaps.ready(function(){
            var myMap = new ymaps.Map('map-{$inputId}', {
                {$configs}
            });

            var myPlacemark = new ymaps.Placemark([{$value}], {
            }, {
                preset: 'islands#redDotIcon',
                draggable: true
            });

            myPlacemark.events.add(['dragend'], function (e) {
                input.val(myPlacemark.geometry.getCoordinates()[1] + ',' + myPlacemark.geometry.getCoordinates()[0]);
            });

            myMap.geoObjects.add(myPlacemark);
        });
EOT;
        $this->script[] = $script;
    }

    protected function baiduScript()
    {
        $inputId = $this->getId();

        $value = $this->renderValue();

        $position = explode(',', $value);
        if (count($position) != 2) {
            $value = '102.709629,24.847463';
        }

        $this->jsOptions = array_merge([
            'zoom' => 14,
        ], $this->jsOptions);

        $zoom = $this->jsOptions['zoom'];

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT

        var input = $('#{$inputId}');

        var map = new BMap.Map("map-{$inputId}");
        var point = new BMap.Point({$value});
        map.centerAndZoom(point, {$zoom});

        var marker = new BMap.Marker(point);        // 创建标注
        map.addOverlay(marker);

        if(!input.val())
        {
            var geolocation = new BMap.Geolocation();
            geolocation.getCurrentPosition(function(r){
                if(this.getStatus() == BMAP_STATUS_SUCCESS){
                    marker.setPosition(r.point);
                    map.panTo(r.point);
                    input.val(r.point.lng + ',' + r.point.lat);
                }
                else {
                    console.log('failed' + this.getStatus());
                }
            });
        }

        marker.enableDragging();
        marker.addEventListener("dragend", function(e){
            input.val(e.point.lng + ',' + e.point.lat);
        })

        map.addEventListener("click", function(e){
            marker.setPosition(e.point);
            input.val(e.point.lng + ',' + e.point.lat);
        });

        var ac = new BMap.Autocomplete({"input" :  "search-{$inputId}", "location" : map});

        var myValue;

        ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
            var _value = e.item.value;
            myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            setPlace();
        });

        function setPlace(){
            function myFun(){
                var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                map.centerAndZoom(pp, {$zoom});
                marker.setPosition(pp);
                input.val(pp.lng + ',' + pp.lat);
            }
            var local = new BMap.LocalSearch(map, { //智能搜索
                onSearchComplete: myFun
            });
            local.search(myValue);
        }

        map.addControl(new BMap.NavigationControl());
        map.addControl(new BMap.ScaleControl());
        map.addControl(new BMap.OverviewMapControl());
EOT;
        $this->script[] = $script;
    }

    protected function amapScript($jsKey)
    {
        $inputId = $this->getId();

        if (is_array($this->default)) {
            $this->default = implode(',', $this->default);
        }

        $value = $this->renderValue();

        $position = explode(',', $value);
        if (count($position) != 2) {
            $position = [102.709629, 24.847463];
            $value = '102.709629,24.847463';
        }

        $this->jsOptions = array_merge([
            'center' => $position,
            'zoom' => 15,
        ], $this->jsOptions);

        $zoom = $this->jsOptions['zoom'];

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT

        window.amapInit = function(){
            var input = $('#{$inputId}');
            var map = new AMap.Map('map-{$inputId}', {
                {$configs}
            });

            var marker = new AMap.Marker({
                draggable: true,
                position: new AMap.LngLat({$value}),   // 经纬度对象，也可以是经纬度构成的一维数组
            });

            // 将创建的点标记添加到已有的地图实例：
            map.add(marker);

            map.on('click', function(e) {
                marker.setPosition(e.lnglat);
                input.val(e.lnglat.getLng() + ',' + e.lnglat.getLat());
            });

            marker.on('dragend', function (e) {
                input.val(e.lnglat.getLng() + ',' + e.lnglat.getLat());
            });

            if(!input.val()) {
                map.plugin('AMap.Geolocation', function () {
                    geolocation = new AMap.Geolocation();
                    map.addControl(geolocation);
                    geolocation.getCurrentPosition();
                    AMap.event.addListener(geolocation, 'complete', function (data) {
                        marker.setPosition(data.position);
                        input.val(data.position.getLng() + ',' + data.position.getLat());
                    });
                });
            }

            AMap.plugin('AMap.Autocomplete',function(){
                var autoOptions = {
                    input : "search-{$inputId}"
                };

                var autocomplete= new AMap.Autocomplete(autoOptions);
                AMap.event.addListener(autocomplete, "select", function(data){
                    map.setZoomAndCenter({$zoom}, data.poi.location);
                    marker.setPosition(data.poi.location);
                    input.val(data.poi.location.lng + ',' + data.poi.location.lat);
                });
            });
        }

        var url = '$jsKey&callback=amapInit';

        var jsapi = document.createElement('script');
        jsapi.charset = 'utf-8';
        jsapi.src = url;
        document.body.appendChild(jsapi);
EOT;
        $this->script[] = $script;
    }
}
