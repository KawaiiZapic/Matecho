## 开发

### 依赖

`node>=24`
`pnpm>=11`
`php>=7.4`

### 启动

项目会自动下载并启动一个开发用途的临时Typecho(位于`node_modules/.cache/typecho`), 不需要额外配置.

```
pnpm i
pnpm dev
```

#### 使用外部的Typecho实例

可以通过创建`.env.local`文件并设置`VITE_BACKEND_URL`指定要使用的Typecho实例.  
例如`VITE_BACKEND_URL=http://localhost:8080/`  
若要使用自定义的Typecho实例, 需要将`dist`文件夹软链接到Typecho目录中`/usr/themes/Matecho/`  
若使用了Docker安装Typecho，软链接不会生效，请使用Docker的挂载选项将`dist`文件夹挂载到Typecho目录中`/usr/themes/Matecho/`  
Vite被配置为从PHP服务器拉取HTML再处理, 故Vite需要能够访问到PHP服务器.  
同时需要配置Typecho的`站点地址`为Vite暴露的开发服务器地址, 否则某些静态资源可能会出现跨域问题.  
如果需要使用其他域名, 需要修改Vite设置`server.host`为相同域名.  
使用其他域名时, 由于浏览器安全限制, 除`localhost`外的域名在不启用SSL的情况下无法使用某些特性.  
如果需要启用SSL, 需要同时为PHP服务器和Vite都配置SSL, 否则会导致请求来源不匹配, 无法在`dev`环境里使用评论等功能.

**注意**

1. 该项目并未预期直接修改编译后产物, 构建过程包括很多重要的逻辑, 务必从源码修改后编译(参考上方"自定义"章节快速修改源码)
2. 以`m-`开头的CSS类在由UnoCSS在构建过程中自动生成, 会随源码改变而改变, 不可依赖其定位元素

## 构建

```
pnpm i
pnpm build
```

生成在`dist`目录下, 重命名`dist`文件夹到`Matecho`, 并放在`usr/theme`下.
