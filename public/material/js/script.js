
$(document).ready(function(){

    

    $('.nav-tabs .nav-link').on('click',function(){

        $('.nav-tabs .nav-link').not(this).removeClass('active');
        $(this).addClass('active');
        $('.tab-content .tab-pane').removeClass('show');
        $('.tab-content .tab-pane').removeClass('active');
        var tab_id=$(this).attr('href');
        $(tab_id).addClass('show');
        $(tab_id).addClass('active');

    })



    if($('.select_ajax_reload').length > 0) {
        if ($('.select_ajax_reload').val().length > 0) {
            $('.drag-table tbody').addClass('Selected');
        } else {
            $('.drag-table tbody').removeClass('Selected');
        }
    }

    if($('.Selected').length > 0){
        $('.drag-table tbody').sortable({
            update: function(event, ui) {
                var backgrounds = [];
                $('.Selected tr').each(function () {
                    backgrounds.push($(this).data('id'));
                });
                console.log(backgrounds);
                $.ajax({
                    url: $('.Selected').data('route'),
                    method:'get',
                    data:{
                        backgrounds: backgrounds,
                        category: $('.select_ajax_reload').val(),
                    },
                    success:function () {
                        alertify.success('Background Position Changed Successfully!');
                    },
                    error:function(){
                        alertify.error('Internal Servor Errror!');
                    }
                });
            }
        });
    }


    /*Chat JS*/
    $('body').on('click','.btn-chat-open',function () {
       var current = $(this);
        $.ajax({
            url:$(this).data('route'),
            method: 'get',
            data:{
                // order_product_id:$(this).data('product'),
                apply: 'Designer',
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
                setSeen();
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
                    ' <div class="sent_msg">\n' +
                    '<p>\n' +
                    '<img class="image" src="'+e.target.result+'" >\n' +
                    '</p>\n' +
                    '<span class="time_date"> '+moment().format('kk:mm a')+' | '+moment().format('MMM  DD,YYYY')+' </span></div>\n' +
                    '</div>');
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
                setSeen();
            },
            error: function(data) {
                console.log('error');
            }
        });
    });

    function setSeen(){
        console.log('send');
        /*Set Notifications to seen*/
        var current = $('.btn-chat-open');
        $.ajax({
            url:'/seenNotifications',
            method: 'get',
            data:{
                order_id:current.data('order_id'),
                target: 'Customer',
            },
            success:function (response) {
                current.text('Chat');
                current.removeClass('btn-red text-white animated bounce slower');
                current.addClass('btn-blue text-white');
            }
        });
    }


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
                    target: 'Customer'
                },
                success:
                    function (data) {
                        if (data.count !== -1) {
                            var target = $('.btn-chat-open');
                            $.each(data.count, function( array_index, value ) {
                                target.each(function( index ) {
                                    if(array_index === index){
                                        if(value > 0){
                                            $(this).text('You Have New Messages');
                                            $(this).addClass('btn-red text-white animated  bounce slower');
                                            $(this).removeClass('btn-blue');
                                            // alertify.error('You Have New Message');
                                        }
                                        else {
                                            $(this).text('Customer Chat');
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

    $(".search_field").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    var switchStatus = 0;
    var text = '';
    $("body").on('change','#togBtn', function() {
        if ($(this).is(':checked')) {
            switchStatus = 1;
            text = 'Active';
            $(this).parents('.col-md-1').next().find('.status').text(text);
            $(this).parents('.col-md-1').next().find('.status').removeClass('text-danger');
            $(this).parents('.col-md-1').next().find('.status').addClass('text_active');
        }
        else {
            switchStatus = 0;
            text = 'Disabled';
            $(this).parents('.col-md-1').next().find('.status').text(text);
            $(this).parents('.col-md-1').next().find('.status').removeClass('text_active');
            $(this).parents('.col-md-1').next().find('.status').addClass('text-danger');
        }

        $.ajax({
            url: $(this).data('route'),
            method: $(this).data('method'),
            data:{
                designer: $(this).data('designer'),
                status: switchStatus,
            },
            success:function () {
                alertify.success('Designer '+text+' Changed!')
            }
        });
    });


    ///////////////////filter order status///////////////
    /*Set Filter for Arrows*/
    function set_filter($type,$value){
        $.ajax({
            url:$('#filter-route').data('url'),
            method: 'GET',
            data:{
                value: $value,
                type: $type
            }
        });
    }


    // $('.order_status').click(function () {
    $('body').on('click','.order_status',function () {
        set_filter('status',$(this).text());
        $("#myTable tr").each(function() {
            $(this).show();
        });
        var value=$(this).text();
        console.log(value);
        $('.order_status_button').text(value);
        if($(this).text() !== 'All Statuses'){
            $("#myTable tr .design-status h6").filter(function() {
                if($(this).data('text') !== value){
                    $(this).parents('tr').hide();
                }
                else{
                    $(this).parents('tr').show() ;
                }
            });
        }
        else{
            $("#myTable tr").each(function() {
                $(this).show();
            });
        }


    });
    /*Design Uplaod*/

    $('body').on('click','.upload-design-button',function () {
        $(this).parent().next().find('.design-file').trigger('click');
    });
    $('body').on('change','.design-file',function () {
        $(this).closest('form').submit();
    });
    $('body').on('click','.upload-print-button',function () {
        $(this).next().find('.design-file').trigger('click');
    });


    $('body').on('click','.modal_button',function () {

        var modal = $(this).data('target');
        if($(modal).length > 0){
            $(modal).modal({
                show: true,
                focus:true
            });
        }
    });

    /*Fill Rating Stars*/
    if($('body').find('.input_rating').length > 0) {
        $('.input_rating').each(function () {
            var rating = $(this).val();
            $(this).next().find('.star').each(function (index) {
                if (index < rating) {
                    $(this).addClass('selected');
                }
            })
        });
    }

    /*Style Change*/
    $('body').on('change','.style-change',function () {
        $(this).parent().next().find('.category_input').val($(this).val());
        $(this).parent().next().submit();
    });
    ///////////change order status//////////////////

    function order_status(){
        $('.order-status-value').each(function () {
            var value = $(this).val();
            if(value === "New Order"){
                $(this).next().css("background","#0066CC");
                $(this).next().find('.dropdown').find('.pr-5').text(value);

            }else if (value === "Not Completed") {
                $(this).next().css("background","#a53838");
                $(this).next().find('.dropdown').find('.pr-5').text(value);
            }else if(value === "Completed" ){
                $(this).next().css("background","#449d44");
                $(this).next().find('.dropdown').find('.pr-5').text(value);
                $(this).next().closest('tr').css("background","#c8bfdf");
                $(this).next().closest('tr').css("color","White")
            }
        });
    }

    if($('.order-status-value').length > 0){
        order_status();
    }

    // $(".change_status").click(function () {
    $('body').on('click','.change_status',function () {

        var current = $(this);
        var text=$(this).text();
        // text=text.split(" ");

        var drop_menu=$(this).parent();
        drop_menu=$(drop_menu).parent();
        var td =$(drop_menu).parent();

        drop_menu=$(drop_menu)[0].children
        var span =$(drop_menu)[0];
        if($(this).data('type') === 'order-inner'){
            if (text === "Not Completed") {
                var h6 = current.parents('.dropdown').find('h5');
                h6.text(text)
                h6.append('<i class=" m-l-5 fa fa-chevron-down"></i>');
                h6.css("background","#a53838");

            }else if(text === "Completed" ){
                var h6 = current.parents('.dropdown').find('h5');
                h6.text(text)
                h6.append('<i class=" m-l-5 fa fa-chevron-down"></i>');
                h6.css("background","#449d44");
            }
        }else{
            if(text === "New Order"){
                $(span).text(text)
                td.css("background","#0066CC");
                td.closest('tr').css("background","#FFFFFF");
                td.closest('tr').css("color","#67757c")

            }else if (text === "Not Completed") {
                $(span).text(text)
                td.css("background","#a53838");
                td.closest('tr').css("background","#FFFFFF");
                td.closest('tr').css("color","#67757c")

            }else if(text === "Completed" ){
                $(span).text(text)
                td.css("background","#449d44");
                td.closest('tr').css("background","#c8bfdf");
                td.closest('tr').css("color","White")
            }
        }


        $.ajax({
            url:$(this).data('route'),
            method:$(this).data('method'),
            data:{
                id:$(this).data('id'),
                status:$(this).data('status-id'),
            },
            success:function (response) {
                alertify.success('Status Changed Successfully!');
            },
            error:function () {
                alertify.success('Internal Server Error!');
            },
        })
    })


    ////////filter designer//////////////

    // $(".change_designer").click(function () {
    $('body').on('click','.change_designer',function () {
       set_filter('designer',$(this).text());
        var value=$(this).text();
        $('.designer-text').text(value);
        if(value !== 'All Designers'){
            $("#myTable tr td .designer").filter(function() {
                var td=$(this).parent();
                $($(td).parent()).toggle($(this).text().toLowerCase().indexOf(value.toLowerCase()) > -1)
            });
        }
        else{
            $("#myTable tr").each(function() {
                $(this).show();
            });

        }

    });


    ////////////filter of columns////////////


    // $('.icon_down').click(function () {
    $('body').on('click','.icon_down',function () {

        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("order_table");
        if($(this).hasClass('mdi-menu-down')){
            $(this).removeClass('mdi-menu-down');
            $(this).addClass('mdi-menu-up');
        }else if($(this).hasClass('mdi-menu-up')){
            $(this).removeClass('mdi-menu-up');
            $(this).addClass('mdi-menu-down');
        }

        var n=$(this).prop("id");
        switching = true;
        //Set the sorting direction to ascending:
        dir = "asc";
        /*Make a loop that will continue until
        no switching has been done:*/
        while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /*Loop through all table rows (except the
            first, which contains table headers):*/
            for (i = 1; i < (rows.length - 1); i++) {
                //start by saying there should be no switching:
                shouldSwitch = false;
                /*Get the two elements you want to compare,
                one from current row and one from the next:*/
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /*check if the two rows should switch place,
                based on the direction, asc or desc:*/
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch= true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /*If a switch has been marked, make the switch
                and mark that a switch has been done:*/
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                //Each time a switch is done, increase this count by 1:
                switchcount ++;
            } else {
                /*If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again.*/
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    })


    /////////////mark orders all //////////////////
    $('.actionbox').hide();

    // $('#check-all').click(function () {
    $('body').on('click','#check-all',function () {

        if (this.checked) {
            $(".checkSingle").each(function() {
                this.checked=true;
            });
            $('.actionbox').show();


        } else {

            $(".checkSingle").each(function() {
                this.checked=false;
                $('.actionbox').hide();
            });
        }
        selectedOrders();

    })

    ///////////////single order check///////////

    // $(".checkSingle").click(function () {
    $('body').on('click','.checkSingle',function () {
        console.log($('.checkSingle:checked').length)
        selectedOrders();

        if($('.checkSingle:checked').length > 0){
            $('.actionbox').show();
        }
        else{
            $('.actionbox').hide();
        }
        if ($(this).is(":checked")) {
            var isAllChecked = 0;

            $(".checkSingle").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
            });

            if (isAllChecked === 0) {
                $("#check-all").prop("checked", true);
            }
        }
        else {
            $("#check-all").prop("checked", false);
        }
    });

    $('body').on('click','.set-to-complete-orders',function () {
        $(this).next().submit();
    });

    function selectedOrders(){
        var orders_array =[];
        $(".checkSingle").each(function() {
            if (this.checked)
            {
                orders_array.push($(this).data('order_id'));
            }

        });
        // console.log(orders_array);
        $('#orders_array').val(orders_array);
        console.log($('#orders_array').val());;
    }

    /*Delete Category With Its Background*/
    $('body').on('click','.delete-category-btn',function () {
        var value =$(this).parents('.row').find('select[name=category]').val();
        if(value !== ""){
            window.location.href = $(this).data('route')+'?category='+value;
        }
        else{
            alertify.error('Select Category First!')
        }
    });

    /*Filter Orders*/
    $('body').on('keyup','.filter-search',function () {

        $.ajax({
            url: $(this).data('route'),
            method: 'GET',
            data:{
                search : $(this).val(),
            },
            success:function (response){
                $('#order_table').find('#myTable').empty();
                $('#order_table').find('#myTable').html($(response).find('#myTable').html());
                order_status();
            },
        });

    });
    $('body').on('click','.change_product',function () {
        $('.product-filter-button').text($(this).text());
        if($(this).text() === 'All Products'){
            var $product = null;
        }
        else{
            var $product = $(this).text();
        }

        $.ajax({
            url: $(this).data('route'),
            method: 'GET',
            data:{
                product : $product,
            },
            success:function (response){
                $('#order_table').find('#myTable').empty();
                $('#order_table').find('#myTable').html($(response).find('#myTable').html());
                order_status();
            },
        });
    });

    /*Filter Asc Desc Column*/
    // $('body').on('click','.th-table',function () {
    //
    // });

    $('body').on('click','.send-email',function () {
        console.log($(this).data('route'));
        $.ajax({
            url:$(this).data('route'),
            method:'GET',
            data:{
                order: $(this).data('id'),
            },
            success:function(response){
                if(response.status === 'error'){
                    alertify.error(response.message);
                }
                else{
                    alertify.success(response.message);
                }
            },
        })
    });

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

    $('.submit-gooten-order').click(function () {
        $this = $(this);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Processing!',
                    'Your order processing to submit on gooten',
                    'success'
                );
                window.location.href=$($this).data('href');
            }
        })
    });

});
