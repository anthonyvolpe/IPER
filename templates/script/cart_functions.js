/**
 * Created by linus on 05/02/16.
 */
window.plan=0;
jQuery(function(){

    function setCookieAjax_1(plan, plan_id){

        //ajax
        var data_plan = {
            'action': 'iper_set_cookie_plan',
            'plan': plan,
            'plan_id': plan_id
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajax_object.ajax_url, data_plan, function(response) {});
    }

    function iperFPSetProductPlanCookie(plan,planID,product,productID){

        if(!plan || !planID || !product || !productID){ return ; }

        var arrPlan=plan.split(',');
        if(arrPlan.length<2){ return ; }

        var planWPID=arrPlan[0];
        var planPrice=arrPlan[1];

        var data = {
            plan_wp_id: planWPID,
            plan_price: planPrice,
            plan_id: planID,
            product_wp_id: product,
            product_id: productID
        };

        jQuery.post(ajax_object.ajax_url, {data:data,action: 'iper_set_cookie_product_plan'},function(response){

            response=jQuery.parseJSON(response);
        });
    }

    function iperFPSetProductPlanUpsellCookie(upsell, targetURL,link,productID,planID,upsellID){

        var arrupsell=upsell.split(',');
        if(arrupsell.length<2){ return ; }
        var upsellWPID=arrupsell[0];
        var upsellPrice=arrupsell[1];

        var data = {
            upsell: upsellWPID,
            upsellPrice: upsellPrice,
            upsellID:upsellID,
            product: productID,
            plan: planID

        };

        jQuery.post(ajax_object.ajax_url, {data:data,action: 'iper_set_cookie_product_plan_upsell'},function(response){
            if(targetURL!=null && targetURL=="" && link!=null){
                window.location=link;
            }
        });
    }

    jQuery(".btIperSelectPlan").click(function(){

        var plan=jQuery(this).attr("data-plan");
        var planID=jQuery(this).attr("data-plan-id");
        var product=jQuery(this).attr("data-product");
        var productID=jQuery(this).attr("data-product-id");

        window.plan=parseInt(planID);

        iperFPSetProductPlanCookie(plan,planID,product,productID);
        //setCookieAjax_1(jQuery(this).attr("data-plan"), jQuery(this).attr("data-plan-id"));
    });

        function setCookieAjax_2(product, product_fk_id, link){

            //ajax
            var data_product = {
                'action': 'iper_set_cookie_product',
                'product': product,
                'product_fk_id': product_fk_id
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajax_object.ajax_url, data_product, function(response) {

                if(link!=null){
                    window.location=link;
                }
            });
        }

        /*jQuery(".iperModalProduct").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            setCookieAjax_2(jQuery(this).data("product"), jQuery(this).data("base-product-id"),jQuery(this).attr('href'));
        });
        jQuery('.product-id').load(function(){
            setCookieAjax_2(jQuery('.product-id').data("id"));
        });*/


    function setCookieAjax_3(upsell, targetURL,link){

        //ajax
        var data_upsell = {
            'action': 'iper_set_cookie_upsell',
            'upsell': upsell

        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajax_object.ajax_url, data_upsell, function(response) {
            if(targetURL!=null && targetURL=="" && link!=null){
                window.location=link;
            }
        });
    }

    /*jQuery(".plan_select_final").click(function(e){
        setCookieAjax_3();
    });*/

    jQuery(".iperModalUpsell").click(function(e){

        var targetURL=jQuery(this).attr("data-target");
        var link=jQuery(this).attr("href");
        if(targetURL==""){
            e.preventDefault();
            e.stopPropagation();
        }

        var product=jQuery(this).data("product-id");
        var upsellID=jQuery(this).data("upsell-id");


        iperFPSetProductPlanUpsellCookie(jQuery(this).data("upsell"),targetURL,link,product,window.plan,upsellID);
        //setCookieAjax_3(jQuery(this).data("upsell"),targetURL,link);
    });

});

function removeAccessoryFromCart(groupID,accID,product){

    var data = {
        action: 'iper_remove_accessory_to_cart',
        data:{
            groupID: groupID,
            accID: accID,
            product: product
        }
    };

    $.post(ajax_object.ajax_url, data, function(response) {

        var res= $.parseJSON(response);
        if(res.status==1){
            for(var i in res.cart){
                var single=res.cart[i];
                iperRenderCartJS(single);
            }

            $("div[data-accGroupID="+groupID+"] div[data-accID="+accID+"]").removeClass('active');
            $("div[data-accGroupID="+groupID+"] div[data-accID="+accID+"] a.btn").html("Select");
        }
    });
}