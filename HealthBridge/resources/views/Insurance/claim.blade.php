<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Claims</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/insurance.css') }}">
</head>
<body>

<a href="{{ route('Insurance.dashboard') }}">
    <img src="{{ asset('images/HealthBridgeLogo.png') }}" alt="Logo" class="logo">
</a>


<div class="icon">
    <img src="{{ asset('images/msg.png') }}" alt="Message Icon" class="contact-icon" style="height: 15px;">
</div>

<div class="icon flex flex-col items-center pointer-events-auto">
    <!-- Go to Profile page-->
    <a href="{{ route('Insurance.profile') }}"><img src="{{ asset('images/icon1.png') }}" alt="Insurance"></a>
    <span class="text-sm text-gray-700">{{ $log}}</span>
</div>
<div class="contact-us-box">
    <img src="{{ asset('images/msg.png') }}" alt="Message Icon" class="contact-icon" style="height: 15px;">
    <a href="mailto:snehakhan52@gmail.com?subject=Support%20Request&body=Hi%20Team,%0A%0AI%20need%20help%20with..." class="contact-us-text">Contact</a>
</div>

{{--<!-- LogOut -->--}}
{{--<div class="logout ">--}}
{{--    <form method="POST" action="{{ route('logout') }}">--}}
{{--        @csrf--}}
{{--        <button type="submit">--}}
{{--            <img src="{{ asset('images/arrow.png') }}" alt="logout" style="height: 30px; width: auto;">--}}
{{--        </button>--}}
{{--    </form>--}}
{{--</div>--}}




<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Header Title -->
                <h3 class="text-center mb-4">Insurance Claims</h3>
                <!-- Total Count and View Details -->
                <div class="d-flex align-items-center justify-content-end">
                    <p class="mb-0 me-2 text-custom">
                        <strong>
                            <a href="{{ route('Insurance.dashboard') }}" class=" colortext text-decoration-underline text-custom">
                                Total Claims: {{ $totalFilled }}
                            </a>
                        </strong>
                    </p>

                </div>

            </div>
            <!-- Scrollable table container -->
            <div class="table-responsive table-scrollable" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                    <tr>
                        <th>Lab Name</th>
                        <th>Patient Name</th>
                        <th>Claim File</th>
                        {{--                        <th>Status</th>--}}
                        <th>
                            Status
                            <button class="btn btn-sm btn-light sort-btn" data-sort="asc">▲</button>
                            <button class="btn btn-sm btn-light sort-btn" data-sort="desc">▼</button>
                        </th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($claims as $claim)
                        <tr>
                            <td>{{ $claim->Lab_Name }}</td>
                            <td>{{ $claim->Pt_Name }}</td>
                            <td>
                                {{--                                <a--}}
                                {{--                                    href="#"--}}
                                {{--                                    class="download-btn"--}}
                                {{--                                    data-appointment-id="{{ $claim->AppointmentID }}"--}}
                                {{--                                    data-patient-id="{{ $claim->PatientID }}"--}}
                                {{--                                    data-lab-id="{{ $claim->LabID }}"--}}
                                {{--                                >--}}
                                {{--                                    Download--}}
                                {{--                                </a>--}}
                                <form method="POST" action="{{ route('Insurance.claim.download') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="AppointmentID" value="{{ $claim->AppointmentID }}">
                                    <input type="hidden" name="PatientID" value="{{ $claim->PatientID }}">
                                    <input type="hidden" name="LabID" value="{{ $claim->LabID }}">
                                    <button type="submit" class="btn btn-link">Download</button>
                                </form>

                            </td>
                            <td id="status-{{ $claim->ClaimID }}">{{ ucfirst($claim->Approval_status ?? 'Pending') }}</td>
                            <td>
                                @if ($claim->Approval_status === 'None')
                                    <form method="POST" action="{{ route('Insurance.claim.update') }}" id="claimForm-{{ $claim->ClaimID }}">
                                        @csrf
                                        <!-- Hidden inputs for ClaimID and additional data -->
                                        <input type="hidden" name="ClaimID" value="{{ $claim->ClaimID }}">
                                        {{--                                        <input type="hidden" name="PatientID" value="{{ $claim->PatientID }}">--}}
                                        {{--                                        <input type="hidden" name="LabID" value="{{ $claim->LabID }}">--}}
                                        {{--                                        <input type="hidden" name="AppointmentID" value="{{ $claim->AppointmentID }}">--}}
                                        <input type="hidden" name="Reason_for_rejection" class="hidden-reason-field">

                                        <div class="d-flex align-items-center">
                                            <!-- Dropdown for selecting Accept or Reject -->
                                            <select class="form-select me-2 action-select" name="action" required>
                                                <option value="">Select Action</option>
                                                <option value="accept">Approve</option>
                                                <option value="reject" data-claim-id="{{ $claim->ClaimID }}">Reject</option>
                                            </select>

                                            <!-- Submit button -->
                                            <button type="submit" class="btn custom-btn-claim">Submit</button>
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">No actions available</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>

<!-- Modal for Reject -->
<!-- Modal for Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reason for Rejection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="rejectionReason" placeholder="Enter reason for rejection" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-reason-btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const actionSelects = document.querySelectorAll('.action-select');
        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        const rejectionReasonTextarea = document.getElementById('rejectionReason');
        const saveReasonBtn = document.querySelector('.save-reason-btn');
        let currentForm = null;

        // Trigger modal for rejection
        actionSelects.forEach(select => {
            select.addEventListener('change', function () {
                const selectedValue = this.value;
                const claimFormId = this.closest('form').id;

                if (selectedValue === 'reject') {
                    currentForm = document.getElementById(claimFormId);
                    rejectionReasonTextarea.value = ''; // Clear previous input
                    rejectModal.show();
                }
            });
        });

        // Save reason for rejection
        saveReasonBtn.addEventListener('click', function () {
            const reason = rejectionReasonTextarea.value.trim();

            if (!reason) {
                alert('Please provide a reason for rejection.');
                return;
            }

            if (currentForm) {
                // Set the hidden reason field
                const hiddenReasonField = currentForm.querySelector('.hidden-reason-field');
                hiddenReasonField.value = reason;

                // Close the modal
                rejectModal.hide();

                // Submit the form
                currentForm.submit();
            }
        });

        // Close modal if user cancels
        const closeModalButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Reset the action select to default
                if (currentForm) {
                    const actionSelect = currentForm.querySelector('.action-select');
                    actionSelect.selectedIndex = 0;
                }
            });
        });
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sortButtons = document.querySelectorAll('.sort-btn');

        // Check if buttons are selected correctly
        if (sortButtons.length === 0) {
            console.error("Sort buttons not found. Ensure the '.sort-btn' class is present on the buttons.");
            return;
        }

        sortButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent any default action

                const sortOrder = this.dataset.sort;
                const rows = Array.from(document.querySelectorAll('tbody tr'));

                // Debugging: Check the number of rows
                if (rows.length === 0) {
                    console.error("No rows found in the table. Ensure <tbody> contains rows.");
                    return;
                }

                rows.sort((a, b) => {
                    const statusA = a.querySelector('td:nth-child(4)').textContent.trim();
                    const statusB = b.querySelector('td:nth-child(4)').textContent.trim();

                    const order = { 'None': 1, 'Approved': 2, 'Reject': 3 };

                    // Debugging: Check the extracted statuses
                    console.log(`Sorting: ${statusA} vs ${statusB}`);

                    return sortOrder === 'asc'
                        ? order[statusA] - order[statusB]
                        : order[statusB] - order[statusA];
                });

                const tbody = document.querySelector('tbody');

                // Debugging: Ensure tbody exists
                if (!tbody) {
                    console.error("Table body (<tbody>) not found.");
                    return;
                }

                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });

</script>
{{--<script>--}}
{{--    document.addEventListener('DOMContentLoaded', function () {--}}
{{--        const downloadButtons = document.querySelectorAll('.download-btn');--}}

{{--        downloadButtons.forEach(button => {--}}
{{--            button.addEventListener('click', function (event) {--}}
{{--                event.preventDefault(); // Prevent default link behavior--}}

{{--                // Fetch hidden fields--}}
{{--                const appointmentId = this.dataset.appointmentId;--}}
{{--                const patientId = this.dataset.patientId;--}}
{{--                const labId = this.dataset.labId;--}}

{{--                // Construct the filename--}}
{{--                const fileName = `${appointmentId}_${patientId}_${labId}`;--}}
{{--                //console.log('Generated Filename:', fileName);--}}


{{--                // Construct file URL--}}
{{--                const fileUrl = `/storage/app/public/Claim/${fileName}`;--}}

{{--                // Verify file existence before downloading--}}
{{--                fetch(fileUrl, { method: 'HEAD' })--}}
{{--                    .then(response => {--}}
{{--                        if (response.ok) {--}}
{{--                            // If file exists, trigger download--}}
{{--                            const anchor = document.createElement('a');--}}
{{--                            anchor.href = fileUrl;--}}
{{--                            anchor.download = fileName;--}}
{{--                            document.body.appendChild(anchor);--}}
{{--                            anchor.click();--}}
{{--                            document.body.removeChild(anchor);--}}
{{--                        } else {--}}
{{--                            alert('File not found. Please ensure the file exists.');--}}
{{--                        }--}}
{{--                    })--}}
{{--                    .catch(() => {--}}
{{--                        alert('An error occurred while fetching the file.');--}}
{{--                    });--}}
{{--            });--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}


<footer class="footer mt-4">
    <p class="text-center">&copy; 2024 HealthBridge. All rights reserved.</p>
</footer>
</body>
</html>
