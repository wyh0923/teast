/**
 * Created by qirupeng on 2016/8/25.
 */
var HelpTemplate = new function(){
    var lt = "{%", rt = "%}";
    var template_data	= null;
    var template_text	= null;
    var template_fields = null;
    var cache_text		= null;
    var function_to		= null;

    //init
    void function(){
        restart();
    }();

    function baseToFunction(outText,in_obj){
        if( typeof( in_obj ) == "object" ){
            in_obj.text = outText;
        }
    }

    function restart(){
        template_data	= null;
        template_text	= "";
        template_fields = [];
        cache_text		= "";
        function_to = baseToFunction;
    }

    function templateBuilder(text){

        var arr = text.split(lt);
        var fields = [];
        for(var i=0;i<arr.length;i++){
            if( arr[i].indexOf(rt)!=-1){
                var tp = arr[i].split(rt)[0];
                if( tp!=""){
                    fields.push( tp );
                }
            }
        }
        return fields;
    }

    function templateOut(text,data,fields){
        var outText = text;
        fields.forEach(function(key,idx){
            if( typeof(data[key])!="undefined"){
                outText = outText.replace( new RegExp( lt + key + rt ) , data[key] );
            }
        });
        return outText;
    }

    this.TextTemplate = function(txt,data){
        var text	= txt;
        var fields	= templateBuilder(text);
        var out		= templateOut(text,data,fields);
        return out;
    };
    this.Begin = function(parames){
        template_data	= parames.templateData||template_data;
        function_to		= parames.outCall||function_to;
        template_text	= parames.templateText||template_text;

        template_fields = templateBuilder(template_text);
        if( typeof(function_to) != "function" ){
            function_to = baseToFunction;
        }
        return this;
    };

    this.dataEach = function(fun){
        if( template_data!= null && typeof(fun)=="function" ){
            for( var idx in template_data ){
                var retbool = fun(template_data[idx],idx);
                if( retbool == false ){
                    return this;
                }
            }
        }
        return this;
    };

    this.ParseFor = function(data){
        cache_text = templateOut(template_text,data,template_fields);
        return this;
    };
    this.To		  = function(in_obj){
        function_to(cache_text,in_obj);
        return this;
    };
    this.Out	= function(){
        function_to(cache_text);
        return this;
    };

    this.End	= function(obj){
        if( obj||false){
            this.To(obj);
        }
        restart();
        return this;
    };
    this.OutText	= function(){
        return cache_text;
    }
    this.OutTextEnd	= function(){
        var text = cache_text;
        restart();
        return text;
    };

};