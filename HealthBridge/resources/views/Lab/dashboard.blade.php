@extends('layouts.lab')

@section('content')
@php $page = 'Lab_dashboard'; @endphp
<div class="w-full h-full p-6" style="margin-top: 40px;">
    <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-6">
        <!-- Claims This Week Chart -->
        <div class="flex-1 bg-white rounded-lg shadow p-6">
            {{-- <h2 class="text-xl font-semibold mb-4 text-purple-900">Claims this week</h2> --}}
            <div style="height: 400px; width: 500px;">
                <img src="{{ asset('images/Lab_dashboard_bar_chart.png') }}" alt="barChart">
            </div>
        </div>

        <!-- Accepted Claims Chart -->
        <div class="flex-1 bg-white rounded-lg shadow p-6">
            {{-- <h2 class="text-xl font-semibold mb-4 text-purple-900">Accepted Claims this week</h2> --}}
            <div style="height: 400px; width: 500px;">
                <img src="{{ asset('images/Revenue.png') }}" alt="barChart"">
            </div>
        </div>
    </div>
</div>
@endsection
