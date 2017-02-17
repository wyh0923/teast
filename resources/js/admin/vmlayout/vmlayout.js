

function VMNetLayout(site,url){
	var baseContainer = "#base-container ";
	var Config = {
		  nop			: 0
		, portCount		: 3
		, areaSize		: [170,236,388,652,917,1182,1447,1712]
		, template		: url + "/resources/js/teacher/vmlayout/vmlayout-res.html"
	};
	
	var data = {
		groups:[
			{area:"操作区",open:true  ,links:0	,items:[
			 	{open:false,sys:"",info:null}
			]}
			,{area:"LAN1",open:false  ,links:0	,items:[
				{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null}
			]}
			,{area:"LAN2",open:false ,links:0	,items:[
				{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null}
			]}
			,{area:"LAN3",open:false ,links:0	,items:[
				{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null}
			]}
			,{area:"LAN4",open:false ,links:0	,items:[
				{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null}
			]}
			,{area:"LAN5",open:false ,links:0	,items:[
				{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null}
			]}
			,{area:"LAN6",open:false ,links:0	,items:[
				{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null}
			]}
			,{area:"LAN7",open:false ,links:0	,items:[
				{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null},{open:false,sys:"",info:null}
			]}
		]
		, delItem:function(gi,ii){
			var itemMax = this.groups[gi].items.length-1;
			var startI = ii;
			var group =  data.groups[gi];
			for( var i=ii;i<=itemMax;i++){
				if( i<itemMax && group.items[i+1].open ){
					group.items[i].info = group.items[i+1].info;
					group.items[i+1].info = null;
					group.items[i].open = true;
					group.items[i+1].open = false;
				} else {
					group.items[i].open = false;
				}
			}
			
			if(itemMax==0){
				group.items[itemMax].info = null;
				group.items[itemMax].open = false;
			}
		}
	};
	
	this.parseData = function(data){
		
	};
	
	this.getData = function(){
		var json = [];
		data.groups.forEach(function(ele,idx){
			void function(){
				if( !ele.open ){
					return;
				}
				var obj = {open:ele.open,items:[],links:ele.links};
				ele.items.forEach(function(it,itidx){
					if( it.open ){
						obj.items.push({uuid: it.info.vm_tpl_uuid,disk_size: it.info.disk_size,memory_size: it.info.memory_size,docker_cmd: it.info.docker_cmd});
					}
				});
				json.push(obj);
			}();
		});
		var dataString = JSON.stringify( json );
		// console.log( dataString );
		return dataString;
	};
	
	var com_site = null;
	var link_area	= {};
	var link_lines	= {};
	var routePorts	= {};
	var panels		= {};
	
	var jqPorts		= [];//可以操作的端口
	var jqItems		= [];//区域
	var jqLabels	= [];//标题
	
	var templateNames = {
		  nop:null
		, "link-label"	: "#panel-template-link-label"
		, "link-button"	: "#panel-template-link-button"
		, "op-rom"		: "#panel-template-op-rom"
	};
	var template	= {
		  nop:null
		, "link-label"	: ""
		, "link-button"	: ""
		, "op-rom"		: ""
	};
	var pack		= {
		  nop:null
		, areaIndex:0
		, itemIndex:0
		, append:function(singleData){
			addItem(this.areaIndex,this.itemIndex,singleData||null);
		}
	};
	var HelpPlugButton = new function(){
		this.SetConnect = function(jqObj,callback){
			jqObj.removeClass("disconnected").removeClass("connected").addClass("connect").unbind('click');
			if( typeof( callback )!= "undefined" ){
				jqObj.click(function(){
					callback();
				});
			}
			return jqObj;
		};
		this.SetConnected = function(jqObj,callback){
			jqObj.removeClass("connect").removeClass("disconnected").addClass("connected").unbind('click');
			if( typeof( callback )!= "undefined" ){
				jqObj.click(function(){
					callback();
				});
			}
			return jqObj;
		};
		this.SetDisconnected = function(jqObj){
			jqObj.removeClass("connect").removeClass("connected").addClass("disconnected").unbind('click');
			return jqObj;
		};
	}
	var HelpOpRom = new function(){
		var jqObj = null;
		this.Begin	= function(in_jqObj){
			jqObj = in_jqObj;
			return this;
		};
		this.End	= function(){
			jqObj = null;
			return this;
		};
		this.SetSystemType = function(type){
			jqObj.find(".sys-ico").removeClass("win linux").addClass(type);
			return this;
		};
		this.SetText = function(txt){
			jqObj.find(".sys-text").text(txt);
			return this;
		};
		this.TextTemplate = function(TemplateTxt,data){
			var lt = "{%", rt = "%}";
			var arr = TemplateTxt.split(lt);
			var fields = [];
			for(var i=0;i<arr.length;i++){
				if( arr[i].indexOf(rt)!=-1){
					var tp = arr[i].split(rt)[0];
					if( tp!=""){
						fields.push( tp );
					}
				}
			}
			var outText = TemplateTxt;
			fields.forEach(function(key,idx){
				if( typeof(data[key])!="undefined"){
					outText = outText.replace( new RegExp( lt + key + rt ) , data[key] );
				}
			});
			this.SetText(outText);
			return this;
		};
	};
	
	function openState(idx){
		return data.groups[idx].open?(1<<idx):0;
	}
	
	function popAddTtemWindow(ai,ii){
		//popwin
		pack.areaIndex = ai;
		pack.itemIndex = ii;
		
		//alert(ai+"|"+ii);
		popVmList(pack);
	}
	
	function refresh_titles(){
		var len = data.groups.length;
		var groups = data.groups;
		jqLabels.forEach(function(ele,idx){
			var currIdx = idx;
			var currEle	= ele;
			var labelBtn = [];
			if( groups[idx].open ){
				for(var i=0;i<groups.length;i++){
					var destIdx = i;
					if(destIdx!=currIdx && groups[destIdx].open){
						var OnClick = null;
						void function(){
							var c_idx = currIdx;
							var d_idx = destIdx;
							OnClick = function(){
								if( $(this).is(".on") ){
									$(this).removeClass("on").addClass("off");
									data.groups[c_idx].links &= (~(1<<d_idx)&0xFF);
								} else {
									$(this).removeClass("off").addClass("on");
									data.groups[c_idx].links |= (1<<d_idx);
								}
							};
							labelBtn.push(
								$(template["link-label"]).removeClass("on off")
								.addClass( (groups[currIdx].links&(1<<destIdx))!=0?"on":"on" )
								.unbind("click").click(OnClick).find("span").html(groups[destIdx].area).parent()
							);
							/* //有一个是不通的  默认要全通
							labelBtn.push(
								$(template["link-label"]).removeClass("on off")
								.addClass( (groups[currIdx].links&(1<<destIdx))!=0?"on":"off" )
								.unbind("click").click(OnClick).find("span").html(groups[destIdx].area).parent()
							);*/
						}();
					}
				}
				ele.html("");
				if( labelBtn.length > 0 ){
					ele.append( labelBtn );
					var btn = $( template['link-button'] );
					btn.find(".all").unbind("click").click(function(){
						for(var i=0;i<groups.length;i++){
							if( currIdx != i ){
								groups[currIdx].links |= openState(i);
							}
						}
						currEle.find("label").removeClass("off").addClass("on");
					});
					btn.find(".unall").unbind("click").click(function(){
						groups[currIdx].links = 0;
						currEle.find("label").removeClass("on").addClass("off");
					});
					ele.append( btn );
				}
			}
			
		});
	}
	
	function delItem(ai,ii){
		if( typeof(data.groups[ai]) == "undefined" || typeof(data.groups[ai].items[ii]) == "undefined"){
			return ;
		}
		data.delItem(ai,ii);
		var dataItems = data.groups[ai].items;
		
		jqItems[ai].find(".op-rom:eq("+1*ii+")").fadeOut("fast",function(){
			jqItems[ai].find(".op-rom:eq("+1*ii+"),.op-rom:gt("+1*ii+")").remove();
			for( var i=ii+1;i<dataItems.length;i++){
				HelpPlugButton.SetDisconnected($(jqPorts[ai][i]));
			}
			HelpPlugButton.SetConnect($(jqPorts[ai][ii]),function(){popAddTtemWindow(ai,ii)});
			for( var i=ii;i<dataItems.length;i++){
				if( dataItems[i].open ){
					addItem(ai,i);
				}
			}
		});
	}
	
	function addItem(ai,ii,singleData,state){
		if( typeof(data.groups[ai]) == "undefined" || typeof(data.groups[ai].items[ii]) == "undefined"){
			return ;
		}
		var item = data.groups[ai].items[ii];
		if( typeof(singleData)!="undefined" && singleData!=null ){
				item.info = singleData;
				item.open = true;
		}
		HelpPlugButton.SetConnected($(jqPorts[ai][ii]));
		var nextI = ii+1;
		if( typeof(jqPorts[ai][ii])!="undefined" ){
			HelpPlugButton.SetConnect( $(jqPorts[ai][nextI]),function(){popAddTtemWindow(ai,nextI);});
		}
		var op_rom = $(template["op-rom"]);
		HelpOpRom.Begin(op_rom).SetSystemType(item.info.os_type.toLowerCase()).TextTemplate("{%vm_tpl_name%}（系统：{%os_type%} {%os_version%},漏洞信息：{%vm_leak_info%}）",item.info).End();
		op_rom.find(".ico-empty").unbind('click').click(function(){
			delItem(ai,ii);
		});
		$(jqItems[ai]).append(op_rom);
		refresh_titles();
	}
	
	function initItems(){
		var len = data.groups.length;
		for(var i=0;i<len;i++){
			var row = data.groups[i].items;
			var cc = true;//未分配
			$(jqPorts[i]).each(function(idx){
				var currI = i;
				var nextI = idx;
				if( !row[idx].open && cc ){
					cc = false;
					HelpPlugButton.SetConnect($(this),function(){popAddTtemWindow(currI,nextI);});
				} else {
					if(!row[idx].open){
						HelpPlugButton.SetDisconnected($(this));
					}
				}
			});
			data.groups[i].links = 0;
		}
	}
	
	function port_OnClick(){
		var idx = $(this).attr("idx");
		if(versions == 0){
			$('#okBox p.promptNews').html('此版本是专研版的功能，您使用的是教育版，请升级后使用。');
			fnShow("#okBox","fadeOutUp","fadeInDown");
		}else if(versions == 1 && idx !=1){
			$('#okBox p.promptNews').html('此版本是专研版的功能，您使用的是基础版，请升级后使用。');
			fnShow("#okBox","fadeOutUp","fadeInDown");
			groups.length =1;
		}else{
			var routePort = $(routePorts[idx]).children(".image").first();
			var linkStates= $(baseContainer+".op-link-state");
			if( routePort.is(".on") ){
				data.groups.forEach(function(ele,eidx){
					if( eidx >= idx ){
						if(ele.open){
							ele.open = false;
							$(routePorts[eidx]).children(".image").first().removeClass("on").addClass("off");
							$(panels[eidx]).hide();
							$(link_lines[eidx]).hide();
							$(linkStates[eidx]).removeClass("open").addClass("close");
							for(var i=0;i<idx;i++){
								data.groups[i].links &= (~(1<<eidx));
							}
							ele.links = 0;
						}
					}
				});
			} else {
				data.groups.forEach(function(ele,eidx){
					if( eidx > 0 && eidx<=idx){
						if( $(panels[eidx]).is(":hidden") ){
							ele.open = true;
							$(routePorts[eidx]).children(".image").first().removeClass("off").addClass("on");
							if(eidx!=0){
								ele.links |= 1;
							}
							$(panels[eidx]).show();
							$(link_lines[eidx]).show();
							$(linkStates[eidx]).removeClass("close").addClass("open");
							
							for(var i=0;i<=idx;i++){
								if(i!=eidx){
									data.groups[i].links |= (1<<eidx);
								}
							}
						}
					}
					if( eidx == idx ){
						for(var i=0;i<data.groups.length;i++){
							if( i != eidx ){
								data.groups[eidx].links |= openState(i);
							}
						}
					}
					
				});

			}
			
			
			refresh_titles();
			link_area.height( Config.areaSize[idx] );
		}
	}
	
	var init = this.init = function(){
		
		//模板初始化
		void function(){
			template["link-label"]	= $(templateNames["link-label"]).html();
			template["link-button"]	= $(templateNames["link-button"]).html();
			template["op-rom"]		= $(templateNames["op-rom"]).html();
		}();
		
		//连接线初始化
		void function(){
			link_area = $(baseContainer+".vm-line");
			link_lines = link_area
				.height(Config.areaSize[0])
				.find("use")
				.each(function(idx){
					if(idx>0){
						$(this).hide();
					}
				});
		}();
		
		//端口、面板初始化
		void function(){
			jqPorts = [];
			routePorts	= $(baseContainer+".route-port");
			panels		= $(baseContainer+".op-layout");
			panels.each(function(idx){
				if(idx>0){
					$(this).hide();
				}
				//插槽(口)获取
				jqPorts.push( $(this).find(".op-port .port:not(.uplink)") );
				//插槽条获取
				jqItems.push( $(this).find(".op-container") );
				//工具条，连通性label
				jqLabels.push( $(this).find(".op-box") );
			});
			
			var len = routePorts.length -1;
			if( len > Config.portCount ){
				len = Config.portCount;
			}
			for(var i=1;i<=len;i++){
				$(routePorts[i])
					.removeClass("close")
					.find(".image")
					.removeClass("close")
					.addClass("open")
					.attr("idx",i)
					.click(port_OnClick);
			}
		}();
		//初始化插槽
		initItems();
		
	};
	
	var __construct = this.__construct = function(in_site){
		com_site = site||in_site||null;
		if( com_site != null ){
			com_site = $(com_site);
			$.get(Config.template,function(data,status){
				com_site.html(data);
				init();
			});
		}
	}
	
	void function(){
		__construct();
		
	}();
}

