<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<html>
<head>
	<meta charset="<?php $this->options->charset(); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="renderer" content="webkit">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>
	<link rel="stylesheet" href="//cdnjs.loli.net/ajax/libs/mdui/0.4.3/css/mdui.min.css">
	<script src="//cdnjs.loli.net/ajax/libs/mdui/0.4.3/js/mdui.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/pjax@0.2.8/pjax.min.js"></script>
	<style>
		body{
			height: 100%;
			overflow: hidden;
		}
		#content-box{
			overflow-y: auto;
			overflow-x: hidden;
			height: 92%;
			padding-top:16px;
		}
		.footer-nav{
			height: 100px;
		}
		.card-archive{
			margin-top: 30px;
			margin-bottom: 30px;
			max-width: 800px;
		}
	</style>
	<?php $this->header(); ?>
</head>

<body class="mdui-theme-primary-<?php if ($this->options->me_PrimaryColor) echo $this->options->me_PrimaryColor; else echo "indigo"; ?> mdui-theme-accent-<?php if ($this->options->me_AccentColor) echo $this->options->me_AccentColor; else echo "pink"; ?> mdui-drawer-body-left mdui-appbar-with-toolbar">
	<div class="mdui-appbar mdui-appbar-fixed">
		<div class="mdui-toolbar mdui-color-theme">
			<a id="btn-drawerctl" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">menu</i></a>
			<a href="<?php $this->options->siteUrl(); ?>" class="mdui-typo-headline"><?php $this->options->title() ?></a>
			<div class="mdui-toolbar-spacer"></div>
			<a class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">search</i></a>
			<a class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">more_vert</i></a>
		</div>
	</div>
	<?php $this->need('sidebar.php');?>
	<div id="content-box">