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
                        :language="languages['es']" :format="'dd/MM/yyyy'" @input="searchFilter()"></datepicker>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div style="width: 100%; height: 500px;" id="mapAll">
                        <p v-if="this.ubicaciones.length == 0">No existen ubicaciones el día de hoy</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalFamiliar" tabindex="-1" role="dialog" aria-labelledby="modalFamiliar" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFamiliarLabel">Familiar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-3">Nombres</dt>
                            <dd class="col-sm-9">{{ familiar.nombres }}</dd>
                            
                            <dt class="col-sm-3">Teléfono</dt>
                            <dd class="col-sm-9">{{ familiar.telefono }}</dd>
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
                    telefono: ""
                }
            }
        },
        mounted() {
            this.infoWindow = new google.maps.InfoWindow({
                content: ''
            });

            this.getUbicaciones();
            // this.showMapAll();
            $('#mute').removeClass('on');
        },
        methods: {
            searchFilter() {
                // this.fecha_inicio = moment(this.fecha_inicio).format("DD/MM/YYYY");
                // this.fecha_final = moment(this.fecha_final).format("DD/MM/YYYY");
                this.date_inicio = moment(this.fecha_inicio).format("DD/MM/YYYY");
                this.date_final = moment(this.fecha_final).format("DD/MM/YYYY");
                console.log("fecha inicio ", this.date_inicio, "fecha final ", this.date_final);
                this.getUbicaciones();
            },
            getUbicaciones() {
                axios.get(route("ubicacion.all", {fecha_inicio: this.date_inicio, fecha_final: this.date_final})).then((response) => {
                    console.log("response ubicaciones ", response);
                    this.ubicaciones = response.data;
                    // console.log("ubicaciones getUbicaciones ", this.ubicaciones);
                    this.setMarkers();
                });
            },
            // showMapAll() {
            //     this.mapAll = new GMaps({
            //         div: "#mapAll",
            //         zoom: 15,
            //         lat: this.ubicaciones[0].latitude ? this.ubicaciones[0].latitude : -6.7718781,
            //         lng: this.ubicaciones[0].longitude ? this.ubicaciones[0].longitude : -79.8385601
            //     });

            //     this.setMarkers();
            // },
            pinSymbol(color) {
                var template = [
                    '<?xml version="1.0" encoding="utf-8"?>',
                        '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 26 26" style="enable-background:new 0 0 26 26;" xml:space="preserve">',
                            '<g><g><path fill="#CB2228" d="M13.3,0C7.8,0,3.4,4.5,3.4,9.9c0,2.3,0.8,4.5,2.1,6.2l7.8,9.8l7.8-9.8c1.3-1.7,2.1-3.8,2.1-6.2 C23.3,4.5,18.8,0,13.3,0z M13.3,18.2c-4.4,0-8-3.6-8-8c0-4.4,3.6-8,8-8c4.4,0,8,3.6,8,8C21.3,14.6,17.7,18.2,13.3,18.2z"/></g>',
                            '<circle fill="#B2000D;" cx="13.3" cy="10.2" r="8"/><g><g><g><circle class="st2" cx="13.2" cy="4.9" r="1.8"/></g></g>',
                            '<g><g><path fill="#FFFFFF" d="M15.8,11.5l-0.4-3.1c0-0.5-0.4-0.8-0.9-0.8h-0.5c-0.1,0-0.2,0-0.3,0.1l-0.6,0.6l-0.6-0.6 c-0.1-0.1-0.2-0.1-0.3-0.1h-0.5c-0.5,0-0.8,0.3-0.9,0.8l-0.4,3.1c0,0.1,0,0.3,0.1,0.3c0.1,0.1,0.2,0.2,0.3,0.2h0.5l0.4,4.5 c0,0.5,0.4,0.8,0.9,0.8h1c0.5,0,0.8-0.3,0.9-0.8l0.4-4.5h0.5c0.1,0,0.2-0.1,0.3-0.2C15.8,11.7,15.8,11.6,15.8,11.5z"/></g></g></g></g>',
                        '</svg>'
                ].join('\n');
                // var svg = template.replace('{{ color }}', "#CB2228");
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
                this.mapAll = new GMaps({
                    div: "#mapAll",
                    zoom: 15,
                    lat: this.ubicaciones.length > 0 ? this.ubicaciones[0].latitude : -6.7718781,
                    lng: this.ubicaciones.length > 0 ? this.ubicaciones[0].longitude : -79.8385601
                });

                if(this.mapAll) {
                    // console.log("aa");
                    this.markers.map(marker => {
                        marker.setMap(null);
                    });

                    this.markers = [];

                    this.ubicaciones.map(data => {
                        let marker = this.mapAll.addMarker({
                            lat: data.latitude ? data.latitude : -12.043333,
                            lng: data.longitude ? data.longitude : -77.028333,
                            draggable: false,
                            animation: google.maps.Animation.DROP,
                            icon: this.pinSymbol("#CB2228")
                        });

                        marker.addListener('click', () => {
                            // console.log("detail ubicación");

                            if (document.exitFullscreen) {
                                document.exitFullscreen();
                            } else if (document.mozCancelFullScreen) {
                                document.mozCancelFullScreen();
                            } else if (document.webkitCancelFullScreen) {
                                document.webkitCancelFullScreen();
                            }

                            this.familiar.nombres = data.nombres;
                            this.familiar.telefono = data.telefono;
                            $('#modalFamiliar').modal('show');
                        });

                        this.markers.push(marker);
                    })
                }               
            }
        }
    }
</script>

