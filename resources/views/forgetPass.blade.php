@extends('layouts.auth')

@section('title')
forget password
@endsection

@section('header')
@endsection

@section('content')
<div class="box">
    <div class="wrapper mt-5">
        <h2>Forget Password</h2>
        <form>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
            <button type="button" class="btn btn-primary w-100 mt-2" id="btnEmail" onclick="validateEmail()">Submit</button>

            <div id="otpSection" style="display: none;">
                <label for="email">Otp:</label>
                <input type="text" id="otp" name="otp">
                <button type="button" class="btn btn-primary w-100 mt-2" onclick="verifyOTP()">Verify</button>
            </div>
            <div id="passSection" style="display: none;">
                <label for="password">Reset Password:</label>
                <input type="password" id="newPass" name="newPass">
                <label for="ConfirmPassword">Confirm Password:</label>
                <input type="password" id="ConfirmPassword" name="newPass">
                <button type="button" class="btn btn-primary w-100 mt-2" onclick="changePassword()">Verify</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@if(session('success','error'))
@if(Session::has('message'))
@endif
@endif

<script>
    function validateEmail() {
        var email = $("#email");
        let emailRegex = /^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/;

        if (emailRegex.test(email.val())) {
            $.ajax({
                type: "POST",
                url: "/forgtpass/add",
                data: {
                    email: email.val(),
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status === 200) {
                        // Email exists, show OTP input
                        document.getElementById("btnEmail").style.display = "none";
                        document.getElementById("otpSection").style.display = "block";
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        }
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error(response.message);
                }
            });
        } else {
            toastr.error("Please enter a valid email address");
        }
    }

    function verifyOTP() {
        var email = $("#email");
        var otpValue = $('#otp');

        $.ajax({
            type: "POST",
            url: "/forgtpass/verifyOtp",
            data: {
                email: email.val(),
                otp: otpValue.val(),
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                console.log(response);
                if (response.status === 200) {
                    document.getElementById("otpSection").style.display = "none";
                    document.getElementById("passSection").style.display = "block";
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error(response.message);
            }
        });
    }


    function changePassword() {
        var email = $("#email");
        var newPass = $('#newPass');
        var ConfirmPassword = $('#ConfirmPassword');

        $.ajax({
            type: "POST",
            url: "/forgtpass/changePass",
            data: {
                email: email.val(),
                newPass: newPass.val(),
                ConfirmPassword: ConfirmPassword.val(),
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                console.log(response);
                window.location.href = '/home/login';
                toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success(response.message);
            },
            error: function(response) {
                console.log(response);
                toastr.error(response.message);
            }
        });
    }
</script>

@endsection