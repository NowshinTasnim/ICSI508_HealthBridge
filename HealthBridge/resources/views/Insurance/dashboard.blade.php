@extends('layouts.insurance')

@section('content')
    @php $page = 'Insurance_dashboard'; @endphp
    <div class="w-full h-full p-6 flex justify-center items-center" style="margin-top: 40px;">
        <!-- Claims Pie Chart -->
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center" style="width: 600px;">
            <h2 class="text-xl font-semibold mb-4 text-purple-900 text-center">Claim Status Distribution</h2>
            <div class="flex items-center justify-center" style="height: 400px; width: 500px;">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData); // Data passed from controller

            const ctx = document.getElementById('pieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Approved', 'Rejected', 'None'],
                    datasets: [{
                        data: [chartData.Approved, chartData.Rejected, chartData.None],
                        backgroundColor: ['#4C3957', '#41658A', '#70A37F'], // Green, Red, Yellow
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        });
    </script>
@endsection
