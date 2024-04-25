 @extends('layouts.admin')

 @section('title')
 address
 @endsection

 @section('header')
 @endsection

 @section('heading')
 <a class="navbar-brand" href="#">Address</a>
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
                             <a class="navbar-brand">User Address</a>
                         </h3>
                     </div>
                     <div class="col-2">
                         <img src="{{ asset('img/excel.png') }}" alt="excelImg" data-bs-toggle="modal" data-bs-target="#ExcelModal">
                         <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Address</button>
                     </div>
                 </div>
             </div>
         </div>
         <div class="card-body">
             @if($address->count()==0)
             Address not found
             @else
             <table id="basic-data-table" class="table nowrap" style="width:100%">
                 <thead>
                     <tr>
                         <th>Id</th>
                         <th>Address</th>
                         <th>AddressType</th>
                         <th>State</th>
                         <th>City</th>
                         <th>Landmark</th>
                         <th>Action</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach ($address as $data)
                     <tr>
                         <td>{{$loop->iteration}}</td>
                         <td>
                             {{$data->address1}},
                             {{$data->address2}}
                         </td>
                         <td>{{$data->addressType}}</td>
                         <td>{{$data->addstate ? $data->addstate->state: 'Not Found'}}</td>
                         <td>{{$data->city}}</td>
                         <td>{{$data->landmark}}</td>
                         <td>
                             <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="editUser('{{$data->id}}')">Edit</button>
                             <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteData" onclick="deleteUser('{{$data->id}}')">Delete</button>
                         </td>
                     </tr>
                     @endforeach
                 </tbody>
             </table>
             @endif
         </div>
     </div>
 </div>

 <!-- Modal Add  user address-->
 <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title fs-5" id="exampleModalLabel">User Address</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form action="{{url('/address/add')}}" class="modalForm" method="POST">
                     @csrf
                     <div class="row">
                         <input type="hidden" name="id" value="{{$user->id}}">
                         <input type="hidden" name="addressId" id="addressId">
                         <div class="col-6">
                             <div class="address1 pt-2">
                                 <label for="address1">Address 1:</label>
                                 <input type="text" name="address1" id="address1" placeholder="Enter AddressLine 1" required>
                             </div>
                         </div>
                         <div class="col-6">
                             <div class="address2 pt-2">
                                 <label for="address2">Address 2:</label>
                                 <input type="text" name="address2" id="address2" placeholder="Enter AddressLine 2" required>
                             </div>
                         </div>
                         <div class="col-6">
                             <div class="status pt-2">
                                 <label for="addressType">Address Type:</label>
                                 <select id="addressType" name="addressType" required>
                                     <option value="">Select Address Type</option>
                                     <option value="home">home</option>
                                     <option value="office">office</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-6">
                             <div class="state pt-2">
                                 <label for="state">State:</label>
                                 <select id="state" name="state" required>
                                     <option value="">Select State</option>
                                     @foreach ($state as $dataState)
                                     <option value="{{$dataState->id}}">{{$dataState->state}}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="col-6">
                             <div class="city pt-2">
                                 <label for="city">City:</label>
                                 <input type="text" name="city" id="city" placeholder="Enter city" required>
                             </div>
                         </div>
                         <div class="col-6">
                             <div class="pincode pt-2">
                                 <label for="pincode">Pincode:</label>
                                 <input type="number" name="pincode" id="pincode" placeholder="Enter pincode" required>
                             </div>
                         </div>
                         <div class="col-12">
                             <div class="landmark pt-2">
                                 <label for="landmark">Landmark:</label>
                                 <input type="text" name="landmark" id="landmark" placeholder="Enter landmark" required>
                             </div>
                         </div>
                     </div>
                     <div class="modal-footer mt-3">
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

 <div class="modal fade" id="ExcelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add ExcelSheets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/address/addressFile')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <label for="">Upload Excel File:</label>
                    <input type="file" name="addressFile" class="form-control" id="excelFile">
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

 <!-- Modal Delete-->
 <div class="modal fade" id="deleteData" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
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
                 <form action="/address/delete" method="post">
                     @csrf
                     <input type="hidden" name="id" id="deleteId">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-danger">Delete</button>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!--  -->
 @endsection
 @section('scripts')
 <script>
     function editUser(userId) {
         var address = @json($address);
         var address = address.find(x => x, id => userId);
         console.log(address);

         var state = @json($state);
         console.log(state);

         $('#addressId').val(address.id);
         $('#address1').val(address.address1);
         $('#address2').val(address.address2);
         $('#addressType').val(address.addressType).change();
         $.each(state, function(i, statee) {
             $('#state').append($('<option>', {
                 value: statee.id,
                 text: statee.state,
                 selected: statee.id == address.state
             }))
         });
         $('#city').val(address.city);
         $('#pincode').val(address.pincode);
         $('#landmark').val(address.landmark);

     }

     function deleteUser(userId) {
         var address = @json($address);
         var address = address.find(x => x, id => userId);
         console.log(address);
         $('#deleteId').val(address.id);
     }
 </script>
 @endsection