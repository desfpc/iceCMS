$(function () {

    $('.widgetImages__carousel-control-right').mouseover(function () {

        var margin = (widgetImagesCnt - 5) * 52;

        $('.widgetImages__carousel-wrapper').animate({marginLeft: '-' + margin + 'px'}, 300);

    });

    $('.widgetImages__carousel-control-left').mouseover(function () {

        $('.widgetImages__carousel-wrapper').animate({marginLeft: '0'}, 300);

    });

});