import L from 'leaflet';

export default function initView () {
    const map = L.map('map').setView([0, 0], 0);

    L.control.scale().addTo(map);

    L.tileLayer('https://tile.openstreetmap.be/osmbe/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
            'Tiles courtesy of <a href="https://geo6.be/" target="_blank">GEO-6</a>.'
    }).addTo(map);

    const options = {
        pointToLayer: (feature, latlng) => {
            return L.circleMarker(latlng, {
                radius: 5
            });
        },
        style: {
            interactive: false
        }
    };
    if (typeof window.app.geojson.legend !== 'undefined') {
        options.style = (feature) => {
            const color = feature.properties.color || '#fff';

            return {
                interactive: false,
                fillColor: color,
                color: '#000',
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            };
        };
    }

    const geojson = L.geoJSON(window.app.geojson, options).addTo(map);

    if (geojson.getBounds().isValid() === true) {
        map.fitBounds(geojson.getBounds());
    }
}
