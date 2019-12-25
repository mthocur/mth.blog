<form action="{{route('writeComment',$post->id)}}" method="post" id="commentForm">
    @csrf
    @include('inc.flash_messages')
    <div class="replyToAlert alert alert-info alert-block" style="display:none;">
        <button type="button" class="close" id="replyCancelButton" data-dismiss="alert">×</button>
        <strong>Reply to: <span class="replyToUser"></span></strong>
    </div>
    <div class="form-group">
        <textarea name="body" class="form-control" id="commentBody" rows="3" placeholder="Write a comment.."></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
