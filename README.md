# Matecho

Material Design typecho theme base on MDUI.
![screenshot](https://media.githubusercontent.com/media/KawaiiZapic/Matecho/md3/public/screenshot.png)

## 特性

1. 自定义主题色
2. 使用分块和按需加载技术, 首屏(不包括图片)只需要`100 KBytes`的资源, 根据文章的内容自动确定需要加载的插件.
3. 支持`Fancybox`图片灯箱
4. 支持`Prism`/`Shiki`代码高亮
5. 支持`KaTeX`公式渲染
6. 支持`Mermaid`图表绘制
7. 丰富的编辑器集成, 在Typecho后台的编辑器中即时渲染图表, 公式, 以及代码高亮
8. 通过glot.io在沙箱中即时运代码块中的代码并显示结果, 允许就地编辑修改并执行新的代码, 支持数十种语言
9. 支持`OpenGraph`/`TwitterCard`, 在受支持的应用中以卡片形式展示文章链接
10. `ExSearch`前端即时搜索增强集成(使用主题自带样式而不是使用它默认的搜索弹窗)
11. `Mailer`邮件插件集成
12. `Links`友链插件集成
13. 完善的响应式支持, 手机电脑共用一套主题
14. 使用最新的Web技术构建, 并向前兼容到`Chrome >= 66`, `Firefox >= 67`, `Safari >= 12`(对于较旧的浏览器仅包括有限的支持)

## 安装

1. 从Releases下载最新版的主题文件或者Action中下载自动构建的测试版主题
2. 将主题解压到`/usr/themes/Matecho/`中
3. 在Typecho设置中启用主题

## 插件集成

### ExSearch

支持与原版[ExSearch](https://github.com/AlanDecode/Typecho-Plugin-ExSearch)直接集成.  
原版插件已不再维护, 缺乏对 Typecho > 1.2 的支持, 如果原版无法使用, 请使用此版本: [KawaiiZapic/Typecho-Plugin-ExSearch](https://github.com/KawaiiZapic/Typecho-Plugin-ExSearch)  
无需额外设置, 只需启用插件即可.

### Links

支持与原版Links插件直接集成.  
原版Links插件存在严重的漏洞, 请使用第三方维护的版本[Mejituu/Links](https://github.com/Mejituu/Links).
启用插件后, 请在后台创建一个独立页面, 并使用"友情链接"作为模板. 标题将会作为友链页面的标题, 禁用评论将会禁用友链申请表单.

### Mailer

支持与原版Mailer插件直接集成.
原版插件已不再维护, 缺乏对 Typecho > 1.2 的支持, 在高版本的情况下会出现无法异步发送邮件的情况, 并静默失败导致丢信, 如果遇到问题, 请使用此版本[KawaiiZapic/Typecho-Plugin-Mailer](https://github.com/KawaiiZapic/Typecho-Plugin-Mailer).

## 配置glot.io代码运行

1. 请确保启用了 PHP 的 cURL 拓展
2. 在[https://glot.io/auth/page/simple/register](https://glot.io/auth/page/simple/register)中注册账号
3. 从[https://glot.io/account/token](https://glot.io/account/token)复制API token并填写到主题设置中
4. 在需要运行的代码块前添加`<!--{runnable}-->`注释
   ````
   <!--{runnable}-->
   ```c
   #include <stdio.h>
   void main() {
       printf("hello");
   }
   ```
   ````

## 说明文档

[自定义指南](./docs/customization.md): 轻度修改源码, 添加额外的语法高亮支持, 添加需要的图标.  
[开发指南](./docs/development-setup.md): 从零快速搭建开发环境, 只需拥有基本的运行时.
