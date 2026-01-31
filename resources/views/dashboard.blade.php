@extends('backend.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <h4 class="fw-bold mb-4">Dashboard</h4>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-animate p-3">
                    <p>Total Users</p>
                    <h2>{{ $totalUsers ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-animate p-3">
                    <p>Active Users</p>
                    <h2>{{ $activeUsers ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-animate p-3">
                    <p>Visitors</p>
                    <h2>{{ $visitors ?? 0 }}</h2>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="card card-animate p-3">
                    <p>Referral Users</p>
                    <h2>{{ $referralUsers ?? 0 }}</h2>
                </div>
            </div> --}}
        </div>

        <!-- User Growth Chart -->
        <div class="card p-4 mt-4">
            <h5>User Growth</h5>

            <div class="btn-group mb-3">
                <button class="btn btn-primary btn-sm chart-btn" data-period="week">This Week</button>
                <button class="btn btn-light btn-sm chart-btn" data-period="month">Last Month</button>
                <button class="btn btn-light btn-sm chart-btn" data-period="year">This Year</button>
            </div>

            <canvas id="userChart" height="100"></canvas>
        </div>

    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let ctx = document.getElementById('userChart').getContext('2d');

        let userChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'New Users',
                    data: [],
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(54,162,235,0.2)',
                    borderColor: 'rgba(54,162,235,1)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function loadChart(period) {
            $.ajax({
                url: "{{ route('backend.admin.userGrowth') }}",
                type: "GET",
                data: {
                    period: period
                },
                success: function(res) {
                    userChart.data.labels = res.labels;
                    userChart.data.datasets[0].data = res.users;
                    userChart.update();
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        loadChart('week');

        $('.chart-btn').on('click', function() {
            $('.chart-btn').removeClass('btn-primary').addClass('btn-light');
            $(this).removeClass('btn-light').addClass('btn-primary');

            loadChart($(this).data('period'));
        });
    </script>
@endpush
