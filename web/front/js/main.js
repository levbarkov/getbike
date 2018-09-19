$(document).ready(function () {
    var dateToday = new Date();
    $('#date-from, #date-to').datepicker({
        minDate: dateToday,
        beforeShow: function (textbox, instance) {
            var txtBoxOffset = $(this).offset();
            var top = txtBoxOffset.top;
            var left = txtBoxOffset.left;
            var parent = $(this).parent('.content__first__block__form__date__item');
            var leftPadding = parent.innerWidth() - parent.width();
            var textBoxWidth = $(this).outerWidth();
            if(window.innerWidth >= 1020){
                setTimeout(function () {
                    parent.css({
                        "z-index": 99999,
                    });
                    $('.layout').show();
                    instance.dpDiv.css({
                        top: top - instance.dpDiv.height() - parent.outerHeight(true) - 10, //you can adjust this value accordingly
                        left: left - leftPadding,
                    });
                }, 0);
                // setTimeout(function () {
                //     $('.datepicker_bottom').css({
                //         top: top - parent.outerHeight(true) - 10, //you can adjust this value accordingly
                //         left: left - leftPadding,
                //     })
                //     $('.datepicker_bottom').show();
                // }, 0);
            } else {
                setTimeout(function () {
                    parent.css({
                        "z-index": 99999,
                    });
                    $('.layout').show();
                    instance.dpDiv.css({
                        top: 40,
                        left: 0,
                        right: 0,
                        margin: "0 auto"
                    });
                    // $('.datepicker_bottom').css({
                    //     top: $(instance.dpDiv).height() + 40, //you can adjust this value accordingly
                    //     left: 0,
                    //     right: 0,
                    //     margin: "0 auto",
                    // })
                    // $('.datepicker_bottom').show();
                }, 0);
            }
        },
        onClose: function () {
            $('.content__first__block__form__date__item').css({
                "z-index": 1,
            });
            setTimeout(function () {
                $('.datepicker_bottom').hide();
                $('.layout').hide();
            }, 150)
        }
    });
    $('body').on("click", '.datepicker_bottom_current', function () {
        $("#date-from").datepicker( "setDate", dateToday );
    });

    $('#date-from, #date-to').click(
        function () {
            $(this).datepicker("show");
            $(this).blur();
        }
    );


    $('.js-bike-availability').on("click", function(){
        $(this).closest("li").toggleClass("js-bike-inactive");
    });


    $('.js-slick-prev').click(
        function () {
            $('.content__second__list').slick('slickPrev');
        }
    );
    $('.js-slick-next').click(
        function () {
            $('.content__second__list').slick('slickNext');
        }
    );

    //
     $('.content__second__list').on('init', function(event, slick){
         $('.js-slick-total').text(slick.slideCount)
     });
     $('.content__second__list').on('afterChange', function(event, slick, currentSlide){
		$("#bike_id").val($("#"+$("div[aria-hidden=false]").attr("id")).attr("bike"));
		$("#condition_id").val($("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")).attr("condition"));
		var current_helmets_count=$("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")+" .content__second__list__item__helmets .content__second__list__item__helmets__list").children("div[class *= 'content__second__list__item__helmets__list__item--active']").attr("helmet");
		$("#helmets_count").val(current_helmets_count);        
         $('.js-slick-current').text(currentSlide+1);
     });
     $('.content__second__list').slick({
         arrows: false,
     });

    $('.js-model-list-toggle').click(function () {
        listToggle();
        return false;
    });

    $('input:radio[name="model"]').change(function () {
        $('.js-model-list-toggle-text').text($('input[name="model"]:checked').next().text());
        listToggle();
    });

    $('.layout').click(function () {
        $('.js-model-list').slideUp();
        $('.admin__content__main__form__item--selector').css({
            "z-index": 999
        });
        $('.layout').hide();
        $('.location_map').hide();
        $(this).hide();
        $('.admin__menu--show').removeClass('admin__menu--show');
        $('.slide_menu').animate({
            left: '-175px',
        }, 200).removeClass('active');

        $('body').animate({
            left: '0px'
        }, 200, function () {
            $('body').css('overflow', 'auto');
        });
    });

    $('.js-choose-location').click(function () {
        $('.location_map').show();
        $('#address').focus();
        $('.layout').show();
        return false;
    });

    $('.js-close-location, .location_map__top__buttons__submit').click(function () {
        $('.location_map').hide();
        $('.layout').hide();
    });

    // $('.content__third__right__bill__item__right').truncate({
    //     width: "auto"
    // });
    var body = $('body').css('position');

    $('.header__logo__menu').click(function () {
        $('.admin__menu').addClass("admin__menu--show");
        $('.layout').show();

        if($('.slide_menu').hasClass('active')){
            $('.slide_menu').animate({
                left: '-175px',
            }, 200).removeClass('active');

            $('body').animate({
                left: '0px'
            }, 200, function () {
                $('body').css('position',body).css('overflow', 'auto');
            });
        }else{
            $('.slide_menu').animate({
                left: '0px'
            }, 200).addClass('active');
            $('body').css('position','relative').css('overflow', 'hidden').animate({
                left: '175px'
            }, 200);
        }

    });
    if (($("#name").length>0) && (localStorage['name']!=undefined) && (localStorage['name']!='')) {
		$("#name").val(localStorage['name']);
    }
    if(($("#email").length>0) && (localStorage['email']!=undefined) && (localStorage['email']!='')) {
		$("#email").val(localStorage['email']);
    }
    if(($("#phone").length>0) && (localStorage['phone']!=undefined) && (localStorage['phone']!='')) {
		$("#phone").val(localStorage['phone']);
    }
    
});
  window.onload = function() {
        if($("div[aria-hidden=false]").length > 0){
		$("#bike_id").val($("#"+$("div[aria-hidden=false]").attr("id")).attr("bike"));
		$("#condition_id").val($("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")).attr("condition"));
		var current_helmets_count=$("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")+" .content__second__list__item__helmets .content__second__list__item__helmets__list").children("div[class *= 'content__second__list__item__helmets__list__item--active']").attr("helmet");
		$("#helmets_count").val(current_helmets_count);
		}

  };

function listToggle() {
    var $parent = $('.admin__content__main__form__item--selector');
    var $form = $parent.closest('form');
    var parentHeight = $parent.outerHeight();
    if($('.js-model-list').is(":visible")){
        $parent.css({
            "z-index": 999
        });
        $('.js-model-list').slideToggle();
        $('.layout').hide();
    } else {
        $form.css({
            "padding-top": parentHeight + 15,
        });
        $parent.css({
            "z-index": 9999,
            position: "absolute",
            top: 0
        });
        $('.js-model-list').slideToggle();
        $('.layout').show();
    }
}
function back_index() {
    location('/index');
}
