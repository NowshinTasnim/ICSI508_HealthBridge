@extends('layouts.lab')

@section('content')
@php $page = 'Lab_profile'; @endphp

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6 text-center" style="color: #4b0082;">Profile</h1>


        {{-- Lab Details --}}
        <div class="border p-4 rounded" style="border: 2px solid #a855f7; border-radius: 5px; margin:20px">
            <div style=" margin: 30px">
                <h4 class="text-xl font-semibold mb-4" style="color: #4b0082;" >Basic Information</h4>
                <p style="text-indent: 1.5em;"><strong>Name:</strong> {{ $lab->Lab_Name }}</p>
                <p style="text-indent: 1.5em;"><strong>Address:</strong> {{ $lab->Physical_address }}</p>
                <p style="text-indent: 1.5em;"><strong>License No:</strong> {{ $lab->License_no }}</p>
                <p style="text-indent: 1.5em;"><strong>Phone:</strong> {{ $lab->Phone_no }}</p>
                <p style="text-indent: 1.5em;"><strong>Email:</strong> {{ $lab->Email }}</p>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="border p-4 rounded" style="border: 2px solid #a855f7; border-radius: 5px; margin:20px">
            <div style="margin: 30px">
                <h4 class="text-xl font-semibold mb-4" style="color: #4b0082;">Want to Update Password?</h4>
                {{-- <form action="{{ route('lab.updatePassword') }}" method="POST" style= "margin-left: 25px; margin-right: 25 px;"> --}}
                    <div style="margin:auto;width: 600px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <form action="{{ route('Lab.updatePassword') }}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="mb-3" style="margin-bottom: 10px;">
                                <label for="current_password" class="form-label" style="font-weight: bold;">Current Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="current_password"
                                    name="current_password"
                                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                                    placeholder="Enter your current password"
                                    required
                                >
                                @error('current_password')
                                    <span class="text-danger" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3" style="margin-bottom: 10px;">
                                <label for="new_password" class="form-label" style="font-weight: bold;">New Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="new_password"
                                    name="new_password"
                                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                                    placeholder="Enter your new password"
                                    required
                                >
                                @error('new_password')
                                    <span class="text-danger" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3" style="margin-bottom: 10px;">
                                <label for="confirm_password" class="form-label" style="font-weight: bold;">Confirm New Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirm_password"
                                    name="new_password_confirmation"
                                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                                    placeholder="Confirm your new password"
                                    required
                                >
                                @error('new_password_confirmation')
                                    <span class="text-danger" style="color: red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div style="display: flex; justify-content: center; margin-top: 20px;">
                                <button type="submit" class="btn" style="background-color: #4b0082; color: white; padding: 10px 20px; border-radius: 5px; border: none;">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>

        {{-- Available Tests --}}
        <div class="border p-4 rounded mb-6" style="border: 2px solid #a855f7; border-radius: 5px; margin:20px">
            <div style=" margin: 30px">
                <h4 class="text-xl font-semibold mb-4" style="color: #4b0082;">Available Supported Tests</h4>
                <ul class="space-y-4" style="margin-left: 25px;margin-right: 25px;">
                    @foreach ($availableTests as $test)
                        <li class="flex items-center justify-between bg-gray-100 p-3 rounded">
                            <span class="text-gray-700">{{ $test->Test_name }}</span>
                            <form method="POST" action="{{ route('Lab.updateTest') }}" class="ml-auto">
                                @csrf

                                <input type="hidden" name="LabID" value="{{ $lab->LabID }}">
                                <input type="hidden" name="TestID" value="{{ $test->TestID }}">
                                <input type="hidden" name="Action" value="Delete">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded transition" style= "border-radius: 5px;"
                                onmouseover="this.style.backgroundColor='#991b1b';" onmouseout="this.style.backgroundColor= '#ef4444';">
                                    Delete
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Add New Tests --}}
        <div class="border p-4 rounded" style="border: 2px solid #a855f7; border-radius: 5px; margin:20px">
            <div style=" margin: 30px">
                <h4 class="text-xl font-semibold mb-4" style="color: #4b0082;" >Want to Add Tests?</h4>
                <form method="POST" action="{{ route('Lab.updateTest') }}">
                    @csrf
                    <input type="hidden" name="LabID" value="{{ $lab->LabID }}">
                    <div class="space-y-3">
                        @foreach ($allTests as $test)
                            <div class="flex items-center bg-gray-100 p-3 rounded" style="margin-left: 25px;margin-right: 25px; padding:6px;">
                                <input type="checkbox" name="TestID[]" value="{{ $test->TestID }}" class="mr-2">
                                <span class="text-gray-700">{{ $test->Test_name }}</span>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="Action" value="Add">
                    <button type="submit" class="bg-purple-800 text-white px-5 py-2 mt-4 rounded hover:bg-purple-700 transition block mx-auto"
                    style="width: 80px; border-radius: 5px">Add
                    </button>
                </form>
            </div>
        </div>
    </div>


@endsection
