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
		<div class="mdui-card-actions" onclick="window.loaction='<?php $this->author->permalink(); ?>'">
		<div class="mdui-chip">
  			<img class="mdui-chip-icon" src="<?php echo getGravatar($this->author->mail);?>"/>
  			<span class="mdui-chip-title mdui-text-color-theme-accent"><?php $this->author->screenName(); ?></span>
		</div>
		<?php $this->date(); ?> in <?php $this->category(','); ?>.
	    	<?php $this->commentsNum('%d Comments'); ?>.
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

<?php $this->need('footer.php');?>