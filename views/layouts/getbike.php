<?php
use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html class="no-js" lang="<?= Yii::$app->language ?>">
<head>
 <meta charset="<?= Yii::$app->charset ?>">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <?= Html::csrfMetaTags() ?>
 <title><?= Html::encode($this->title) ?></title>
 <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        <!-- Add your site or application content here -->

        <div class="main">
            <div class="header">
                <div class="header__logo">
                    <div class="header__logo__menu icon icon-burger"></div>
                    <a href="#" class="header__logo__logo">getbike</a>
                </div>
                <div class="header__social">
                    <div class="header__social__item">
                        <a href="#"><span class=" icon icon-fb"></span></a>
                    </div>
                    <div class="header__social__item">
                        <a href="#"><span class="icon icon-tw"></span></a>
                    </div>
                    <div class="header__social__item">
                        <a href="#"><span class="icon icon-insta"></span></i></a>
                    </div>
                </div>
                <div class="header__nav">
                    <div class="header__nav__item">
                        <a href="">About us</a>
                    </div>
                    <div class="header__nav__item">
                        <a href="">Contact</a>
                    </div>
                    <div class="header__nav__item">
                        <a href="">Prices</a>
                    </div>
                    <div class="header__nav__item">
                        <a href="">FAQ</a>
                    </div>
                    <div class="header__nav__item">
                        <a href="">Terms</a>
                    </div>
                </div>
                <div class="header__auth">
                    <div class="header__auth__reg">
                        <a href="">Register</a>
                    </div>
                    <div class="header__auth__login">
                        <a href="">Log In</a>
                    </div>
                </div>
            </div>
         <?= $content ?>
        </div>
<?php $this_url=Yii::$app->request->resolve(); 
if ($this_url[0]=='site/index'){
?>
        <div class="layout" style="display: none"></div>
        <div class="location_map" style="display:none;">
            <div class="location_map__close js-close-location"><i></i></div>
            <div class="location_map__top">
                <div class="location_map__top__form">
                    <label for="address">Pickup and drop at</label>
                    <input type="text" name="address" id="address" placeholder="Enter your location here">
                    <input type="hidden" name="location" id="location">
                </div>
                <div class="location_map__top__buttons">
                    <div class="location_map__top__buttons__location"><i class="icon icon-location"></i></div>
                    <div class="location_map__top__buttons__submit">
                        <p>Confirm <i class="icon-right-arrow icon"></i></p>
                    </div>
                </div>
            </div>
            <div id="map"></div>
        </div>        
<?php
$script0 = <<< JS
            var marker;
            var map;
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -8.3693355, lng: 115.0350728},
                    zoom: 10
                });
                var icon = {
                    path: "M 8.39844 24.6367C 8.59375 24.9785 8.93555 25.125 9.375 25.125C 9.76562 25.125 10.1074 24.9785 10.3516 24.6367L 13.623 19.9492C 15.2344 17.6055 16.3086 16.043 16.8457 15.2129C 17.5781 14.041 18.0664 13.0645 18.3594 12.2832C 18.6035 11.502 18.75 10.5742 18.75 9.5C 18.75 7.83984 18.3105 6.27734 17.4805 4.8125C 16.6016 3.39648 15.4785 2.27344 14.0625 1.39453C 12.5977 0.564453 11.0352 0.125 9.375 0.125C 7.66602 0.125 6.10352 0.564453 4.6875 1.39453C 3.22266 2.27344 2.09961 3.39648 1.26953 4.8125C 0.390625 6.27734 0 7.83984 0 9.5C 0 10.5742 0.0976562 11.502 0.390625 12.2832C 0.634766 13.0645 1.12305 14.041 1.9043 15.2129C 2.39258 16.043 3.4668 17.6055 5.12695 19.9492C 6.44531 21.8535 7.51953 23.416 8.39844 24.6367Z",
                    fillColor: "#FF1779",
                    anchor: new google.maps.Point(0,0),
                    scale: 1,
                    fillOpacity: 1,
                    strokeWeight: 0,
                }
                google.maps.event.addListener(map, 'click', function(event){
                    var location = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                    if(marker){
                        marker.setPosition(location);
                    } else {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()),
                            map: map,
                            icon: icon
                        })
                    }
                })
            }
JS;
$this->registerJs($script0, yii\web\View::POS_END);
//$this->registerJsFile('//code.jquery.com/ui/1.12.1/jquery-ui.js',  [
//    'position' => $this::POS_END,
//    'integrity' => 'sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=',
//    'crossorigin' => 'anonymous'
//]);
$script01 = <<< JS
    $( ".location_map__top__buttons__submit" ).on('click', function(e){
	   $("#location_from_map").val($("#location").val());
	   $("#name_from_map").val($("#address").val());	
	   $(".content__first__block__form__location__link--choosed").html('<i class="icon icon-location"></i>'+$("#address").val());
	   $(".location_map").hide();
	   $(".layout").hide();		
    });
	
    $( "#address" ).autocomplete({
      source: '/find',
      select: function(event, ui) { $("#location").val(ui.item.value); $("#address").val(ui.item.label); return false; },
      delay: 200
    });
   
JS;
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDDBwfDyvcgYomhA2tu8InTnSrjfd76iC0&callback=initMap',  [
    'position' => $this::POS_END,
    //'async' => 'async',
    'defer' => 'defer'
]);
$this->registerJs($script01, yii\web\View::POS_END);
}

$script = <<< JS
            window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
            ga('create','UA-XXXXX-Y','auto');ga('send','pageview')
JS;
$this->registerJs($script, yii\web\View::POS_END);

$this->registerJsFile('https://www.google-analytics.com/analytics.js',  [
    'position' => $this::POS_END,
    'async' => 'async',
    'defer' => 'defer'
]);
?>
    <?php $this->endBody() ?>    
    </body>
</html>
<?php $this->endPage() ?>
