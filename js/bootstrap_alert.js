/*
    author:ddc
    email: 2223445830@qq.com
    function:实现boostrap 弹出警告框
 */
;(function($){

    if($ == undefined) return;
    var success_html = '<div id="alert_success_html" class="alert alert-success row" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>成功!</strong><span></span></div>';
    var info_html = '<div id="alert_info_html" class="alert alert-info" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>信息:</strong><span></span></div>';
    var warning_html = '<div id="alert_warning_html" class="alert alert-warning" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>警告!</strong><span></span></div>';
    var danger_html = '<div id="alert_danger_html" class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>错误!</strong><span></span></div>';

    var ddc_alert = {};
    ddc_alert.show_alert = function (type,msg) {
        var dom_obj = null;
        switch (type){
            case 'success' :
                var old_obj = $("#alert_success_html");
                dom_obj = old_obj.size() == 0 ? $(success_html) : old_obj;
                break;
            case 'info' :
                var old_obj = $("#alert_info_html");
                dom_obj = old_obj.size() == 0 ? $(info_html) : old_obj;
                break;
            case 'warning' :
                var old_obj = $("#alert_warning_html");
                dom_obj = old_obj.size() == 0 ? $(warning_html) : old_obj;
                break;
            case 'danger' :
                var old_obj = $("#alert_danger_html");
                dom_obj = old_obj.size() == 0 ? $(danger_html) : old_obj;
                break;
            default:
                dom_obj = $(info_html);
        }

        dom_obj.find("span").html(msg);
        dom_obj.css({"width":"50%","position":"absolute","top":"10%","left":"25%"});
        dom_obj.appendTo($("body"));
        window.setInterval(function () {
            dom_obj.alert("close");
        },2000);
    };
    ddc_alert.success = function(msg){
        this.show_alert("success",msg);
    };
    ddc_alert.info = function(msg){
        this.show_alert("info",msg);
    };
    ddc_alert.warning = function(msg){
        this.show_alert("warning",msg);
    };
    ddc_alert.danger = function(msg){
        this.show_alert("danger",msg);
    };

    $.ddc_alert = ddc_alert;


}(jQuery));
