<style>

    .recent_heading h4 {
        color: #0465ac;
        font-size: 16px;
        margin: auto;
        line-height: 29px;
    }

    .chat_ib h5 {
        font-size: 15px;
        color: #464646;
        margin: 0 0 8px 0;
    }

    .chat_ib h5 span {
        font-size: 13px;
        float: right;
    }

    .chat_ib p {
        font-size: 12px;
        color: #989898;
        margin: auto;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat_img {
        float: left;
        width: 11%;
    }

    .chat_img img {
        width: 100%
    }

    .incoming_msg_img {
        display: inline-block;
        width: 6%;
    }

    .incoming_msg_img img {
        width: 100%;
    }

    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }

    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 0 15px 15px 15px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }

    .received_withd_msg {
        width: 57%;
    }

    .mesgs{
        float: left;
        /*padding: 30px 15px 0 25px;*/
        width:100%;
    }

    .sent_msg p {
        background:#0465ac;
        border-radius: 12px 15px 15px 0;
        font-size: 14px;
        margin: 0;
        color: #fff;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .outgoing_msg {
        overflow: hidden;
        margin: 26px 0 26px;
    }

    .sent_msg {
        float: right;
        width: 46%;
    }

    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
        outline:none;
    }

    .type_msg {
        border-top: 1px solid #c4c4c4;
        position: relative;
    }

    .msg_send_btn {
        background: #01c0c8 none repeat scroll 0 0;
        border: none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        font-size: 15px;
        height: 33px;
        position: absolute;
        right: 36px;
        top: 9px;
        width: 33px;
    }

    .new_pic{
        position: absolute;
        right: 1px;
        top: 54px;
    }

    .messaging {
        padding: 0 0 50px 0;
    }
    .image {
        width: 100%;
        max-width: 210px;
    }

    .msg_history {
        height: 350px;
        overflow-y: auto;
    }

    .freq-title h4{
        font-weight: 600;
    }
    .frequent_question{
        background: #01c0c8 ;
        color: white;
        width: max-content;
        margin-bottom: 5px;
        display: block;
    }
</style>
<div class="mesgs">
    <div class="msg_history">
        @foreach($msgs as $msg)
            @if($apply == 'Customer')
                @if($msg->name == 'Customer')
                    <div class="outgoing_msg">
                        <i style="float: right" data-id="{{$msg->id}}" data-route="{{route('chat.delete')}}" class="mdi mdi-close-circle delete-msg"></i>
                        <div class="sent_msg">
                            @if($msg->type == 'text')
                            <p>{{$msg->content}}</p>
                            @else
                                <p>
                                    <a target="_blank" href="{{asset($msg->content)}}">
                                        <img class="image" src="{{asset($msg->content)}}" alt="">
                                    </a>
                                </p>
                            @endif
                            <span class="time_date"> {{date_create($msg->created_at)->format('H:i a')}}   |   {{date_create($msg->created_at)->format('M d,Y')}}</span></div>
                    </div>

                @else
                    <div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                        <div class="received_msg">
                            <div class="received_withd_msg">
                                @if($msg->type == 'text')
                                    <p>{{$msg->content}}</p>
                                @else
                                    <p>
                                        <a target="_blank" href="{{asset($msg->content)}}">
                                            <img class="image" src="{{asset($msg->content)}}" alt="">
                                        </a>
                                    </p>
                                @endif
                                <span class="time_date"> {{date_create($msg->created_at)->format('H:i a')}}   |   {{date_create($msg->created_at)->format('M d,Y')}}</span></div>
                        </div>
                    </div>
                @endif
            @else
                @if($msg->name == 'Customer')
                    <div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                        <div class="received_msg">
                            <div class="received_withd_msg">
                                @if($msg->type == 'text')
                                    <p>{{$msg->content}}</p>
                                @else
                                    <p>
                                        <a target="_blank" href="{{asset($msg->content)}}">
                                            <img class="image" src="{{asset($msg->content)}}" alt="">
                                        </a>
                                    </p>
                                @endif
                                <span class="time_date"> {{date_create($msg->created_at)->format('H:i a')}}   |   {{date_create($msg->created_at)->format('M d,Y')}}</span></div>
                        </div>
                    </div>
                @else
                    <div class="outgoing_msg">
                        <i style="float: right" data-id="{{$msg->id}}" data-route="{{route('chat.delete')}}" class="mdi mdi-close-circle delete-msg"></i>
                        <div class="sent_msg">
                            @if($msg->type == 'text')
                                <p>{{$msg->content}}</p>
                            @else
                                <p>
                                    <a target="_blank" href="{{asset($msg->content)}}">
                                        <img class="image" src="{{asset($msg->content)}}" alt="">
                                    </a>
                                </p>
                            @endif
                            <span class="time_date"> {{date_create($msg->created_at)->format('H:i a')}}   |   {{date_create($msg->created_at)->format('M d,Y')}}</span></div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
    <div class="type_msg">
        <div class="input_msg_write">
            <textarea class="write_msg form-control"  placeholder="Type a message" style="width: 78%; @if($apply == 'Designer') height:200px; @else height:94px; @endif"></textarea>
            <form class="image-form" action="{{route('chat.save')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input name="content" style="display: none" type="file" class="image_send">
                <input type="hidden" name="name" value="{{$apply}}">
                <input type="hidden" name="type" value="image">
                <input type="hidden" name="order_id" value="{{$order}}">
            </form>

            <button class="msg_send_btn send_btn" data-route="{{route('chat.save')}}" data-name="{{$apply}}" data-type="text" data-order="{{$order}}" ><i class="fa fa-paper-plane"></i></button>
            <button class="btn btn-danger new_pic send_btn_image" data-route="{{route('chat.save')}}" data-name="{{$apply}}" data-type="image" data-order="{{$order}}">New Photo</button>
        </div>
        @if($apply == 'Customer')
        <div class="freq">
            <div class="freq-title"><h4>some common questions:</h4></div>
            <div class="frequent_question btn btn-sm" data-answer="You can upload some other photos for us to take a look at!
                        Just click the 'New Photo' button and upload your photos.
                        We'll email you once your new design is ready for your review😉
                    ">How can I change my photo?</div>
{{--            <div class="frequent_question btn btn-sm" data-answer="That is the fun part😉 Just click the 'Choose Colors/Choos Background' button. Once you've found your favorite, select it and click to confirm. Then, all you need to do is approve your artwork and that's it!">--}}
{{--                How can I choose the colors/background?--}}
{{--            </div>--}}
            <div class="frequent_question btn btn-sm" data-answer="You can definitely change the style of your design! Just let us know the one you want. We'll email you once your new design is ready for your review😉">How can I change the style?</div>
        </div>
            @endif
    </div>
</div>
