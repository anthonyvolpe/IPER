(function($) {

    if(window.cart){
        renderCart(window.cart);
    }

    /*********************************************************************************************************/
    /* -------------------------------------- Sticky navigation ------------------------------------------ */
    /*********************************************************************************************************/
    /*$("#preview-order-payment").sticky({
        topSpacing: 0,
        className: 'sticky',
        responsiveWidth: true
    });*/



    /*********************************************************************************************************/
    /* -------------------------------------- FORM PAYMENT ------------------------------------------ */
    /*********************************************************************************************************/
    $('#form-payment').validator();
    $('.selectpicker').selectpicker();


    //billing same as shipping
    $('#billing_sameas_shipping').on('click', function(){
        if ($(this).is(':checked')) {
            var first_name  = $('#firstName_shipping').val(),
                last_name   = $('#lastName_shipping').val(),
                country     = $('#country_shipping').val(),
                addr1       = $('#address1_shipping').val(),
                addr2       = $('#address2_shipping').val(),
                city        = $('#city_shipping').val(),
                state       = $('#state_shipping').val(),
                zip         = $('#zip_shipping').val(),
                email       = $('#email_shipping').val(),
                tel         = $('#phone_shipping').val();


            $('#firstName_billing').val(first_name);
            $('#lastName_billing').val(last_name);
            $('#country_billing').val(country);
            $('#address1_billing').val(addr1);
            $('#address2_billing').val(addr2);
            $('#city_billing').val(city);
            $('#state_billing').val(state);
            $('#zip_billing').val(zip);
            $('#email_billing').val(email);
            $('#phone_billing').val(tel);


            jQuery('#state_billing').html("");
            jQuery('#state_shipping option').each(function(){
                var opt=jQuery(this).clone();
                jQuery('#state_billing').append(opt);
            });

            jQuery('#country_billing').selectpicker('refresh');
            jQuery('#state_billing').selectpicker('refresh');

            $('#country_billing').selectpicker('val',country);
            $('#state_billing').selectpicker('val',state);

        }else{
            /*
            $('#firstName_billing').val('');
            $('#lastName_billing').val('');
            $('#country_billing').val('');
            $('#address1_billing').val('');
            $('#address2_billing').val('');
            $('#city_billing').val('');
            $('#state_billing').val('');
            $('#zip_billing').val('');
            $('#email_billing').val('');
            $('#phone_billing').val('');

            $('.bootstrap-select button[data-id="country_billing"] .filter-option').text('Country*');
            $('.bootstrap-select button[data-id="state_billing"] .filter-option').text('State*');*/
        }
    });

    //change price
    $('input[name="shippingType"]').on('change', function(){
        $('.shipping-tax .shipping .price').text($(this).attr('data-price'));
    });

    $(document).on('submit','form#form-payment',function(){
        if ($("#payment_terms").prop('checked') == false ) {
            $("#payment_terms").closest('.form-group').addClass('has-error');
            return false;
        }
    });

    function removeClassActive(el){
        if($(el).hasClass('active')){
            $(el).removeClass('active');
        }
    }

    /*$('.card-container').on('click', function(){
        removeClassActive($('.card-container'));
        $(this).toggleClass('active');

        $price = $('.price', this).html();
        $wp_id = $('.post_id' , this).html();
        $('.plan_select_final').attr('data-plan', $wp_id+','+$price);

        $plan_id = $('.plan_id' , this).html();
        $('.plan_select_final').attr('data-plan-id', $plan_id);

        $upsell_id = $('.upsell_id' , this).html();
        $('.plan_select_final').attr('data-target', '#modalProduct'+$upsell_id);


    });

    $('.plan_select_final').on('click', function(e){

        if(!$(this).attr('data-plan-id')){
            e.preventDefault();
            $(this).attr('data-target', '');
            $('.plan_check_error').html('Select a Rate Plan');
        } else {
            $('.plan_check_error').html('');
           }
    });*/

    $('.list-product').load(function(){

        if($('.card-container').hasClass('active')) {
            $id = $('.post_id', this).html();
            $('.plan_select_final').attr('data-plan', $id);
        }
    });





    $('.card-select').on('click', function(){

        var permanent = $('.list-select').attr('data-permanent');

        if(permanent=='0'){//verifico se gli oggetti sono permanenti o meno

            if ($(this).hasClass('active')) {
                $(this).toggleClass('active').find('.btn').html('Select');
                removeToCart(this);
            } else {

                var parent = $(this).closest("div.row");
                var singleSelection = parent.attr('data-singleSelection');

                if (singleSelection == 1) {

                    var elToRemove = $(".card-select.active", parent);
                    if (elToRemove) {
                        removeToCart(elToRemove);
                    }
                    $(".card-select.active", parent).removeClass('active');
                }


                $(this).toggleClass('active').find('.btn').html('Selected');

                var el = this;

                addToCart(el);
            }
        }
    });

    function addToCart(el){

        showLoader();
        var accessories=$(el).data('json');
        var product=$(el).data('product');
        var accGroup=$(el).data('accgroup');

        var data = {
            action: 'iper_add_product_plan_accessories_to_cart',
            data:{
                accessories: accessories,
                product: product,
                accGroup: accGroup
            }
        };

        $.post(ajax_object.ajax_url, data, function(response) {

            var res= $.parseJSON(response);
            if(res.status==1){
                for(var i in res.cart){
                    var single=res.cart[i];
                    iperRenderCartJS(single);
                }
            }

            hideLoader();
        });
    }

    function removeToCart(el){

        var accessories=$(el).data('json');
        var product=$(el).data('product');
        var accGroup=$(el).data('accgroup');

        if(!product || product=="null") return ;

        var data = {
            action: 'iper_remove_product_plan_accessories_to_cart',
            data:{
                accessories: accessories,
                product: product,
                accGroup: accGroup
            }
        };

        showLoader();

        $.post(ajax_object.ajax_url, data, function(response) {

            var res= $.parseJSON(response);
            if(res.status==1){
                for(var i in res.cart){
                    var single=res.cart[i];
                    iperRenderCartJS(single);
                }
            }
            hideLoader();
        });
    }

    function renderCart(cart){

        var wrapper=$("#preview-select-payment .order-detail");

        //set tax
        /*$(".shipping-tax li.tax .order-text-space .price",wrapper).html(cart.tax);*/

        //set products
       $("ul.productListCart",wrapper).html("");

        if(cart.products.length){
            for(var i in cart.products){
                var product=cart.products[i];

                $("ul.productListCart",wrapper).append("<li><strong>"+product.post_title+"</strong><div class='order-text-space'> <span class='price'>Included</span></div><div class='clearfix'></div></li>");
               /* $(".total1",wrapper).append("1");*/

            }
        }
    }


    /*$( window ).scroll(function() {

        if($(window).width()<768) return ;
        var btn_order = $( "#place_order" );
        var btn_cart_order = $( "#preview-order-payment" );

        var footer = $( "#colophon" );
        var footer_offset = footer.offset();

        var offset = btn_order.offset();
        var offset_cart = btn_cart_order.offset();



        *//*if(offset_cart.top+btn_cart_order.height() >= offset.top){

            $("#preview-order-payment").css("position","absolute");
            $("#preview-order-payment").css("top", ""+(offset_cart.top-btn_cart_order.height())+"px");
        } else {
            $("#preview-order-payment").css("position","fixed").css("top",""+(offset_cart.top)+"px");

        }*//*


        //controlla con lo scroll del document

        var offsetScroll = $(document).scrollTop()+$(window).height();

        console.log("scroll "+offsetScroll);
        console.log("footer top "+footer_offset.top);

        if(offsetScroll >= footer_offset.top){

            $("#preview-order-payment").css("position","absolute");
            $("#preview-order-payment").css("top", (offset_cart.top)+"px");

        }





        *//*var bottomOffset=offset.top+btn_order.height();
        var bottomOffsetCart=offset_cart.top+btn__cart_order.height()*//*

        *//*btn__cart_order.html( "btTop: "+bottomOffset + ", top: " + bottomOffsetCart );*//*


        *//*if( bottomOffsetCart >= bottomOffset ){
            console.log("HERE");


            $("#preview-order-payment").css("position","absolute").css("top",""+(bottomOffset- $("#preview-order-payment").height()-30-293)+"px");

        }else{
            $("#preview-order-payment").css("position","fixed").css("top","auto")
        }*//*
    });*/



    $('#_a_add_contact').click( function() {

        $('.emergency_contact').toggleClass('hide_emergency_contact');

        if($('.emergency_contact').hasClass('hide_emergency_contact')){
            $('#_a_add_contact').html('+Add Another Emergency Contact');
        } else {$('#_a_add_contact').html('+Remove an Emergency Contact');}



    });



    /*$('#_a_add_contact').click( function() {
        $('#_add_contact').append(new_contact);
        var u = '  ';
            });
        var new_contact = [
        '<div class="row">',
        '                            <div class="col-xs-12"><label>Secondary Contact</label></div>',
        '                            <div class="col-sm-4">',
        '                                <div class="form-group has-feedback">',
        '                                    <div class="form-subtitle2">',
        '                                        <input type="text" class="form-control" id="firstName_shipping" placeholder="First Name*" required>',
        '                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>',
        '                                    </div>',
        '                                </div>',
        '                            </div>',
        '                            <div class="col-sm-4">',
        '                                <div class="form-subtitle2">',
        '                                    <div class="form-group has-feedback">',
        '                                        <input type="text" class="form-control" id="lastName_shipping" placeholder="Last Name*" required>',
        '                                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>',
        '                                    </div>',
        '                                </div>',
        '                            </div>',
        '                            <div class="col-sm-4">',
        '                                <div class="form-group">',
        '                                    <div class="form-subtitle2">',
        '                                        <select id="state_billing_2" class="form-control selectpicker show-tick" data-live-search="true" title="Relationship" required>',
        '                                        <option value="">Relationship</option>',
        '                                        <option value="Aunt">Aunt</option>',
        '                                        <option value="Caretaker">Caretaker</option>',
        '                                        <option value="Daughter">Daughter</option>',
        '                                        <option value="Doctor">Doctor</option>',
        '                                        <option value="Ex-Husband">Ex-Husband</option>',
        '                                        <option value="Ex-Wife">Ex-Wife</option>',
        '                                        <option value="Father">Father</option>',
        '                                        <option value="Friend">Friend</option>',
        '                                        <option value="Granddaughter">Granddaughter</option>',
        '                                        <option value="Grandfather">Grandfather</option>',
        '                                        <option value="Grandmother">Grandmother</option>',
        '                                        <option value="Grandparent">Grandparent</option>',
        '                                        <option value="Grandson">Grandson</option>',
        '                                        <option value="Mother">Mother</option>',
        '                                        <option value="Niece">Niece</option>',
        '                                        <option value="Neighbor">Neighbor</option>',
        '                                        <option value="Nephew">Nephew</option>',
        '                                        <option value="Nurse">Nurse</option>',
        '                                        <option value="Sibling">Sibling</option>',
        '                                        <option value="Son">Son</option>',
        '                                        <option value="Uncle">Uncle</option>',
        '                                        <option value="Wife">Wife</option>',
        '                                        <option value="Other">Other</option>',
        '                                        </select>',
        '                                    </div>',
        '                                </div>',
        '                            </div>',
        '                        </div>',
        '                        <div class="row">',
        '                            <div class="col-sm-8">',
        '                                <div class="form-group has-feedback">',
        '                                    <input type="text" class="form-control" id="firstName_shipping" placeholder="Phone*" required>',
        '                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>',
        '                                </div>',
        '                            </div>',
        '                        </div>'
    ].join('');*/








    /*var ddl = document.getElementById("cardtype");
    if(ddl){
        var selectedValue = ddl.options[ddl.selectedIndex].value;

        if (selectedValue == "selectcard")
        {
            alert("Please select a card type");
        }
    }*/
    /*function checkEmail()
    {
        val = jQuery('#email_shipping').val();
    }*/
    function validateEmail(email)
    {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    function addLoader() {
        $("body").append('<div id="site-loader"><div class="sk-three-bounce"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div></div>');
        $("#site-loader").hide();
    }

    function showLoader(){
        $("#site-loader").show();
    }

    function hideLoader(){
        $("#site-loader").hide();
    }

    addLoader();

    /*$('.terms_and_conditions').on('click', function(){
        $text = $('#terms_text');
        if($text.css('display')=='none'){
            $text.css('display','block');} else{
            $text.css('display','none');
        }
    });

    $('.close2').on('click', function(){
        $('#terms_text').css('display','none');

    });*/


})(jQuery);