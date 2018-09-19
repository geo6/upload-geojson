import L from 'leaflet';

export default function initView () {
    for (let i = 0; i < window.app.files.length; i++) {
        const map = L.map(`map-${i}`).setView([0, 0], 0);

        L.tileLayer('https://tile.openstreetmap.be/osmbe/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
                'Tiles courtesy of <a href="https://geo6.be/" target="_blank">GEO-6</a>.'
        }).addTo(map);

        const data = L.geoJSON(window.app.files[i]).addTo(map);

        if (data.getBounds().isValid() === true) {
            map.fitBounds(data.getBounds());
        }
    }
}
