适用于dcat admin的payjs支付插件
======
## 前提
已安装好laravel及dcat admin 2.*

## 安装

```
composer require dedemao/dcat-admin-payjs
php artisan payjs:install -m
````
执行以上命令后，将自动创建数据库表、自动生成后台相关菜单等，登录后台填写相关配置信息即可使用。

## 卸载
```
composer remove dedemao/dcat-admin-payjs
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
