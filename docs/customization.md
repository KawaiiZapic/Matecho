## 自定义

如果需要快速修改源码, 可以Fork此仓库, 并启用Github Action, 在网页上直接修改源码, 提交后自动触发构建, 可以在Artifact里找到构建好的主题.
在项目根目录下创建`matecho.config.ts`, 写入以下模板:

```ts
export default {
    PrismLanguages: [];
    ExtraMaterialIcons: [];
}
```

为了保证构建稳定, 项目固定了环境为某一版本, 如果action触发后长时间处于等待中的状态, 可能是GitHub已经将构建所使用的环境标记为弃用并移除.  
请手动更新`.github/workflows/build.yaml`中`runs-on:`字段使用的环境, 可以考虑直接更新为`ubuntu-latest`, 具体请参考GitHub文档.

#### 自定义Prism支持的语言

Prism语言包较小, 如果按Shiki动态加载语言会造成较大的overhead, 因此引入Prism时会直接将所有需要的语言包打包好. 主题默认自带超过30种常用语言, 如果发现你所需的语言没有被打包, 可以在`PrismLanguages`中添加自己想要的语言:

```ts
export default {
    PrismLanguages: ["rust", "groovy"];
    ExtraMaterialIcons: [];
}
```

默认自带的语言参考`vite.config.ts`的`PrismJS`插件配置部分.
如果你计划使用Shiki来高亮代码, 则不需要配置此项, Shiki通过按需加载来高亮所有语言.

#### 自定义Material Icon图标

Material Icon图标非常多, 如果全部加载会使得文件巨大, 故主题只选取了部分图标打包. 如果需要其他的图标, 参考[MDUI图标组件库](https://www.mdui.org/zh-cn/docs/2/libraries/icons)添加.  
只需要写入图标名称即可, 如`import '@mdui/icons/4k-plus.js';`, 只需要填入`4k-plus`.

```ts
export default {
    PrismLanguages: [];
    ExtraMaterialIcons: ["4k-plus", "adjust--rounded"];
}
```

提交代码后, 如果已经启用Action, 主题会自动开始构建, 下载最新构建产物即可.
