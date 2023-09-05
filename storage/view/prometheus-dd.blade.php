@php
    $var = $externalURL;
    $urimsg = "";
@endphp

@foreach($alerts as $k => $v)
    @if($v["status"] == "resolved")
## [Prometheus恢复信息]({{ $v["generatorURL"] }})
#### [{{ $v["labels"]["alertname"] }}]({{ $var }})
###### 告警级别：{{ $v["labels"]["severity"] }}
###### 开始时间：{{ $v["startsAt"] }}
###### 结束时间：{{ $v["endsAt"] }}
###### 故障主机IP：{{ $v["labels"]["instance"] }}
##### {{ $v["annotations"]["description"] }}
![Prometheus](https://raw.githubusercontent.com/feiyu563/PrometheusAlert/master/doc/images/alert-center.png)
    @else
## [Prometheus告警信息]({{ $v["generatorURL"] }})
#### [{{ $v["labels"]["alertname"] }}]({{ $var }})
###### 告警级别：{{ $v["labels"]["severity"] }}
###### 开始时间：{{ $v["startsAt"] }}
###### 故障主机IP：{{ $v["labels"]["instance"] }}
##### {{ $v["annotations"]["description"] }}
![Prometheus](https://raw.githubusercontent.com/feiyu563/PrometheusAlert/master/doc/images/alert-center.png)
    @endif
@endforeach

@foreach($commonLabels as $key => $value)
    @php
        $urimsg .= $key . "%3D%22" . $value . "%22%2C";
    @endphp
@endforeach

[*** 点我屏蔽该告警]({{ $var }}/#/silences/new?filter=%7B{{ substr($urimsg, 0, -3) }}%7D)