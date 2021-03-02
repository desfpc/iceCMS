<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

$this->jsready = '

if ($(".main-menu").html()) {
            SecMenuTop = $(".main-menu").offset().top;


            $(window).on("scroll.main-menu", function() {
                if ($(window).scrollTop() >= SecMenuTop && $(window).width() > 800) {
                    if (!$(".main-menu .row").hasClass("fixed")) {
                        $(".main-menu .row").addClass("fixed");
                    }
                } else {
                    $(".main-menu .row").removeClass("fixed");    
                }
            });
        }

$(\'.slimmenu\').slimmenu(
{
    resizeWidth: \'800\',
    collapserTitle: \'Главное меню\',
    animSpeed: \'medium\',
    easingEffect: null,
    indentChildren: false,
    childrenIndenter: \'&nbsp;\'
});

$(\'.dropdown-submenu-treug\').click(function(e) {
  if (!$(this).next().hasClass(\'show\')) {
    $(this).parents(\'.dropdown-menu\').first().find(\'.show\').removeClass("show");
  }
  var $subMenu = $(this).next(".dropdown-menu");
  $subMenu.toggleClass(\'show\');


  $(this).parents(\'li.nav-item.dropdown.show\').on(\'hidden.bs.dropdown\', function(e) {
    $(\'.dropdown-submenu .show\').removeClass("show");
  });


  return false;
});

';