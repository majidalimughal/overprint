

$(document).ready(function() {
    // if($('#slick-count').length < 7){
    //     var slick_count = parseInt($('#slick-count').val()) ;
    //     console.log(slick_count);
    //     $(".custom-slider").slick({
    //         infinite: true,
    //         slidesToShow: slick_count,
    //         slidesToScroll: 1,
    //         adaptiveHeight :true,
    //         responsive: [{
    //             breakpoint: 1024,
    //             settings: {
    //                 slidesToShow: 5,
    //                 slidesToScroll: 1,
    //                 infinite: true
    //             }
    //         }, {
    //             breakpoint: 767,
    //             settings: {
    //                 slidesToShow: 3,
    //                 slidesToScroll: 3,
    //                 infinite: true
    //             }
    //         }
    //         ]
    //     });
    // }
    // else{
        $(".custom-slider").slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            adaptiveHeight :true,
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    infinite: true
                }
            }, {
                breakpoint: 767,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true
                }
            }
            ]
        });
    // }
    $('img').each(function (index) {
        var src = $(this).attr('src');
        // console.log(src)
        if (src.search("&uu=") !== -1) {
            var url = new URL(src);
            var id = url.searchParams.get("id");
            var c = url.searchParams.get("uu");
            // console.log(c);
            if(c !== null){
                var new_src = 'https://cdn.getuploadkit.com/'+c+'/';
                console.log(new_src)
                $(this).attr('src',new_src);
            }

        }
    });


    $('body').on('click','.get-sms-updates',function () {
        $.ajax({
            url: $(this).data('url'),
            method: 'get',
            data:{
                order: $(this).data('order'),
                setting: $(this).data('setting'),
            },
            success:function (response) {
              if(response.status === 'success'){
                  location.reload();
              }
              else{
                  location.reload();
              }
            },
        })
    });

    /*Chat JQuery*/

    $('body').on('click','.btn-chat-open',function () {
        var current = $(this);
        /*Set Notifications to seen*/
        $.ajax({
            url:'/seenNotifications',
            method: 'get',
            data:{
                order_id:$(this).data('order_id'),
                target: 'Designer',
            },
            success:function (response) {
                current.find('b').text('Chat With Your Designer');
                current.removeClass('btn-red text-white animated bounce slower');
                current.addClass('btn-blue text-white');
            }
        });
        $.ajax({
            url:$(this).data('route'),
            method: 'get',
            data:{
                // order_product_id:$(this).data('product'),
                apply: 'Customer',
                order : $(this).data('order_id'),
            },
            success:function (response) {
                var modal = $(current).data('target');
                // $(modal).find('.modal-title').text(current.prev().text());
                $(modal).find('.content-drop').empty();
                $(modal).find('.content-drop').append(response.html);
                $(modal).modal({
                    show: true,
                    focus:true
                });

            }
        });
    });
    $('body').on('click','.frequent_question',function () {
        var question = $(this).text();
        var answer = $(this).data('answer');

        $('.msg_history').append('<div class="outgoing_msg">\n' +
            '<div class="sent_msg">\n' +
            '<p>'+question+'</p>\n' +
            '<span class="time_date"> '+moment().format('kk:mm a')+' | '+moment().format('MMM  DD,YYYY')+' </span></div>\n' +
            '</div>');
        $('.msg_history').append('<div class="incoming_msg">\n' +
            '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>\n'+
            '<div class="received_msg">\n' +
            '<div class="received_withd_msg">\n'+
            '<p>'+answer+'</p>\n' +
            '<span class="time_date"> '+moment().format('kk:mm a')+' | '+moment().format('MMM  DD,YYYY')+' </span></div>\n' +
            '</div>\n'+
            '</div>');
        $(".msg_history").scrollTop(1000);
    });

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
                // order_product_id : $(this).data('product'),
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
                                            $(this).find('b').text('You Have New Messages');
                                            $(this).addClass('btn-red text-white animated  bounce slower');
                                            $(this).removeClass('btn-blue');
                                            // alertify.error('You Have New Message');
                                        }
                                        else {
                                            $(this).find('b').text('Chat With Your Designer');
                                            $(this).removeClass('btn-red text-white animated bounce  slower');
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
        var value =$(this).find('img').attr('src');
        var name =$(this).find('img').data('name');
        $('.background_title').text(name);
        value = 'url('+value+')';
        $('.image-contain').css('background-image',value);
        $('#background-category').val($(this).find('img').attr('data-id'));
        $('.background-div').each(function () {
            $(this).css('border',0);
        });
        $(this).css('border','4px solid black');

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

    });

    $('body').on('click','.set-secondary-approved',function(){
        var current = $(this);
        $.ajax({
            url:'/customer/order/secondary-save',
            method:'get',
            data:{
                product : current.data('id'),
                secondary : current.data('secondary'),
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

    });

});
