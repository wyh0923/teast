function fullScreen() { 
	if(document.documentElement.requestFullScreen) { 
		document.documentElement.requestFullScreen(); 
	} else if(document.documentElement.webkitRequestFullScreen ) { 
		document.documentElement.webkitRequestFullScreen(); 
	} else if(document.documentElement.mozRequestFullScreen) { 
		document.documentElement.mozRequestFullScreen(); 
	} 
	else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

function exitFullScreen(){
	if (document.exitFullscreen) {  
	    document.exitFullscreen();  
	} else if (document.mozCancelFullScreen) {  
	    document.mozCancelFullScreen();  
	} else if (document.webkitCancelFullScreen) {  
	    document.webkitCancelFullScreen();  
	}
	else if (document.msExitFullscreen) { 
         document.msExitFullscreen(); 
}
}

$(function() {
	//header
	$(".btn-minify-top").click(function(){
		var width = document.documentElement.clientWidth||document.documentElement.innerWidth;
		var height = document.documentElement.clientHeight||document.documentElement.innerHeight;
		if( width == window.screen.width || height == window.screen.height ){
			exitFullScreen();

		} else{
			fullScreen();
		}
	});

	/*$(".quanping").click(function(){
		$(".header-content").show();
		$(".quanping").hide();
		$("#noVNC_canvas").css("width","1024px");
		$("#noVNC_canvas").css("height","768px");
	});*/
});