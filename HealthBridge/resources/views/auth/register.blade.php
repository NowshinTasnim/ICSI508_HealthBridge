<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <img src="{{ asset('images/HealthBridgeLogo.png') }}" alt="Logo" class="logo">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .image-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }
        .image-grid img {
            width: 150px;
            height: 150px;
            cursor: pointer;
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .image-grid img:hover {
            transform: scale(1.1);
        }
        .image-grid p {
            text-align: center;
            margin-top: 10px;
        }
        .hidden {
            display: none;
        }

    </style>
</head>
<body>

<!-- Initial Page with Images -->
<div id="image-section">
    <div class="container">
        <h1 class="text-center mb-5" style="color: white; background-color: #4b0082; padding: 20px; border-radius: 15px; width: 40%; margin: auto;">Register As</h1>
        <div class="image-grid">
            <div>
                <img src="{{ asset('images/Patients.png') }}" alt="Patient" onclick="showRegistrationPage('patient')">
                <p>Patient</p>
            </div>
            <div>
                <img src="{{ asset('images/Lab.png') }}" alt="Lab" onclick="showRegistrationPage('lab')">
                <p>Lab</p>
            </div>
            <div>
                <img src="{{ asset('images/Insurance.png') }}" alt="Insurance Company" onclick="showRegistrationPage('insurance')">
                <p>Insurance Company</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <p class="custom-text">
                Already have an account? <a href="{{ route('login') }}" class="custom-signup-link">Login</a>
            </p>
        </div>
    </div>
</div>



<!-- Registration Form Section -->
<div id="registration-section" class="container2 hidden">
    <div class="container mt-5">
        <div class="row justify-content-center" style="height: auto; width: 100%">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Patient Form -->
                        <div id="patient-form" class="form-content hidden">
                            <form method="POST" action="{{ route('patientRegister.submit') }}" id="patientRegister.submit">

                                @csrf
                                <input type="hidden" name="formType" value="patient">

                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="patientName" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="patientName" name="patientName" value="{{ old('patientName') }}"  required>
                                            <div id="patientNameError" class="text-danger"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="patientEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="patientEmail" name="patientEmail" value="{{ old('patientEmail') }}" required>
                                            <div id="patientEmailError" class="text-danger"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="patientPhone" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="patientPhone" name="patientPhone" value="{{ old('patientPhone') }}" required>
                                            <div id="patientPhoneError" class="text-danger"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="patientDOB" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" id="patientDOB" name="patientDOB" value="{{ old('patientDOB') }}" required>
                                            <div id="patientDOBError" class="text-danger"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="mailingAddress" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="mailingAddress" name="mailingAddress" value="{{ old('mailingAddress') }}" required>
                                            <div id="mailingAddressError" class="text-danger"></div>
                                        </div>
                                    </div>


                                    <!-- Right Column -->
                                    <div class="col-md-6">

                                        <div class="mb-3">
                                            <label for="insuranceProvider" class="form-label">Insurance Provider</label>
                                            <select class="form-control custom-select-placeholder" id="insuranceProvider" name="insuranceProvider" required>
                                                <option value="" class="placeholder" disabled {{ old('insuranceProvider') ? '' : 'selected' }}>Select your Insurance Provider</option>
                                                @foreach ($allInsuranceProviders as $providers)
                                                    <option value="{{ $providers->InsuranceID }}" {{ old('insuranceProvider') == $providers->InsuranceID ? 'selected' : '' }}>
                                                        {{ $providers->Ins_Name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('insuranceProvider')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="insuranceID" class="form-label">Insurance ID Number</label>
                                            <input type="text" class="form-control" id="insuranceID" name= "insuranceID" value="{{ old('insuranceID') }}" required>
                                            <div id="insuranceIDError" class="text-danger"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ptloginID" class="form-label">Login ID</label>
                                            <input type="text" class="form-control" id="ptloginID" name="ptloginID" value="{{ old('ptloginID') }}" required>
                                            <div id="ptloginIDError" class="text-danger"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="patientPassword" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="patientPassword" name="patientPassword" value="{{ old('patientPassword') }}" required>
                                            <div id="patientPasswordError" class="text-danger"></div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn custom-btn w-50">{{ __('Register') }}</button>
                            </form>
                        </div>

                   {{--<!-- Lab Form -->--}}
                        <div id="lab-form" class="form-content hidden">
                            <form  method="POST" action="{{ route('labRegister.submit') }}" id="labRegister_form">

                                @csrf
                                <input type="hidden" name="formType" value="lab">

                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="labName" class="form-label">Lab Name</label>
                                            <input type="text" class="form-control" id="labName" name="labName" value="{{ old('labName') }}"  required>
                                            <div id="labNameError" class="text-danger"></div>
                                            @error('labName')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="labEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="labEmail" name="labEmail" value="{{ old('labEmail') }}" required>
                                            <div id="labEmailError" class="text-danger"></div>
                                            @error('labEmail')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="labPhone" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="labPhone"  name="labPhone" value="{{ old('labPhone') }}" required>
                                            <div id="labPhoneError" class="text-danger"></div>
                                            @error('labPhone')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="labAddress" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="labAddress" name="labAddress" value="{{ old('labAddress') }}" required>
                                            <div id="labAddressError" class="text-danger"></div>
                                            @error('labAddress')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">

                                        <div class="mb-3">
                                            <label for="labLicenseNumber" class="form-label">License Number</label>
                                            <input type="text" class="form-control" id="labLicenseNumber" name="labLicenseNumber" value="{{ old('labLicenseNumber') }}" required>
                                            <div id="labLicenseNumberError" class="text-danger"></div>
                                            @error('labLicenseNumber')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="labloginID" class="form-label">Login ID</label>
                                            <input type="text" class="form-control" id="labloginID" name="labloginID" value="{{ old('labloginID') }}" required>
                                            <div id="labloginIDError" class="text-danger"></div>
                                            @error('labloginID')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="labPassword" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="labPassword"  name="labPassword" value="{{ old('labPassword') }}" required>
                                            <div id="labPasswordError" class="text-danger"></div>
                                            @error('labPassword')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="mb-3">
                                            <label class="form-label">Types of Tests Offered</label>

                                            <div class="dropdown">
                                                <button class="btn custom-btn-drop dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Select Tests
                                                </button>
                                                <ul class="dropdown-menu custom-dropdown-menu keep-open" aria-labelledby="dropdownMenuButton" style="padding: 10px; width: 100%;">
                                                    @foreach ($allTests as $test)
                                                        <li>
                                                            <label class="dropdown-item">
                                                                <input type="checkbox" name="TestID[]" value="{{ $test->TestID }}"> {{ $test->Test_name }}
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn custom-btn w-50">{{ __('Register') }}</button>

                            </form>
                        </div>

                        <!-- Insurance Company Form -->
                        <div id="insurance-form" class="form-content hidden">
                            <form method="POST" action="{{ route('insuranceRegister.submit') }}" id="insuranceRegister_form">
                                @csrf
                                <input type="hidden" name="formType" value="insurance">

                                <!-- Company Name Field -->
                                <div class="mb-3">
                                    <label for="insuranceCompany" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="insuranceCompany" name="insuranceCompany" value="{{ old('insuranceCompany') }}" placeholder="Enter your company name" required>
                                    <div id="insuranceCompanyError" class="text-danger"></div>
                                </div>

                                <!-- Email Field -->
                                <div class="mb-3">
                                    <label for="insuranceEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="insuranceEmail" name="insuranceEmail" value="{{ old('insuranceEmail') }}" placeholder="Enter your email" required>
                                    <div id="insuranceEmailError" class="text-danger"></div>
                                </div>

                                <!-- Login ID Field -->
                                <div class="mb-3">
                                    <label for="InsuranceloginID" class="form-label">Login ID</label>
                                    <input type="text" class="form-control" id="InsuranceloginID" name="InsuranceloginID" value="{{ old('InsuranceloginID') }}" placeholder="Enter your login ID" required>
                                    <div id="InsuranceloginIDError" class="text-danger"></div>
                                </div>

                                <!-- Password Field -->
                                <div class="mb-3">
                                    <label for="insurancePassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="insurancePassword" name="insurancePassword" value="{{ old('insurancePassword') }}" placeholder="Enter your password" required>
                                    <div id="insurancePasswordError" class="text-danger"></div>
                                </div>


                                <!-- Submit Button -->
                                <button type="submit" class="btn custom-btn w-50">Register</button>
                            </form>


                        </div>


                        <button type="submit" class="btn custom-btn2 w-50" onclick="goBack()">{{ __('Back to Selection') }}</button>

                        <div class="text-center mt-3">
                            <p class="custom-text">
                                Already have an account? <a href="{{ route('login') }}" class="custom-signup-link">Login</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showRegistrationPage(type) {
        document.getElementById('image-section').classList.add('hidden');
        document.getElementById('registration-section').classList.remove('hidden');

        if (type === 'patient') {
            document.getElementById('patient-form').classList.remove('hidden');
            document.getElementById('lab-form').classList.add('hidden');
            document.getElementById('insurance-form').classList.add('hidden');
        } else if (type === 'lab') {
            document.getElementById('lab-form').classList.remove('hidden');
            document.getElementById('patient-form').classList.add('hidden');
            document.getElementById('insurance-form').classList.add('hidden');
        } else if (type === 'insurance') {

            document.getElementById('insurance-form').classList.remove('hidden');
            document.getElementById('patient-form').classList.add('hidden');
            document.getElementById('lab-form').classList.add('hidden');
        }
    }

    function goBack() {
        document.getElementById('image-section').classList.remove('hidden');
        document.getElementById('registration-section').classList.add('hidden');
    }

    function showTermsModal() {
        const modal = new bootstrap.Modal(document.getElementById('termsPopup'));
        modal.show();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownMenu = document.querySelector('.dropdown-menu');

        // Prevent dropdown from closing when clicking inside the dropdown
        dropdownMenu.addEventListener('click', function (event) {
            event.stopPropagation();
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("insuranceRegister_form");
        const insuranceCompany = document.getElementById("insuranceCompany");
        const insuranceEmail = document.getElementById("insuranceEmail");
        const InsuranceloginID = document.getElementById("InsuranceloginID");
        const insurancePassword = document.getElementById("insurancePassword");
        const insuranceTermsCheck = document.getElementById("insuranceTermsCheck");

        // Fetch already taken Login IDs from Laravel backend
        const takenLoginIDs = @json(\DB::table('credentials')->pluck('Login_ID')->toArray());

        form.addEventListener("submit", (event) => {
            let isValid = true;

            // Clear previous error messages
            clearErrors();

            // Validate Login ID
            if (!InsuranceloginID.value.trim()) {
                isValid = false;
                showError("InsuranceloginIDError", "The login ID is required.");
            } else if (InsuranceloginID.value.length > 255) {
                isValid = false;
                showError("InsuranceloginIDError", "The login ID may not exceed 255 characters.");
            } else if (takenLoginIDs.includes(InsuranceloginID.value.trim())) {
                isValid = false;
                showError("InsuranceloginIDError", "The login ID has already been taken.");
            }

            // Validate Password
            if (!insurancePassword.value.trim()) {
                isValid = false;
                showError("insurancePasswordError", "The password field is required.");
            } else if (insurancePassword.value.length < 8) {
                isValid = false;
                showError("insurancePasswordError", "The password must be at least 8 characters.");
            }

            // Validate Company Name
            if (!insuranceCompany.value.trim()) {
                isValid = false;
                showError("insuranceCompanyError", "The company name is required.");
            } else if (insuranceCompany.value.length > 255) {
                isValid = false;
                showError("insuranceCompanyError", "The company name may not exceed 255 characters.");
            }

            // Validate Email
            if (!insuranceEmail.value.trim()) {
                isValid = false;
                showError("insuranceEmailError", "The email address is required.");
            } else if (!validateEmail(insuranceEmail.value)) {
                isValid = false;
                showError("insuranceEmailError", "The email address must be valid.");
            } else if (insuranceEmail.value.length > 255) {
                isValid = false;
                showError("insuranceEmailError", "The email address may not exceed 255 characters.");
            }

            // // Validate Terms Check
            // if (!insuranceTermsCheck.checked) {
            //     isValid = false;
            //     showError("insuranceTermsCheckError", "You must agree to the terms and conditions.");
            // }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });

        // Helper function to display errors
        function showError(elementId, message) {
            document.getElementById(elementId).innerText = message;
        }

        // Helper function to clear all errors
        function clearErrors() {
            document.querySelectorAll(".text-danger").forEach((el) => {
                el.innerText = "";
            });
        }

        // Email validation function
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("labRegister_form");
        const labName = document.getElementById("labName");
        const labEmail = document.getElementById("labEmail");
        const labPhone = document.getElementById("labPhone");
        const labAddress = document.getElementById("labAddress");
        const labLicenseNumber = document.getElementById("labLicenseNumber");
        const labloginID = document.getElementById("labloginID");
        const labPassword = document.getElementById("labPassword");
        // const testCheckboxes = document.querySelectorAll('input[name="TestID[]"]');
        // const dropdownContainer = document.querySelector('.dropdown-menu'); // Adjust to your container's class or ID.


        // Fetch already taken Login IDs and Emails from Laravel backend
        const takenLoginIDs = @json(\DB::table('credentials')->pluck('Login_ID')->toArray());
        const takenEmails = @json(\DB::table('lab')->pluck('Email')->toArray());
        const takeLiceseNumbers = @json(\DB::table('lab')->pluck('License_no')->toArray());

        form.addEventListener("submit", (event) => {
            let isValid = true;

            // Clear previous error messages
            clearErrors();

            // Perform validations, added at 3:24
            if (!isValid) {
                event.preventDefault(); // Stop form submission
            }

            // Validate Lab Name
            if (!labName.value.trim()) {
                isValid = false;
                showError("labNameError", "The lab name is required.");
            } else if (labName.value.length > 255) {
                isValid = false;
                showError("labNameError", "The lab name may not exceed 255 characters.");
            }

            // Validate Email
            if (!labEmail.value.trim()) {
                isValid = false;
                showError("labEmailError", "The email address is required.");
            } else if (!validateEmail(labEmail.value)) {
                isValid = false;
                showError("labEmailError", "The email address must be valid.");
            } else if (labEmail.value.length > 50) {
                isValid = false;
                showError("labEmailError", "The email address may not exceed 50 characters.");
            } else if (takenEmails.includes(labEmail.value.trim())) {
                isValid = false;
                showError("labEmailError", "The email address has already been taken.");
            }

            // Validate Phone Number
            if (!labPhone.value.trim()) {
                isValid = false;
                showError("labPhoneError", "The phone number is required.");
            } else if (!/^\d{10}$/.test(labPhone.value)) {
                isValid = false;
                showError("labPhoneError", "The phone number must be exactly 10 digits.");
            }

            // Validate Address
            if (!labAddress.value.trim()) {
                isValid = false;
                showError("labAddressError", "The address is required.");
            } else if (labAddress.value.length > 255) {
                isValid = false;
                showError("labAddressError", "The address may not exceed 255 characters.");
            }

            // Validate License Number
            if (!labLicenseNumber.value.trim()) {
                isValid = false;
                showError("labLicenseNumberError", "The license number is required.");
            } else if (labLicenseNumber.value.length > 100) {
                isValid = false;
                showError("labLicenseNumberError", "The license number may not exceed 100 characters.");
            } else if (takeLiceseNumbers.includes(labLicenseNumber.value.trim())) {
                isValid = false;
                showError("labLicenseNumberError", "The license number has already been taken.");
            }

            // Validate Login ID
            if (!labloginID.value.trim()) {
                isValid = false;
                showError("labloginIDError", "The login ID is required.");
            } else if (labloginID.value.length > 255) {
                isValid = false;
                showError("labloginIDError", "The login ID may not exceed 255 characters.");
            } else if (takenLoginIDs.includes(labloginID.value.trim())) {
                isValid = false;
                showError("labloginIDError", "The login ID has already been taken.");
            }

            // Validate Password
            if (!labPassword.value.trim()) {
                isValid = false;
                showError("labPasswordError", "The password field is required.");
            } else if (labPassword.value.length < 8) {
                isValid = false;
                showError("labPasswordError", "The password must be at least 8 characters.");
            }

            // // Validate Test Selection
            // const isTestSelected = Array.from(testCheckboxes).some((checkbox) => checkbox.checked);
            // if (!isTestSelected) {
            //     isValid = false;
            //     const errorMessage = document.createElement('div');
            //     errorMessage.className = 'text-danger';
            //     errorMessage.id = 'testCheckboxError';
            //     errorMessage.innerText = 'Please select at least one test.';
            //     dropdownContainer.appendChild(errorMessage);
            // }
            //
            // // Prevent form submission if invalid
            // if (!isValid) {
            //     event.preventDefault();
            // }


        });

        // Helper function to display errors
        function showError(elementId, message) {
            document.getElementById(elementId).innerText = message;
        }

        // Helper function to clear all errors
        function clearErrors() {
            document.querySelectorAll(".text-danger").forEach((el) => {
                el.innerText = "";
            });
            const testError = document.getElementById("testCheckboxError");
            if (testError) testError.remove();
        }

        // Email validation function
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("patientRegister.submit");
        const patientName = document.getElementById("patientName");
        const patientEmail = document.getElementById("patientEmail");
        const patientPhone = document.getElementById("patientPhone");
        const patientDOB = document.getElementById("patientDOB");
        const mailingAddress = document.getElementById("mailingAddress");
        const insuranceProvider = document.getElementById("insuranceProvider");
        const insuranceID = document.getElementById("insuranceID");
        const ptloginID = document.getElementById("ptloginID");
        const patientPassword = document.getElementById("patientPassword");

        // Fetch already taken data from the Laravel backend
        const takenLoginIDs = @json(\DB::table('credentials')->pluck('Login_ID')->toArray());
        const takenEmails = @json(\DB::table('patient')->pluck('Email')->toArray());
        const takenInsuranceIDs = @json(\DB::table('patient')->pluck('Ins_member_id')->toArray());

        form.addEventListener("submit", (event) => {
            let isValid = true;

            // Clear previous error messages
            clearErrors();

            // Validate Full Name
            if (!patientName.value.trim()) {
                isValid = false;
                showError("patientNameError", "The full name is required.");
            } else if (patientName.value.length > 255) {
                isValid = false;
                showError("patientNameError", "The full name may not exceed 255 characters.");
            }

            // Validate Email
            if (!patientEmail.value.trim()) {
                isValid = false;
                showError("patientEmailError", "The email address is required.");
            } else if (!validateEmail(patientEmail.value)) {
                isValid = false;
                showError("patientEmailError", "The email address must be valid.");
            } else if (patientEmail.value.length > 100) {
                isValid = false;
                showError("patientEmailError", "The email address may not exceed 100 characters.");
            } else if (takenEmails.includes(patientEmail.value.trim())) {
                isValid = false;
                showError("patientEmailError", "The email address is already in use.");
            }

            // Validate Phone Number
            if (!patientPhone.value.trim()) {
                isValid = false;
                showError("patientPhoneError", "The phone number is required.");
            } else if (!/^\d{10}$/.test(patientPhone.value)) {
                isValid = false;
                showError("patientPhoneError", "The phone number must be exactly 10 digits.");
            }

            // Validate Date of Birth
            if (!patientDOB.value.trim()) {
                isValid = false;
                showError("patientDOBError", "The date of birth is required.");
            }

            // Validate Address
            if (mailingAddress.value.trim() && mailingAddress.value.length > 255) {
                isValid = false;
                showError("mailingAddressError", "The address may not exceed 255 characters.");
            }

            // Validate Insurance Provider
            if (!insuranceProvider.value.trim()) {
                isValid = false;
                showError("insuranceProviderError", "The insurance provider is required.");
            }

            // Validate Insurance ID
            if (!insuranceID.value.trim()) {
                isValid = false;
                showError("insuranceIDError", "The insurance ID is required.");
            } else if (takenInsuranceIDs.includes(insuranceID.value.trim())) {
                isValid = false;
                showError("insuranceIDError", "The insurance ID is already in use.");
            }

            // Validate Login ID
            if (!ptloginID.value.trim()) {
                isValid = false;
                showError("ptloginIDError", "The login ID is required.");
            } else if (takenLoginIDs.includes(ptloginID.value.trim())) {
                isValid = false;
                showError("ptloginIDError", "The login ID is already in use.");
            }

            // Validate Password
            if (!patientPassword.value.trim()) {
                isValid = false;
                showError("patientPasswordError", "The password is required.");
            } else if (patientPassword.value.length < 8) {
                isValid = false;
                showError("patientPasswordError", "The password must be at least 8 characters.");
            }

            // Prevent form submission if invalid
            if (!isValid) {
                event.preventDefault();
            }
        });

        // Helper function to display errors
        function showError(elementId, message) {
            document.getElementById(elementId).innerText = message;
        }

        // Helper function to clear all errors
        function clearErrors() {
            document.querySelectorAll(".text-danger").forEach((el) => {
                el.innerText = "";
            });
        }

        // Email validation function
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Determine the form type from Laravel's old input data
        const formType = @json(old('formType'));

        // Show the relevant form based on the formType
        if (formType === 'lab') {
            showRegistrationPage('lab');
        }
        else if (formType === 'patient') {
            showRegistrationPage('patient');}

         else if (formType === 'insurance') {
            showRegistrationPage('insurance');
        }
    });
    console.log();
</script>




</body>
</html>
