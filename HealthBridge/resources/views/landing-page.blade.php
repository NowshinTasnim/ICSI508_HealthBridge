@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h1 class="text-4xl font-bold text-purple-900">Streamline Your Diagnostic Journey</h1>
            <p class="text-gray-600 text-lg">At Healthbridge, we're revolutionizing how patients, laboratories, and insurance companies interact.</p>
            <p class="text-gray-600">Our innovative Double Claim Filing System significantly reduces out-of-pocket expenses for diagnostic tests.</p>

            <div class="space-y-4 mt-8">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-purple-800 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">Efficient test scheduling and result delivery</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-purple-800 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">Automated double claim filing to maximize coverage</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-purple-800 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">Real-time updates on claim status</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-purple-800 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700">Reduces stress and financial burden for patients</span>
                </div>
            </div>

            <a href="{{ route('register') }}"
               class="inline-block mt-8 bg-purple-800 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                Get Started Now
            </a>
        </div>

        <div class="hidden md:block">
{{--            <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">--}}
{{--                <span class="text-gray-500">Healthcare Illustration Placeholder</span>--}}
{{--            </div>--}}
                <img src="{{ asset('images/landing_page.png') }}" alt="description of image" style ="width: 470px; height:auto" >

        </div>
    </div>
</div>
@endsection
