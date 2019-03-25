let i = 0;
let origin;
let destination;
let map;
let flag = true;
let act_position = 0;
let timer = 1000;
let pos;
let selected_position;
let searchMarkers = [];
let ant_marker = null;
let searchLines = [];
let markerLines = [];

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
                streetViewControl: true,
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
    delete_search_lines();
    delete_search_markers();
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

const addLine = (points, map, color) =>{
    let data = get_data_two_points(points);
    let line = new google.maps.Polyline({
        path: points,
        geodesic: true,
        strokeColor: color,
        strokeOpacity: 1.0,
        strokeWeight: 2
    });
    let marker = new google.maps.Marker({
        position: data.middle,
        label: {
            text: Math.round(data.distance).toString() + ' mts.',
            fontWeight:'bold',
            color: '#141344',
            fontSize: '12px'
        },
        icon:'../img/transparent.png',
        map: map
    });

    markerLines.push(marker);

    line.setMap(map);

    return line;
};

const delete_search_lines = () =>{
    if (searchLines.length){
        for(let i = 0; i < searchLines.length; i++){
            searchLines[i].setMap(null);
        }
        searchLines = [];
    }

    if (markerLines.length){
        for(let i = 0; i < markerLines.length; i++){
            markerLines[i].setMap(null);
        }
        markerLines = [];
    }
};

const delete_search_markers = () => {
    if (searchMarkers.length){
        for(let i = 0; i < searchMarkers.length; i++){
            searchMarkers[i].setMap(null);
        }
        searchMarkers = [];
    }
};

const get_data_two_points = (points) => {
    let x1  = parseFloat(points[0].lat);
    let x2  = parseFloat(points[1].lat);
    let y1  = parseFloat(points[0].lng);
    let y2  = parseFloat(points[1].lng);

    let distance = Math.sqrt(Math.pow((x2-x1),2) + Math.pow((y2-y1),2));
    distance = distance * 111110;

    let middle_point = {lat: ((x1+x2)/2), lng: ((y1+y2)/2)};

    return {distance: distance, middle: middle_point}
};