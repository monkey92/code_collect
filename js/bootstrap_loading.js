;((function($){
    if($ == undefined) return;
    var loading_html = '<div id="loading_modal" class="modal fade" tabindex="-1"><div class="loading">请稍后...</div></div>';
    var ddc_loading = {};
    ddc_loading.loading = function(msg){
        var oldLoader = $("#loading_modal");
        var hasOldLoader = oldLoader.size() > 0;
        if(hasOldLoader){
            oldLoader.modal({
                backdrop:'static',
                keyboard:false
            })
        }else{
            $(loading_html).css({
                width:"160px",
                height:"56px",
                position: "absolute",
                top:"45%",
                left:"50%",
                "line-height":"56px",
                color:"#fff",
                "padding-left":"60px",
                "font-size":"15px",
                background: "#000 url(/images/common/loading.gif) no-repeat 10px 50%",
                opacity: "0.7",
                "z-index":"9999",
                "-moz-border-radius":"20px",
                "-webkit-border-radius":"20px",
                "border-radius":"20px",
                filter:"progid:DXImageTransform.Microsoft.Alpha(opacity=70)"
            }).appendTo("body").modal({
                backdrop:'static',
                keyboard:false
            });
        }
    };
    ddc_loading.unloading = function(){
        var loader = $("#loading_modal");
        loader.modal("hide");
    };
    $.ddc_loading = ddc_loading;
})(jQuery));