@extends('layout.master')

@section('title')
Albums
@endsection

@section('css')
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Albums</h2>
    <div class="row">
        <div class="col-12">
            <div class="card ">
                <div class="card-header">
                    <div class="user-block">
                        <h3 class="card-title">Albums</h3>
                    </div>
                    <div class="card-tools">
                        <button id="add" class="btn btn-sm btn-info AddAlbum" data-bs-toggle="modal" data-bs-target="#AddAlbum">
                            <i class="fas fa-plus-circle"></i> Add New Album </i>
                        </button>
                        
                    </div>
                </div>
                <br>
                <div class="card-body">
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
                    <table class="table  table-bordered table-striped" id='albums'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Pictures Count</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <br><br>
                    <canvas id="myChart" width="800" height="450"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="updateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Album name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="album_id" value="0">
                    <button type="button" class="btn btn-success btn-sm" id="btn_save">Save</button>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="AddAlbum" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <form id='add_album_form' class='form' action="{{route('albums.store')}}" method='POST' enctype="multipart/form-data">
                @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Album</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name = 'name' placeholder="Enter Album name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="album_id" value="0">
                    <button type="submit" class="btn btn-success btn-sm" id="add">Add Album</button>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="deleteAlbum" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p>
                        Are You sure do you wanna delete this album ?
                    </p>
                    <div class="form-group" id ='ch_alb'>
                        <input type="hidden" id ='id' name="id" value="">
                        <label for="album_del_id">Choose Album</label>
                        <select class="form-control"  id = 'album_del_id' name ='album_del_id'>
                            @foreach ($data as  $album)
                                <option value="{{$album->id}}">{{$album->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="album_id" value="0">
                    <button type="button" class="btn btn-success btn-sm" id="btn_tran">Move to Album </button>
                    <button type="button" class="btn btn-success btn-sm" id="btn_del">Delete</button>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
   
   
</div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var albums = {}
            $("#album_del_id option").each(function() {
                albums[this.value] = this.text; 
            }); 

            var table = $('#albums').DataTable({
                // processing: true,
                // serverSide: true,
                ajax: "{{ route('albums.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'pictures_count', name: 'pictures_count'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: true, 
                        searchable: true
                    },
                ]
            });
           
            // Update record
            $('#albums').on('click','.updateAlbum',function(){
                var id = $(this).data('id');
                $('#album_id').val(id);

                // AJAX request
                $.ajax({
                    url: "{{ route('getAlbum') }}",
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                    },
                    dataType: 'json',
                    success: function(response){
                        if(response.success == 1){
                            $('#name').val(response.name);
                            table.ajax.reload();
                        }else{
                            alert("Invalid ID.");
                        }
                    }
                });

            });

            // Save album 
            $('#btn_save').click(function(){
                    var id = $('#album_id').val();
                    var name = $('#name').val().trim();
                    if(name !='' ){
                        // AJAX request
                        $.ajax({
                            url: "{{ route('updateAlbum') }}",
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id,
                                "name": name,
                            },  
                            dataType: 'json',
                            success: function(response){
                                if(response.success == 1){
                                    alert(response.msg);
                                    // Empty and reset the values
                                    $('#album_id').val(0);
                                    // Reload DataTable
                                    table.ajax.reload();
                                    // Close modal
                                    $('#updateModal').modal('toggle');
                                }else{
                                    alert(response.msg);
                                }
                            }
                        });

                    }else{
                        alert('Please fill all fields.');
                    }
            });
        
            // Delete albums
            $('#albums').on('click','.deleteAlbum',function(){
                    var id = $(this).data('id');
                    document.getElementById('id').value = id;
                    var count = $(this).data('count');
                    $("#album_del_id").empty();
                    if(count>0 && Object.keys(albums).length>1){

                        document.getElementById('ch_alb').hidden = false;
                        document.getElementById('btn_tran').hidden = false;

                        for (const [key, value] of Object.entries(albums)) {
                        $('#album_del_id').append(`<option value="${key}">
                                            ${value}
                                        </option>`);
                        }
                        $("#album_del_id option[value='"+id+"']").remove();
                    }else{
                        document.getElementById('ch_alb').hidden = true;
                        document.getElementById('btn_tran').hidden = true;
                    }
            });

            $('#btn_del').click(function(){
                    var id = document.getElementById('id').value;
                    // alert($('#album_del_id').val())
                    $.ajax({
                        url: "{{ route('DeleteAlbum') }}",
                        type: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                        },  
                        dataType: 'json',
                        success: function(response){
                            if(response.success == 1){
                                alert(response.msg);
                                // Reload DataTable
                                table.ajax.reload();
                                // Close modal
                                $('#deleteAlbum').modal('toggle');
                            }else{
                                alert(response.msg);
                            }
                        }
                    });
                
            });

            $('#btn_tran').click(function(){
                    var id = document.getElementById('id').value;
                    var move_to = $('#album_del_id').val();
                    $.ajax({
                        url: "{{ route('MoveAlbum') }}",
                        type: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "move_to": move_to,
                        },  
                        dataType: 'json',
                        success: function(response){
                            if(response.success == 1){
                                alert(response.msg);
                                // Reload DataTable
                                table.ajax.reload();
                                // Close modal
                                $('#deleteAlbum').modal('toggle');
                            }else{
                                alert(response.msg);
                            }
                        }
                    });

                });
            
            });
            // Chart view
            $.ajax({
                url: "{{ route('Chart') }}",
                type: 'get',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response){
                        new Chart(document.getElementById("myChart"), {
                            type: 'doughnut',
                            data: {
                            labels: response[0],
                            datasets: [
                                {
                                label: "count (images)",
                                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                                data:  response[1]
                                }
                            ]
                            },
                            options: {
                            title: {
                                display: true,
                                text: 'image count in each album '
                            },
                            
                            }
                        });
                    }
                }
            });
            
        
    </script>

@endsection