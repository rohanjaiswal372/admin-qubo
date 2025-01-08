<div class="box box-primary direct-chat direct-chat-primary">
    <div class="box-header">
        <div class="box-title"><i class="fa fa-comments text-info"></i> Comments:</div>
        <div class="box-tools pull-right">
            <span data-toggle="tooltip"
                  title=""
                  class="badge bg-yellow"
                  data-original-title="{{$item->totalCommentCount()}} Total Messages">{{$item->totalCommentCount()}}</span>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            @if(Auth::user()->hasPermission('admin'))
                <a class="btn btn-box-tool"
                   href="#"
                   id="purge"
                   title="Purge all comments"
                   data-toggle="tooltip"
                   onClick="return confirm('Are you sure you want to purge all comments?');">
                    <i class="fa fa-trash-o text-danger"></i></a>
            @endif
            <button type="button"
                    class="btn btn-box-tool"
                    data-toggle="tooltip"
                    title="Contacts"
                    data-widget="chat-pane-toggle">
                <i class="fa fa-comments"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        @if($item->comments)
            <div class="direct-chat-messages">
                @foreach($item->comments as $comment)
                    <div class="direct-chat-msg @if($loop->index & 1) right @endif">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left ">{{$comment->commented->fullName}}</span>
                            <span class="direct-chat-timestamp pull-right ">{{Carbon::parse($comment->updated_at)->diffForHumans()}}</span>
                        </div>
                        <span class="direct-chat-img"><i class="fa fa-user fa-2x fa-fw text-muted"></i></span>
                        {{--<img class="direct-chat-img" src="" alt="message user image"><!-- /.direct-chat-img -->--}}
                        <div id="comment-{{$comment->id}}" class="direct-chat-text"  data-clipboard-action="copy" data-clipboard-text="{{$comment->comment}}" data-comment-id="{{$comment->id}}">
                            {{$comment->comment}}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="box-footer">
        <div class="input-group input-group-sm">
            <input type="text" name="comment" id="comment" placeholder="Type Message ..." class="form-control"/>
            <span class="input-group-btn">
                            <button type="submit"
                                    id="comment-submit"
                                    class="btn btn-primary btn-flat btn-sm">Comment <i class="fa fa-comment"></i></button>
                          </span>
        </div>
    </div>
</div>






