<div class="header">
    <div class="headerbox clearfix">
        <div class="headerlogobox clearfix">
            <a class="headerlogo" href="<?php echo base_url(); ?>" id="headerlogo"><img src="<?php echo base_url() ?>resources/imgs/public/logo.png" ></a>
            <p><?php echo config_item ('webtitle');?></p>
        </div>
        <div class="headernavbox">
            <?php foreach ($this->nav['nav'] as $item): ?>
                <a  class="headernav <?php echo $item['id'] == $this->nav['nav_id'] ? " navact" : ""; ?>" href="<?php echo site_url($item['url']); ?>" ><?php echo $item['title']; ?><span></span></a>
            <?php endforeach; ?>
        </div>
        <div class="loginbox">
            <div class="tx"> <img width="40" height="40" src="<?php echo base_url().'resources/files/picture/'.$this->userinfo['UserIcon'];?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/imgs/public/<?php echo $this->default_icon;?>'"></div>
            <p class="txtitle" id="txtitle" title="<?php echo $this->userinfo['UserName']; ?>"><?php echo $this->userinfo['UserName']; ?><em></em></p>
            <div class="loginlist" id="loginlist">
                <em></em>
                <?php if($this->userinfo['UserRole'] == 3):?>
                    <p title="课程积分：<?php if($this->userinfo["UserPoint"]){ echo $this->userinfo["UserPoint"]; }else{ echo 0;} ?>" id="no_student"><a href="<?php echo site_url('Personal/statistic'); ?>">课程积分：<?php if($this->userinfo["UserPoint"]){ echo $this->userinfo["UserPoint"]; }else{ echo 0;} ?></a></p>
                <?php endif;?>
                <p class="logout"><a href="<?php echo site_url('Login/logout');?>"><i class="fa  fa-power-off"></i>&nbsp;退出登录</a><p>
            </div>
        </div>
    </div>
</div>