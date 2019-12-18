<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->comments()->to($comments);
function threadedComments($comments, $options) {
    $commentLevelClass = $comments->levels == 1 ? '' : 'mdui-collapse-item-open';
    $commentLevelClass .= $comments->levels == 0 ? ' topLevelComment ' : '';
    $commentLevelClass .= $comments->levels > 1 ? ' micro-margin' : 'mdui-m-a-1'; 
    $arrowLevelClass = $comments->levels > 3 ? ' mdui-hidden-xs-down' : '';
?>
<div id="<?php $comments->theId();?>" class="mdui-card mdui-collapse-item <?php echo $commentLevelClass; ?>">
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
            <div id="reply-<?php $comments->theId();?>"></div>
            <button class="mdui-btn mdui-ripple mdui-float-right replyButton" onclick="replyTo('<?php $comments->theId();?>')">回复</button>

        </div>
    </div>
</div>
<?php }?>
<a id="comments" name="comments"></a>
<form class="mdui-card mdui-m-a-1" id="<?php echo $this->respondId;?>" role="form" method="post" action="<?php $this->commentUrl();?>">
<?php if (!$this->allow('comment')): ?>
<div class="mdui-valign disabled-overlay mdui-ripple">
    <div class="mdui-center mdui-typo mdui-container mdui-text-center"><i class="mdui-icon material-icons mdui-typo-display-3-opacity mdui-text-color-theme-accent">&#xe92a;</i><div class="mdui-typo-subheading-opacity mdui-m-t-1">评论已关闭</div></div>
</div>
<?php endif;?>
    <div class="mdui-text-color-theme mdui-typo-headline mdui-m-t-3 mdui-m-l-2">添加评论</div>
    <?php if ($this->user->hasLogin()): ?>
        <div class="mdui-m-l-2 mdui-m-t-2" style="margin-bottom: -15px;"> <img class="mdui-card-header-avatar mdui-m-r-1" src="<?php echo getGravatar($this->user->mail); ?>" /> <div class="mdui-typo mdui-p-t-1 mdui-m-r-1" style="display:inline-block"><a href="<?php $this->options->profileUrl();?>"><?php $this->user->screenName();?> </a></div><a class="mdui-btn mdui-btn-icon" href="<?php $this->options->logoutUrl();?>" title="Logout"><i class="mdui-icon material-icons">&#xe879;</i></a></div>
    <?php else: ?>
    <div class="mdui-m-x-1 mdui-row">
            <div class="mdui-textfield mdui-textfield-floating-label mdui-col-sm-4 mdui-col-xs-12">
                <div id="avatar-loading" class="mdui-spinner mdui-card-header-avatar mdui-m-t-2 mdui-m-l-1 mdui-hidden"></div>
                <img id="reply-avatar" class="mdui-card-header-avatar mdui-icon" src="https://cdn.v2ex.com/gravatar/0?s=40&d=mp&r=g" />
                <label class="mdui-textfield-label">Email</label>
                <input class="mdui-textfield-input" name="mail" id="reply-mail" type="email" value="<?php $this->remember('mail');?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif;?> <?php if (!$this->allow('comment')) echo "disabled"; ?>/>
                <div class="mdui-textfield-error">无效邮箱</div>
                <div class="mdui-textfield-helper"><?php echo $this->options->commentsRequireMail? "必" : "选" ?>填,用于发送回复邮件.</div>
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label mdui-col-sm-4 mdui-col-xs-6">
                <label class="mdui-textfield-label">昵称</label>
                <input class="mdui-textfield-input" name="author" id="reply-author" maxlength="30" value="<?php $this->remember('author');?>" required <?php if (!$this->allow('comment')) echo "disabled"; ?>/>
                <div class="mdui-textfield-helper">必填,最多30个字符</div>
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label mdui-col-sm-4 mdui-col-xs-6">
                <label class="mdui-textfield-label">博客网址</label>
                <input class="mdui-textfield-input" type="url" name="url" id="reply-url" value="<?php $this->remember('url');?>" <?php if ($this->options->commentsRequireURL): ?> required<?php endif;?> <?php if (!$this->allow('comment')) echo "disabled"; ?>/>
                <div class="mdui-textfield-error">无效网址</div>
                <div class="mdui-textfield-helper"><?php echo $this->options->commentsRequireURL? "必" : "选" ?>填</div>
            </div>
    </div>
    <?php endif;?>
    <div class="mdui-card-content">
        <div id="reply-label" class="mdui-textfield mdui-textfield-floating-label">
            <i class="mdui-icon material-icons">textsms</i>
            <label class="mdui-textfield-label">内容</label>
            <textarea class="mdui-textfield-input" name="text" id="reply-textarea" class="textarea" required <?php if (!$this->allow('comment')) echo "disabled"; ?>><?php $this->remember('text');?></textarea>
            <div class="mdui-textfield-error">内容不得为空</div>
        </div>
    </div>
    <div class="mdui-card-actions">
        <button type="submit" id="submit-btn" class="mdui-btn mdui-ripple mdui-float-right">回复</button>
        <button type="button" mdui-menu="{target: '#smile-menu','position':'top'}" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">&#xe7f2;</i></button>
        <ul class="mdui-menu mdui-menu-cascade mdui-p-t-0" id="smile-menu">
        <div class="mdui-tab mdui-tab-centered mdui-tab-scrollable mdui-p-l-0" mdui-tab>
            <a href="#smile-1" class="mdui-ripple">1</a>
            <a href="#smile-2" class="mdui-ripple">2</a>
            <a href="#smile-3" class="mdui-ripple">3</a>
        </div>
        <div id="smile-1">
            
        </div>
        <div id="smile-2">
            
        </div>
        <div id="smile-3">
            
        </div>
</ul>
    </div>
    
</form>
<?php 

if($this->options->commentsPageDisplay=="last"){
    $comments->pageNav('反向加载更多评论','',0,'',array('wrapTag' => 'div','wrapClass' => 'page-navigator mdui-p-a-2','itemTag' => '','prevClass' => 'prev-btn comment-show-btn mdui-btn-raised mdui-btn mdui-btn-block mdui-ripple mdui-color-theme-accent'));
    $comments->listComments("before=<div id=\"comment-area\" mdui-collapse>&after=</div>");
}else{
    $comments->listComments("before=<div id=\"comment-area\" mdui-collapse>&after=</div>");
    $comments->pageNav('','加载更多评论',0,'',array('wrapTag' => 'div','wrapClass' => 'page-navigator mdui-p-a-2','itemTag' => '','nextClass' => 'next-btn comment-show-btn mdui-btn-raised mdui-btn mdui-btn-block mdui-ripple mdui-color-theme-accent'));
}
?>
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
    .comment-into{
        transform: scale(0.7);
        opacity: 0;
        margin-bottom: -170px!important;
    }
</style>
<?php if ($this->options->commentsAntiSpam && $this->is('single')) {?>
    <!-- AntiSpam -->
<script>
    $$("#<?php echo $this->respondId;?>").append("<input type=\"hidden\" id=\"anti-spam-token\" name=\"_\" value=\"\" />");
    $$(document).one("keyup touchstart mousemove",function(){
        var anti_token = <?php echo Typecho_Common::shuffleScriptVar($this->security->getToken($this->request->getRequestUrl()));?>;
        $$("#anti-spam-token").val(anti_token);
    });
</script>
<?php } ?>
<script>
$$("#reply-mail").on("blur",function(){
    var email = md5($$(this).val());
    var url = "//cdn.v2ex.com/gravatar/"+email+"?s=40&d=mp&r=g";
    $$("#avatar-loading").removeClass("mdui-hidden");
    $$("#reply-avatar").attr("src",url).addClass("mdui-hidden").one("load",function(){$$("#avatar-loading").addClass("mdui-hidden");$$(this).removeClass("mdui-hidden");});
});
$$("#<?php echo $this->respondId;?>").on("submit",function(e){
    e.preventDefault();
    var commentform=this;
    if(!this.checkValidity()) return false;
    $$.ajax({
        'method':'post',
        'url':$$(commentform).attr("action"),
        'data':$$(commentform).serialize(),
        beforeSend: function(){
            $$("#submit-btn").html('<div class="mdui-spinner"></div>').attr("disabled","true");
            mdui.mutation();
        },
        complete: function(){
            $$("#submit-btn").html('回复').removeAttr("disabled");
        },
        success: function (data) {
            $$("#reply-textarea").val("");
            $$("#reply-label").removeClass("mdui-textfield-not-empty");
            mdui.snackbar("评论已提交.");
        },
        error: function(){
            mdui.snackbar({"message":"未能提交评论.","buttonText":"重试","closeOnOutsideClick":false,"onButtonClick":function(){$$("#submit-btn").trigger("click");}});
        }
    })
    });
$$(".prev-btn").on("click",function(e){
    e.preventDefault();
    var url = this.href;
    $$("#comment-area").prepend('<div id="comment-loading" class="mdui-spinner mdui-center mdui-m-a-3"></div>');
    mdui.mutation("#comment-loading");
    $$(".page-navigator").hide();
    scrollToAnchor("comment-loading");
    loadElfromAjax(url,function(el){el[0]=IntoAniPre(el[0]);$$("#comment-area").prepend(el[0]);$$(".comment-into").transition(".25s").each(function(i,v){setTimeout(function(){$$(v).removeClass("comment-into");},25*i);});$$("#comment-loading").remove();if($$(el[1]).attr("href")!==undefined){$$(".prev-btn").attr("href",$$(el[1]).attr("href"));$$(".page-navigator").show();}else{$$(".page-navigator").remove();}mdui.mutation();},".topLevelComment",".prev-btn");
});
$$(".next-btn").on("click",function(e){
    e.preventDefault();
    var url = this.href;
    $$("#comment-area").append('<div id="comment-loading" class="mdui-spinner mdui-center mdui-m-a-3"></div>');
    mdui.mutation("#comment-loading");
    $$(".page-navigator").hide();
    scrollToAnchor("comment-loading");
    loadElfromAjax(url,function(el){el[0]=IntoAniPre(el[0]);$$("#comment-area").append(el[0]);$$(".comment-into").transition(".25s").each(function(i,v){setTimeout(function(){$$(v).removeClass("comment-into");},25*i);});$$("#comment-loading").remove();if($$(el[1]).attr("href")!==undefined){$$(".next-btn").attr("href",$$(el[1]).attr("href"));$$(".page-navigator").show();}else{$$(".page-navigator").remove();}mdui.mutation();},".topLevelComment",".next-btn");
});
function loadElfromAjax(){
    if(arguments.length < 3) return false;
    var url = arguments[0];
    var callbackfn = arguments[1];
    var sltor = {};
    for(i=2;i<arguments.length;i++){
        sltor[i-2]=(arguments[i]);
    }
    var xhr = $$.ajax({
        "method":"GET",
        "url":url,
        success:function(d){
            var el = {};
            $$.each(sltor,function(i,v){
                el[i] = $$(d).find(v);
            })
            callbackfn(el);
        },
        error:function(d){
            
        }
    });
}
function IntoAniPre(el){
    $$.each(el,function(i,v){$$(v).addClass("comment-into");});
    return el;
}
function replyTo(comm){
    var url = "<?php $this->permalink();?>";
    var id= comm.split("-")[1];
    var replyUrl = url+"?replyTo="+id;
    
}
</script>