适用于dcat admin的payjs支付插件
======
## 前提
已安装好laravel及dcat-admin 2.*

安装文档：http://www.dcatadmin.com/docs/2.x/installation.html

## 安装

1. 下载插件压缩包：https://github.com/dedemao/dcat-admin-payjs/archive/v1.0.1.zip

2. 登录后台，在菜单中选择`开发工具`-->`扩展`，点击`本地安装`，在弹窗中选择上面下载的压缩包文件，点击`提交`按钮。

3. 启用并设置插件

4. 发布插件所需要的资源

   ```
   php artisan payjs:install -m
   ```

   这一步将自动创建数据库表、生成路由、生成后台菜单等。

## 卸载

1. 登录后台，在菜单中选择`开发工具`-->`扩展`，点击`卸载`按钮。
2. 删除数据库表及后台菜单：

```
php artisan payjs:uninstall -m
````

## 使用
### 如何支付
指定订单金额：
http://yourname/pay/index?total_fee=0.01

指定订单号：
http://yourname/pay/index?total_fee=0.01&out_trade_no=123456

指定订单标题：
http://yourname/pay/index?total_fee=0.01&subject=测试

指定支付通道：
http://yourname/pay/index?total_fee=0.01&pay_channel=weixin

指定使用JSAPI支付
http://yourname/pay/index?total_fee=0.01&pay_mode=jsapi

指定使用收银台支付
http://yourname/pay/index?total_fee=0.01&pay_mode=cashier

全都指定：
http://yourname/pay/index?out_trade_no=123456&total_fee=0.01&subject=测试&pay_channel=weixin

### 异步通知
异步通知在pay/notify中

### 退款
退款请参考OrderService中的refund方法
