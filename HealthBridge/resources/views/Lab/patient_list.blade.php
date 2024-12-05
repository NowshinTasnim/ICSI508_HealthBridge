@extends('layouts.lab')

@section('content')
@php $page = 'Patient_list'; @endphp
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
                        <th class="text-white">Completed?</th>
                        <th class="text-white">Test Name</th>
                        <th class="text-white">Appointment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                        <tr style="color: #3b0764; text-align:center;">
                            <td>{{ $patient->Patient_Name }}</td>
                            <td>{{ $patient->Phone_Number }}</td>
                            <td>
                                @if ($patient->Test_Status == 'Done')
                                    <span class="text-green-500 font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Done
                                    </span>
                                @else
                                    <input type="checkbox" class="status-checkbox" value="{{ $patient->Appointment_ID }}" onclick="updateStatus('{{ $patient->Appointment_ID }}')">
                                @endif
                            </td>
                            <td>{{ $patient->Test_Name }}</td>
                            <td>{{ $patient->Appointment_Date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Test status Mark as done
    function updateStatus(appointmentId) {
        // Reference the clicked checkbox
        const checkbox = document.querySelector(`.status-checkbox[value="${appointmentId}"]`);

        // Display a confirmation dialog
        if (confirm("Are you sure you want to mark this test as Done?")) {
            // If user confirms, proceed with the AJAX request
            $.ajax({
                url: "{{ route('Lab.patient_list.markAsDone') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    appointmentId: appointmentId
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload(); // Optionally reload the page
                    } else {
                        alert('Failed to update status: ' + response.message);
                        checkbox.checked = false; // Revert checkbox on error
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    checkbox.checked = false; // Revert checkbox on error
                }
            });
        } else {

            checkbox.checked = false;
        }
    }

//     // search bar for the patient_list page
//     function searchPatient() {
//         const searchQuery = document.getElementById('searchInput').value;

//         // Send an AJAX request to fetch filtered patient data
//         $.ajax({
//             url: "{{ route('Lab.patient_list.search') }}", // Define this route in web.php
//             type: "GET",
//             data: {
//                 query: searchQuery, // Pass the search query to the server
//                 page: "Patient_list",
//             },
//             success: function (response) {
//                 // Update the patient table with the new data
//                 let tableBody = '';
//                 response.forEach(patient => {
//                     tableBody += `
//                         <tr style="color: #3b0764; text-align:center;">
//                             <td>${patient.Patient_Name}</td>
//                             <td>${patient.Phone_Number}</td>
//                             <td>
//                                 ${patient.Test_Status === 'Done' ? `
//                                     <span class="text-green-500 font-bold">
//                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
//                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
//                                         </svg>
//                                         Done
//                                     </span>` : `
//                                     <input type="checkbox" class="status-checkbox" value="${patient.Appointment_ID}" onclick="updateStatus('${patient.Appointment_ID}')">
//                                 `}
//                             </td>
//                             <td>${patient.Test_Name}</td>
//                             <td>${patient.Appointment_Date}</td>
//                         </tr>
//                     `;
//                 });
//                 document.querySelector('tbody').innerHTML = tableBody;
//             },
//             error: function (error) {
//                 console.error('Error fetching patient data:', error);
//             }
//         });
//     }

// </script>

@endsection
