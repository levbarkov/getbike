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
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NJ3FWQD');</script>
    <!-- End Google Tag Manager -->
</head>
<body>
<?php $this->beginBody() ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NJ3FWQD"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        <!-- Add your site or application content here -->

<div class="main">
    <?=\app\widgets\Hmenu::widget()?>
  <!--  <div class="slide_menu">
        <div class="admin__menu__title">
            <a href="/">getbike.io</a>
        </div>
        <ul>
            <li><a href="/contacts">Contacts</a></li>
            <li><a href="/delivery">Delivery</a></li>
            <li><a href="/hiw">How it works</a></li>
        </ul>
    </div>
            <div class="header">
                <div class="header__logo">
                    <div class="icon-menu header__logo__menu icon icon-burger"></div>
                    <a href="/" class="header__logo__logo">getbike</a>
                </div>
                <div class="header__social hidden">
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
                    <div class="header__nav__item ">
                        <a href="/contacts">Contacts</a>
                    </div>
                    <div class="header__nav__item ">
                        <a href="/delivery">Delivery</a>
                    </div>
                    <div class="header__nav__item ">
                        <a href="/hiw">How it works</a>
                    </div>
                    <div class="header__nav__item">
                        <a href="/about">About us</a>
                    </div>
                    <div class="header__nav__item hidden">
                        <a href="">Contact</a>
                    </div>
                    <div class="header__nav__item hidden">
                        <a href="">Prices</a>
                    </div>
                    <div class="header__nav__item hidden">
                        <a href="">FAQ</a>
                    </div>
                    <div class="header__nav__item hidden">
                        <a href="">Terms</a>
                    </div>
                </div>
                <div class="header__auth hidden">
                    <div class="header__auth__reg">
                        <a href="">Register</a>
                    </div>
                    <div class="header__auth__login">
                        <a href="">Log In</a>
                    </div>
                </div>
            </div>-->
         <?= $content ?>
        </div>
<div class="layout" style="display: none"></div>
<?php $this_url=Yii::$app->request->resolve(); 
if ($this_url[0]=='site/index' OR $this_url[0] == 'dev/second'){
?>

        <div class="location_map" style="display:none;">
            <div class="location_map__close js-close-location"><i></i></div>
            <div class="location_map__top">
                <div class="location_map__top__form">
                    <label for="address">Pickup and drop at</label>
                    <input type="text" name="address" id="address" placeholder="Kuta, Bali">
                </div>
                <div class="location_map__top__buttons">
                    <div class="location_map__top__buttons__location" onClick="curLocation();"><i class="icon icon-location"></i></div>
                    <div class="location_map__top__buttons__submit">
                        <p>Confirm <i class="icon-right-arrow icon"></i></p>
                    </div>
                </div>
            </div>
            <div id="map"></div>
        </div>
    <style>
        #type-selector {
            color: #fff;
            background-color: #4d90fe;
            padding: 5px 11px 0px 11px;
        }

        #type-selector label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }
        #target {
            width: 345px;
        }
    </style>
<?php
$script0 = <<< JS
/*
            var marker;
            var map;
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 8
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
            }*/
      var markers = [];     
      function initAutocomplete() {
         map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -8.6908602, lng: 115.169085},
          zoom: 13,
          mapTypeId: 'roadmap'
        });
        infoWindow = new google.maps.InfoWindow({map: map});
        //curLocation();-8.6908602|115.169085
         var d_pos = {         
              lat: -8.6908602,
              lng: 115.169085
        }
        addMarker(d_pos);
        google.maps.event.addListener(map, 'click', function(e) {
       
         var location = e.latLng;
         $('[name=location_from_map]').val(location.lat()+'|'+location.lng());
         deleteMarkers();
        addMarker(location);
        var pos = {         
              lat: location.lat(),
              lng: location.lng()
        }
        geocoder(pos);
 
     });

      
        // Create the search box and link it to the UI element.
        var input = document.getElementById('address');
        var searchBox = new google.maps.places.SearchBox(input);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));
            $('[name=location_from_map]').val(place.geometry.location.lat()+'|'+place.geometry.location.lng());
            
        var pos = {         
              lat: place.geometry.location.lat(),
              lng: place.geometry.location.lng()
        }
        geocoder(pos);
        
            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }
      function addMarker(location) {
        var marker = new google.maps.Marker({
          position: location,
          map: map
        });
        markers.push(marker);
      }
       function clearMarkers() {
        setMapOnAll(null);
      }
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }
      function geocoder(pos) {
         var geocoder = new google.maps.Geocoder();
        geocoder.geocode({address: pos.lat + ',' + pos.lng}, function (results, status) {
            if (status !== google.maps.GeocoderStatus.OK || !results[0]) {
                return;
            }
            var result = results[0];
            
            var city, region, country, establishment;
        
            for (var i = 0; i < result.address_components.length; i++) {
                if (result.address_components[i].types[0] === "locality") {
                    city = result.address_components[i];
                }
                if (result.address_components[i].types[0] === "administrative_area_level_1") {
                    region = result.address_components[i];
                }
                if (result.address_components[i].types[0] === "country") {
                    country = result.address_components[i];
                }
                
            }
             establishment = results[0].formatted_address;
                    $('[name=name_from_map]').val(establishment);
                    $('.__cur_location').html(establishment);
                    $('#address').val(establishment);
                   // console.log(establishment)
        }); 
      }
      function curLocation() {
                if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            //infoWindow.setPosition(pos);
            //infoWindow.setContent('Your location.');
            deleteMarkers();
            addMarker(pos);
            $('[name=location_from_map]').val(pos.lat+'|'+pos.lng);
            geocoder(pos);
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
           function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
      }
      }
      

JS;
$this->registerJs($script0, yii\web\View::POS_END);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCUeo6QYSENtOV7IsQKXk_9OPzZBRmTFfw&callback=initAutocomplete&libraries=places',  [
    'position' => $this::POS_END,
    //'async' => 'async',
    'defer' => 'defer'
]);
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
