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
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$this->need('header.php');
?>
<div id="content-box" class="mdui-container">
    <div class="mdui-text-color-theme-accent mdui-typo-display-1 mdui-p-b-3"><?php
	$this->archiveTitle(array(
		'category'  =>  _t('分类 %s 下的文章'),
		'search'    =>  _t('包含关键字 %s 的文章'),
		'tag'       =>  _t('标签 %s 下的文章'),
		'author'    =>  _t('%s 发布的文章')
	), '', ''); 
	?></div>
    <?php while ($this->next()): ?>
    <div class="mdui-card card-archive mdui-center">
        <div class="mdui-card-media">
            <div
                style="background:url(<?php $this->options->themeUrl("static/img/material-1.png");?>);height:300px;background-position:center center;background-size:cover">
            </div>
            <div class="mdui-card-media-covered">
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title"><?php $this->title();?></div>
                    <div class="mdui-card-primary-subtitle mdui-text-truncate"><?php $this->excerpt(100, '...');?>
                    </div>
                </div>
            </div>
        </div>
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
                <a href="<?php $this->permalink();?>"
                    class="mdui-btn mdui-ripple mdui-float-right mdui-color-theme-accent mdui-m-l-3">开始阅读</a>
					<div class="mdui-chip mdui-hidden-sm-down mdui-float-right mdui-m-l-1"
                    onclick="pjax.loadUrl('<?php $this->permalink();?>#comments')">
                    <span class="mdui-chip-icon mdui-color-theme-accent"><i
                            class="mdui-icon material-icons">comment</i></span>
                    <span class="mdui-chip-title"><?php $this->commentsNum('无评论', '%d 条评论');?></span>
                </div>
                <div class="mdui-chip mdui-hidden-sm-down mdui-float-right mdui-m-l-1"
                    <?php if (count($this->tags) > 0) {?>mdui-menu="{target:'#tagmenu_<?php echo $this->cid(); ?>',position:'top',covered:false}"
                    <?php }?>>
                    <span class="mdui-chip-icon mdui-color-theme-accent"><i
                            class="mdui-icon material-icons">local_offer</i></span>
                    <span
                        class="mdui-chip-title"><?php echo count($this->tags) > 0 ? count($this->tags) . "个" : "无"; ?>标签</span>
                </div>
                <?php if (count($this->tags) > 0) {?>
                <ul class="mdui-menu" id="tagmenu_<?php echo $this->cid(); ?>">
                    <li class="mdui-menu-item mdui-ripple">
                        <?php $this->tags('<li class="mdui-menu-item mdui-ripple">', true, '');?></li>
                </ul>
                <?php }?>
				<div class="mdui-chip mdui-hidden-sm-down mdui-float-right mdui-m-l-1"
                    onclick="pjax.loadUrl('<?php $this->widget('Widget_Metas_Category_List')->parse("{permalink}");?>')">
                    <span class="mdui-chip-icon mdui-color-theme-accent"><i
                            class="mdui-icon material-icons">apps</i></span>
                    <span class="mdui-chip-title"><?php $this->widget('Widget_Metas_Category_List')->name();?></span>
                </div>
                
            </div>

        </div>
    </div>
    <?php endwhile;?>
    <?php if ($this->is("index")) {?>
    <div class="mdui-row mdui-center mdui-m-b-3" style="max-width:800px;">
        <div class="mdui-col-xs-6 mdui-col-sm-4 mdui-m-t-1" style="text-align:center;">
            <?php if ($this->_currentPage > 1) {?>
            <?php $this->pageLink('<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">上一页</button>');?>
            <?php } else {?>
            <a class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" disabled>上一页</a>
            <?php }?>
        </div>
        <div class="mdui-hidden-sm-up mdui-col-xs-6 mdui-col-sm-4 mdui-m-t-1" style="text-align:center;">
            <?php if ($this->_currentPage < ceil($this->getTotal() / $this->parameter->pageSize)) {?>
            <?php $this->pageLink('<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">下一页</button>', 'next');?>
            <?php } else {?>
            <a class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" disabled>下一页</a>
            <?php }?>
        </div>
        <div class="mdui-col-xs-12 mdui-col-sm-4 mdui-m-t-1" style="text-align:center;">
            <a class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent mdui-btn-block">
                <?php echo $this->_currentPage; ?>/<?php echo ceil($this->getTotal() / $this->parameter->pageSize); ?>
            </a>
        </div>
        <div class="mdui-hidden-xs mdui-col-sm-4 mdui-m-t-1" style="text-align:center;">
            <?php if ($this->_currentPage < ceil($this->getTotal() / $this->parameter->pageSize)) {?>
            <?php $this->pageLink('<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">下一页</button>', 'next');?>
            <?php } else {?>
            <a class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" disabled>下一页</a>
            <?php }?>
        </div>
    </div>
    <?php }?>

</div>
<?php $this->need('footer.php');?>