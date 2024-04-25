  @extends('layouts.auth')

  @section('title')
  Login
  @endsection

  @section('header')
  @endsection

  @section('content')
  <div class="box">
    <div class="wrapper">
      <h2>Login</h2>
      <form action="{{url('/home/login')}}" method="post" onsubmit="validation();" name="myForm">
        @csrf
        <!-- {{Auth::guest() ? 'logged out' : 'logged in'}} -->
        <div class="">
          <label for="phone">Enter Phone:</label>
          <input type="tel" class="form-control" name="phone" id="phoneNum" placeholder="Enter phone Number" onkeyup="validatePhone()">
          <!-- <span class="errorPhone" id="errorPhone"></span> -->
          <div class="invalid-feedback" id="errorPhone"></div>

        </div>
        <div class="mt-3">
          <label for="pass">Enter Password:</label>
          <input type="password" class="form-control" name="pass" id="password" placeholder="Enter password">
          <div class="invalid-feedback" id="errorPass"></div>
        </div>
        <div class="pt-3">
            <a href="{{url('/forgetPass')}}" style="text-decoration: none; color:blue">Forgot password?</a>
        </div>
        <div class="input-box button">
          <button type="submit" class="btn btn-primary w-100" onclick="validation()" >login</button>
        </div>
        <div class="text">
          <h3>Already have an account? <a href="{{url('/home/signup')}}">create one</a></h3>
        </div>
      </form>
    </div>
  </div>
  @endsection

  @section('scripts')
  <script>
    function validatePhone(e) {
      // let form = document.getElementById("myForm");
      let phoneNum = document.getElementById('phoneNum').value;
      if (phoneNum.length < 10) {
        // phoneNum.classList.remove("invalid");
        // phoneNum.classList.add("valid");
        document.getElementById("errorPhone").innerHTML = "Please enter 10 digit phone number";
        return false;
      } else {
        // phoneNum.classList.remove("valid");
        // phoneNum.classList.add("invalid");
        document.getElementById("errorPhone").innerHTML = "valid phone given" + "<style> .errorPhone{ color: green; }</style>";
        return true;
      }
    }

    function validation() {
      let phone = $('#phoneNum');
      let phoneError = $('#errorPhone');
      
      let password = $('#password');
      let passwordError = $('#errorPass');

      console.log("in validation function");

      if (phone.val()) {
        phone.addClass('is-valid');
        phone.removeClass('is-invalid');
        phoneError.html('')
        
      } else {
        phone.removeClass('is-valid');
        phone.addClass('is-invalid');
        phoneError.html('Pls enter a valid Phone')

      }

      if (password.val()) {
        password.removeClass("is-invalid");
        password.addClass("is-valid");
      } else {
        password.removeClass("is-valid");
        password.addClass("is-invalid");
      }

    }
  </script>
  @endsection