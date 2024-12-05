@extends('layouts.lab')

@section('content')
@php $page = 'Upload_reports'; @endphp
<div class="container_start">
    <div class = "container">
        <!-- Total number -->
        <div class= "text-right" style="margin-right: 10px;">
            {{-- <h5> Total Number: 3</h5> --}}
            <h5> Total Number: {{$patients->count()}}</h5>
        </div>

        <!-- Table -->
        <div class ="row justify-content-center" style="margin-top: 40px;">

            <table class="w-3-table w3-bordered w3-card-4 center" style="width: 100%;">
                <thead style="background-color:#7e22ce; height: 45px;">
                    <tr >
                        <th class="text-white">Patient Name</th>
                        <th class="text-white">Contact No.</th>
                        <th class="text-white">Test Name</th>
                        <th class="text-white">Appointment Date</th>
                        <th class="text-white">Reports</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                        <tr style="color: #3b0764; text-align:center;">
                            <td>{{ $patient->Patient_Name }}</td>
                            <td>{{ $patient->Phone_Number }}</td>
                            <td>{{ $patient->Test_Name }}</td>
                            <td>{{ $patient->Appointment_Date }}</td>
                            <td>
                                <button onclick="openReportModal('{{ $patient->Patient_Name }}', '{{ $patient->Patient_ID }}', '{{ $patient->Lab_ID }}','{{ $patient->Appointment_ID}}')"
                                    style="margin: auto;"
                                    class="bg-purple-800 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                                    <span>Upload</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
                    <h2 class="text-xl text-purple-800 font-bold mb-4" style="text-align: center;">Submit Report</h2>

                    <form action="{{ route('upload.report') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <!-- Patient Name -->
                            <div class="flex items-center space-x-2">
                                <span class="block text-sm font-medium text-grey-700">Patient Name:</span>
                                <label class="block text-sm font-medium text-gray-700" id="modalPatientName"></label>
                            </div>

                            <!-- Hidden Inputs for Patient ID and Lab ID -->
                            <input type="hidden" name="patient_id" id="modalPatientID">
                            <input type="hidden" name="lab_id" id="modalLabID">
                            <input type="hidden" name="appointment_id" id="modalAppointmentID">

                            <!-- File Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Attach Documents</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-800 hover:text-purple-700">
                                                <span>Upload a file</span>
                                                <input type="file" name="report" class="sr-only" id="fileInput" onchange="displayFileName()" required>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                                        <!-- Display selected file name -->
                                        <p class="text-sm text-gray-800 mt-2" id="fileNameDisplay"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="closeReportModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-purple-800 text-white rounded-lg hover:bg-purple-700">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openReportModal(patientName, patientID, labID, appointmentID) {
                document.getElementById('modalPatientName').textContent = patientName;
                document.getElementById('modalPatientID').value = patientID;
                document.getElementById('modalLabID').value = labID;
                document.getElementById('modalAppointmentID').value = appointmentID;
                document.getElementById('reportModal').classList.remove('hidden');
            }

            function closeReportModal() {
                document.getElementById('reportModal').classList.add('hidden');
            }
            function displayFileName() {
                const fileInput = document.getElementById('fileInput');
                const fileNameDisplay = document.getElementById('fileNameDisplay');

                if (fileInput.files.length > 0) {
                    const fileName = fileInput.files[0].name;
                    fileNameDisplay.textContent = `Selected file: ${fileName}`;
                } else {
                    fileNameDisplay.textContent = '';
                }
            }
        </script>
    </div>
</div>
{{-- <script>
    //search bar for the patient_list page
    function searchPatient() {
        const searchQuery = document.getElementById('searchInput').value;

        // Send an AJAX request to fetch filtered patient data
        $.ajax({
            url: "{{ route('Lab.patient_list.search') }}", // Define this route in web.php
            type: "GET",
            data: {
                query: searchQuery, // Pass the search query to the server
                page: "Upload_reports",
            },
            success: function (response) {
                // Update the patient table with the new data
                let tableBody = '';
                response.forEach(patient => {
                    tableBody += `
                        <tr style="color: #3b0764; text-align:center;">
                            <td>${patient->Patient_Name}</td>
                            <td>${patient->Phone_Number}</td>
                            <td>${patient->Test_Name}</td>
                            <td>${patient->Appointment_Date}</td>
                            <td>
                                <button onclick="openReportModal('${patient->Patient_Name }', '${patient->Patient_ID }', '${patient->Lab_ID }','${patient->Appointment_ID}')"
                                    style="margin: auto;"
                                    class="bg-purple-800 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                                    <span>Upload</span>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                document.querySelector('tbody').innerHTML = tableBody;
            },
            error: function (error) {
                console.error('Error fetching patient data:', error);
            }
        });
    }
</script> --}}
@endsection
