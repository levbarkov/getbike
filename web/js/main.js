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
            if (window.innerWidth >= 1020) {
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
        $("#date-from").datepicker("setDate", dateToday);
    });

    $('#date-from, #date-to').click(
        function () {
            $(this).datepicker("show");
            $(this).blur();
        }
    );


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
    // $('.content__second__list').on('init', function(event, slick){
    //     $('.js-slick-total').text(slick.slideCount)
    // });
    // $('.content__second__list').on('afterChange', function(event, slick, currentSlide){
    //     console.log(currentSlide);
    //     $('.js-slick-current').text(currentSlide+1);
    // });
    // $('.content__second__list').slick({
    //     arrows: false,
    // });

    $('.js-model-list-toggle').click(function () {
        listToggle();
        return false;
    });

    $('input:radio[data-type="model"]').change(function () {
        $('.js-model-list-toggle-text').text($('input[data-type="model"]:checked').next().text());
        listToggle();
    });

    $('input:radio[data-type="model"]').each(function () {
        if ($(this).attr('checked') != undefined) {
            $('.js-model-list-toggle-text').text($('input[data-type="model"]:checked').next().text());
        }
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
    });

    $('.js-choose-location').click(function () {
        $('.location_map').show();
        $('.layout').show();
        return false;
    });

    $('.js-close-location').click(function () {
        $('.location_map').hide();
        $('.layout').hide();
    });

    // $('.content__third__right__bill__item__right').truncate({
    //     width: "auto"
    // });

    $('.header__logo__menu').click(function () {
        $('.admin__menu').addClass("admin__menu--show");
        $('.layout').show();
    });

    $('.js-bike-availability').on("click", function () {
        //var radio = $(this).find('[data-toggle="garage_status"]');
        var garage_id = $(this).attr('data-garage_id');
        $.get('/rental/changestatus?id=' + garage_id).done(function (response) {
            response = parseInt(response);
            if (response === 1) {
                $(this).closest("li").toggleClass("js-bike-inactive");
                console.log('true')
                return true;
            } else {
                console.log('false');
                return false;
            }
        });
    });

/*    $('[data-toggle="garage_status"]').click(function () {
        var garage_id = $(this).attr('data-garage_id');
        $.get('/rental/changestatus?id=' + garage_id).done(function (response) {
            response = parseInt(response);
            if (response === 1) {
                console.log('true')
                return true;
            } else {
                console.log('false');
                return false;
            }
        })
    });*/
});

function listToggle() {
    var $parent = $('.admin__content__main__form__item--selector');
    var $form = $parent.closest('form');
    var parentHeight = $parent.outerHeight();
    if ($('.js-model-list').is(":visible")) {
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