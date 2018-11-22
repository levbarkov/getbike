<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rental */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="rental-form">

        <?php $form = ActiveForm::begin(['action' => '/admin/rental/create']); ?>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'radius')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'adress')->textInput(['id' => 'adress']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'region_id')->dropDownList($data['region_list']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#open_map">
                    Open map
                </button>

                <div id="open_map" class="collapse">
                    <div id="map" style="height: 500px"></div>
                </div>
                <?= $form->field($model, 'coord')->hiddenInput()->label('') ?>
            </div>
        </div>
        <? //= $form->field($model, 'hash')->textInput(['maxlength' => true]) ?>

        <?php ActiveForm::end(); ?>

    </div>
<?php
if ($model->coord) {
    $lat = explode('|', $model->coord)[0];
    $lng = explode('|', $model->coord)[1];
} else {
    $lat = '-33.8688';
    $lng = '151.2195';
}

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
          center: {lat: $lat, lng: $lng},
          zoom: 13,
          mapTypeId: 'roadmap'
        });
        var pos = {
              lat: $lat,
              lng: $lng
            };
         addMarker(pos);
         
       google.maps.event.addListener(map, 'click', function(e) {   
         var location = e.latLng;
         $('[name="Rental[coord]"]').val(location.lat()+'|'+location.lng());
         deleteMarkers();
        addMarker(location);
 
     });

      
        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        
                // Create the search box and link it to the UI element.
        var input = document.getElementById('adress');
        var searchBox = new google.maps.places.SearchBox(input);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

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
            deleteMarkers();
            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));
            $('[name="Rental[coord]"]').val(place.geometry.location.lat()+'|'+place.geometry.location.lng());
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
      

JS;
$this->registerJs($script0, yii\web\View::POS_END);
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCUeo6QYSENtOV7IsQKXk_9OPzZBRmTFfw&callback=initAutocomplete&libraries=places', [
    'position' => $this::POS_END,
    //'async' => 'async',
    'defer' => 'defer'
]);

?>