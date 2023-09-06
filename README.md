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

### 访问（只支持alertmanager推送）
POST http://127.0.0.1/alerts  
POST http://127.0.0.1//prometheusalert  

### TODO
- 路由可按关键词匹配
- 支持静默（按路由/关键词）