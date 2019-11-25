//动态载入反垃圾
<?php
    $header = '';
    if ($this->options->commentsAntiSpam && $this->is('single')) {
                $header .= "<script type=\"text/javascript\">


    var r = document.getElementById('{$this->respondId}'),
    input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_';
    input.value = " . Typecho_Common::shuffleScriptVar(
        $this->security->getToken($this->request->getRequestUrl())) . "
    if (null != r) {
        var forms = r.getElementsByTagName('form');
        if (forms.length > 0) {
            function append() {
                    forms[0].appendChild(input);
				
			}
			append();
		}
	}

</script>";
            } else {
                $header .= '<script src="" type="text/javascript"></script>';
            }
echo $header;
    ?>

    //评论
