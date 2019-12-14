@extends('layouts.admin')

@section('module')
Categories
@endsection

@section('page')
List
@endsection

@section('content')
<div class="container-fluid">
    <h3>
        Categories <a href="{{route('categoryCreate')}}" class="text-left">
            <svg xmlns="http://www.w3.org/2000/svg" height="30pt" viewBox="0 0 512 512" width="30pt">
                <path d="m256 0c-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256-114.835938-256-256-256zm0 0" fill="#2196f3" />
                <path d="m368 277.332031h-90.667969v90.667969c0 11.777344-9.554687 21.332031-21.332031 21.332031s-21.332031-9.554687-21.332031-21.332031v-90.667969h-90.667969c-11.777344 0-21.332031-9.554687-21.332031-21.332031s9.554687-21.332031 21.332031-21.332031h90.667969v-90.667969c0-11.777344 9.554687-21.332031 21.332031-21.332031s21.332031 9.554687 21.332031 21.332031v90.667969h90.667969c11.777344 0 21.332031 9.554687 21.332031 21.332031s-9.554687 21.332031-21.332031 21.332031zm0 0" fill="#fafafa" /></svg>
        </a>
    </h3>
    <div class="col-md-12">

        <table class="table table-bordered" id="laravel_datatable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Parent Category</th>
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
var route = "{{route("categoryMain")}}";
$('#laravel_datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('categoryAjax') }}",
    columns: [
        {
            data: 'id',
            name: 'id'
        },
        {
            data: 'name',
            name: 'name'
        },
        {
            data: 'parent',
            name: 'parent'
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
        //alert('It works');
        var id = $(".deletebtn").attr("data-post-id");
        if (confirm("Are you sure to delete?") ){
            window.location = "{{route('categoryMain')}}/delete/" + id;
        }
    });
});

@endsection