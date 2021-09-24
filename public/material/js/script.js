
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



$(document).ready(function () {

    $('.import-btn').click(function () {
        $(this).text('Loading');

    });

    $('.edit-tab-button').click(function () {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');

        var modal = $('.edit_tab_modal');
        modal.find('.tab-id').val(id);
        modal.find('.tab-title').val(title);
        console.log(description, modal.find('.tab-description'));
        modal.find('.tab-description').val(description);
        modal.modal("show");
    });

    // Category Filter
    $('.parent-category').change(function(){
        var id = $(this).val();
        $('.sub-categories-section').html('Please Wait..');

        $.ajax({
            url: `/category/${id}/get/sub-categories`,
            type: 'GET',
            success: function(res) {
                $('.sub-categories-section').empty().html(res);
            }
        });
    });


    // Product Tiered Price Feature
    $(document).on('click', '.add-price-row-btn', function() {
        var id = $(this).attr('id');
        $(this).parent().parent().parent().append(`
              <div class="row mb-3">
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="min_qty${id}[]">
                </div>
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="max_qty${id}[]">
                </div>
                <div class="col-md-3">
                    <select name="type${id}[]" id="" class="form-control">
                        <option value="fixed">Fixed</option>
                        <option value="discount">Discount</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input  type="number" step="any" class="form-control" name="tiered_price${id}[]"  placeholder="$0.0">
                </div>
                <div class="col-md-2 btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-sm btn-primary add-price-row-btn">+</button>
                    <button type="button" class="btn btn-sm btn-danger remove-price-row-btn">-</button>
                </div>
            </div>
        `);

    });

    $(document).on('click', '.add-single-product-price-row-btn', function() {
        var id = $(this).attr('id');
        $(this).parent().parent().parent().append(`
              <div class="row mb-3">
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="min_qty[]">
                </div>
                <div class="col-md-2">
                    <input  type="number" class="form-control" name="max_qty[]">
                </div>
                <div class="col-md-3">
                    <select name="type[]" id="" class="form-control">
                        <option value="fixed">Fixed</option>
                        <option value="discount">Discount</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input  type="number" step="any" class="form-control" name="tiered_price[]"  placeholder="$0.0">
                </div>
                <div class="col-md-2 btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-sm btn-primary add-single-product-price-row-btn">+</button>
                    <button type="button" class="btn btn-sm btn-danger remove-price-row-btn">-</button>
                </div>
            </div>
        `);

    });

    $(document).on('click', '.remove-price-row-btn', function() {
        $(this).parent().parent().empty();
    });

    $(document).on('click', '.remove-price-row-from-db-btn', function() {
        var id = $(this).data('item');
        var row = $(this).parent().parent();

        $.ajax({
            url: `/products/${id}/remove/tiered/price`,
            type: 'GET',
            success: function(res) {
                var response = res.data;
                if(response == 'success') {
                    row.empty();
                }
            }
        });
    });

    // Product Tiered Price Feature End

    // Bulk Tiered Pricing Feature Start

    $(document).on('input', '#bulk-min-qty', function() {
       var bulk_min_qty = $(this).val();
        $('.min-qty-row').each(function(){
            $(this).val(bulk_min_qty);
        });
    });

    $(document).on('input', '#bulk-max-qty', function() {
        var bulk_max_qty = $(this).val();
        $('.max-qty-row').each(function(){
            $(this).val(bulk_max_qty);
        });
    });

    $(document).on('change', '#bulk-type', function() {
        var selected_option = $(this).children("option:selected").val();
        $('.type-row').each(function(){
            $(this).find(`option[value=${selected_option}]`).attr('selected',true);
        });
    });

    $(document).on('input', '#bulk-price', function() {
        var bulk_price = $(this).val();
        $('.price-row').each(function(){
            $(this).val(bulk_price);
        });
    });

    // Bulk Tiered Pricing Feature End

    // Bulk Variant Pricing & Cost Feature Start

    $(document).on('input', '#bulk-var-price', function() {
        var bulk_price = $(this).val();
        $('.var-price-row').each(function(){
            $(this).val(bulk_price);
        });
    });

    $(document).on('input', '#bulk-var-cost', function() {
        var bulk_cost = $(this).val();
        $('.var-cost-row').each(function(){
            $(this).val(bulk_cost);
        });
    });

    // Bulk Variant Pricing & Cost Feature End










    var radioState;

    $('#example-radio-best-seller').on('click', function(e) {
        if (radioState === this) {
            this.checked = false;
            radioState = null;
        } else {
            radioState = this;
        }
    });

    $('#example-radio-winning-product').on('click', function(e) {
        if (radioState === this) {
            this.checked = false;
            radioState = null;
        } else {
            radioState = this;
        }
    });

    /*Admin Module - Rate Type Selection*/
    $('body').on('change','.rate_type_select',function () {
        if ($(this).val() !== 'flat') {
            $('.condition-div').show();
            // $('.max-condtion').attr('required','true');
            $('.min-condtion').attr('required','true');
        } else {
            // $('.max-condtion').attr('required','false');
            $('.min-condtion').attr('required','false');
            $('.condition-div').hide();
        }
    });
    /* Admin Module - Category Open JS */
    $('body').on('click','.category_down',function () {
        if($(this).data('value') === 0){
            $(this).find('i').addClass('fa-angle-down');
            $(this).find('i').removeClass('fa-angle-right');
            $(this).data('value',1);

        }
        else{
            $(this).find('i').removeClass('fa-angle-down');
            $(this).find('i').addClass('fa-angle-right');
            $(this).data('value',0);
        }
        $(this).next().next().toggle();
    });
    /* Admin Module - Category Checkbox Selection JS */
    $('body').on('change','.category_checkbox',function () {
        if($(this).is(':checked')){
            $(this).parent().next().find('input[type=checkbox]').prop('checked',true);
            $(this).parent().next().show();
        }
        else{
            $(this).parent().next().find('input[type=checkbox]').prop('checked',false);
            $(this).parent().next().hide();
        }
    });
    /* Admin Module - SubCategory Checkbox Selection JS */
    $('body').on('change','.sub_cat_checkbox',function () {
        if($(this).is(':checked')){
            $(this).parents('.product_sub_cat').prev().find('.category_checkbox').prop('checked',true);
        }
        else{
            var checked = $(this).parents('.product_sub_cat').find('input[type=checkbox]:checked').length;
            if(checked === 0){
                $(this).parents('.product_sub_cat').prev().find('.category_checkbox').prop('checked',false);
            }
        }
    });
    /* Admin Module - Dropzone Click JS */
    // $('body').on('click','.dropzone',function () {
    //     $('.images-upload').trigger('click');
    // });

    var storedFiles = [];
    /* Admin Module - Images UPLOAD JS */
    $('body').on('change','.images-upload',function (e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function (f) {

            if (!f.type.match("image.*")) {
                return;
            }
            storedFiles.push(f);
            console.log(storedFiles);
            //$('.preview-drop').empty();
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.preview-drop').append(' <div class="col-lg-4 preview-image animated fadeIn">\n' +
                    '            <div class="img-fluid options-item">\n' +
                    '                <img class="img-fluid options-item" src="'+e.target.result+'" alt="">\n' +
                    '            </div>\n' +
                    '        </div>');

            }
            reader.readAsDataURL(f);
        });
    });

    /* Admin Module - Add Option of Variants JS */
    $('body').on('click','.add-option-div',function () {
        $(this).parent().hide();
        $(this).parent().next().show();
    });
    /* Admin Module - Delete Option of Variants JS */
    $('body').on('click','.delete-option-value',function () {
        $(this).parents('.div2').hide();
        $(this).parents('.div2').prev().show();
    });
    /*Admin Module - Remove Option of Variants JS*/
    $('body').on('click','.remove-option',function () {
        $(this).parents('.badge').hide();
        $('.variant-options-update-save').data('deleted','1');
        var new_val =  $(this).parents('.badge').find('span').text();
        var value = "";
        if($(this).data('option') == 'option1'){
            $('#variant-options-update').append('<input type="hidden" class="delete_option1" name="delete_option1[]" value="'+new_val+'">');
            $('.delete_option1').each(function () {
                if(value === ""){
                    value = $(this).val();
                }
                else{
                    value = value+', '+$(this).val();
                }
            });
            $('.variant-options-update-save').data('option1',value);
        }
        else if($(this).data('option') == 'option2'){
            $('#variant-options-update').append('<input type="hidden" class="delete_option2" name="delete_option2[]" value="'+new_val+'">');
            $('.delete_option2').each(function () {
                if(value === ""){
                    value = $(this).val();
                }
                else{
                    value = value+', '+$(this).val();
                }

            });
            $('.variant-options-update-save').data('option2',value);
        }
        else{
            $('#variant-options-update').append('<input type="hidden" class="delete_option3" name="delete_option3[]" value="'+new_val+'">');
            $('.delete_option3').each(function () {
                if(value === ""){
                    value = $(this).val();
                }
                else{
                    value = value+', '+$(this).val();
                }
            });
            $('.variant-options-update-save').data('option3',value);
        }
    });

    /*Admin Module - Save Options of Variants JS*/
    // $('body').on('click', '.variant-selected-options-update-save', function() {
    //
    //     var option1 = $(".js-tags-options-update").val();
    //     $('.old-option-1').val(option1);
    //   //  var option1Array = option1.split(',');
    //     $('.old-option1-update-form').submit();
    //
    //
    // });

    $('body').on('click', '.update-option-1-btn', function() {

        $('.old-option1-update-form').submit();


    });


    $('body').on('click','.variant-options-update-save',function () {
        if($(this).data('deleted') === '1'){
            $(this).next().trigger('click');
            var option1 = "";
            var option2 = "";
            var option3 = "";
            if($(this).data('option1') !== ""){
                option1 =  '<li style="width: max-content">Option1 : '+$(this).data("option1")+'</li>';
            }

            if($(this).data('option2') !== ""){
                option2 =  '<li style="width: max-content">Option2 : '+$(this).data("option2")+'</li>';

            }
            if($(this).data('option3') !== ""){
                option3 =  '<li style="width: max-content">Option3 : '+$(this).data("option3")+'</li>';

            }
            Swal.fire({
                title: ' Are you sure?',
                html:'<p>Deleting these options will cause permanent deletion of their related variant!</p>'+
                    '<ul style="margin: 0px 44px;">' +
                    option1+option2+option3+
                    '</ul>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Deleted!',
                        'Your options and its variants has been deleted.',
                        'success'
                    );
                    $('#variant-options-update').submit();
                }
                else{
                    location.reload();
                }
            });
        }
        else if($('.option-value').val() !== ""){
            $('.new-option-add').submit();
        }
        else{
            Swal.fire(
                'Alert!',
                'Please First Add or Delete SomeOption',
                'warning'
            )
        }

    });

    /*Admin Module - Product Images Save JS*/
    $('body').on('click', '.save-img', function() {
        console.log(324);
        $('.product-images-form').submit();
    });


    $('body').on('submit','.product-images-form',function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url : $(this).attr('action'),
            type : $(this).attr('method'),
            data : formData,
            cache:false,
            contentType: false,
            processData: false,
        });
    });
    /*Admin Module - Update Product  Save JS*/
    $('.submit_all').click(function () {
        $('.pre-loader').css('display','flex');
        if($('#forms-div').find('form').length > 0){
            let forms = [];
            $('#forms-div').find('form').each(function () {
                if($(this).hasClass('product-images-form')){
                    $(this).submit();
                }
                else{
                    forms.push({
                        'data' : $(this).serialize(),
                        'url' : $(this).attr('action'),
                        'method' : $(this).attr('method'),
                    });
                }

            });
            if($('.variants-div').find('form').length > 0) {
                $('.variants-div').find('form').each(function () {
                    forms.push({
                        'data': $(this).serialize(),
                        'url': $(this).attr('action'),
                        'method': $(this).attr('method'),
                    });
                });
            }
            ajaxCall(forms);
        }
    });
    /*Stack ajax*/
    function ajaxCall(toAdd) {
        if (toAdd.length) {
            var request = toAdd.shift();
            var data = request.data;
            var url = request.url;
            var type = request.method;

            $.ajax({
                url: url,
                type:type,
                data: data,
                success: function(response) {
                    ajaxCall(toAdd);
                },
                error:function () {
                    ajaxCall(toAdd);
                }
            });

        } else {
            $.ajax({
                url: $('#notification').data('route'),
                type:'GET',
            });
            window.location.reload();
        }
    }
    /*Admin Module - Variant Image Change JS*/
    $('body').on('click','.img-avatar-variant',function () {
        var target = $(this).data('form');
        $(target).find('input[type=file]').trigger('click');
    });
    $('.varaint_file_input').change(function () {
        $(this).parents('form').submit();
    });
    /*Admin Module - Image Delete JS*/
    $('body').on('click','.delete-file',function () {
        var $this = $(this);
        var file = $(this).data("file");
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data: {
                _token: $(this).data('token'),
                type: $(this).data('type'),
                file: file,
            },
            success:function (data) {
                if(data.success === 'ok'){
                    $this.parents('.preview-image').remove();
                }
            }
        });
    });
   /*Admin Module - Product Status Change JS*/
    $('body').on('change','.status-switch',function () {
        var status = '';
        if($(this).is(':checked')){
            status = 1;
            $('.status-text').text('Published')
        }
        else{
            status = 0;
            $('.status-text').text('Draft')
        }
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data:{
                _token: $(this).data('csrf'),
                type : 'status_update',
                status : status
            }
        })
    });

    /*Admin Module - Email Template Status Change JS*/
    $('body').on('change','.template-status-switch',function () {
        var status = '';
        if($(this).is(':checked')){
            status = 1;
            $(`.status-text_${$(this).data('template')}`).text('Published')
        }
        else{
            status = 0;
            $(`.status-text_${$(this).data('template')}`).text('Draft')
        }
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data:{
                _token: $(this).data('csrf'),
                template : $(this).data('template'),
                status : status
            }
        })
    });

    /*Input Tag Script JS*/
    $('.js-tags-input').tagsInput({
        height: '36px',
        width: '100%',
        defaultText: 'Add tag',
        removeWithBackspace: true,
        delimiter: [',']
    });
    $('.js-tags-options').tagsInput({
        height: '36px',
        width: '100%',
        defaultText: 'Add tag',
        removeWithBackspace: true,
        onRemoveTag:function(){
            var option1 = $('input[type="text"][name="option1"]').val();
            var option2 = $('input[type="text"][name="option2"]').val();
            if(option1 === ''){
                $('input[type="text"][name="option2"]').val('');
                $('input[type="text"][name="option3"]').val('');
                $('.option_2').hide();
                $('.option_3').hide();
                $('.option_btn_2').hide();
                $('.option_btn_1').show();
                $('.variants_table').hide();
                $("tbody").empty();

            }
            if(option2 === ''){
                $('input[type="text"][name="option3"]').val('');
                $('.option_3').hide();
                $('.option_btn_2').show();


            }
        },
        onChange: function(){
            var price = $('input[type="number"][name="price"]').val();
            var cost = $('input[type="number"][name="cost"]').val();
            var sku = $('input[type="text"][name="sku"]').val();
            var quantity = $('input[type="number"][name="quantity"]').val();
            var option1 = $('input[type="text"][name="option1"]').val();
            console.log(option1);
            var option2 = $('input[type="text"][name="option2"]').val();
            var option3 = $('input[type="text"][name="option3"]').val();
            var substr1 = option1.split(',');
            var substr2 = option2.split(',');
            var substr3 = option3.split(',');
            $('.variants_table').show();
            $("tbody").empty();
            var title = '';
            jQuery.each(substr1, function (index1, item1) {
                title = item1;
                jQuery.each(substr2, function (index2, item2) {
                    if(item2 !== ''){
                        title = item1+'/'+item2;
                    }
                    jQuery.each(substr3, function (index3, item3) {

                        if(item3 !== ''){
                            title = item1+'/'+item2+'/'+item3;
                        }

                        $('tbody').append('   <tr>\n' +
                            '                                                    <td class="variant_title">' + title + '<input type="hidden" name="variant_title[]" value="' + title + '"></td>\n' +
                            '                                                    <td><input type="number" step="any" class="form-control" name="variant_price[]" placeholder="$0.00" value="' + price + '">\n' +
                            '                                                    </td>\n' +
                            '                                                    <td><input type="number" step="any" class="form-control" name="variant_cost[]" value="' + cost + '" placeholder="$0.00"></td>\n' +
                            '                                                    <td><input type="number" step="any" class="form-control" name="variant_quantity[]" value="'+quantity+'" placeholder="0"></td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_sku[]" value=""></td>\n' +
                            '                                                    <td><input type="text" class="form-control" name="variant_barcode[]" placeholder=""></td>\n' +
                            '                                                </tr>');
                    });
                });
            });
        },
        delimiter: [',']
    });

    $('.js-tags-options1-update').tagsInput({
        height: '36px',
        width: '100%',
        defaultText: 'Add more tags to option',
        removeWithBackspace: true,
        onRemoveTag:function(){
            var option1 = $('input[type="text"][name="option1-update"]').val();
            if(option1 === ''){
                $('.variants_table').hide();
                $(".option-1-table-body").empty();
            }
        },
        onChange: function(){
            var price = $('input[type="number"][name="price"]').val();
            var cost = $('input[type="number"][name="cost"]').val();
            var sku = $('input[type="text"][name="sku"]').val();
            var quantity = $('input[type="number"][name="quantity"]').val();
            var option1 = $('input[type="text"][name="option1-update"]').val();
            console.log()
            var substr1 = option1.split(',');
            console.log(substr1);
            $('.variants_table').show();
            $(".option-1-table-body").empty();
            var title = '';
            jQuery.each(substr1, function (index1, item1) {
                title = item1;
                $('.option-1-table-body').append('   <tr>\n' +
                    '                                                    <td class="variant_title">' + title + '<input type="hidden" name="variant_title[]" value="' + title + '"></td>\n' +
                    '                                                    <td><input type="number" step="any" class="form-control" name="variant_price[]" placeholder="$0.00" value="' + price + '">\n' +
                    '                                                    </td>\n' +
                    '                                                    <td><input type="number" step="any" class="form-control" name="variant_cost[]" value="' + cost + '" placeholder="$0.00"></td>\n' +
                    '                                                    <td><input type="number" step="any" class="form-control" name="variant_quantity[]" value="'+quantity+'" placeholder="0"></td>\n' +
                    '                                                    <td><input type="text" class="form-control" name="variant_sku[]" value=""></td>\n' +
                    '                                                    <td><input type="text" class="form-control" name="variant_barcode[]" placeholder=""></td>\n' +
                    '                                                </tr>');
            });
        },
        delimiter: [',']
    });


    $('input[type="checkbox"][name="variants"]').click(function () {
        if ($(this).prop("checked") == true) {
            $('.variant_options').show();
        } else if ($(this).prop("checked") == false) {
            $('.variant_options').hide();
        }
    });
    $('.option_btn_1').click(function () {
        if($(this).prev().find('.options-preview').val() !== ''){
            $('.option_2').show();
            $('.option_btn_1').hide();
        }
        else{
            alertify.error('The Option1 must have atleast one option value');
        }

    });
    $('.option_btn_2').click(function () {
        if($(this).prev().find('.options-preview').val() !== ''){
            $('.option_3').show();
            $('.option_btn_2').hide();
        }
        else{
            alertify.error('The Option2 must have atleast one option value');
        }
    });
    /*Fulfillment Control*/
    $('.fulfill_quantity').change(function () {
        if($(this).val() > $(this).attr('max')){
            $(this).val($(this).attr('max'));
            alertify.error('Please provide correct quantity of item!');
        }
        var total_fulfillable = 0;
        $('body').find('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        $('.fulfillable_quantity_drop').empty();
        $('.fulfillable_quantity_drop').append(total_fulfillable+' of '+$('.fulfillable_quantity_drop').data('total'));
        if(total_fulfillable === 0) {
            $('.atleast-one-item').show();
            $('.fulfill_items_btn').attr('disabled',true);
            $('.bulk_fulfill_items_btn').attr('disabled',true);

        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
            $('.bulk_fulfill_items_btn').attr('disabled',false);

        }

    });

    $('.fulfill_items_btn').click(function () {
        var total_fulfillable = 0;
        $('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        if(total_fulfillable > 0) {
           $('#fulfilment_process_form').submit();
        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
        }
    });

    /*Bulk Fulfillment*/
    $('.bulk_fulfill_items_btn').click(function () {
        var total_fulfillable = 0;
        $('.fulfill_quantity').each(function () {
            total_fulfillable = total_fulfillable + parseInt($(this).val()) ;
        });
        if(total_fulfillable > 0) {
            $('.pre-loader').css('display','flex');
            if($('.fulfilment_process_form').length > 0){
                let forms = [];
              $('.fulfilment_process_form').each(function () {
                    var total_fulfillable_form = 0;
                  $(this).find('.fulfill_quantity').each(function () {
                        total_fulfillable_form = total_fulfillable_form + parseInt($(this).val()) ;
                    });

                  if(total_fulfillable_form > 0){
                        forms.push({
                            'data' : $(this).serialize(),
                            'url' : $(this).attr('action'),
                            'method' : $(this).attr('method'),
                        });
                    }

                });
                console.log(forms);
                BulkAjaxCall(forms);
            }
        }
        else{
            $('.atleast-one-item').hide();
            $('.fulfill_items_btn').attr('disabled',false);
        }
    });
    function BulkAjaxCall(toAdd) {
        if (toAdd.length) {
            var request = toAdd.shift();
            var data = request.data;
            var url = request.url;
            var type = request.method;

            $.ajax({
                url: url,
                type:type,
                data: data,
                success: function(response) {
                    BulkAjaxCall(toAdd);
                },
                error:function () {
                    BulkAjaxCall(toAdd);
                }
            });

        } else {
            window.location.href = $('.bulk_fulfill_items_btn').attr('data-redirect');
        }
    }


    /*Select Photos From Existing*/
    $('.choose-variant-image').click(function () {
        var current = $(this);
        $.ajax({
            url: '/variant/'+$(this).data('variant')+'/change/image/'+$(this).data('image')+'?type='+$(this).data('type'),
            type: 'GET',
            success:function (response) {
                if(response.message === 'success'){
                    current.removeClass('bg-info');
                    current.addClass('bg-success');
                    current.text('Updated');
                    alertify.success('Variant image has been updated!');
                    current.parents('.modal').prev()
                        .attr('src', current.prev().attr('src'));
                }
                else{
                    alertify.error('Something went wrong!');
                }
            }
        })

    });
    /* Image Re-arrange JS */
    $('#image-sortable').sortable({
        update: function(event, ui) {
            var orders = [];
            $(this).find('.preview-image').each(function () {
                orders.push($(this).data('id'));
            });
            console.log(orders);
            $.ajax({
                url: $('#image-sortable').data('route'),
                method:'get',
                data:{
                    positions: orders,
                    product: $('#image-sortable').data('product'),
                },
                success:function (response) {
                    if(response.message === 'success'){
                        alertify.success('Image Position Changed Successfully!');
                    }
                    else{
                        alertify.error('Internal Server Error!');

                    }
                },
                error:function(){
                    alertify.error('Internal Server Error!');
                }
            });
        }
    });
    // $( "#image-sortable" ).disableSelection();

    $('#category-sortable').sortable({
        update: function(event, ui) {
            var orders = [];
            $(this).find('.preview-category').each(function () {
                orders.push($(this).data('id'));
            });
            console.log(orders);
            $.ajax({
                url: $('#category-sortable').data('route'),
                method:'get',
                data:{
                    positions: orders,
                    category: $('#category-sortable').data('category'),
                },
                success:function (response) {
                    if(response.message === 'success'){
                        alertify.success('Image Position Changed Successfully!');
                    }
                    else{
                        alertify.error('Internal Server Error!');

                    }
                },
                error:function(){
                    alertify.error('Internal Server Error!');
                }
            });
        }
    });

    /* Approve Bank Transfer JS */
    $('body').on('click','.approve-bank-transfer-button',function () {
        var button = $(this);
            Swal.fire({
                title: ' Are you sure?',
                html:'<p> A amount of '+ $(this).data('amount') +' will be added to wallet number '+ $(this).data('wallet')+' !</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.value) {
                    Swal.fire(
                        'Approved!',
                        'Amount added to wallet!',
                        'success'
                    );
                   window.location.href = button.data('route');
                }
            });
    });

    $('body').on('keyup','#search-create-input-stores-users',function () {
        $.ajax({
            url: $(this).data('route'),
            type: 'GET',
            data:{
              search : $(this).val(),
            },
            success:function (response) {
                if(response.message === 'success'){
                    $('.drop-content').empty();
                    $('.drop-content').append(response.html);
                }
            }
        })

    });


    $('body').on('keyup','#search-edit-input-stores-users',function () {
        $.ajax({
            url: $(this).data('route'),
            type: 'GET',
            data:{
                search : $(this).val(),
                id:$(this).data('manager'),
            },
            success:function (response) {
                if(response.message === 'success'){
                    $('.drop-content').empty();
                    $('.drop-content').append(response.html);
                }
            }
        })

    });

    $('body').on('change','.preference-check',function () {
        console.log(3);
        if($(this).val() === '0'){
            $(this).parents('.form-group').next().show();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',true);
        }
        else{
            $(this).parents('.form-group').next().hide();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',false);
        }
    });

    $('body').on('change','.preference-fixed',function () {
        console.log(34);
        if($(this).val() === '0'){
            $(this).parents('.form-group').next().show();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',true);
        }
        else{
            $(this).parents('.form-group').next().hide();
            $(this).parents('.form-group').next().find('.shop-preference').attr('required',false);
        }
    });


    if(!$('body').find('.rating-stars').hasClass('disabled')){
        /* 1. Visualizing things on Hover - See next part for action on click */
        $('body').on('mouseover','#stars li',function(){
            // $('#stars li').on('mouseover', function(){
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

        })
        $('body').on('mouseout','#stars li',function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });


        /* 2. Action to perform on click */
        $('body').on('click','#stars li',function(){
            // $('#stars li').on('click', function(){
            $('#rating-input').val($(this).data('value'));
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

    if($('body').find('input[name=rating]').length > 0){
        $('input[name=rating]').each(function () {
            var rating = $(this).val();
            $(this).closest('div').find('.star').each(function (index) {
                if(index < rating){
                    $(this).addClass('selected');
                }
            })
        });

    }



    if($('body').find('#canvas-graph-one').length > 0){
        console.log('ok');
        var config = {
            type: 'bar',
            data: {
                labels: JSON.parse($('#canvas-graph-one').attr('data-labels')),
                datasets: [{
                    label: 'Order Count',
                    backgroundColor: '#00e2ff',
                    borderColor: '#00e2ff',
                    data: JSON.parse($('#canvas-graph-one').attr('data-values')),
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary Orders Count'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

        var ctx = document.getElementById('canvas-graph-one').getContext('2d');
        window.myBar = new Chart(ctx, config);
    }

    if($('body').find('#canvas-graph-two').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-two').attr('data-labels')),
                datasets: [{
                    label: 'Orders Sales',
                    backgroundColor: '#5c80d1',
                    borderColor: '#5c80d1',
                    data: JSON.parse($('#canvas-graph-two').attr('data-values')),
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary Orders Sales'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Sales'
                        }
                    }]
                }
            }
        };

        var ctx_2 = document.getElementById('canvas-graph-two').getContext('2d');
        window.myLine = new Chart(ctx_2, config);
    }

    if($('body').find('#canvas-graph-three').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-three').attr('data-labels')),
                datasets: [{
                    label: 'Refunds',
                    backgroundColor: '#d18386',
                    borderColor: '#d14d48',
                    data: JSON.parse($('#canvas-graph-three').attr('data-values')),
                    fill: 'start',
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary Orders Refunds'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Refunds'
                        }
                    }]
                }
            }
        };

        var ctx_3 = document.getElementById('canvas-graph-three').getContext('2d');
        window.myLine = new Chart(ctx_3, config);
    }

    if($('body').find('#canvas-graph-four').length > 0){
        console.log('ok');
        var config = {
            type: 'line',
            data: {
                labels: JSON.parse($('#canvas-graph-four').attr('data-labels')),
                datasets: [{
                    label: 'Stores',
                    backgroundColor: '#61d154',
                    borderColor: '#61d154',
                    data: JSON.parse($('#canvas-graph-four').attr('data-values')),
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Summary New Stores'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Stores'
                        }
                    }]
                }
            }
        };

        var ctx_4 = document.getElementById('canvas-graph-four').getContext('2d');
        window.myLine = new Chart(ctx_4, config);
    }

    $('.check-order-all').change(function () {
        unset_bulk_array()
        set_bulk_array();

        if($(this).is(':checked')){
            $('.bulk-div').show();
        }
        else{
            $('.bulk-div').hide();

        }

    });
    $('.check-order').change(function () {
        if($(this).is(':checked')){
            $('.bulk-div').show();
            unset_bulk_array();
            set_bulk_array();
        }
        else{
            unset_bulk_array();
            set_bulk_array();
            if($('.check-order:checked').length === 0){
                $('.bulk-div').hide();
            }

        }

    });
    function set_bulk_array() {
        var values = [];
        $('.check-order:checked').each(function () {
            values.push($(this).val());
        });

        $('#bulk-fullfillment').find('input:hidden[name=orders]').val(values);

    }
    function unset_bulk_array() {
        $('#bulk-fullfillment').find('input:hidden[name=orders]').val('');

    }
    $('.bulk-fulfill-btn').click(function () {
       $('#bulk-fullfillment').submit();
    });

    $('body').on('change','#import-tracking',function () {
        Swal.fire({
            title: ' Are you sure?',
            html:'<p>You want to add tracking details in all pending orders</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Add it!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Processing!',
                    'All Pending Orders are under processed. Please wait on this page. Dont Refresh the page!',
                    'success'
                );
                setTimeout(function () {
                    Swal.close();
                    $('.pre-loader').css('display','flex');
                    $('#import-tracking').closest('form').submit();
                },2000);

            }
            else{
               $('#import-tracking').val('');
            }
        });
    });


    $('#custom_order_form').submit(function (e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url : $form.attr('action'),
            type : $form.attr('method'),
            data : $form.serialize(),
            success: function (data) {
                if(data.status == 'success'){
                    if(data.payment == 'paypal'){
                        $('#paypal_pay_trigger').html(data.popup);
                        $('.ajax_paypal_form_submit').html(data.form);
                        triggerPaypal(data.cost);
                        $('#paypal_pay_trigger').modal('show');
                    }else{
                        window.location.href = data.redirect_url;
                    }
                }else{
                    alert('something went wrong.');
                }
            },
            error: function () {
                alert('something went wrong');
            }
        });
    });
});


function triggerPaypal(price){
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: price
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                $('.ajax_paypal_form_submit').find('textarea').val(JSON.stringify(details));
                $('.ajax_paypal_form_submit form').submit();
            });
        }
    }).render('#paypal-button-container');
}


