var getbikeWidget = (function () {
    // Для выполнения ajax-запросов
    var XHR = ("onload" in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;

    // Конструктор виджета
    function Widget() {
        this.url = 'https://getbike.io/widget';
        this.ui = {
            widjetDiv: null
        };

        this.init();
    }

    // Обновление данных о погоде
    Widget.prototype._updateData = function(e) {
        var xhr = new XHR(),
            widjetDiv = this.ui.widjetDiv;

        xhr.open('GET', this.url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send();

        xhr.onreadystatechange = function() {
            if (this.readyState != 4) return;
            if (this.status != 200) {
                console.log('Request error');
                return;
            }
            console.log(widjetDiv);
            document.getElementById('getbike_W').innerHTML = this.responseText;
            var elem = document.createElement( 'link' );
            elem.rel = 'stylesheet';
            elem.type = 'text/css';
            elem.href = 'https://getbike.io/front/css/full.css';
            document.getElementsByTagName('head')[0].appendChild(elem);

            var main = document.createElement('script');
            main.src = 'https://getbike.io/front/js/full.js';
            document.getElementsByTagName('head')[0].appendChild(main);
        }
    }

    // Инициализация компонентов ui
    Widget.prototype._initUI = function() {
        this.ui.widjetDiv = document.getElementById('getbike_W');
    };

    // Инициализация виджета
    Widget.prototype.init = function() {
        //this._initUI();
        //this._bindHandlers();
        this._updateData();
    }

    // Возвращаем класс виджета
    return Widget;
})();

new getbikeWidget();
function show_hide(id_block, key_bike) {
    $("#bike_"+key_bike).css({"background-image":"url('"+$("#img_"+id_block).val()+"')"});
    $("#"+id_block).show();
    var id_block1='bike_'+key_bike+'condition_1';
    if (id_block1!=id_block){
        $("#"+id_block1).hide();
    }
    var id_block2='bike_'+key_bike+'condition_2';
    if (id_block2!=id_block){
        $("#"+id_block2).hide();
    }
    var id_block3='bike_'+key_bike+'condition_3';
    if (id_block3!=id_block){
        $("#"+id_block3).hide();
    }
    var current_slide_id=$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id");
    $("#bike_id").val($("#"+$("div[aria-hidden=false]").attr("id")).attr("bike"));
    $("#condition_id").val($("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")).attr("condition"));
    var current_helmets_count=$("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")+" .content__second__list__item__helmets .content__second__list__item__helmets__list").children("div[class *= 'content__second__list__item__helmets__list__item--active']").attr("helmet");
    $("#helmets_count").val(current_helmets_count);
}
function helmet_select(show_count, hide_count){
    $("#"+hide_count).removeClass("content__second__list__item__helmets__list__item--active");
    $("#"+show_count).addClass("content__second__list__item__helmets__list__item--active");
    $("#bike_id").val($("#"+$("div[aria-hidden=false]").attr("id")).attr("bike"));
    $("#condition_id").val($("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")).attr("condition"));
    var current_helmets_count=$("#"+$("div[aria-hidden=false]").children("div[id *= 'bike']:not([style*='display: none;'])").attr("id")+" .content__second__list__item__helmets .content__second__list__item__helmets__list").children("div[class *= 'content__second__list__item__helmets__list__item--active']").attr("helmet");
    $("#helmets_count").val(current_helmets_count);
}

