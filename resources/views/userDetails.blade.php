@extends('layouts.admin')

@section('title')
Users
@endsection

@section('header')
<!--  -->
@endsection

@section('heading')
<a class="navbar-brand" href="#">User</a>
@endsection

@section('content')
<div class="container mt-5">
    <div class="">
        @if(Session::has('error_message'))
        <div class="alert alert-danger">
            {{ Session::get('error_message') }}
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>
    <div class="">
        @if(Session::has('sucess_message'))
        <div class="alert alert-success">
            {{ Session::get('sucess_message') }}
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-10">
                        <h3>
                            <a class="navbar-brand">User Details</a>
                        </h3>
                    </div>
                    <div class="col-2">
                        <img src="{{ asset('img/excel.png') }}" alt="excelImg" data-bs-toggle="modal" data-bs-target="#ExcelModal">
                        <button type="button" class="btn btn-success ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Add user</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="basic-data-table" class="table nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>profileImg</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Address</th>
                        <th>User Images</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $data)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><img src="{{$data->profileImg ? '/'.$data->profileImg : asset('/img/blank.png')}}" alt="profileImage" class="profile" /></td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->email}}</td>
                        <td>{{$data->phone}}</td>
                        <td>{{$data->rolee ? $data->rolee->name : 'Not Found'}}</td>
                        <td>
                            <button type="button" class="btn btn-primary adressBtn"><a href="{{url('/address')}}/{{$data->id}}">view</a></button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userImg{{$data->id}}">view Images</button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editUser{{$data->id}}">Edit</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteData{{$data->id}}">Delete</button>
                        </td>

                        <!-- modal user Images -->
                        <div class="modal fade" id="userImg{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="userImgsModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">User Images</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{url('/user/userImgs')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="userId" value="{{$data->id}}">
                                            <label for="">Upload Images:</label>
                                            <input type="file" name="images[]" class="form-control" id="userImgs" multiple="">
                                            <div class="btns m-3">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                        <div class="row">
                                            @foreach ($data->userImgs as $imagesdata)
                                            <div class="col-6">
                                                <img src="{{ asset($imagesdata->images) }}" alt="Item Image" class="userImags">
                                                <div>
                                                    <form action="{{url('/user/deleteImgs')}}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="hiddenId" value="{{$imagesdata->id}}">
                                                        <button type="submit" class="btn btn-danger m-2">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editUser{{$data->id}}" tabindex="-1" aria-labelledby="EditModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fs-5" id="EditModal">Edit Profile</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{url('/user/edit')}}" class="modalForm" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="userId" value="{{$data->id}}">

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-4">
                                                        @if ("/{{ $data->profileImg }}")
                                                        <img src="/{{($data->profileImg)}}" class="imgOld" id="imgOld{{$data->id}}">
                                                        @else
                                                        <p>No image found</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label>Upload Profile Image:</label>
                                                    <input type="file" name="profileImg" id="pic{{$data->id}}" onchange="changeProfile('{{$data->id}}')">
                                                </div>
                                                <div class="col-6 pt-2">
                                                    <label for="name">Name:</label>
                                                    <input type="text" name="name" placeholder="Name" value="{{$data->name}}" required>
                                                </div>
                                                <div class="col-6 pt-2">
                                                    <label for="email">Email:</label>
                                                    <input type="email" name="email" placeholder="Email" value="{{$data->email}}">
                                                </div>
                                                <div class="col-6 pt-2">
                                                    <label for="phone">Phone:</label>
                                                    <input type="tel" name="phone" placeholder="Phone Number" value="{{$data->phone}}" required>
                                                </div>
                                                <div class="col-6 pt-2">
                                                    <label for="dob">Date Of Birth:</label>
                                                    <input type="date" name="dob" placeholder="Date Of Birth" value="{{$data->dob}}" required>
                                                </div>
                                                <div class="col-6 pt-2">
                                                    <label for="gender">Gender:</label>
                                                    <div>
                                                        <input type="radio" name="gender" value="male" {{ $data->gender=="male"? 'checked':''}}>
                                                        <label for="male" class="m-2">Male</label>
                                                        <input type="radio" name="gender" value="female" {{ $data->gender=="female"? 'checked':''}}>
                                                        <label for="female" class="m-2">Female</label>
                                                    </div>
                                                </div>
                                                <div class="col-6 pt-2">
                                                    <label for="role">Role:</label><br>
                                                    <select name="role">
                                                        @foreach ($role as $dataRoles)
                                                        <option value="{{$dataRoles->slug}}" {{ $dataRoles->slug==$data->role ? 'selected':''}}>{{$dataRoles->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="status pt-2">
                                                    <label for="status">Status:</label>
                                                    <select id="status" class="form-select" name="status" required>
                                                        <option value="">Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer mt-4">
                                                <div class="btns">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Delete-->
                        <div class="modal fade" id="deleteData{{$data->id}}" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this item?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{url('user/delete')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="userId" value="{{$data->id}}">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal  Add User-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="exampleModalLabel">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('/home/signup/add')}}" class="modalForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4">
                                <img src="" class="profile-pic" id="imgOld" alt="" onerror="">
                            </div>
                        </div>
                        <div class="col-12">
                            <label>Upload Profile Image:</label>
                            <input type="file" name="profileImg" id="ImgProfile" required>
                        </div>
                        <div class="col-6">
                            <div class="name pt-2">
                                <label for="name">Name:</label>
                                <input type="text" name="name" id="name" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="email pt-2">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="phone pt-2">
                                <label for="phone">Phone:</label>
                                <input type="tel" name="phone" id="phone" placeholder="Phone Number" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="password pt-2">
                                <label for="pass">Password:</label>
                                <input type="password" name="pass" id="pass" placeholder="Create password" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="Confirmpassword pt-2">
                                <label for="pass">Confirm Password:</label>
                                <input type="password" name="pass" id="confirmpass" placeholder="Confirm password" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="dob pt-2">
                                <label for="dob">Date Of Birth:</label>
                                <input type="date" name="dob" id="dob" placeholder="Date Of Birth" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="gender">
                                <label for="">Gender:</label><br>
                                <input type="radio" name="gender" value="male" id="male">
                                <label for="male" class="m-2">Male</label>
                                <input type="radio" name="gender" value="female" id="female">
                                <label for="female" class="m-2">Female</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="role pt-2">
                                <label for="role">Role:</label>
                                <select id="role" name="role" required>
                                    @foreach ($role as $dataRoles)
                                    <option value="{{$dataRoles->slug}}">{{$dataRoles->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="status pt-2">
                                <label for="status">Status:</label>
                                <select id="status" class="form-select" name="status" required>
                                    <option value="">Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer mt-4">
                            <div class="btns">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal Add excel -->
<div class="modal fade" id="ExcelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add ExcelSheets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/user/excelFile')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <label for="">Upload Excel File:</label>
                    <input type="file" name="excelFile" class="form-control" id="excelFile">
                </div>
                <div class="modal-footer">
                    <div class="btns m-3">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <button type="submit" class="btn btn-warning">Dawonload Format</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#ImgProfile').change(function() {
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#imgOld').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });

    // function openDeleteImageModal(userId, imageId){
    //     $('#userImg'+userId).modal('hide');
    //     $('#deleteImgData'+imageId).modal('toggle');
    // }

    function changeProfile(id) {
        var fileInput = document.getElementById('pic' + id);
        var imageElement = document.getElementById('imgOld' + id)
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                imageElement.src = e.target.result;
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>
@endsection