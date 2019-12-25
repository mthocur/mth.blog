@extends('layouts.admin')

@section('module')
Posts
@endsection

@section('page')
Edit
@endsection

@section('content')
<h3>
    Edit Comment
</h3>
<form method="post" action="{{route('commentUpdate',$comment->id)}}">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label>Owner: {{ $comment->user->name }}</label>
            </div>
            <div class="form-group">
                <label for="commentBody">Body</label>
                <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="commentBody">{{$comment->body}}</textarea>
                @error('body')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{$comment->status==1 ? "checked":""}}>
                <label class="form-check-label" for="statusActive">
                    Active
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="statusDeactive" value="0" {{$comment->status==0 ? "checked":""}}>
                <label class="form-check-label" for="statusDeactive">
                    Deactive
                </label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

</form>
@endsection

