 @extends('layouts.admin')

@section('title')
    roles
@endsection
@section('header')
@endsection

@section('heading')
    <a class="navbar-brand" href="#">Roles</a>
@endsection

@section('content')
<div class="container mt-3">
        <div class="card">
            <div class="card-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-10">
                             <h3>             
                                    <a class="navbar-brand">Roles</a>
                            </h3>
                            </div>
                            <div class="col-2">
                                <form class="d-flex" role="search">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Roles</button>
                             </form>        
                            </div>
                        </div>
                    </div>    
            </div>            
            <div class="card-body">
                <table id="basic-data-table" class="table nowrap" style="width:100%">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($role as $data)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$data->name}}</td>
                                <td>
                                    @if($data->status=='1')
                                    active
                                    @else
                                    inactive
                                    @endif
                                </td>
                            </tr>    
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <!-- Modal  Add Roles-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalLabel">Roles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('/roles/add')}}" class="modalForm" method="post" required>
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="name pt-2">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" rrequired>
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
                            </div>
                            <div class="modal-footer">
                                <div class="btns mt-3">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>      
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
@section('scripts')
@endsection