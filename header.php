<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
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
<?php $this->header(); ?>
</head>
<body class="mdui-theme-primary-<?php if ($this->options->primaryColor) echo $this->options->primaryColor; else echo "indigo"; ?> mdui-theme-accent-<?php if ($this->options->accentColor) echo $this->options->accentColor; else echo "pink"; ?>">
    <div class="mdui-appbar">
      <div class="mdui-toolbar mdui-color-theme">
        <a class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">menu</i></a>
        <a href="<?php $this->options->siteUrl(); ?>" class="mdui-typo-headline"><?php $this->options->title() ?></a>
        <div class="mdui-toolbar-spacer"></div>
        <a class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">search</i></a>
        <a class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">more_vert</i></a>
      </div>
    </div>