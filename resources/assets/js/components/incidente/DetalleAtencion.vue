<template>
    <div class="card">
        <div class="card-header">
            Detalle Incidente
        </div>
        <div class="card-body" v-if="incidente">
            <dl class="row">
                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{incidente.fecha}}</dd>

                <dt class="col-sm-3">Ciudadano</dt>
                <dd class="col-sm-9" v-if="incidente.persona">{{incidente.persona.nombres}}
                    {{incidente.persona.ape_paterno}} {{incidente.persona.ape_materno}}
                </dd>

                <dt class="col-sm-3">Dirección</dt>
                <dd class="col-sm-9">{{incidente.direccion}}</dd>

                <dt class="col-sm-3">Urbanización</dt>
                <dd class="col-sm-9" v-if="incidente.urbanizacion">
                    {{incidente.urbanizacion.descripcion}}
                </dd>

                <dt class="col-sm-3">Territorio Vecinal</dt>
                <dd class="col-sm-9" v-if="incidente.urbanizacion">
                    {{incidente.urbanizacion.territorio_vecinal.descripcion}}
                </dd>

                <dt class="col-sm-3">Descripcion</dt>
                <dd class="col-sm-9" v-if="incidente.urbanizacion">
                    {{incidente.descripcion}}
                </dd>

                <dt class="col-sm-3">Tipo de Incidente</dt>
                <dd class="col-sm-9" v-if="incidente.tipo_incidente">
                    {{incidente.tipo_incidente.descripcion}}
                </dd>

                <dt class="col-sm-3" v-if="incidente.tipo_incidente_id == 1">Nivel de Agua</dt>
                <dd class="col-sm-9" v-if="incidente.tipo_incidente_id == 1">
                    {{incidente.inundacion.nivel_agua.descripcion}}
                </dd>

                <dt class="col-sm-3" v-if="incidente.tipo_incidente_id == 2">Tipo de Obstaculo</dt>
                <dd class="col-sm-9" v-if="incidente.tipo_incidente_id == 2">
                    {{incidente.calle_obstaculo.tipo_obstaculo.descripcion}}
                </dd>


                <dt class="col-sm-3">Estado</dt>
                <dd class="col-sm-9" v-if="incidente.estado_incidente">
                    {{incidente.estado_incidente.descripcion}}
                </dd>
            </dl>
            <div class="row">
                <div class="col-sm-12">
                    <div id="map" style="width: 100%; height: 350px;">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <h5>Atención de Incidente</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th class="number-col">#</th>
                            <th>Fecha</th>
                            <th>Atendido</th>
                            <th>
                                Descripción
                            </th>
                        </tr>
                        <tr v-for="(row, index) in incidente.atencion_incidente">
                            <td>
                                {{index + 1}}
                            <td>
                            {{row.fecha}}
                            </td>
                            <td>
                            {{row.persona.ape_paterno}} {{row.persona.ape_materno}}, {{row.persona.nombres}}
                            </td>
                            <td>
                                {{row.descripcion}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h5>Media</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <img :src="incidente.src_imagen" alt="incidente" class="img-thumbnail" v-if="incidente.src_imagen">
                </div>

                <div class="col-sm-3" v-for="row in incidente.incidentesmedia">
                    <img :src="row.incidente_media_url" alt="incidente" class="img-thumbnail">
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <a class="btn disenio-boton-accion" :href="cancel_url">
              <i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i>Volver
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                incidente: {},
                errors: [],
                loading: false,
                cancel_url: route('incidente.attention',1),
                polgygon: null,
                polylines: [],
                map: null,
                marker: null,
                infoWindow: null
            }
        },
        mounted() {
            let id = this.getIDfromURL();
            this.infoWindow = new google.maps.InfoWindow({
                content: ''
            });

            axios.get(route('incidente.show', {id: id})).then((response) => {
                this.incidente = response.data.incidente;


                let latitude = this.incidente.latitud ? this.incidente.latitud : -6.7718781;
                let longitude = this.incidente.longitud ? this.incidente.longitud : -79.8385601;

                if (!this.map) {

                    this.map = L.map('map', {editable: true}).setView([latitude, longitude], 17);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        maxZoom: 30,
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox.streets'
                    }).addTo(this.map);
                }
                else{
                    this.clearMap(this.map);
                }

                this.marker = L.marker([latitude, longitude], {draggable: false}).addTo(this.map);

                this.polygon = L.polygon(this.incidente.urbanizacion.coordenadas, {
                    color: 'red',
                    weight: 2,
                    interactive: true
                }).addTo(this.map);

                for (let polyline of this.incidente.polylines) {
                    let color = 'blue';
                    let path = polyline.coordinates;
                    let g_polyline = L.polyline(path, {color: color}).addTo(this.map);
                    let new_data = {color: color, polyline: g_polyline, descripcion: polyline.descripcion};
                    this.createInfoWindow(new_data);
                    this.polylines.push(new_data);
                }

            });
          $('#mute').removeClass('on');
        },
        methods: {
            getIDfromURL() {
                let path = window.location.pathname;
                let segments = path.split("/");
                let id = null;

                for (let segment of segments) {
                    if (!isNaN(segment)) {
                        if (!id) {
                            id = segment;
                        }
                    }
                }

                return id;
            },
            getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            },
            createInfoWindow(poly) {
                poly.polyline.on('click', (event) => {
                    L.popup()
                        .setLatLng(event.latlng)
                        .setContent(poly.descripcion)
                        .openOn(this.map);
                });
            },
            clearMap: function(mapa) {
                mapa.eachLayer(function (layer) {
                    if(layer._path != undefined) {
                        try {
                            mapa.removeLayer(layer);
                        }
                        catch(e) {
                            console.log("problem with " + e + layer);
                        }
                    }
                });
            }
        }
    }
</script>