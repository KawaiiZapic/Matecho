<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form) {
    $primarycolors = array(
        "red"=>"红色",
        "pink"=>"粉色",
        "purple"=>"紫色",
        "deep-purple"=>"深紫色",
        "indigo"=>"靛蓝色",
        "blue"=>"蓝色",
        "light-blue"=>"淡蓝色",
        "cyan"=>"蓝绿色",
        "teal"=>"水鸭色",
        "green"=>"绿色",
        "light-green"=>"淡绿色",
        "lime"=>"青橙绿色",
        "yellow"=>"黄色",
        "amber"=>"琥珀色",
        "orange"=>"橘色",
        "deep-orange"=>"深橘色",
        "brown"=>"褐色",
        "grey"=>"灰色",
        "blue-grey"=>"蓝灰色"
    );
    $accentcolors = array(
        "red"=>"红色",
        "pink"=>"粉色",
        "purple"=>"紫色",
        "deep-purple"=>"深紫色",
        "indigo"=>"靛蓝色",
        "blue"=>"蓝色",
        "light-blue"=>"淡蓝色",
        "cyan"=>"蓝绿色",
        "teal"=>"水鸭色",
        "green"=>"绿色",
        "light-green"=>"淡绿色",
        "lime"=>"青橙绿色",
        "yellow"=>"黄色",
        "amber"=>"琥珀色",
        "orange"=>"橘色",
        "deep-orange"=>"深橘色"
    );

    $primarycolor = new Typecho_Widget_Helper_Form_Element_Select('primaryColor',$primarycolors, _t('主题色'), _t('设置页面主题色'));
    $form->addInput($primarycolor);
    $accentcolor = new Typecho_Widget_Helper_Form_Element_Select('accentColor',$accentcolors, _t('强调色'), _t('设置页面强调色'));
    $form->addInput($accentcolor);
}
