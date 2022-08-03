import 'leaflet';

class Map {

    constructor() {

    }

    static init() {
        const that = this;
        if ($(".component-map").length) {
            $('.component-map').each(function () {
                const id = 'map'
                const map = $('#' + id)
                const layers = {
                    'satellite': "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}"
                }

                if (map.length) {
                    const lat = parseFloat(map.data('lat'))
                    const lng = parseFloat(map.data('lng'))
                    const zoom = parseFloat(map.data('zoom'))
                    const layer = map.data('layer')

                    const mymap = L.map(id).setView([lat, lng], zoom);

                    let icon = L.icon({
                        iconUrl: 'data:image/png;base64,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',
                        iconSize: [36, 36],
                    });
                    let marker = new L.marker([lat, lng], {icon: icon}).addTo(mymap);

                    L.tileLayer(layers[layer]).addTo(mymap);
                    mymap.invalidateSize();
                }
            })
        }
    }
}

export default Map
