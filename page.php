<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');
?>
<div id="content-box" class="mdui-container">
	<div class="mdui-card card-archive mdui-center">
	<div class="mdui-card-actions">
            <div class="mdui-card-header" style="display:inline-block;cursor: pointer;">
                <img class="mdui-card-header-avatar" onclick="pjax.loadUrl('<?php $this->author->permalink();?>')"
                    src="<?php echo getGravatar($this->author->mail); ?>" />
                <div class="mdui-card-header-title" onclick="pjax.loadUrl('<?php $this->author->permalink();?>')">
                    <?php $this->author->screenName();?></div>
                <div class="mdui-card-header-subtitle"
                    onclick="pjax.loadUrl('<?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=F Y')->parse("{permalink}");?>')">
                    <?php $this->date();?></div>
            </div>
            <div class=" mdui-m-t-2 mdui-float-right" style="display:inline-block!important">
					<div class="mdui-chip mdui-hidden-sm-down mdui-float-right mdui-m-l-1"
                    onclick="scrollToAnchor('comments')">
                    <span class="mdui-chip-icon mdui-color-theme-accent"><i
                            class="mdui-icon material-icons">comment</i></span>
                    <span class="mdui-chip-title"><?php $this->commentsNum('无评论', '%d 条评论');?></span>
                </div>
            </div>
        </div>
		<div class="mdui-card-media ">
			<div
				style="background:url(<?php $this->options->themeUrl("static/img/material-1.png");?>);height:200px;background-position:center center;background-size:cover">
			</div>
			<div class="mdui-card-media-covered mdui-card-media-covered-gradient">
				<div class="mdui-card-primary">
					<div class="mdui-card-primary-title"><?php $this->title();?></div>
				</div>
			</div>
		</div>
		<div class="mdui-card-content mdui-p-t-5">
			<div class="mdui-typo">
				<?php $this->content();?>
			</div>
		</div>
		<div class="mdui-typo"><hr></div>
			<?php $this->need('comments.php');?>
	</div>
</div>

<?php $this->need('footer.php');?>