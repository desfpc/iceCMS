function number_format( number, decimals, dec_point, dec_end, thousands_sep ) {
    var i, j, kw, kd, km;

    if (isNaN(decimals = Math.abs(decimals))){
        decimals = 2;
    }
    if(dec_point === undefined){
        dec_point = ".<small>";
    }
    if(dec_end === undefined){
        dec_end = " ₽</small>";
    }
    if(thousands_sep === undefined){
        thousands_sep = " ";
    }

    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

    if( (j = i.length) > 3 ){
        j = j % 3;
    } else{
        j = 0;
    }

    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

    return km + kw + kd + dec_end;
}

$(function () {
    //bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('click', '[data-toggle="lightbox"]', function (e) {
        e.preventDefault();
        $(this).ekkoLightbox();
    });

    //person info btn
    $('.person-round').click(function () {

        let round = $(this);
        if (round.hasClass('passive')) {
            round.children('.person-info').show(300, function () {
                round.removeClass('passive');
                round.addClass('active');
            });
        }
    });

    //close person info
    $('.person-info .close').click(function () {

        let round = $(this).parent().parent();
        if (round.hasClass('active')) {
            round.children('.person-info').hide(300, function () {
                round.removeClass('active');
                round.addClass('passive');
            });
        }
    });

    //show header cart details
    $('.header-cart').click(function (){
        $('.header-cart__details').show();
    });

    $('.header-cart__details').mouseleave(function (){
        $('.header-cart__details').hide();
    });

    $('.header-cart__details').click(function (){
        document.location.href = '/cart';
    });

    //cart change count
    $('.cart-good-cnt').keyup(function () {
        //console.log($(this).val());
        //console.log($(this).attr('data'));

        let id = $(this).attr('data');
        let val = $(this).val();

        $.ajax({
            method: "POST",
            url: "/?menu=ajax&action=cart&type=edit&id="+id+"&count="+val,
            dataType: "json"
        }).done(function ( res ) {
            console.log(res);

            changeCartVidget (res);
            changeCart (res);
        });


    });

    function changeCart(res) {
        $('.cart_allCnt').html(res.allCnt);
        $('.cart_allCost').html(res.allFormatedCost);

        let goods = res.goods;
        let out = '';

        $.each( goods, function( i, e ) {
            $('.cart_cost_' + e.id).html(e.formatedCost);
        });

    }

    function changeCartVidget(res) {
        $('.header-cart__cost').html('&nbsp;&nbsp;<strong>'+res.allFormatedCost+'</strong>');
        $('.header-cart__cnt').html('&nbsp;&nbsp;'+res.allCnt+'&nbsp;&nbsp;');

        let goods = res.goods;
        let out = '';

        $.each( goods, function( i, e ) {
            out += '<li>';
            out += e.name + ' (' + e.count + 'шт)';
            out += '</li>';
        });

        $('.header-cart__goods').html(out);
    }

    //to cart btn click
    $('.btn-cart').click(function (e) {

        e.preventDefault();
        let id = $(this).attr('data');

        $.ajax({
            method: "POST",
            url: "/?menu=ajax&action=cart&type=add&id="+id,
            dataType: "json"
        }).done(function (res) {

            changeCartVidget (res);

        });

    });

})