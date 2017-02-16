<div class="sidebar">
    <div class="allcatagory">
        <div class="allcatagory">
            <a href="javascript:;"><?php echo $this->nav['nav'][$this->nav['nav_id']]['title'];?></a>
        </div>
    </div>
    <ul id="sidebarUl" class="sidebar1">
        <?php foreach ($this->nav['left_nav'] as $item): ?>
        <li class="firstcatory  <?php echo $item['id'] == $this->nav['left_nav_id'] ? " active" : ""; ?>">
            <p class="clearfix firstcatoryBox">
                <i class="fa <?php echo $item['icon']; ?>"></i>
                <a class="links " href="<?php if($item['url'] == 'javascript:;'):?>javascript:;<?php else: ?><?php echo site_url($item['url']); ?><?php endif;?>"><?php echo $item['title']; ?></a>
            </p>
            <?php if(isset($item['sub'])):?>
                <?php foreach ($item['sub'] as $sub): ?>
                    <li  class="secondcatory <?php echo $sub['id'] == $this->nav['left_nav_id'] ? " active" : ""; ?>"><a href="<?php echo site_url($sub['url']); ?>" class="secondLinks"><?php echo $sub['title']; ?></a></li>
                <?php endforeach; ?>
            <?php endif;?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>