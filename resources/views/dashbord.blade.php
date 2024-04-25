@extends('layouts.admin')
@section('title')
Dashboard
@endsection

@section('header')
@endsection

@section('heading')
<a class="navbar-brand" href="#">Dashboard</a>
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Welcome to dashboard page</h2>
    <div id="chart"></div>
    <div id="piechart" style="width: 500px; height: 400px;"></div>
</div>
@endsection

@section('scripts')
<script>
    // pincode users apex chart
    const pincodes = @json($pincodes);
    const userCounts = @json($userCounts);

    var options = {
        chart: {
            type: 'bar',
            height: 350,
            width: '100%'
        },
        series: [{
            name: 'Users',
            data: userCounts
        }],
        xaxis: {
            categories: pincodes,
            title: {
                text: 'Pincode'
            }
        },
        yaxis: {
            title: {
                text: 'Number of Users'
            },
            min: 0, // Start Y-axis from 0
            max: 7, // Adjust according to your data
            labels: {
                formatter: function(value) {
                    return value; // No need to format as integers since they're already integers
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();


    // staus users pie charts
    var statusWiseUsersData = @json($statusUser);

    var options = {
        chart: {
            type: 'pie',
        },
        series: [],
        labels: [],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    statusWiseUsersData.forEach(function(item) {
        var label = (item.status == '1') ? 'Active' : 'Inactive';
        options.series.push(item.total);
        options.labels.push(label);
    });

    var chart = new ApexCharts(document.querySelector("#piechart"), options);
    chart.render();
</script>

@endsection