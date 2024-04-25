@extends('layouts.auth')

@section('title')
signup
@endsection

@section('header')
@endsection

@section('content')
<div class="box">
  <div class="wrapper mb-4">
    <h2>Registration</h2>
    <form action="{{url('/home/signup/add')}}" method="post" id="signUpForm" enctype="multipart/form-data">
      @csrf
      <div class="row d-flex justify-content-center ">
        <div class="col-12">
          <div class="mb-4">
            <img src="" class="profile-pic" id="oldImg" alt="" onerror="">
          </div>
        </div>
        <div class="col-12">
          <label>Upload Profile Image:</label>
          <input type="file" name="profileImg" class="form-control" id="ImgProfile" onchange="validateprofile()">
          <div class="invalid-feedback" id="errorfile"></div>
        </div>
        <div class="col-6">
          <div class="pt-2">
            <label for="name">Name:</label>
            <input type="text" class="form-control" placeholder="Enter your name" name="name" id="name" onkeyup="validateName()">
            <!-- <span class="msg_Name" id="errorName"></span> -->
            <div class="invalid-feedback" id="errorName"></div>
          </div>
        </div>
        <div class="col-6">
          <div class="pt-2">
            <label for="email">Email:</label>
            <input type="email" class="form-control" placeholder="Enter your email" name="email" id="email" onkeyup="validateEmail()">
            <!-- <span class="msg_Email" id="errorEmail"></span> -->
            <div class="invalid-feedback" id="errorEmail"></div>
          </div>
        </div>
        <div class="col-6">
          <div class="pt-2">
            <label for="phone">Enter Phone:</label>
            <input type="number" class="form-control" name="phone" id="phone" placeholder="Enter phone Number" step="0.01" maxlength="10" onkeyup="validatePhone()">
            <!-- <span class="errorPhone" id="errorPhone"></span> -->
            <div class="invalid-feedback" id="errorPhone"></div>
          </div>
        </div>
        <div class="col-6">
          <div class="pt-2">
            <label for="dob">Date Of Birth:</label>
            <input type="date" class="form-control" placeholder="Enter Date of Birth" class="form-control" name="dob" id="dob" onchange="validateDob()">
            <div class="invalid-feedback" id="errorDob"></div>
          </div>
        </div>
        <div class="col-12">
          <div class="pt-2">
            <label for="pass">Password:</label>
            <input type="password" class="form-control" placeholder="Create password" class="form-control" name="pass" id="pass" onkeyup="validatePass()">
            <div class="invalid-feedback" id="errorPass"></div>
            <div class="row">
              <div class="col-6" id="check1">
                <i class="fa-regular fa-circle-check"></i>
                <span>A Special Char</span>
              </div>
              <div class="col-6" id="check2">
                <i class="fa-regular fa-circle-check"></i>
                <span>A Uppercase leeter</span>
              </div>
              <div class="col-6" id="check3">
                <i class="fa-regular fa-circle-check"></i>
                <span>A number</span>
              </div>
              <div class="col-6" id="check4">
                <i class="fa-regular fa-circle-check"></i>
                <span>Minimum 8 characters</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="pt-2">
            <label for="conPass">Confirm Password:</label>
            <input type="password" class="form-control" placeholder="Confirm password" class="form-control" name="pass" id="conPass" onkeyup="validateConPass()">
            <!-- <span class="errorConPass" id="errorConPass"></span> -->
            <div class="invalid-feedback" id="errorConPass"></div>
          </div>
        </div>
        <div class="col-12 mt-3">
          <div class="gender">
            <label for="">Gender:</label><br>
            <input type="radio" name="gender" value="male" id="male" checked>
            <label for="male" class="m-2">Male</label>
            <input type="radio" name="gender" value="female" id="female">
            <label for="female" class="m-2">Female</label>
          </div>
        </div>
        <div class="input-box button">
          <button type="button" class="btn btn-primary w-100" onclick="validation()">Register Now</button>
        </div>
        <div class="text">
          <h3>Already have an account? <a href="{{url('/home/login')}}">Login now</a></h3>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function(e) {
    $('#ImgProfile').change(function() {
      let reader = new FileReader();
      reader.onload = (e) => {
        $('#oldImg').attr('src', e.target.result);
      }
      reader.readAsDataURL(this.files[0]);
    });
  });

  function validateprofile() {
    let ImgProfile = $('#ImgProfile');
    let errorfile = $('#errorfile');
    if (ImgProfile.val() == "" || ImgProfile.val() == null) {
      ImgProfile.removeClass('is-valid');
      ImgProfile.addClass('is-invalid');
      errorfile.html('Profile is requried')
      return false;
    } else {
      ImgProfile.addClass('is-valid');
      ImgProfile.removeClass('is-invalid');
      errorfile.html('');
      return true;
    }
  }

  function validateName() {
    let regName = /^[a-zA-Z]+ [a-zA-Z]+$/;
    let name = $('#name');
    let errorName = $('#errorName');

    if (name.val().length == 0) {
      name.removeClass('is-valid');
      name.addClass('is-invalid');
      errorName.html('Name is requried')
      return false;
    } else if (!regName.test(name.val())) {
      name.removeClass('is-valid');
      name.addClass('is-invalid');
      errorName.html('Pls enter full Name')
      return false;
    } else {
      name.addClass('is-valid');
      name.removeClass('is-invalid');
      errorName.html('');
      return true;
    }
  }

  function validateDob() {
    let ImgProfile = $('#ImgProfile');
    let errorfile = $('#errorfile');

    let dob = $('#dob');
    let errorDob = $('#errorDob');
    if (dob.val() == "" || dob.val() == null) {
      dob.removeClass('is-valid');
      dob.addClass('is-invalid');
      errorDob.html('Dob is requried')
      return false;
    } else {
      dob.addClass('is-valid');
      dob.removeClass('is-invalid');
      errorDob.html('');
      return true;
    }
  }

  function validateEmail() {
    let regEmail = /^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/;
    let email = $('#email');
    let errorEmail = $('#errorEmail');
    if (email.val().length == 0) {
      email.removeClass('is-valid');
      email.addClass('is-invalid');
      errorEmail.html('email is requried')
      return false;
    } else if (!regEmail.test(email.val())) {
      email.removeClass('is-valid');
      email.addClass('is-invalid');
      errorEmail.html('Pls enter valid email')
      return false;
    } else {
      email.addClass('is-valid');
      email.removeClass('is-invalid');
      errorEmail.html('');
      return true;
    }
  }

  function validatePhone() {
    let regPhone = /^[6-9][0-9]{9}$/;
    let phone = $('#phone');
    let errorPhone = $('#errorPhone');
    if (phone.val().length == 0) {
      phone.removeClass('is-valid');
      phone.addClass('is-invalid');
      errorPhone.html('Required phone Number')
      return false;
    } else if (phone.val().length < 10) {
      phone.removeClass('is-valid');
      phone.addClass('is-invalid');
      errorPhone.html('Invalid phone Number')
      return false;
    } else if (!regPhone.test(phone.val())) {
      phone.removeClass('is-valid');
      phone.addClass('is-invalid');
      errorPhone.html('Invalid phone Number')
      return false;
    } else {
      phone.addClass('is-valid');
      phone.removeClass('is-invalid');
      errorPhone.html('');
      return true;
    }
  }

  function validatePass() {
    let password = $('#pass');
    let errorPass = $('#errorPass');
    let specialChar = /[\!\@\#\$\%\^\&\*\)\(\+\=\.\<\>\{\}\[\]\:\;\'\"\|\~\`\_\-]/g;

    if (password.val().length>=8) {
      password.removeClass("is-invalid");
      password.addClass("is-valid");
      errorPass.html('');
    } else {
      password.removeClass("is-valid");
      password.addClass("is-invalid");
      errorPass.html('');
    }

    if (password.val().match(specialChar)) {
      document.getElementById('check1').style.color = "green";
    } else {
      document.getElementById('check1').style.color = "red";
    }

    let uppercaseRegx = /[A-Z]/g;

    if (password.val().match(uppercaseRegx)) {
      document.getElementById('check2').style.color = "green";
    } else {
      document.getElementById('check2').style.color = "red";
    }

    let numbers = /[0-9]/g;
    if (password.val().match(numbers)) {
      document.getElementById('check3').style.color = "green";
    } else {
      document.getElementById('check3').style.color = "red";
    }

    if (password.val().length >= 8) {
      document.getElementById("check4").style.color = "green";
    } else {
      document.getElementById('check4').style.color = "red";
    }
  }

  function validateConPass() {
    let ConPass = $('#conPass');
    let errorConPass = $('#errorConPass');
    let password = $('#pass');
    if (ConPass.val() != password.val()) {
      ConPass.removeClass('is-valid');
      ConPass.addClass('is-invalid');
      errorConPass.html('password does not match')
      return false;
    } else {
      ConPass.addClass('is-valid');
      ConPass.removeClass('is-invalid');
      errorConPass.html('');
      return true;
    }
  }

  function validation() {
    let ImgProfile = $('#ImgProfile');
    let errorfile = $('#errorfile');
    // error flag
    var imageFlag = false;

    let name = $('#name');
    let errorName = $('#errorName');
    var nameFlag = false;

    let email = $('#email');
    let errorEmail = $('#errorEmail');
    var emailFlag = false;

    let phone = $('#phone');
    let errorPhone = $('#errorPhone');
    var phoneFlag = false;

    let dob = $('#dob');
    let errorDob = $('#errorDob');
    var dobFlag = false;

    let password = $('#pass');
    let errorPass = $('#errorPass');
    var passFlag = false;

    let ConPass = $('#conPass');
    let errorConPass = $('#errorConPass');
    var conPassFlag = false;    

    if (ImgProfile.val()) {
      ImgProfile.addClass('is-valid');
      ImgProfile.removeClass('is-invalid');
      errorfile.html('');
      imageFlag = true;
    } else {
      ImgProfile.removeClass('is-valid');
      ImgProfile.addClass('is-invalid');
      errorfile.html('Pls choose valid file');
      imageFlag = false;
    }


    if (name.val()) {
      name.addClass('is-valid');
      name.removeClass('is-invalid');
      errorName.html('');
      nameFlag = true;
    } else {
      name.removeClass('is-valid');
      name.addClass('is-invalid');
      errorName.html('Pls enter a valid Name')
      nameFlag = false;
    }

    if (email.val()) {
      email.addClass('is-valid');
      email.removeClass('is-invalid');
      errorEmail.html('');
      emailFlag = true;

    } else {
      email.removeClass('is-valid');
      email.addClass('is-invalid');
      errorEmail.html('Pls enter a valid Email')
      emailFlag = false;
    }


    if (phone.val()) {
      phone.addClass('is-valid');
      phone.removeClass('is-invalid');
      errorPhone.html('')
      phoneFlag = true;
    } else {
      phone.removeClass('is-valid');
      phone.addClass('is-invalid');
      errorPhone.html('Pls enter a valid Phone');
      phoneFlag = false;

    }
    if (dob.val()) {
      dob.addClass('is-valid');
      dob.removeClass('is-invalid');
      errorDob.html('')
      dobFlag = true;


    } else {
      dob.removeClass('is-valid');
      dob.addClass('is-invalid');
      errorDob.html('Pls enter a valid Dob');
      dobFlag = false;
    }

    if (password.val()) {
      password.removeClass("is-invalid");
      password.addClass("is-valid");
      errorPass.html('');
      passFlag = true;

    } else {
      password.removeClass("is-valid");
      password.addClass("is-invalid");
      errorPass.html('Pls enter a valid password');
      passFlag = false;
    }

    if (ConPass.val()) {
      ConPass.removeClass("is-invalid");
      ConPass.addClass("is-valid");
      errorConPass.html('');
      conPassFlag = true;

    } else {
      ConPass.removeClass("is-valid");
      ConPass.addClass("is-invalid");
      errorConPass.html('Pls enter a valid confirm password');
      conPassFlag = false;
    }

    if (imageFlag && nameFlag && emailFlag && phoneFlag && dobFlag && passFlag && conPassFlag) {
      // submit form
      $('#signUpForm').submit();
    } else {
       alert('Please fill all the fields');
    }




  }
</script>
@endsection