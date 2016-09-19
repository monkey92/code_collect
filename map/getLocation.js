var map = new BMap.Map("map");
function getLocation(){
    var longitude = $("#longitude").val();
    var latitude = $("#latitude").val();
//            wx_debug.info("longitude:"+longitude);
//            wx_debug.info("latitude:"+latitude);
//            console.log(latitude);
    if(longitude != '' && latitude != ''){
        var point = new BMap.Point(longitude,latitude);
//                wx_debug.info(JSON.stringify(point));

        var geoc = new BMap.Geocoder();
        geoc.getLocation(point, function(rs){
            var addComp = rs.addressComponents;
//                    alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
            var addr =  addComp.province + "," + addComp.city + "," + addComp.district + "," + addComp.street;
            wx_debug.info(addr);
            $("#address").val(addr);
        });

    }else{
        $("#address").attr("placeholder","定位失败,请手动填写");
    }
}