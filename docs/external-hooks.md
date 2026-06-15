## 外部插件挂钩

主题在前端实验性地实现了一套零厂商依赖的JavaScript外部挂钩, 以便第三方插件接管主题内的部分功能, 而不需要知晓其内部实现.  
零厂商依赖可以降低任意主题与任意插件之间的耦合, 即便用户不再使用某插件或者某主题, 或者未来需要进行破坏性的更改, 其余的组件也不会因为过耦合导致灾难性的错误.

后端部分尚未设计挂钩.

### 载荷中的`__v`字段

`__v`初始值为`1`, 仅在原有字段出现变更时或被删除时增加, 前向兼容的修改(即新增字段)不会增加此值.

## 已实现的挂钩列表

### 已离开当前页面 (`x-page-unload`)

#### 事件类型

`CustomEvent<{__v: number}>`

**`detail` 载荷：**

| 字段  | 类型     | 说明                                 |
| ----- | -------- | ------------------------------------ |
| `__v` | `number` | 挂钩发起方版本, 预留确保跨版本兼容性 |

#### 返回值

_无返回值_

#### 行为

_此挂钩只能监听_

#### 示例代码

```javascript
window.addEventListener("x-page-unload", e => {
  // clean up
});
```

### 友情链接提交挂钩 (`x-link-submit`)

外部插件可以通过监听 `x-link-submit` 事件接管友情链接申请提交的处理流程.

#### 事件类型

`LinkSubmitEvent`（扩展自 `CustomEvent<LinkInfo>`）

**`detail` 载荷：**

| 字段          | 类型                | 说明                                 |
| ------------- | ------------------- | ------------------------------------ |
| `name`        | `string` (可能为空) | 站点名称                             |
| `url`         | `string` (可能为空) | 站点地址                             |
| `avatar`      | `string` (可能为空) | 头像地址                             |
| `description` | `string` (可能为空) | 站点描述                             |
| `email`       | `string` (可能为空) | 申请者邮箱                           |
| `__v`         | `number`            | 挂钩发起方版本, 预留确保跨版本兼容性 |

#### 返回值

回调可返回 `void` 或对象：

| 返回值                               | 行为                                               |
| ------------------------------------ | -------------------------------------------------- |
| `undefined` / `void`                 | 视为成功, 显示默认成功消息并关闭对话框             |
| `{ success: true }`                  | 视为成功, 同上                                     |
| `{ success: true, message: "xxx" }`  | 视为成功, 显示自定义 message, 并关闭对话框         |
| `{ success: false, message: "xxx" }` | 视为失败, 显示自定义 message, 对话框保持打开       |
| 抛出异常                             | 显示默认异常消息, 对话框保持打开, 不会执行其他流程 |

#### 行为

- 调用 `handleEvent(callback)` 会立即调用 `stopImmediatePropagation()`, 阻止其他同级监听器重复接管
- 当 `handleEvent` 被调用后, 主题原有的提交友链流程在任何情况下(即便抛出异常)都**不会执行**
- 如果没有任何监听器调用 `handleEvent`, 则会执行原有的友链提交流程

#### 示例代码

```javascript
const handler = e => {
  const { name, url, avatar, description, email, __v } = e.detail;
  if (__v !== 1) return;

  e.handleEvent(async () => {
    // 自定义提交流程
    const response = await fetch("/your-api", {
      method: "POST",
      body: JSON.stringify({ name, url, avatar, description, email })
    });
    const data = await response.json();

    if (data.ok) {
      return { success: true, message: "提交成功" };
    } else {
      return { success: false, message: data.error };
    }
  });
};
window.addEventListener("x-link-submit", handler);

// 务必在离开页面时清理监听器, 否则会造成重复监听或者内存泄露
window.addEventListener(
  "x-page-unload",
  e => {
    window.removeEventListener("x-link-submit", handler);
  },
  { once: true }
);
```
