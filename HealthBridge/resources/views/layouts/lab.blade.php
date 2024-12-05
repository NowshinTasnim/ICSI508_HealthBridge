<!-- resources/views/layouts/lab.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HealthBridge Lab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- <script src="{{ asset('js/lab.js') }}"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/lab.css') }}">
</head>
<body class="bg-gray-50">
<!-- Header/Navigation -->
<nav class="bg-white shadow-lg">
    <div class="head_part">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('Lab.dashboard') }}">
                    <img src="{{ asset('images/HealthBridgeLogo.png') }}" alt="HealthBridge" style="height: 40px;" >
                </a>
            </div>

            <!-- Search and User Info -->
            <div class="flex items-center space-x-6">
                <!-- Search -->
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <button class="p-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                    <input type="text" id="searchInput"
                           placeholder="  Search for Patient"
                           class="w-64 px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-purple-500"
                           onkeyup="searchPatient('{{ $page }}')">
                </div>

                <!-- Contact Us -->
                <div class="relative flex items-center contact-us-box">
                    <img src="{{ asset('images/msg.png') }}" alt="Message Icon" class="contact-icon" style="height: 15px;">
                    <a href="mailto:snehakhan52@gmail.com?subject=Support%20Request&body=Hi%20Team,%0A%0AI%20need%20help%20with..." class="contact-us-text" style="text-decoration: none;">Contact</a>
                </div>
                {{-- <div class="relative flex items-center contact-us-box" style="display: flex; align-items: center; gap: 5px;">
                    <a href="mailto:snehakhan52@gmail.com?subject=Support%20Request&body=Hi%20Team,%0A%0AI%20need%20help%20with..." class="contact-us-text" style="display: flex; align-items: center; text-decoration: none;">
                        <img src="{{ asset('images/msg.png') }}" alt="Message Icon" class="contact-icon" style="height: 15px; margin-right: 5px;">
                        <span>Contact</span>
                    </a>
                </div> --}}


                <!-- Lab Profile -->
                <div class="relative">
                    {{-- <div class="flex flex-col items-center pointer-events-auto" @click="open = !open"> --}}
                    <div class="flex flex-col items-center pointer-events-auto">
                        <!-- Go to Profile page-->
                        <a href="{{ route('Lab.profile') }}"><img src="{{ asset('images/icon1.png') }}" alt="Lab"></a>
                        <a href="{{ route('Lab.profile') }}"><span class="text-sm text-gray-700">{{ $log}}</span></a>
                    </div>

                    {{-- <div x-show="open"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-purple-50">
                                Logout
                            </button>
                        </form>
                    </div> --}}
                </div>

                <!-- LogOut -->
                <div class="relative flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('images/arrow.png') }}" alt="logout" style="height: 30px; width: auto;">
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="flex min-h-screen" style="position: sticky; top:0; overflow-y: auto; height: 100vh;">
{{--    <div style="position:fixed; height:100%, width : 100%">--}}
    <!-- Sidebar -->
    <div class="w-64 bg-purple-800 text-white">
        <!-- Dashboard Link -->
        <a href="{{ route('Lab.dashboard') }}"
           class="flex items-center space-x-2 px-4 py-4 {{ request()->routeIs('Lab.dashboard') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
            <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard" style="width: 20px;" >
            {{-- <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg> --}}
            <span class="font-semibold">Dashboard</span>
        </a>
        <hr class= "Custom_line2">
        <hr class= "Custom_line">
        <!-- Side Navigation Section -->
        <div class="mt-6">
            <div class="space-y-1">
                <a href="{{ route('Lab.patient_list') }}"
                   class="flex items-center space-x-2 px-4 py-2 {{ request()->routeIs('Lab.patient_list') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
                    <img src="{{ asset('images/patient.png') }}" alt="patient" style="width: 20px;" >
                    {{-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                     </svg> --}}
                    <span>Patient List</span>
                </a>
                <a href="{{ route('Lab.upload_reports_view') }}"
                   class="flex items-center space-x-2 px-4 py-2 {{ request()->routeIs('Lab.upload_reports_view') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
                    <img src="{{ asset('images/medical-reports.png') }}" alt="report" style="width: 20px;" >
                    {{-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                     </svg> --}}
                    <span>Upload Reports</span>
                </a>
                <a href="{{ route('Lab.upload_bills_view') }}"
                   class="flex items-center space-x-2 px-4 py-2 {{ request()->routeIs('Lab.upload_bills_view') ? 'bg-purple-900' : 'hover:bg-purple-700' }}">
                    <img src="{{ asset('images/bill.png') }}" alt="bill" style="width: 20px;" >
                    {{-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                     </svg> --}}
                    <span>Upload Bills</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden bg-gray-50">
        <main class="main_content">
            @yield('content')
            <script>
                function searchPatient(pageN) {
                    const searchQuery = document.getElementById('searchInput').value;

                    // Send an AJAX request to fetch filtered patient data
                    $.ajax({
                        url: "{{ route('Lab.patient_list.search') }}", // Use the same route for both pages
                        type: "GET",
                        data: {
                            query: searchQuery, // Pass the search query
                            page: pageN,         // Pass the current page
                        },
                        success: function (response) {
                            let tableBody = '';

                            response.forEach(patient => {
                                if (pageN === "Patient_list") {
                                    // Render table for Patient_list
                                    tableBody += `
                                        <tr style="color: #3b0764; text-align:center;">
                                            <td>${patient.Patient_Name}</td>
                                            <td>${patient.Phone_Number}</td>
                                            <td>
                                                ${patient.Test_Status === 'Done' ? `
                                                    <span class="text-green-500 font-bold">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Done
                                                    </span>` : `
                                                    <input type="checkbox" class="status-checkbox" value="${patient.Appointment_ID}" onclick="updateStatus('${patient.Appointment_ID}')">
                                                `}
                                            </td>
                                            <td>${patient.Test_Name}</td>
                                            <td>${patient.Appointment_Date}</td>
                                        </tr>
                                        `;
                                } else if (pageN === "Upload_reports") {
                                    // Render table for Upload_reports
                                    tableBody += `
                                            <tr style="color: #3b0764; text-align:center;">
                                                <td>${patient.Patient_Name}</td>
                                                <td>${patient.Phone_Number}</td>
                                                <td>${patient.Test_Name}</td>
                                                <td>${patient.Appointment_Date}</td>
                                                <td>
                                                    <button onclick="openReportModal('${patient.Patient_Name }', '${patient.Patient_ID }', '${patient.Lab_ID }','${patient.Appointment_ID}')"
                                                        style="margin: auto;"
                                                        class="bg-purple-800 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                                                        <span>Upload</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        `;
                                } else if (pageN === "Upload_bills") {
                                    // Render table for Upload_reports
                                    tableBody += `
                                            <tr style="color: #3b0764; text-align:center;">
                                                <td>${patient.Patient_Name }</td>
                                                <td>${patient.Phone_Number }</td>
                                                <td>${patient.Test_Name }</td>
                                                <td>${patient.Appointment_Date }</td>
                                                <td>
                                                    <button onclick="openBillModal('${patient.Patient_Name }', '${patient.Patient_ID }', '${patient.Lab_ID}','${patient.Appointment_ID}')"
                                                        style="margin: auto;"
                                                        class="bg-purple-800 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                                                        <span>Upload</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        `;
                                }
                            });

                            document.querySelector('tbody').innerHTML = tableBody;
                        },
                        error: function (error) {
                            console.error('Error fetching patient data:', error);
                        }
                    });
                }
            </script>

        </main>
    </div>


</div>



</body>
</html>
