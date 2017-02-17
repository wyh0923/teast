/**
 * @author R5 <idarkfox@qq.com>
 */

if( typeof(SerialCall) == "undefined" ){
    "use strict";
    window.SerialCall = function( /* auto */ context /* array */,task ,idx  ){
        var cidx = idx||0;
        if( typeof(task[cidx]) == "undefined" ){
            return;
        }
        task[cidx](context,function(){
            SerialCall( context,task,++cidx );
        });
    }
}

if( typeof(TaskBegin) == "undefined" ){
    "use strict";
    window.TaskBegin = function(/* auto */ context ,/* obj  */ in_objs ){
        return new function(){
        	var TMID = {timeid:0};
            var objs = in_objs||null;
            var task = [];
            this.append = function(funcNameOrFun,in_ms){
                var ms = in_ms||0;

                if( typeof(funcNameOrFun) == "string" ){
                    var arr = funcNameOrFun.split("->");
                    var obj = objs;
                    var names = [];
                    arr.forEach( function(ele,idx){
                        names.push(ele);
                        if( typeof(obj[ele])!="undefined" && idx != (arr.length -1) ){
                            obj = obj[ele];
                        } else {
                            if( typeof(obj[ele])!= "undefined" && idx == (arr.length -1) ){
                                if(ms){
                                    task.push(function(context,next){
                                        TMID.timeid = setTimeout( function(){
                                        	TMID.timeid = 0;
                                            obj[ele](next,context);
                                        },ms);
                                    });
                                } else {
                                    task.push(function(context,next){
                                        obj[ele](next,context);
                                    });
                                }
                            } else {
                                console.log( names.join(".") + " undefined!" );
                                return false;
                            }
                        }
                    });
                } else {
                    if( ms){
                        task.push(function(context,next){
                            TMID.timeid = setTimeout( function(){
                            	TMID.timeid=0;
                                funcNameOrFun(next,context);
                            },ms);
                        });
                    } else {
                        task.push(function(context,next){
                            funcNameOrFun(next,context);
                        });
                    }

                }
                return this;
            };
            this.clearTime = function(){
            	clearTimeout(TMID.timeid);
            	TMID.timeid = 0;
            	return this;
            }
            this.putTimeid = function(IN_TMID){
            	TMID = IN_TMID.timeid;
            	return this;
            }
            this.outTimeid = function(OUT_TMID){
            	OUT_TMID.timeid = timeid;
            	return this;
            }
            this.insert = function(){};
            this.End = function(){
                SerialCall(context,task,0);
            };
        }

    }
}