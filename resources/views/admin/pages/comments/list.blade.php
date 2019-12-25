@extends('layouts.admin')

@section('module')
Comments
@endsection

@section('page')
List
@endsection

@section('content')
<div class="container-fluid">
    <h3>
        Comments
    </h3>
    <div class="col-md-12">

        <table class="table table-bordered" id="laravel_datatable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Owner</th>
                    <th>Body</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Manage</th>
                </tr>
            </thead>
        </table>

    </div>
</div>
@endsection

@section("docReadyScript")
var route = "{{route("commentMain")}}";
$('#laravel_datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('commentAjax') }}",
    columns: [
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'owner',
            name: 'owner'
        },
        {
            data: 'body',
            name: 'body'
        },
        {
            data: 'status',
            name: 'status'
        },
        {
            data: 'created_at',
            name: 'created_at'
        },
        {
            data: 'updated_at',
            name: 'updated_at'
        },
        {
            data: null,
            render: function ( data, type, row ) {
                var html = '<a href="'+route+'/edit/'+row.id+'" class="btn btn-info">Edit</a>';
                html += '<a data-post-id="'+row.id+'" class="deletebtn btn btn-danger">Delete</a>';
                return html;
            }
        }
    ]
});

@endsection

@section("afterScript")

$(function(){
    $(document).on('click', '.deletebtn', function(){
        var id = $(this).attr("data-post-id");
        if (confirm("Are you sure to delete?") ){
            window.location = "{{route('commentMain')}}/delete/" + id;
        }
    });
});

@endsection
