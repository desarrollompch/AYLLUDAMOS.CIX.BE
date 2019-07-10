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
                      <thead>
                        <tr>
                            <th class="number-col">#</th>
                            <th>Fecha</th>
                            <th>Atendido</th>
                            <th>Descripción</th>
                        </tr>
                      </thead>
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
                cancel_url: route('incidente.index',1),
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

                this.map = new GMaps({
                    div: '#map',
                    zoom: 17,
                    lat: this.incidente.latitud ? this.incidente.latitud : -12.043333,
                    lng: this.incidente.latitud ? this.incidente.longitud : -77.028333
                });


                this.marker = this.map.addMarker({
                    lat: this.incidente.latitud ? this.incidente.latitud : -12.043333,
                    lng: this.incidente.longitud ? this.incidente.longitud : -77.028333,
                    animation: google.maps.Animation.DROP
                });

                this.polygon = this.map.drawPolygon({
                    paths: this.incidente.urbanizacion.coordenadas,
                    strokeColor: '#e9e513',
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    fillColor: '#da151b',
                    fillOpacity: 0.6
                });

                for (let polyline of this.incidente.polylines) {
                    let g_polyline = this.map.drawPolyline({
                        path: polyline.coordinates,
                        strokeColor: 'blue',
                        strokeOpacity: 1,
                        strokeWeight: 6
                    });

                    let new_data = {color: 'blue', polyline: g_polyline, descripcion: polyline.descripcion};
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
                poly.polyline.addListener('click', (event) => {
                    // infowindow.content = content;
                    this.infoWindow.setContent(poly.descripcion);
                    // infowindow.position = event.latLng;
                    this.infoWindow .setPosition(event.latLng);
                    this.infoWindow .open(this.map.map);
                });
            }
        }
    }
</script>