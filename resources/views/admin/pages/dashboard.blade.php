@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-4">

        <div class="col-md-12">
            <div class="widget">
                <div class="widget-heading clearfix">
                    <div class="pull-left">Posts</div>
                </div>
                <div class="widget-body clearfix">
                    <div class="pull-left">
                        <i class="fa fa-file"></i>
                    </div>
                    <div class="pull-right number">{{\App\Post::count()}}</div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <div class="widget">
                <div class="widget-heading clearfix">
                    <div class="pull-left">Categories</div>
                </div>
                <div class="widget-body clearfix">
                    <div class="pull-left">
                        <i class="fa fa-folder-open"></i>
                    </div>
                    <div class="pull-right number">{{\App\Category::count()}}</div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="chart"></div>
    </div>

</div>

@endsection

@section("scriptSrc")
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section("docReadyScript")

var options = {
    chart: {
        height: 350,
        type: 'line',
        shadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 1
        },
        toolbar: {
            show: false
        }
    },
    colors: ['#77B6EA', '#545454'],
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: 'smooth'
    },
    series: [{
            name: "Page Views",
            data: {{$views}}
        },
        {
            name: "Unique Visitors",
            data: {{$visitors}}
        }
    ],
    title: {
        text: 'Daily Page Views',
        align: 'left'
    },
    grid: {
        borderColor: '#e7e7e7',
        row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
        },
    },
    markers: {
        size: 6
    },
    xaxis: {
        categories: [ 
            "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", 
            "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00",
            "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"
        ],
        title: {
            text: 'Hours'
        }
    },
    yaxis: {
        title: {
            text: 'Visits'
        },
        min: 0,
        max: Math.max({{$views}})
    },
    legend: {
        position: 'top',
        horizontalAlign: 'right',
        floating: true,
        offsetY: -25,
        offsetX: -5
    }
}

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
@endsection