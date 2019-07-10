<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <div class="row">
                <div class="col-sm-3">
                    <label for="fecha_inicio">
                        Fecha Inicio
                    </label>
                    <datepicker v-model="fecha_inicio" :input-class="'form-control'"
                        :language="languages['es']" :format="'dd/MM/yyyy'"></datepicker>
                </div>
                <div class="col-sm-3">
                    <label for="fecha_final">
                        Fecha Final
                    </label>
                    <datepicker v-model="fecha_final" :input-class="'form-control'"
                        :language="languages['es']" :format="'dd/MM/yyyy'"></datepicker>
                </div>
                <div class="col-sm-3" style="margin-top:10px;">
                    <br>
                    <a href="#" class="btn disenio-boton-accion" v-on:click="searchFilter()">
                        Consultar
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <span style="color:red; font-size:12px">* Por defecto se muestra la información del día.</span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12" style="z-index: 1">
                    <p v-if="this.ubicaciones.length == 0">No existen ubicaciones registradas</p>
                    <div style="width: 100%; height: 600px;" id="mapAll">                        
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalFamiliar" tabindex="-1" role="dialog" aria-labelledby="modalFamiliar" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFamiliarLabel">Ubicación de Familiar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-3">Alias Familiar:</dt>
                            <dd class="col-sm-9">{{ familiar.nombres }}</dd>
                            
                            <dt class="col-sm-3">Teléfono:</dt>
                            <dd class="col-sm-9">{{ familiar.telefono }}</dd>

                            <dt class="col-sm-3">Fecha:</dt>
                            <dd class="col-sm-9">{{ familiar.fecha }}</dd>

                            <dt class="col-sm-3">Descripción:</dt>
                            <dd class="col-sm-9">{{ familiar.descripcion }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import * as lang from "vuejs-datepicker/src/locale";
    import * as moment from 'moment';

    export default {
        components: {
            Datepicker
        },
        data() {
            return {
                ubicaciones: [],
                map:null,
                marker: null,
                polygon: null,
                polylines: [],
                languages: lang,
                loading: false,
                fecha_inicio: '',
                fecha_final: '',
                date_inicio: '',
                date_final: '',
                mapAll: null,
                showMap: false,
                markers: [],
                infoWindow: null,
                familiar: {
                    nombres: "",
                    telefono: "",
                    fecha: "",
                    descripcion: ""
                }
            }
        },
        mounted() {
            this.infoWindow = new google.maps.InfoWindow({
                content: ''
            });

            this.getUbicaciones();
            $('#mute').removeClass('on');
        },
        methods: {
            searchFilter() {
                this.date_inicio = moment(this.fecha_inicio).format("DD/MM/YYYY");
                this.date_final = moment(this.fecha_final).format("DD/MM/YYYY");
                this.getUbicaciones();
            },
            getUbicaciones() {
                this.loading = true
                axios.get(route("ubicacion.all", {fecha_inicio: this.date_inicio, fecha_final: this.date_final})).then((response) => {
                    this.ubicaciones = response.data;
                    this.setMarkers();
                });
                this.loading = false
            },

            pinSymbol(color) {
                var template = [
                    '<?xml version="1.0" encoding="utf-8"?>',
                        '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 26 26" style="enable-background:new 0 0 26 26;" xml:space="preserve">',
                            '<g><g><path fill="#CB2228" d="M13.3,0C7.8,0,3.4,4.5,3.4,9.9c0,2.3,0.8,4.5,2.1,6.2l7.8,9.8l7.8-9.8c1.3-1.7,2.1-3.8,2.1-6.2 C23.3,4.5,18.8,0,13.3,0z M13.3,18.2c-4.4,0-8-3.6-8-8c0-4.4,3.6-8,8-8c4.4,0,8,3.6,8,8C21.3,14.6,17.7,18.2,13.3,18.2z"/></g>',
                            '<circle fill="#B2000D;" cx="13.3" cy="10.2" r="8"/><g><g><g><circle class="st2" cx="13.2" cy="4.9" r="1.8"/></g></g>',
                            '<g><g><path fill="#FFFFFF" d="M15.8,11.5l-0.4-3.1c0-0.5-0.4-0.8-0.9-0.8h-0.5c-0.1,0-0.2,0-0.3,0.1l-0.6,0.6l-0.6-0.6 c-0.1-0.1-0.2-0.1-0.3-0.1h-0.5c-0.5,0-0.8,0.3-0.9,0.8l-0.4,3.1c0,0.1,0,0.3,0.1,0.3c0.1,0.1,0.2,0.2,0.3,0.2h0.5l0.4,4.5 c0,0.5,0.4,0.8,0.9,0.8h1c0.5,0,0.8-0.3,0.9-0.8l0.4-4.5h0.5c0.1,0,0.2-0.1,0.3-0.2C15.8,11.7,15.8,11.6,15.8,11.5z"/></g></g></g></g>',
                        '</svg>'
                ].join('\n');
                return {
                    fillColor: color,
                    fillOpacity: 1,
                    strokeColor: '#000',
                    strokeWeight: 2,
                    scale: 1,
                    icon: {url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(template), scaledSize: new google.maps.Size(20, 20)}
                };
            },
            setMarkers() { 

                let latitude = this.ubicaciones.length > 0 ? this.ubicaciones[0].latitude : -6.7718781;
                let longitude = this.ubicaciones.length > 0 ? this.ubicaciones[0].longitude : -79.8385601;

                if (!this.mapAll) {

                    this.mapAll = L.map('mapAll', {editable: true}).setView([latitude, longitude], 15);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        maxZoom: 30,
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox.streets'
                    }).addTo(this.mapAll);

                }

                this.clearMap(this.mapAll);

                this.markers = [];

                this.ubicaciones.map( data =>{                        

                        let latitude = data.latitude ? data.latitude : -6.7718781;
                        let longitude = data.longitude ? data.longitude : -79.8385601;

                        let iconcolor = new L.Icon({
                                                  iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                                  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                                  iconSize: [25, 41],
                                                  iconAnchor: [12, 41],
                                                  popupAnchor: [1, -34],
                                                  shadowSize: [41, 41]
                                                });                      
                        
                        let marker = L.marker([latitude, longitude], {icon: iconcolor}, {draggable: false}).addTo(this.mapAll);

                        
                        marker.on('click', (event) => {
                                this.familiar.nombres = data.nombres;
                                this.familiar.telefono = data.telefono;
                                this.familiar.fecha = data.fecha;
                                this.familiar.descripcion = data.descripcion;
                                $('#modalFamiliar').modal('show');                            
                        });

                        this.markers.push(marker);
                    });

                // Centramos el mapa para la ciudad de cix
                let new_center = new L.LatLng("-6.7718781", "-79.8385601");
                this.mapAll.setView(new_center, 15, {animation: true});                

                       
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
            },
        }
    }
</script>

