<!DOCTYPE html>
<html lang="en">
<head>
    <title>HealthBridge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.css">
    <!-- Load an icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .btn-primary, .btn-primary:active, .btn-primary:visited {
            border: none;
        }
        .btn-primary:hover {
            background-color: #8D74AC;
        }
        .btn-primary{
            font-size: 14px;
        }

        .btn-primary, .btn-primary:active, .btn-primary:visited {
            background-color: rgba(87, 17, 132, 0.35);
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

{{--Welcome dashboard--}}
<div class="container-sm">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5">

                            <h3 class="mb-5">Welcome to HealthBridge!</h3>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <ul>
                                    <li><p1>Book appointments with desired labs</p1></li>
                                </ul>
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <ul>
                                    <li><p1>Search for lab tests</p1></li>
                                </ul>
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <ul>
                                    <li><p1>Integrated healthcare system</p1></li>
                                </ul>
                            </div>


                            <hr class="my-4">

                           <a type="button" class="btn btn-lg btn-block btn-primary" style="background-color: #420160;" href="{{ route('show_lab_tests') }}">Search for Labs</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


</body>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.js"></script>

