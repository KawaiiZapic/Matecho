<?php
/**
 * 一个基于MDUI的Material Design皮肤
 * 
 * @package Matecho
 * @author Zapic
 * @version 0.0.1-beta
 * @link https://i.zapic.cc
 * 
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
 $this->need('header.php');
?>
<div id="content-box" class="mdui-container">
<?php while($this->next()): ?>
	<div class="mdui-card card-archive mdui-center">
  <div class="mdui-card-media">
<div style="background:url(<?php $this->options->themeUrl("static/img/material-1.png");?>);height:300px;background-position:center center;background-size:cover"> </div>
    <div class="mdui-card-media-covered">
      <div class="mdui-card-primary">
        <div class="mdui-card-primary-title" ><?php $this->title() ?></div>
        <div class="mdui-card-primary-subtitle mdui-text-truncate"><?php $this->excerpt(100, '...'); ?></div>
      </div>
    </div>
  </div>
  <div class="mdui-card-actions">
	<a href="<?php $this->permalink(); ?>" class="mdui-btn mdui-ripple mdui-float-right mdui-color-theme-accent">开始阅读</a>
	Published by <a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a> on <?php $this->date('F j, Y'); ?> in <?php $this->category(','); ?>.
	    <?php $this->commentsNum('%d Comments'); ?>.
  </div>
</div>
<?php endwhile; ?>
</div>

<?php $this->need('footer.php');?>