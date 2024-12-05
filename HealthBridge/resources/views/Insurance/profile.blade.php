@extends('layouts.insurance')

@section('content')
{{--    @php $page = 'Lab_profile'; @endphp--}}

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6 text-center" style="color: #4b0082;">Insurance Profile</h1>


        {{-- Insurance Details --}}
        <div class="border p-4 rounded" style="border: 2px solid #a855f7; border-radius: 5px; margin:20px">
            <div style=" margin: 30px">
                <h4 class="text-xl font-semibold mb-4" style="color: #4b0082;" >Basic Information</h4>
                <p style="text-indent: 1.5em;"><strong>Name:</strong> {{ $insuranceId->Ins_Name }}</p>
                <p style="text-indent: 1.5em;"><strong>Address:</strong> {{ $insuranceId->Email }}</p>

            </div>
        </div>

{{--        Update Password--}}

        <div class="border p-4 rounded" style="border: 2px solid #a855f7; border-radius: 5px; margin:20px">
            <div style="margin: 30px">
                <h4 class="text-xl font-semibold mb-4" style="color: #4b0082;">Want to Update Password?</h4>
                {{-- <form action="{{ route('lab.updatePassword') }}" method="POST" style= "margin-left: 25px; margin-right: 25 px;"> --}}
                <div style="margin:auto;width: 600px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <form action="{{ route('Insurance.updatePassword') }}" method="POST">
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




@endsection
