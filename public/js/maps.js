let i = 0;
let origin;
let destination;
let map;
let flag = true;
let act_position = 0;
let timer = 1000;
let pos;
let selected_position;
let ant_marker = null;

let myStyles = [
    {
        featureType: "poi",
        elementType: "labels",
        stylers: [
            {visibility: "off"}
        ]
    }
];

let enable_map = (flag) => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            //POSICION ACTUAL DEL DISPOSITIVO
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
                google.maps.event.addListener(map, 'click', (event) => {
                    addMarker(event.latLng, map);
                });
            }
        }, () => {
            alert("error");
        });
    } else {
        alert("Se necesitan permisos o el navegador no soporta geolocalización");
    }
};
// Geolocation
let get_geolocation = () => {
    let actual_position;
    navigator.geolocation.getCurrentPosition((position) => {
        actual_position = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
    });
    return position;
};

let addMarker = (location, map) => {
    $('input#from_places').val('Selección en el Mapa');

    if (ant_marker != null) {
        ant_marker.setMap(null);
    }

    selected_position = {lat:location.lat(),lng:location.lng()};

    let image = {
        url: '../img/here.png',
        labelOrigin: new google.maps.Point(15,35)
    };

    let marker = new google.maps.Marker({
        position: location,
        label: {
            text: "Referencia",
            color: '#141344',
            fontWeight: 'bold',
            fontSize: '13px'
        },
        icon:image,
        map: map
    });
    event_direction = location;
    ant_marker = marker;
};
