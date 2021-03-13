
$(document).ready(function() {

    $(".custom-slider").slick({
        infinite: true,
        centerMode: true,
        slidesToShow: 1,
        slidesToScroll: 3,
        // arrows: true
    });
/*Chat JQuery*/
    $('body').on('click','.send_btn',function(){
        var $msg = $('.write_msg').val();

        if($msg.length > 0){
            $('.write_msg').val('');
            $('.msg_history').append('<div class="outgoing_msg">\n' +
                '                        <div class="sent_msg">\n' +
                '                            <p>'+$msg+'</p>\n' +
                '                            <span class="time_date"> '+moment().format('kk:mm a')+' | '+moment().format('MMM  DD,YYYY')+' </span></div>\n' +
                '                    </div>');
            $(".msg_history").scrollTop(1000);
        }
        $.ajax({

            url: $(this).data('route'),
            method: 'get',
            data:{
                name : $(this).data('name'),
                type : $(this).data('type'),
                content : $msg,
                order_id : $(this).data('order'),
                order_product_id : $(this).data('product'),
            },
            success:function (response) {
                console.log('sent');
            }
        });
    });
    $('body').on('click','.send_btn_image',function () {
        $('.image_send').trigger('click');
    });
    $('body').on('change','.image_send',function () {
        var current = $(this);
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.msg_history').append('<div class="outgoing_msg">\n' +
                    '                        <div class="sent_msg">\n' +
                    '                                <p>\n' +
                    '                                    <img class="image" src="'+e.target.result+'" >\n' +
                    '                                </p>\n' +
                    '                            <span class="time_date"> '+moment().format('kk:mm a')+' | '+moment().format('MMM  DD,YYYY')+' </span></div>\n' +
                    '                    </div>');
                $(".msg_history").scrollTop(1000);
            };
            reader.readAsDataURL(input.files[0]);
            var form = current.parent();
            form.submit();
        }
    });
    $('body').on('click','.delete-msg',function () {
        var current = $(this);
        $.ajax({
            url : $(this).data('route'),
            method: 'get',
            data:{
                id : $(this).data('id'),
            },
            success:function (data) {
                if(data.status === 'deleted'){
                    current.parent().remove();
                }
            }
        }) ;
    });
    $('body').on('submit','.image-form',function (evt) {
        evt.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function(data) {
               console.log('send');
            },
            error: function(data) {
                console.log('error');
            }
        });
    });
    $('body').on('click','.btn-chat-open',function () {
        var current = $(this);
        /*Set Notifications to seen*/
        $.ajax({
            url:'/seenNotifications',
            method: 'get',
            data:{
                product:$(this).data('product'),
                target: 'Designer',
            },
            success:function (response) {
                current.text('Chat');
                current.removeClass('btn-danger text-white animated bounce slower');
                current.addClass('btn-blue text-white');
            }
        });
        $.ajax({
            url:$(this).data('route'),
            method: 'get',
            data:{
                order_product_id:$(this).data('product'),
                apply: 'Customer',
                order : $(this).data('order_id'),
            },
            success:function (response) {
                var modal = $(current).data('target');
                $(modal).find('.modal-title').text(current.prev().text());
                $(modal).find('.content-drop').empty();
                $(modal).find('.content-drop').append(response.html);
                $(modal).modal({
                    show: true,
                    focus:true
                });

            }
        });
    });

    setInterval(getNotifications, 5000);
    function getNotifications(){
        $button = $('#chat-notify');
        if($button.length > 0) {
            $.ajax({
                url: $button.data('notification'),
                method: 'get',
                data: {
                    order: $button.data('order_id'),
                    target: 'Designer'
                },
                success:
                    function (data) {
                        if (data.count !== -1) {
                            var target = $('.btn-chat-open');
                            $.each(data.count, function( array_index, value ) {
                                target.each(function( index ) {
                                    if(array_index === index){
                                        if(value > 0){
                                            $(this).text('New Messages');
                                            $(this).addClass('btn-danger text-white animated  bounce slower');
                                            $(this).removeClass('btn-blue');
                                            // alertify.error('You Have New Message');
                                        }
                                        else {
                                            $(this).text('Chat');
                                            $(this).removeClass('btn-danger text-white animated bounce  slower');
                                            $(this).addClass('btn-blue text-white');
                                        }
                                    }
                                });
                            });

                        }
                        else{
                            // alertify.error('Internal Server Erroorrr');
                        }
                    },
            });
        }
    }



    $('body').on('click','.btn-choose',function () {
        $('.new_photo_input').trigger('click');
    });
    $('body').on('change','.new_photo_input',function (e) {
        var fileName = e.target.files[0].name;
        $(this).next().val(fileName);
    });
    $('body').on('click','.new_photo_modal_button',function () {
        var modal = $(this).data('target');
        if($(modal).length > 0){
            $(modal).find('.order_product_id').val($(this).data('product'));
            $(modal).modal({
                show: true,
                focus:true
            });
        }
    });
    $('body').on('click','.new_photo_upload_button',function () {
        if($(".new_photo_input").val() !== ''){
            $('#new_photo_upload_form').submit();
        }
        else{
            alert('Upload A File FIrst !')
        }
    });


    $('body').on('click','.request_upload_button',function () {
        if($(this).parents('.modal').find(".request_fix").val().length > 0){
            $(this).parents('.modal').find('#fix_request_form').submit();
        }
    });

    $('body').on('click','.background-div',function () {
        $('#design_background').attr('src',$(this).find('img').attr('src'));
        $('#background-category').val($(this).find('img').attr('data-id'));


    });

    $('body').on('click','.background_save_button',function () {
        $('#background_save_form').submit();
    });

    if(!$('.rating-stars').hasClass('disabled')){
        /* 1. Visualizing things on Hover - See next part for action on click */
        $('#stars li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

// Now highlight all the stars that's not after the current hovered star
            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });

        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });


        /* 2. Action to perform on click */
        $('#stars li').on('click', function(){
            $('#rating_input').val($(this).data('value'));
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected

            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
        });
    }



    $('body').on('click','.review-submit',function () {
        if($('#review_form').find('input[name=review]').val() !== ''){
            $('#review_form').submit();
        }
        else{
            alert('write review !');
        }

    });

    $('body').on('click','.set-approved',function(){
        var current = $(this);
        // var form = $('#background_save_form');
        $.ajax({
            url:'/customer/order/save',
            method:'get',
            data:{
                product : current.data('id'),
            },
            success:function (response) {
                if(response.status !== 'error'){
                    var modal = current.data('target');
                    if($(modal).length > 0){
                        $(modal).modal({
                            show: true,
                            focus:true
                        });
                    }

                }
                else{
                    alert(response.status);
                }
            },

        });
        // $.ajax({
        //     url: form.attr('action'),
        //     method: form.attr('method'),
        //     data: form.serialize(),
        //     success:function (response) {
        //
        //     },
        // });


    });

});
