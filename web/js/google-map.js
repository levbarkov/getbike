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
            }
