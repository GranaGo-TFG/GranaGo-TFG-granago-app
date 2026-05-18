var actualizar = true;
var coordAct = { latMin: 0, latMax: 0, longMin: 0, longMax: 0 };
var coordAnt = { latMin: '', latMax: '', longMin: '', longMax: '' };

var markers = [];
var mapa = null;
var popup = true;

var MapaOSM = {
    init() {
        this.initMapaInicio();
        this.initMapaCrearReto();
    },

    initMapaInicio() {
        var elMapa = document.getElementById('inicio-retos-map');

        if (!elMapa || typeof L === 'undefined') {
            return;
        }

        var lt = Number.parseFloat(elMapa.dataset.initialLat || '37.1773');
        var lg = Number.parseFloat(elMapa.dataset.initialLng || '-3.5986');

        mapa = L.map(elMapa).setView([lt, lg], 13);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
            minZoom: 5,
        }).addTo(mapa);

        mapa.on('popupopen', () => {
            if (popup) {
                actualizar = false;
            }
        });

        mapa.on('moveend', () => {
            var buscarLatLon = document.getElementById('buscarLatLon');

            if (buscarLatLon && buscarLatLon.checked) {
                this.actualizarMapa();
            }
        });

        mapa.on('zoomend', () => {
            var buscarLatLon = document.getElementById('buscarLatLon');

            if (buscarLatLon && buscarLatLon.checked) {
                this.actualizarMapa();
            }
        });

        this.cargar();
    },

    async cargar(enfocar = false) {
        var elMapa = document.getElementById('inicio-retos-map');

        if (!elMapa || !mapa) {
            return;
        }

        var endpoint = elMapa.dataset.endpoint;

        if (!endpoint) {
            return;
        }

        actualizar = true;
        this.mapaResetear();

        var status = document.getElementById('retos-map-status');
        if (status) {
            status.textContent = 'Actualizando retos en el mapa...';
        }

        var params = new URLSearchParams();
        var buscarLatLon = document.getElementById('buscarLatLon');

        if (buscarLatLon && buscarLatLon.checked && coordAct.latMin !== 0 && coordAct.latMax !== 0) {
            params.set('latMin', String(coordAct.latMin));
            params.set('latMax', String(coordAct.latMax));
            params.set('longMin', String(coordAct.longMin));
            params.set('longMax', String(coordAct.longMax));
        }

        var url = params.toString().length > 0 ? endpoint + '?' + params.toString() : endpoint;

        try {
            var response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Error al cargar retos para el mapa.');
            }

            var retos = await response.json();

            if (!Array.isArray(retos) || retos.length === 0) {
                if (status) {
                    status.textContent = 'No hay retos publicados en esta zona.';
                }

                coordAnt = { ...coordAct };
                return;
            }

            var bounds = [];

            for (var i = 0; i < retos.length; i++) {
                if (retos[i].lt && retos[i].lg) {
                    this.marcador(retos[i]);
                    bounds.push([retos[i].lt, retos[i].lg]);
                }
            }

            if (enfocar && bounds.length > 0) {
                mapa.fitBounds(bounds, { padding: [20, 20] });
            }

            if (status) {
                status.textContent = 'Mostrando ' + bounds.length + ' retos en mapa.';
            }

            coordAnt = { ...coordAct };
        } catch (error) {
            if (status) {
                status.textContent = 'No se pudo cargar el mapa de retos.';
            }
        }
    },

    marcador(reto, openpopup = false) {
        var icono = this.icono(reto.st);
        var popupHtml = this.etiqueta(reto);

        var marker = L.marker([reto.lt, reto.lg], {
            icon: icono,
            closeButton: false,
        })
            .addTo(mapa)
            .bindPopup(popupHtml, { maxWidth: 280 });

        if (openpopup) {
            marker.openPopup();
        }

        marker.markerid = reto.nr;
        markers[reto.nr] = marker;
    },

    etiqueta(reto) {
        var ref = reto.ref ? reto.ref : 'Granada';

        return "<article class='map-popup-card'>" +
            "<h3>" + this.escapar(reto.nm) + '</h3>' +
            "<p>" + this.escapar(reto.ds || '') + '</p>' +
            "<div class='map-popup-meta'>" +
            "<span>+" + this.escapar(String(reto.pnt || 0)) + ' pts</span>' +
            "<span>" + this.escapar(ref) + '</span>' +
            '</div>' +
            '</article>';
    },

    mapaResetear() {
        for (var i = 0; i < markers.length; i++) {
            if (markers[i] !== undefined && mapa && mapa.hasLayer(markers[i])) {
                mapa.removeLayer(markers[i]);
            }
        }

        markers = [];
    },

    actualizarMapa() {
        if (!mapa) {
            return;
        }

        var bounds = mapa.getBounds();
        var latMin = bounds.getSouthWest().lat;
        var latMax = bounds.getNorthEast().lat;
        var longMin = bounds.getSouthWest().lng;
        var longMax = bounds.getNorthEast().lng;

        var latMaxInput = document.getElementById('latMax');
        var longMaxInput = document.getElementById('longMax');
        var latMinInput = document.getElementById('latMin');
        var longMinInput = document.getElementById('longMin');

        if (latMaxInput) {
            latMaxInput.setAttribute('value', String(latMax));
        }

        if (longMaxInput) {
            longMaxInput.setAttribute('value', String(longMax));
        }

        if (latMinInput) {
            latMinInput.setAttribute('value', String(latMin));
        }

        if (longMinInput) {
            longMinInput.setAttribute('value', String(longMin));
        }

        if (latMin - latMax === 0 && longMin - longMax === 0) {
            return;
        }

        coordAct = {
            latMin: latMin,
            latMax: latMax,
            longMin: longMin,
            longMax: longMax,
        };

        if (JSON.stringify(coordAct) !== JSON.stringify(coordAnt) && actualizar) {
            this.cargar();
        }
    },

    initMapaCrearReto() {
        var elMapa = document.getElementById('crear-reto-map');
        var latInput = document.getElementById('latitud');
        var lngInput = document.getElementById('longitud');

        if (!elMapa || !latInput || !lngInput || typeof L === 'undefined') {
            return;
        }

        var lat = Number.parseFloat(latInput.value || elMapa.dataset.defaultLat || '37.1773');
        var lng = Number.parseFloat(lngInput.value || elMapa.dataset.defaultLng || '-3.5986');

        if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
            lat = 37.1773;
            lng = -3.5986;
        }

        var mapaCrear = L.map(elMapa).setView([lat, lng], 13);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
            minZoom: 5,
        }).addTo(mapaCrear);

        var marker = L.marker([lat, lng], { draggable: true }).addTo(mapaCrear);

        var sincronizarInputs = function (latitud, longitud) {
            latInput.value = latitud.toFixed(7);
            lngInput.value = longitud.toFixed(7);
        };

        sincronizarInputs(lat, lng);

        mapaCrear.on('click', function (event) {
            marker.setLatLng(event.latlng);
            sincronizarInputs(event.latlng.lat, event.latlng.lng);
        });

        marker.on('dragend', function () {
            var posicion = marker.getLatLng();
            sincronizarInputs(posicion.lat, posicion.lng);
        });

        var actualizarMarcadorDesdeInput = function () {
            var nuevaLat = Number.parseFloat(latInput.value);
            var nuevaLng = Number.parseFloat(lngInput.value);

            if (!Number.isFinite(nuevaLat) || !Number.isFinite(nuevaLng)) {
                return;
            }

            marker.setLatLng([nuevaLat, nuevaLng]);
            mapaCrear.panTo([nuevaLat, nuevaLng]);
        };

        latInput.addEventListener('change', actualizarMarcadorDesdeInput);
        lngInput.addEventListener('change', actualizarMarcadorDesdeInput);
    },

    icono(estado) {
        var clase = 'is-caducado';

        if (estado === 'publicado') {
            clase = 'is-publicado';
        } else if (estado === 'borrador') {
            clase = 'is-borrador';
        }

        return L.divIcon({
            className: 'reto-marker ' + clase,
            html: '<span></span>',
            iconSize: [22, 22],
            iconAnchor: [11, 11],
            popupAnchor: [0, -10],
        });
    },

    escapar(texto) {
        return String(texto)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    },
};

document.addEventListener('DOMContentLoaded', function () {
    MapaOSM.init();
});

export { MapaOSM };
