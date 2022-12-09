@extends('layout.master')

@section('title')

@endsection

@section('css')
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        @if(session()->has('success') )
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error') )
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="col-md-12">
            <h2>Album Pictures ({{$album->name}})</h2>
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <button id="add" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddPicture">
                        <i class="fas fa-plus-circle"></i> Add New Picture </i>
                    </button>
                    <h6 class="m-0 font-weight-bold text-primary">Image List</h6>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($images as $image)
                    <tr>
                        <td>{{ $image->id }}</td>
                        <td>{{ $image->pic_name }}</td>
                        <td><img src="{{ $image->getFirstMediaUrl('pictures') }}" alt="no image" width="100" height="100"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="AddPicture" class="modal fade" role="dialog">
                <div class="modal-dialog">
        
                    <!-- Modal content-->
                    <form id='add_picture_form' class='form' action="{{route('pictures.store')}}" method='POST' enctype="multipart/form-data">
                        @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Picture</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="pic_name" name = 'pic_name' placeholder="Enter Picture name" required>
                            </div>
                            <div class="form-group">
                                <label for="image">picture</label>
                                <input type="file" class="form-control" id="image" name = 'image' required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="album_id" name = 'album_id' value="{{$album->id}}">
                            <button type="submit" class="btn btn-success btn-sm" id="add">Add Picture</button>
                            <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
        
                </div>
            </div>
        </div>
    </div>

    
</div>

@endsection

@section('js')
@endsection
