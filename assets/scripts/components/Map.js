import L from 'leaflet';

class Map {
	
	constructor() {
	
	}
	
	static init() {
		const that = this;
		if ($(".component-map").length) {
			$('.component-map').each(function() {
				const id = 'map'
				const map = $('#'+id)
				
				if (map.length) {
					const lat = parseFloat(map.data('lat'))
					const lng = parseFloat(map.data('lng'))
					
					let bounds = []
					
					const mymap = L.map(id).setView([lat, lng], 10)
					
					const defaultMarker = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADkAAAA8CAYAAADc1RI2AAADGklEQVRogd2bO2sUURSAv01MI0QLNTH4auKj0PhCxUflC0EkqdQoipBS8g8sbCwMQgy2WTvFRrCIEhUsfUAe2EQ0ahNMdsWIxsciJKyc5VyZLLvu7M7c2Tvzwal29s755s7jzr1nUtihHdgH7AG2Ams0mj17+wZkgGngNTAKvALeW8opFHYCfcBbIB8g3gDXtT0naATOAy8CipWL50A3sKResl3AhCW54pDePRWl3HrgSURyxfFU92+VC8BcnQRNfAcu2pCUa6+/znLFMaB5hcJSYNgxQRMPNb/Ags8cFTQh+dUs2qhHymVBb4/WdOreiImgib5qBc/GTNDEGb+CMsacjamkjIfb/EgOxVTQxINKgsdiLmjiRDlBee0aC9J4JpPJFzM4OGj9vyViTH0KNHgku1x6vQmIeHSaJrySl+NmUoFe87OR3AwccTHTABxWr3+S3XG08ME5PJKdQVpymE4jKQ//HQmV3A6sFckDDiRjk/0iuSu5fgV2i2SHA4nYpEMkN9jcQ09PD/l8vmK0trbaSmGdSLbYat0R2kRyVcIlVzT42Cj2WJdMp9OkUqmKkc1mreUgkn+ste4GOZH8lHDJaZGccSARm2RF8l1y/QpMNOgKb5IZF8mRhEuOGMmvDiRjA5k/HhXJBeBR8vwKyBrJghkM3K9zMrYoTDQbyaEEPko+q9ciriZk9tzEtVLmK4GfCRHMqU8B7wD9i65JJoF+9SnJMi0Di3MvSinb8kod1RVzSd8LsXdjKnivmkutOcKSsrBi0s9pWswmXZ6Og6DkuaVaQcMh4LfjgpLfwVoFDbIE9stRwTnNLxRk5XbKQcmTfuT8ztaNA3uBl2EdtSipZkpyRq/RK8C8I/m322x8mxbY1vt0vWVT0nBc68Rtinz8z2/DUUga5MZ0O8TnqlQnpz1rp6fLlMJ9iFLS0AQcBW7qTSrnUyqn2w9oVVhTibZX65u+93/zZbZdhK2PXwzyqcNGrUtoKVomnNWb2ZT2iN+b2SU9iGYIJyOzSTvp1xc5aI/9PitDK1SPmB/AHV3HkdJseY6XBvgL2zjoVMWkRT4AAAAASUVORK5CYII=';
					
					let defaultIcon = new L.Icon({
						iconUrl: defaultMarker,
						iconSize: [55, 58],
						iconAnchor: [48, 58],
					});
					
					L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}').addTo(mymap)
				}
			})
		}
	}
}

export default Map
