@php
    $var = $externalURL;
    $urimsg = "";
@endphp

@foreach($alerts as $k => $v)
    @if($v["status"] == "resolved")
[PROMETHEUS-恢复信息]({{ $v["generatorURL"] }})
> **[{{ $v["labels"]["alertname"] }}]({{ $var }})**
> <font color="info">告警级别:</font> {{ $v["labels"]["severity"] }}
> <font color="info">开始时间:</font> {{ $v["startsAt"] }}
> <font color="info">结束时间:</font> {{ $v["endsAt"] }}
> <font color="info">故障主机IP:</font> {{ $v["labels"]["instance"] }}
> <font color="info">**{{ $v["annotations"]["description"] }}**</font>
    @else
[PROMETHEUS-告警信息]({{ $v["generatorURL"] }})
> **[{{ $v["labels"]["alertname"] }}]({{ $var }})**
> <font color="warning">告警级别:</font> {{ $v["labels"]["severity"] }}
> <font color="warning">开始时间:</font> {{ $v["startsAt"] }}
> <font color="warning">故障主机IP:</font> {{ $v["labels"]["instance"] }}
> <font color="warning">**{{ $v["annotations"]["description"] }}**</font>
    @endif
@endforeach

@foreach($commonLabels as $key => $value)
    @php
        $urimsg .= $key . "%3D%22" . $value . "%22%2C";
    @endphp
@endforeach

[*** 点我屏蔽该告警]({{ $var }}/#/silences/new?filter=%7B{{ substr($urimsg, 0, -3) }}%7D)