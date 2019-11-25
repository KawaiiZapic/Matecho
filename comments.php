<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->comments()->to($comments);
function threadedComments($comments, $options) {
    $commentLevelClass = $comments->levels == 1 ? '' : 'mdui-collapse-item-open';
    $commentLevelClass .= $comments->levels > 1 ? ' micro-margin' : ' mdui-m-a-1'; 
    $arrowLevelClass = $comments->levels > 3 ? ' mdui-hidden-xs-down' : '';
?>
<div id="comment-<?php $comments->theId();?>" class="mdui-card mdui-collapse-item <?php echo $commentLevelClass; ?>">
    <div class="mdui-collapse-item-header" style="pointer-events:none">
        <div class="mdui-card-header" style="display:inline-block">
            <img class="mdui-card-header-avatar" src="<?php echo getGravatar($comments->mail); ?>" />
            <div class="mdui-card-header-title"><?php $comments->author();?></div>
            <div class="mdui-card-header-subtitle"><?php $comments->date('Y-m-d H:i');?></div>
        </div>
        <a class="mdui-btn mdui-btn-icon mdui-float-right mdui-ripple arrow-btn<?php echo $arrowLevelClass; ?> ">
            <div class="mdui-collapse-item-arrow" style="height:36px"><i class="mdui-icon material-icons">keyboard_arrow_down</i></div>
        </a>
    </div>
    <div class="mdui-collapse-item-body">
        <div class="mdui-card-contents mdui-p-x-2">
            <?php $comments->content();?>
        </div>
        <?php if ($comments->children) $comments->threadedComments($options);?>
        <div class="mdui-card-actions">
            <button class="mdui-btn mdui-ripple mdui-float-right"><?php $comments->reply();?></button>
            
        </div>
    </div>
</div>
<?php }?>

<form class="mdui-card mdui-m-a-1" id="comment-form" role="form" method="post" action="<?php $this->commentUrl();?>">
<?php if (!$this->allow('comment')): ?>
<div class="mdui-valign disabled-overlay mdui-ripple">
    <div class="mdui-center mdui-typo mdui-container mdui-text-center"><i class="mdui-icon material-icons mdui-typo-display-3-opacity mdui-text-color-theme-accent">&#xe92a;</i><div class="mdui-typo-subheading-opacity mdui-m-t-1">评论已关闭</div></div>
</div>
<?php endif;?>
    <div class="mdui-text-color-theme mdui-typo-headline mdui-m-t-3 mdui-m-l-2">添加评论</div>
    <?php if ($this->user->hasLogin()): ?>
        <div class="mdui-m-l-2 mdui-m-t-2" style="margin-bottom: -16px;"> <img class="mdui-card-header-avatar mdui-m-r-1" src="<?php echo getGravatar($this->user->mail); ?>" /> <div class="mdui-typo mdui-p-t-1 mdui-m-r-1" style="display:inline-block"><a href="<?php $this->options->profileUrl();?>"><?php $this->user->screenName();?> </a></div><a class="mdui-btn mdui-btn-icon" href="<?php $this->options->logoutUrl();?>" title="Logout"><i class="mdui-icon material-icons">&#xe879;</i></a></div>
    <?php else: ?>
    <div class="mdui-m-x-1 mdui-row">
            <div class="mdui-textfield mdui-textfield-floating-label mdui-col-sm-4 mdui-col-xs-12">
                <img class="mdui-card-header-avatar mdui-icon" src="https://cdn.v2ex.com/gravatar/0?s=40&d=mp&r=g" />
                <label class="mdui-textfield-label">Email</label>
                <input class="mdui-textfield-input" name="mail" id="mail" type="email" value="<?php $this->remember('mail');?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif;?> <?php if (!$this->allow('comment')) echo "disabled"; ?>/>
                <div class="mdui-textfield-error">无效邮箱</div>
                <div class="mdui-textfield-helper"><?php echo $this->options->commentsRequireMail? "必" : "选" ?>填,用于发送回复邮件.</div>
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label mdui-col-sm-4 mdui-col-xs-6">
                <label class="mdui-textfield-label">昵称</label>
                <input class="mdui-textfield-input" name="author" id="author" maxlength="30" value="<?php $this->remember('author');?>" required <?php if (!$this->allow('comment')) echo "disabled"; ?>/>
                <div class="mdui-textfield-helper">必填,最多30个字符</div>
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label mdui-col-sm-4 mdui-col-xs-6">
                <label class="mdui-textfield-label">博客网址</label>
                <input class="mdui-textfield-input" type="url" name="url" id="url" value="<?php $this->remember('url');?>" <?php if ($this->options->commentsRequireURL): ?> required<?php endif;?> <?php if (!$this->allow('comment')) echo "disabled"; ?>/>
                <div class="mdui-textfield-error">无效网址</div>
                <div class="mdui-textfield-helper"><?php echo $this->options->commentsRequireURL? "必" : "选" ?>填</div>
            </div>
    </div>
    <?php endif;?>
    <div class="mdui-card-content">
        <div class="mdui-textfield mdui-textfield-floating-label">
            <i class="mdui-icon material-icons">textsms</i>
            <label class="mdui-textfield-label">内容</label>
            <textarea class="mdui-textfield-input" name="text" id="textarea" class="textarea" required <?php if (!$this->allow('comment')) echo "disabled"; ?>><?php $this->remember('text');?></textarea>
        </div>
    </div>
    <div class="mdui-card-actions">
        <button class="mdui-btn mdui-ripple mdui-float-right">回复</button>
        <button class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">&#xe7f2;</i></button>
    </div>
</form>
<?php $comments->listComments("before=<div mdui-collapse>&after=</div>");?>
<style>
    .comment-list {
        padding: 0px !important;
    }

    .micro-margin {
        margin: 8px 4px;
    }

    .arrow-btn {
        pointer-events: all;
        margin: 18px 4px 0 0;
    }
    .disabled-overlay{
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    z-index: 999;
    }
</style>
<script>
</script>