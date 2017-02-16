<div class="header">
	<div class="headerbox clearfix">
		<div class="headerlogobox clearfix"  onclick="">
			<a class="headerlogo" href="#" id="headerlogo"><img src="<?php echo base_url() ?>resources/imgs/public/logo.png" ></a>
			<p>网络安全实训系统</p>
		</div>
		<div class="headernavbox">
		    <a class="headernav  <?php if($nav_handle==='01'){?>navact<?php }?>" href="#">平台管理<span></span></a>
		    <a class="headernav  <?php if($nav_handle==='02'){?>navact<?php }?>" href="#">知识体系管理<span></span></a>
		    <a class="headernav" <?php if($nav_handle==='03'){?>navact<?php }?>" href="#">实训内容管理<span></span></a>
            <a class="headernav" <?php if($nav_handle==='04'){?>navact<?php }?>" href="#">人员管理<span></span></a>
            <a class="headernav" <?php if($nav_handle==='05'){?>navact<?php }?>" href="#">个人统计中心<span></span></a>
		</div>
		<div class="loginbox">
			<div class="tx"> <img class="photoImg" src=""></div>
			<p class="txtitle" id="txtitle">admin<em></em></p>
			<div class="loginlist" id="loginlist">
				<em></em>
				<p class="logout"><a href=""><i class="fa  fa-power-off"></i>退出登录</a><p>
			</div>
		</div>
	</div>
</div>