var i = 0;
var origin;
var destination;
var map;
var flag = true;
var act_position = 0;
var ant_position;
var timer = 1000;
var distancia = 10;
var pos;
var ant_marker = null;

var myStyles =[
  {
    featureType: "poi",
    elementType: "labels",
    stylers: [
      { visibility: "off" }
    ]
  }
];

var enable_map = function(flag) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      /*pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };*/
      pos = {
          lat: -16.498347,
          lng: -68.135207
      };
      map = new google.maps.Map(document.getElementById('map'), {
        center: pos,
        zoom: 15,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: myStyles
      });
      //addMarker(pos,map);
      act_position = pos;
      if (flag) {
        google.maps.event.addListener(map, 'click', function(event) {
          //addMarker(event.latLng, map);
        });
      }
    }, function() {
      alert("error");
    });
  } else {
    alert("Se necesitan permisos o el navegador no soporta geolocalización");
  }
};
// Geolocation
var get_geolocation = function() {
  ant_position = act_position;
  navigator.geolocation.getCurrentPosition(function(position) {
    pos = {
      lat: position.coords.latitude,
      lng: position.coords.longitude
    };
    act_position = pos;
  });
  validate(act_position);
  flag = true;
};

var validate = function(act_position) {
  if (ant_position) {
    x1 = ant_position.lat;
    y1 = ant_position.lng;
    x2 = act_position.lat;
    y2 = act_position.lng;
    distance = Math.sqrt((Math.pow((x1 - x2), 2) + Math.pow((y1 - y2), 2)));
    distance = distance * 40076;
    if (distance < distancia) {
      timer = timer * 1.5;
      distancia = distancia * 1.3;
      if (timer > 30000) {
        timer = 30000;
        distancia = 100;
      }
    } else {
      timer = 1000;
    }
    // console.log('position: '+ant_position);
    // console.log('distancia: '+distancia);
    // console.log('tiempo: '+timer);0000000000000000000000000000000000000000000000000000000000000000000000000555
  } else {
    // algoritmo de logica difusa
    timer = 1000;
  }
  setTimeout(get_geolocation, timer);
};

function addMarker(location, map) {
  if(ant_marker != null){
    ant_marker.setMap(null);
  }
  var marker = new google.maps.Marker({
    position: location,
    map: map
  });
  event_direction = location;
  ant_marker = marker;
}
