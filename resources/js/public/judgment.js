/**
 * author:xiaozhang
 * 判断浏览器版本
 *
 * 
 */
function getBrowser(getVersion)
{
    var ua_str = navigator.userAgent.toLowerCase(), ie_Tridents, trident, match_str, ie_aer_rv, browser_chi_Type;

    

    //判断IE 浏览器, 
    if("ActiveXObject" in self){
        // ie_aer_rv:  指示IE 的版本.
        ie_aer_rv= (match_str = ua_str.match(/msie ([\d.]+)/)) ?match_str[1] :
              (match_str = ua_str.match(/rv:([\d.]+)/)) ?match_str[1] : 0;
        ie_Tridents = {"trident/7.0": 11, "trident/6.0": 10, "trident/5.0": 9, "trident/4.0": 8};
        //匹配 ie8, ie11, edge
        trident = (match_str = ua_str.match(/(trident\/[\d.]+|edge\/[\d.]+)/)) ?match_str[1] : undefined;
        browser_chi_Type = (ie_Tridents[trident] || ie_aer_rv) > 0 ? "ie" : undefined;
    }else{
        //判断 windows edge 浏览器
        // match_str[1]: 返回浏览器及版本号,如: "edge/13.10586"
        // match_str[1]: 返回版本号,如: "edge" 

        //若要返回 "edge" 请把下行的 "ie" 换成 "edge"。 注意引号及冒号是英文状态下输入的
        browser_chi_Type = (match_str = ua_str.match(/edge\/([\d.]+)/)) ? "ie" :
                    //判断firefox 浏览器
                      (match_str = ua_str.match(/firefox\/([\d.]+)/)) ? "firefox" : 
                    //判断chrome 浏览器
                      (match_str = ua_str.match(/chrome\/([\d.]+)/)) ? "chrome" : 
                    //判断opera 浏览器
                      (match_str = ua_str.match(/opera.([\d.]+)/)) ? "opera" : 
                    //判断safari 浏览器
                      (match_str = ua_str.match(/version\/([\d.]+).*safari/)) ? "safari" : undefined;
    }


  

    //返回浏览器类型和版本号

    var verNum, verStr;

    verNum = trident && ie_Tridents[trident] ? ie_Tridents[trident] : match_str[1];

    verStr = (getVersion != undefined) ? browser_chi_Type+"/"+verNum : browser_chi_Type;
    return verStr;
 }
 

