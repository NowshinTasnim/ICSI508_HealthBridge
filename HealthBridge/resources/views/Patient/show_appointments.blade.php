<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
    <!-- Load an icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <style>
        .btn-primary, .btn-primary:active, .btn-primary:visited {
            border: none;
            background-color: #8064A2;
        }
        .btn-primary:hover {
            background-color: #8D74AC;
        }
        .btn-primary{
            font-size: 14px;
        }
        .topnav{
            border-bottom-width: 1px;
            box-shadow: 0px 0px 2px 2px rgba(114, 40, 103, 0.29);
            border-bottom-style: solid;
            border-bottom-color: #2d203d;
        }

    </style>
</head>
<body>

{{--Top navigation--}}
<div id="app" class="topnav mb-5">
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <a class="navbar-brand mt-2" href="{{ route('patient_dashboard') }}"><img src="{{ asset('images/HealthBridgeLogo.png') }}" style="height: 40px;" class="d-inline-block align-top" alt="HealthBridge"></a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" style="color: #420160" onMouseOver="this.style.color='grey'"
                       onMouseOut="this.style.color='#420160'"  href="{{ route('appointment_list') }}">Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color: #420160" onMouseOver="this.style.color='grey'"
                       onMouseOut="this.style.color='#420160'"  href="{{ route('show_lab_tests') }}">Lab Tests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color: #420160" onMouseOver="this.style.color='grey'"
                       onMouseOut="this.style.color='#420160'"  href="{{ route('patient_dashboard') }}">Dashboard</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item mr-3">
                    <a class="nav-link" style="color: #420160" onMouseOver="this.style.color='grey'"
                       onMouseOut="this.style.color='#420160'"  href="{{ route('patient_dashboard') }}"><i class="fa fa-user-circle-o mr-1" aria-hidden="true"></i>{{ $log}}</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link" style="color: #420160; border-style: solid; border-radius: 4px; border-width: thin; border-color:#420160;" onMouseOver="this.style.color='grey'"
                       onMouseOut="this.style.color='#420160'"  href="#"><i class="fa fa-envelope-o mr-1" aria-hidden="true"></i>Contact</a>
                </li>
                <li class="nav-item mr-1">
                    <a class="nav-link" style="color: #420160" onMouseOver="this.style.color='grey'"
                       onMouseOut="this.style.color='#420160'"  href="{{ route('Plogout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

{{--show appointments for the patient user--}}
<div class="container mt-4">
    <table id="LabTests" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th class="text-center">Name</th>
            <th class="text-center">Lab</th>
            <th class="text-center">Lab Test</th>
            <th class="text-center">Test Date</th>
            <th class="text-center">Time</th>
            <th class="text-center">Test Status</th>
            <th class="text-center">Report</th>
        </tr>
        </thead>
        <tbody>
        @foreach($appointments as $appointment)
            <tr>
                <td class="text-center">{{ $appointment->Pt_Name }}</td>
                <td class="text-center">{{ $appointment->Lab_Name }}</td>
                <td class="text-center">{{ $appointment->Test_name }}</td>
                <td class="text-center">{{ $appointment->App_Date }}</td>
                <td class="text-center">{{ $appointment->App_Time }}</td>
                <td class="text-center">{{ $appointment->Test_Status }}</td>
                <td><button type="button" class="btn btn-primary">{{ $appointment->Report_Status }}</button></td>
            </tr>
        @endforeach

        </tbody>
    </table>

</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>

<script>
    new DataTable('#LabTests');
</script>



