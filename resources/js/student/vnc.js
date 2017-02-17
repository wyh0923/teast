var TimeID ={
    timeid            : 0
    , vncMessageTimeid  : 0
};
var context = {
    nop:null
    , _canvas       : null
    , canvas        : function(){
        if( this._canvas == null ){
            this._canvas = $("#noVNC_canvas");
        }
        return this._canvas;
    }
    , _toolbar      : null
    , toolbar       : function(){
        if( this._toolbar == null ){
            this._toolbar = $("#top-toolbar");
        }
        return this._toolbar;
    }
    , _toolbarHand  : null
    , toolbarHand   : function(){
        if( this._toolbarHand == null ){
            this._toolbarHand = $("#top-toolbar-hand");
        }
        return this._toolbarHand;
    }
    , _toolbarBack  : null
    , toolbarBack   : function(){
        if( this._toolbarBack == null ){
            this._toolbarBack = $("#top-toolbar-back");
        }
        return this._toolbarBack;
    }
    , winWidth      : 0
    , winHeight     : 0
    , width     : 0
    , height        : 0
    , x             : 0
    , y             : 0
    , TimeID        : {
        timeid            : 0
        , vncMessageTimeid  : 0
    }
};

function documentWidth(){
    return document.documentElement.clientWidth||document.body.clientWidth||window.innerWidth;
}
function documentHeight(){
    return document.documentElement.clientHeight||document.body.clientHeight||window.innerHeight;
}
function recalculate_win(ctx){
    ctx.winWidth    = documentWidth();
    ctx.winHeight   = documentHeight();
    ctx.width       = screen.availWidth-(ctx.winWidth - ctx.canvas().width());
    ctx.height      = screen.availHeight-(ctx.winHeight - ctx.canvas().height()) + ctx.toolbar().height();

    ctx.x           = Math.floor((screen.availWidth -ctx.width)/2);
    ctx.y           = Math.floor((screen.availHeight-ctx.height)/2);

    if(typeof(screen.availLeft)!="undefined"){
        ctx.x += screen.availLeft;
    }
}
function SetScroll(lheight,height){
    if( lheight >= height ){
        document.documentElement.style.overflowY = "hidden";
    } else {
        document.documentElement.style.overflowY = "scroll";
    }
}
window.onresize = function(){
    var ctx = context;
    function showToolbarBack(){
        ctx.toolbarBack().hide();
        ctx.toolbarBack().height( Math.floor(screen.height-ctx.canvas().height())/2 );
        ctx.toolbarBack().slideDown("fast");
    }
    function hideToolbarBack(){
        ctx.toolbarBack().slideUp("fast");
    }

    ctx.toolbarHand().css("left", Math.floor((documentWidth() -ctx.toolbarHand().width())/2)  );

    if(ctx.canvas().height() < 100 ){
        var vncMsg = $(".vnc-msg");
        vncMsg.text("远程连接已经断开，如果任务已经结束请关闭窗口，否则请重新打开窗口。");
        vncMsg.show("fast");
        return;
    }
    //如果全屏就判断容器高度是否够,不够就隐藏工具条,非全屏的情况就不处理了.
    if( screen.height == documentHeight() ){
        if( (ctx.canvas().height() + ctx.toolbar().height()) > documentHeight() ){
            $(".btn-minify-top").show();
            ctx.toolbar().slideUp("slow",function(){
                showToolbarBack();
                SetScroll(screen.height,ctx.canvas().height());
                ctx.toolbarHand().slideDown("fast",function(){
                    ctx.toolbarHand().click(function(){
                        if( ctx.toolbar().is(':hidden') ){
                            hideToolbarBack();
                            ctx.toolbar().slideDown("fast",function(){
                                SetScroll(screen.height,ctx.canvas().height()+ctx.toolbar().height());
                            });
                        } else {
                            showToolbarBack();
                            ctx.toolbar().slideUp("fast",function(){
                                SetScroll(screen.height,ctx.canvas().height());
                            });
                        }
                    });
                });
            });
        } else {
            SetScroll(screen.height,ctx.canvas().height()+ ctx.toolbar().height());
        }
    } else {

        ctx.toolbarBack().height(0);
        ctx.toolbarHand().unbind();
        ctx.toolbarHand().slideUp("fast");
        if(ctx.toolbar().is(":hidden")){
            ctx.toolbar().slideDown("fast",function(){
                SetScroll(screen.availHeight,ctx.canvas().height()+ctx.toolbar().height());
            });
        } else {
            SetScroll(screen.availHeight,ctx.canvas().height()+ctx.toolbar().height());
        }
    }
};

function WindowResize(){
    if(context.canvas().height() < 100 ){
        var vncMsg = $(".vnc-msg");
        vncMsg.text("远程连接已经断开，如果任务已经结束请关闭窗口，否则请重新打开窗口。");
        vncMsg.show("fast");
        return;
    }
    TaskBegin(context)
        .putTimeid(context.TimeID)
        .clearTime()
        .append( function(next,ctx){
            $(".vnc-msg").hide();
            clearTimeout(ctx.TimeID.vncMessageTimeid);
            TimeID.vncMessageTimeid = 0;
            next();
        })
        .append( function(next,ctx){
            if( (ctx.canvas().height() + ctx.toolbar().height()) > screen.availHeight ){
                if( ctx.TimeID.vncMessageTimeid ==0){
                    $(".btn-minify-top").show();
                    var vncMsg = $(".vnc-msg");
                    vncMsg.text("无法完整显示整个桌面,请点击缩放按钮进入全屏！>>>");
                    vncMsg.show("fast");
                    //document.body.style.overflowY = "scroll";
                    SetScroll(screen.availHeight,ctx.canvas().height()+ctx.toolbar().height());
                    ctx.TimeID.vncMessageTimeid = setTimeout(function(){
                        vncMsg.hide("low");
                        ctx.TimeID.vncMessageTimeid=0;
                    },5000);
                }
            } else {
                //$(".btn-minify-top").hide();
                document.documentElement.style.overflowY = "hidden";
            }
            if(typeof(screen.availLeft)!="undefined"){
                window.moveTo(screen.availLeft,screen.availTop );
            }
            resizeTo(screen.availWidth,screen.availHeight);
            next();
        },80)
        .append( function(next,ctx){
            $(".header").width(ctx.canvas().width());
            recalculate_win(ctx);
            next();
        },50)
        .append( function(next,ctx){
            resizeTo(ctx.width,ctx.height);
            next();
        },50)
        .append( function(next,ctx){
            window.moveTo(ctx.x,ctx.y);
            next();
        },50)
        .append( function(next,ctx){
            //最后的保障,避免切换时间过短导致的切换失败
            if( screen.availHeight > documentHeight() ){
                if( ctx.canvas().width() < documentWidth() ){
                    recalculate_win(ctx);
                    resizeTo(ctx.width,ctx.height);
                    moveTo(ctx.x,ctx.y);
                }
            }
        },3000)
        .End();
}
//-------------------------------------------[fix R5]>>>
/*jslint white: false */
/*global window, $, Util, RFB, */

"use strict";

// Load supporting scripts
Util.load_scripts(["webutil.js", "base64.js", "websock.js", "des.js",
    "keysymdef.js", "keyboard.js", "input.js", "display.js",
    "inflator.js", "rfb.js", "keysym.js"]);

var rfb;
var resizeTimeout;


function UIresize() {
    if (WebUtil.getConfigVar('resize', false)) {
        var innerW = window.innerWidth;
        var innerH = window.innerHeight;
        var controlbarH = $D('noVNC_status_bar').offsetHeight;
        var padding = 5;
        if (innerW !== undefined && innerH !== undefined){
            rfb._supportsSetDesktopSize = true;
            rfb.setDesktopSize(innerW, innerH - controlbarH - padding);
        }
    }
}
function FBUComplete(rfb, fbu) {
    UIresize();
    rfb.set_onFBUComplete(function() { });
}
function passwordRequired(rfb) {
    var msg;
    msg = '<form onsubmit="return setPassword();"';
    msg += '  style="margin-bottom: 0px">';
    msg += 'Password Required: ';
    msg += '<input type=password size=10 id="password_input" class="noVNC_status">';
    msg += '<\/form>';
    $D('noVNC_status_bar').setAttribute("class", "noVNC_status_warn");
    $D('noVNC_status').innerHTML = msg;
}
function setPassword() {
    rfb.sendPassword($D('password_input').value);
    return false;
}
function sendCtrlAltDel() {
    rfb.sendCtrlAltDel();
    return false;
}
function xvpShutdown() {
    rfb.xvpShutdown();
    return false;
}
function xvpReboot() {
    rfb.xvpReboot();
    return false;
}
function xvpReset() {
    rfb.xvpReset();
    return false;
}
function updateState(rfb, state, oldstate, msg) {
    var s, sb, cad, level;
    s = $D('noVNC_status');
    sb = $D('noVNC_status_bar');
    cad = $D('sendCtrlAltDelButton');
    switch (state) {
        case 'failed':       level = "error";  break;
        case 'fatal':        level = "error";  break;
        case 'normal':       level = "normal"; break;
        case 'disconnected': level = "normal"; break;
        case 'loaded':       level = "normal"; break;
        default:             level = "warn";   break;
    }

    if (state === "normal") {
        cad.disabled = false;
    } else {
        cad.disabled = true;
        xvpInit(0);
    }

    if (typeof(msg) !== 'undefined') {
        sb.setAttribute("class", "noVNC_status_" + level);
        s.innerHTML = msg;
    }
}


function xvpInit(ver) {
    var xvpbuttons;
    xvpbuttons = $D('noVNC_xvp_buttons');
    if (ver >= 1) {
        xvpbuttons.style.display = 'inline';
    } else {
        //xvpbuttons.style.display = 'inline';
        xvpbuttons.style.display = 'none';
    }
}

window.onscriptsload = function () {
    var password, path;

    $D('sendCtrlAltDelButton').style.display = "inline";
    $D('sendCtrlAltDelButton').onclick = sendCtrlAltDel;
    $D('xvpShutdownButton').onclick = xvpShutdown;
    $D('xvpRebootButton').onclick = xvpReboot;
    $D('xvpResetButton').onclick = xvpReset;

    WebUtil.init_logging(WebUtil.getConfigVar('logging', 'warn'));
    //document.title = unescape(WebUtil.getConfigVar('title', 'noVNC'));
    // By default, use the host and port of server that served this file
    password = WebUtil.getConfigVar('password', '');
    path = WebUtil.getConfigVar('path', 'websockify');

    // if port == 80 (or 443) then it won't be present and should be
    // set manually
    if (!port) {
        if (window.location.protocol.substring(0,5) == 'https') {
            port = 443;
        }
        else if (window.location.protocol.substring(0,4) == 'http') {
            port = 80;
        }
    }



    // If a token variable is passed in, set the parameter in a cookie.
    // This is used by nova-novncproxy.
    // token = WebUtil.getConfigVar('token', null);

    if (token) {

        // if token is already present in the path we should use it
        path = WebUtil.injectParamIfMissing(path, "token", token);

        WebUtil.createCookie('token', token, 1)
    }

    if ((!host) || (!port)) {
        updateState(null, 'fatal', null, 'Must specify host and port in URL');
        return;
    }

    try {
        rfb = new RFB({'target':       $D('noVNC_canvas'),
            'encrypt':      WebUtil.getConfigVar('encrypt',
                (window.location.protocol === "https:")),
            'repeaterID':   WebUtil.getConfigVar('repeaterID', ''),
            'true_color':   WebUtil.getConfigVar('true_color', true),
            'local_cursor': WebUtil.getConfigVar('cursor', true),
            'shared':       WebUtil.getConfigVar('shared', true),
            'view_only':    WebUtil.getConfigVar('view_only', false),
            'onUpdateState':  updateState,
            'onXvpInit':    xvpInit,
            'onPasswordRequired':  passwordRequired,
            'onFBUComplete': FBUComplete});
        //fix[<<<]
        rfb._supportsSetDesktopSize = true;
        rfb.onWindowResize = WindowResize;

    } catch (exc) {
        updateState(null, 'fatal', null, 'Unable to create RFB client -- ' + exc);
        return; // don't continue trying to connect
    }

    rfb.connect(host, port, password, path);
};

setInterval(function(){
    if(context.canvas().height() < 50 ){
        var vncMsg = $(".vnc-msg");
        vncMsg.text("远程连接已经断开，如果任务已经结束请关闭窗口，否则请重新打开窗口。");
        vncMsg.show("fast");
        return;
    }

},3000)