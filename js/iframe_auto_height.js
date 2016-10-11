
//<iframe src="" id="iframepage"  scrolling="auto | yes | no" width="100%" height="100%"  onload="changeFrameHeight()"  frameborder="0"></iframe>
//iframe 高度自适应
//让水平滚动条消失 要在iframe页面加入 css // html { overflow-x:hidden; }
function changeFrameHeight(){
    var ifm= document.getElementById("iframepage"); 
    ifm.height=document.documentElement.clientHeight;
}
window.onresize=function(){  
     changeFrameHeight();  
} 