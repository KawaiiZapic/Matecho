<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;?>
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
	<script src="//cdnjs.loli.net/ajax/libs/smoothscroll/1.4.9/SmoothScroll.min.js"></script>
	<script src="//cdnjs.loli.net/ajax/libs/iamdustan-smoothscroll/0.4.0/smoothscroll.min.js"></script>
	<script src="//cdnjs.loli.net/ajax/libs/blueimp-md5/2.9.0/js/md5.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/pjax@0.2.8/pjax.min.js"></script>
	<style>
		html{
			position:fixed;
			width:100%;
		}
		#pjax-box{
			overflow-y: auto;
			height: 100vh;
			overflow-x: hidden;
		}
		#content-box{	
			padding-top: 30px;
			padding-bottom: 50px;
			min-height: calc(100% - 214px);
		}
		.footer-nav{
			min-height: 150px;
		}
		.card-archive{
			margin-bottom: 30px;
			max-width: 800px;
		}
		.go-out {
    		opacity: 0;
    		transform: scale(0.9);
			filter: blur(10px);
    		pointer-events: none;
		}
		.go-in {
    		opacity: 1!important;
    		transform: scale(1)!important;
    		z-index: 100!important;
			filter: blur(0px)!important;
		}
		div.page-navigator a:not(.comment-show-btn) {
			display:none;
		}
	</style>
	<script>
        var $$ = mdui.JQ;
        var drawer_left = new mdui.Drawer("#drawer-left", {"swipe":true});
        $$("#btn-drawerctl").on("click",function(){drawer_left.toggle();});
        $$(function(){
			var cb = !!window.ActiveXObject || "ActiveXObject" in window ? true : false;
			var an = window.location.toString().split("#")[window.location.toString().split("#").length-1];if(window.location.toString().split("#").length>1){scrollToAnchor(an);}
		    pjax = new Pjax({ elements: ['a[href]:not([non-pjax])'],selectors: ['title', '#content-box','#drawer-items',"#footer"],cacheBust: cb});
			pjax._loadUrl = pjax.loadUrl;
			pjax.cancelThis = false;
			pjax.cancelEvent = false;
			pjax.cancelRequest = function(){
				this.cancelThis = true;
				this.cancelEvent = true;
			}
			pjax.loadUrl = function(href, options) {
  				pjax._loadUrl(href,options);
				if(this.cancelThis){
					this.abortRequest(this.request);
					this.cancelThis = false;
				}
				
			}
			document.addEventListener('pjax:send', function () {if(pjax.cancelEvent){pjax.cancelEvent=false;return false;} $$('#pjax-box').addClass('go-out');$$('#swap').addClass("go-in");if($$('#is-sm-down').css('display') == "none"){var maindrawer = new mdui.Drawer('#main-drawer');maindrawer.close();}});
			document.addEventListener('pjax:success', function () {if(pjax.cancelEvent){pjax.cancelEvent=false;return false;}$$('#pjax-box').removeClass('go-out');$$('#swap').removeClass("go-in");$$("#pjax-box")[0].scrollTop=0;var an = window.location.toString().split("#")[window.location.toString().split("#").length-1];if(window.location.toString().split("#").length>1){scrollToAnchor(an);}mdui.mutation();});
			document.addEventListener("pjax:error",function(){if(pjax.cancelEvent){pjax.cancelEvent=false;return false;} if(e.triggerElement.href !== undefined ){window.location=e.triggerElement.href;}else{mdui.alert('Unexpected Error.');}});
			$$("#search-open").on("click",function(){$$(".mdui-appbar>.mdui-toolbar").addClass("mdui-color-white").removeClass("mdui-color-theme");setTimeout(function(){$$("#search-open").attr("type","submit");},1);})
			$$("#search-close").on("click",function(){$$(".mdui-appbar>.mdui-toolbar").removeClass("mdui-color-white").addClass("mdui-color-theme");$$("#search-open").attr("type","button");})
		});
		function scrollToAnchor(name){
			var t = $$("[name='"+name+"']");
			t = t.length == 0?$$("#"+name) : t;
			if(t.length!=0){
				//IE禁用平滑滚动
				//var b = !!window.ActiveXObject || "ActiveXObject" in window ? 'auto' : 'smooth';
				$$("#pjax-box")[0].scroll({ top: t.position().top-10, behavior: 'smooth' });
				return true;
			}else{
				return false;
			}
		}
	</script>
	<?php $this->header();?>
</head>

<body class="mdui-theme-primary-<?php echo $this->options->me_PrimaryColor; ?> mdui-theme-accent-<?php echo $this->options->me_AccentColor;?> mdui-drawer-body-left mdui-appbar-with-toolbar">
	<div class="mdui-appbar mdui-appbar-fixed">
		<div class="mdui-toolbar mdui-color-theme" style="transition-duration:.25s">
			<a id="btn-drawerctl" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">menu</i></a>
			<a href="<?php $this->options->siteUrl(); ?>" class="mdui-typo-headline"><?php $this->options->title() ?></a>
			<div class="mdui-toolbar-spacer"></div>
			<div style="max-width:300px">
				<div class="mdui-textfield mdui-textfield-expandable mdui-float-right">
					<form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
  						<button id="search-open" type="button" class="mdui-textfield-icon mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">search</i></button>
  						<input id="search-input" name="s" class="mdui-textfield-input" type="text" placeholder="Search"/>
  						<button id="search-close" type="button" class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">close</i></button>
					</form>
				</div>
			</div>
			<a target="_blank" href="<?php $this->options->siteUrl(); ?>admin" class="mdui-btn mdui-btn-icon mdui-ripple" non-pjax><?php if ($this->user->hasLogin()){?><img class="mdui-img-circle" style="margin-top:4px;" src="<?php echo getGravatar($this->user->mail); ?>" /><?php }else{?><i class="mdui-icon material-icons">more_vert</i><?php }?></a>
		</div>
	</div>
	<?php $this->need('sidebar.php');?>
	<div id="pjax-box" style="transition-duration: .25s;">