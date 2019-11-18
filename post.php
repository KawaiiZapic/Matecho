<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
 $this->need('header.php');
?>
<div id="content-box" class="mdui-container">
	<?php while($this->next()): ?>
	<div class="mdui-card card-archive mdui-center">
		<div class="mdui-card-media">
			<div
				style="background:url(<?php $this->options->themeUrl("static/img/material-1.png");?>);height:300px;background-position:center center;background-size:cover">
			</div>
			<div class="mdui-card-media-covered">
				<div class="mdui-card-primary">
					<div class="mdui-card-primary-title"><?php $this->title() ?></div>
				</div>
			</div>
		</div>
		<div class="mdui-card-actions">
			<div class="mdui-chip" onclick="window.location='<?php $this->author->permalink(); ?>'">
				<img class="mdui-chip-icon" src="<?php echo getGravatar($this->author->mail);?>" />
				<span class="mdui-chip-title"><?php $this->author->screenName(); ?></span>
			</div>
			<div class="mdui-chip"
				onclick="window.location='<?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=F Y')->parse("{permalink}"); ?>'">
				<span class="mdui-chip-icon mdui-color-theme-accent"><i
						class="mdui-icon material-icons">today</i></span>
				<span class="mdui-chip-title"><?php $this->date(); ?> </span>
			</div>
			<div class="mdui-chip mdui-hidden-sm-down"
				onclick="window.location='<?php $this->widget('Widget_Metas_Category_List')->parse("{permalink}"); ?>'">
				<span class="mdui-chip-icon mdui-color-theme-accent"><i class="mdui-icon material-icons">apps</i></span>
				<span class="mdui-chip-title"><?php $this->widget('Widget_Metas_Category_List')->name(); ?></span>
			</div>
			<div class="mdui-chip mdui-hidden-sm-down" <?php if(count($this->tags)>0){ ?>mdui-menu="{target:'#tagmenu_<?php echo $this->cid(); ?>',position:'top',covered:false}"<?php } ?>>
				<span class="mdui-chip-icon mdui-color-theme-accent"><i class="mdui-icon material-icons">local_offer</i></span>
				<span class="mdui-chip-title"><?php echo count($this->tags)>0?count($this->tags)."个":"无";?>标签</span>
			</div>
			<?php if (count($this->tags)>0){ ?>
			<ul class="mdui-menu" id="tagmenu_<?php echo $this->cid(); ?>">
				<li class="mdui-menu-item mdui-ripple">
					<?php $this->tags('<li class="mdui-menu-item mdui-ripple">',true,''); ?></li>
			</ul>
			<?php } ?>
			<div class="mdui-chip mdui-hidden-sm-down"
				onclick="window.location='<?php $this->permalink(); ?>#comments'">
				<span class="mdui-chip-icon mdui-color-theme-accent"><i
						class="mdui-icon material-icons">comment</i></span>
				<span class="mdui-chip-title"><?php $this->commentsNum('0 条评论', '1 条评论', '%d 条评论'); ?></span>
			</div>
		<div class="mdui-typo"><hr></div>
			
  		</div>
		<div class="mdui-card-content">
			<div class="mdui-typo">
				<?php $this->content(); ?>
			</div>
		</div>
	</div>
	<?php endwhile; ?>
</div>
<?php include('comments.php'); ?>
<?php $this->need('footer.php');?>