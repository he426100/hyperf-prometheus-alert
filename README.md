# 介绍
参考了[feiyu563/PrometheusAlert](https://github.com/feiyu563/PrometheusAlert)，基于[guanguans/notify](https://github.com/guanguans/notify)实现多平台推送。做这个是因为原版除了路由还会发送到alertmanager中推送的地址，用起来不顺手。

### 使用  
```
docker run -d \
    -v ~/prometheus-alert/.env:/opt/www/.env \
    --name hyperf-prometheus-alert \
    -p 9501:9501 \
    he426100/hyperf-prometheus-alert
```

### 配置
路由配置：[config/autoload/alerts.php](https://github.com/he426100/hyperf-prometheus-alert/blob/master/config/autoload/alerts.php)  
模板：[storage/view/](https://github.com/he426100/hyperf-prometheus-alert/tree/master/storage/view/)，模板引擎：[blade](https://learnku.com/docs/laravel/5.5/blade/1304)，理论上支持PrometheusAlert的所有模板  
推送通道的密钥写在`.env`文件中

### 接入
只支持[prometheus](https://feiyu563.gitbook.io/prometheusalert/system/system-prometheus)接入  

### 访问
只支持[alertmanager](https://feiyu563.gitbook.io/prometheusalert/system/system-prometheus)推送  
POST http://127.0.0.1/alerts  
POST http://127.0.0.1/prometheusalert  

### TODO
- 路由可按关键词匹配
- 支持静默（按路由/关键词）
