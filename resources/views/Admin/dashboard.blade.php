<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .dashboard-box {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: 0.3s;
        position: relative;
        overflow: hidden;
    }

    .dashboard-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .dashboard-box h4 {
        color: #333;
        font-size: 18px;
        margin-bottom: 10px;
    }

    .dashboard-box p {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 0;
    }

    .dashboard-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 50%;
        height: 4px;
        background: #007bff;
        transform: translateX(-50%);
        border-radius: 2px;
    }

    .row .col-md-3 {
        margin-bottom: 20px;
    }

    .chart-container {
        margin-top: 20px;
        height: 300px;
    }
</style>
<link rel = "icon" href ="/img/logo.jpg" type = "image/x-icon">

@php
    $cats = App\Models\Categories::all()->count();
    $items = App\Models\PizzaItems::all()->count();
    $orders = App\Models\Order::all()->count();
    $users = App\Models\UsersAdmin::all()->count();
@endphp

<body id="body-pd" style="background: #80808045;">
    @extends('admin.layouts.nav')
    @section('content')
        <h1 style="margin-top:98px">Welcome back, <b>{{ session('adminusername') }}</b></h1>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="dashboard-box">
                    <h4>Categories</h4>
                    <p class="counter" data-target="{{ $cats }}">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-box">
                    <h4>Menu Items</h4>
                    <p class="counter" data-target="{{ $items }}">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-box">
                    <h4>Orders</h4>
                    <p class="counter" data-target="{{ $orders }}">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-box">
                    <h4>Customers</h4>
                    <p class="counter" data-target="{{ $users }}">0</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 chart-container">
                    <h3 class="text-center mb-4">Data Distribution</h3>
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="col-md-6 chart-container">
                    <h3 class="text-center mb-4">Daily Orders (Last 7 Days)</h3>
                    <canvas id="orderLineChart"></canvas>
                </div>
            </div>
        </div>
    @endsection
    <script>
        $(document).ready(function() {
            $('.counter').each(function() {
                var $this = $(this),
                    countTo = $this.attr('data-target');
                $({
                    countNum: $this.text()
                }).animate({
                    countNum: countTo
                }, {
                    duration: 1000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                    }
                });
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script>
        // Sample data for daily orders (last 7 days)
        // In a real app, this would come from your backend
        const orderData = {
            dates: ['Nov 19', 'Nov 20', 'Nov 21', 'Nov 22', 'Nov 23', 'Nov 24', 'Nov 25'],
            counts: [12, 18, 22, 15, 27, 24, 20]
        };


        // Counter animation
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter');

            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const increment = Math.ceil(target / 100);
                let count = 0;

                const updateCount = () => {
                    if (count < target) {
                        count += increment;
                        if (count > target) count = target;
                        counter.textContent = count;
                        setTimeout(updateCount, 10);
                    } else {
                        counter.textContent = target;
                    }
                };

                updateCount();
            });

            // Get values for pie chart
            const values = [];
            const labels = [];

            document.querySelectorAll('.dashboard-box').forEach(box => {
                const label = box.querySelector('h4').textContent;
                const value = parseInt(box.querySelector('.counter').getAttribute('data-target'));

                labels.push(label);
                values.push(value);
            });

            // Create pie chart with plugin for displaying labels
            const ctxPie = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#FF4D4D', // Bright Red
                            '#0984E3', // Blue
                            '#FFB84C', // Yellow
                            '#2A9D8F', // Strong Blue
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const sum = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / sum) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        // Simpler approach using the datalabels plugin concept
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / sum) * 100);
                                return value + ' (' + percentage + '%)';
                            },
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'datalabels',
                    afterDatasetsDraw: function(chart) {
                        const ctx = chart.ctx;

                        chart.data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);
                            if (!meta.hidden) {
                                meta.data.forEach((element, index) => {
                                    // Get the data value
                                    const data = dataset.data[index];
                                    const sum = dataset.data.reduce((a, b) =>
                                        a + b, 0);
                                    const percent = Math.round((data / sum) *
                                        100);

                                    // Don't render small slices
                                    if (percent < 5) return;

                                    // Get position and size
                                    const {
                                        x,
                                        y
                                    } = element.getCenterPoint();

                                    // Draw the text
                                    ctx.fillStyle = '#fff';
                                    ctx.font = '12px Arial';
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    ctx.fillText(`${data} (${percent}%)`, x, y);
                                });
                            }
                        });
                    }
                }]
            });

            // Create line chart for daily orders
            const ctxLine = document.getElementById('orderLineChart').getContext('2d');
            const lineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: orderData.dates,
                    datasets: [{
                        label: 'Number of Orders',
                        data: orderData.counts,
                        fill: false,
                        borderColor: '#36A2EB',
                        tension: 0.1,
                        pointBackgroundColor: '#36A2EB',
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Order Count'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Orders: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
