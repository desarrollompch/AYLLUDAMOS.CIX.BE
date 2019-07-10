<template>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="fecha">
                        Fecha
                    </label>
                    <datepicker v-model="incidente.fecha" :input-class="'form-control'" id="fecha"
                                style="margin-top: 16px;"
                                :language="languages['es']" :format="'dd/MM/yyyy'"></datepicker>
                    <small class="form-text text-danger" v-if="errors.fecha"
                           v-for="error in errors.fecha">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-3">
                <label for="urbanizacion_id">Urbanización</label>
                <select name="urbanizacion_id" id="urbanizacion_id" class="form-control"
                        style="margin-top: 16px;"
                        v-model="incidente.urbanizacion_id" v-on:change="changeUrbanizacion()">
                    <option value="">Selecione..</option>
                    <option v-for="urbanizacion in urbanizaciones" :value="urbanizacion.id">
                        {{urbanizacion.descripcion}}
                    </option>
                </select>
                <small class="form-text text-danger" v-if="errors.urbanizacion_id"
                       v-for="error in errors.urbanizacion_id">
                    {{error}}
                </small>
            </div>
            <div class="col-sm-2">
                <label for="estado_incidente_id">Estado</label>
                <select name="estado_incidente_id" id="estado_incidente_id" class="form-control"
                        style="margin-top: 16px;"
                        v-model="incidente.estado_incidente_id">
                    <option value="">Selecione..</option>
                    <option v-for="estadoIncidente in estadosIncidentes" :value="estadoIncidente.id">
                        {{estadoIncidente.descripcion}}
                    </option>
                </select>
                <small class="form-text text-danger" v-if="errors.estado_incidente_id"
                       v-for="error in errors.estado_incidente_id">
                    {{error}}
                </small>
            </div>
            <div class="col-sm-5">
                <label>
                    Persona
                </label>
                <search-persona :persona="incidente.persona" @onSelect="setPersonaId"></search-persona>
                <small class="form-text text-danger" v-if="errors.persona_id"
                       v-for="error in errors.persona_id">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="custom-control custom-radio" v-for="(tipoIncidente,index) in tiposIncidentes">
                    <input type="radio" :id="'customRadio'+index" name="tipo_incidente_id" :value="tipoIncidente.id"
                           v-model="incidente.tipo_incidente_id" class="custom-control-input">
                    <label class="custom-control-label" :for="'customRadio'+index">{{tipoIncidente.descripcion}}</label>
                </div>
                <small class="form-text text-danger" v-if="errors.tipo_incidente_id"
                       v-for="error in errors.tipo_incidente_id">
                    {{error}}
                </small>
            </div>
            <div class="col-sm-6">
                <div class="form-group" v-if="incidente.tipo_incidente_id == 1">
                    <vue-slider v-bind="rangeSlider" v-if="nivelesAgua.length > 0"
                                @callback="setIdNivelAgua"></vue-slider>
                </div>
                <div class="form-group" v-if="incidente.tipo_incidente_id == 2">
                    <div class="custom-control custom-radio" v-for="(tipoObstaculo,index) in tiposObstaculos">
                        <input type="radio" :id="'radioObstaculo'+index" name="tipo_obstaculo_id"
                               :value="tipoObstaculo.id"
                               v-model="incidente.calle_obstaculo.tipo_obstaculo_id" class="custom-control-input">
                        <label class="custom-control-label"
                               :for="'radioObstaculo'+index">{{tipoObstaculo.descripcion}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-12">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3" v-model="incidente.descripcion"
                                  class="form-control"></textarea>
                        <small class="form-text text-danger" v-if="errors.descripcion"
                               v-for="error in errors.descripcion">
                            {{error}}
                        </small>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="imagen">Imagen</label>
                            <input type="file" class="form-control" name="imagen" id="imagen" @change="readFile">
                            <small class="form-text text-danger" v-if="errors.imagen" v-for="error in errors.imagen">
                                {{error}}
                            </small>
                        </div>
                        <br>
                        <div class="img-container">
                            <img :src="incidente.src_imagen" alt="icono" class="img-icono" v-if="incidente.src_imagen">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="descripcion">Dirección</label>
                        <input type="text" id="direccion" class="form-control" placeholder="Ingrese una dirección"
                               name="descripcion" v-model="incidente.direccion"> <!--v-on:change="setMarker()"> Esta funcionalidad queda inhabilitada-->
                        <small class="form-text text-danger" v-if="errors.direccion"
                               v-for="error in errors.direccion">
                            {{error}}
                        </small>
                    </div>
                    <div class="col-sm-12">
                        <div id="map" style="height: 300px; width: 100%; margin-top: 10px">

                        </div>
                    </div>
                </div>
                <br>
                <div class="row" v-if="map">
                    <div class="col-sm-6">
                        <a href="javascript:void(0)" class="btn btn-success" v-on:click="addPolyline()"> Agregar
                            Linea</a>
                        <br>
                        <br>
                        <ul class="list-group" style="width:500px">
                            <li class="list-group-item" v-for="(polyline, index) in this.incidente.polylines">    
                                <i class="fa fa-circle" :style="'color:'+ polyline.color"> </i>
                                <span style="padding-left:10px;" :style="'color:'+ polyline.color">Línea de calle bloqueada  {{index + 1}}</span>
                                <a href="javascript:void(0)" class="text-danger float-right"
                                   v-on:click="removeLine(polyline, index)" alt="Eliminar Línea" title="Eliminar Línea">
                                    <i class="fa fa-times" style="color:red" ></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import * as lang from "vuejs-datepicker/src/locale";
    import vueSlider from 'vue-slider-component';

    export default {
        props: ['incidente', 'errors'],
        components: {
            Datepicker,
            vueSlider
        },
        data() {
            return {
                languages: lang,
                tiposIncidentes: [],
                urbanizaciones: [],
                estadosIncidentes: [],
                nivelesAgua: [],
                tiposObstaculos: [],
                rangeSlider: {
                    value: "",
                    disabled: false,
                    piecewise: true,
                    piecewiseLabel: true,
                    data: [],
                    piecewiseStyle: {
                        "backgroundColor": "#ccc",
                        "visibility": "visible",
                        "width": "12px",
                        "height": "12px"
                    },
                    piecewiseActiveStyle: {
                        "backgroundColor": "#3498db"
                    },
                    labelActiveStyle: {
                        "color": "#3498db"
                    }
                },
                polgygon: null,
                map: null,
                marker: null,
                points: [],
                infoWindow: null
            }
        },
        mounted() {

            this.infoWindow = new google.maps.InfoWindow({
                content: ''
            });

            axios.get(route('tipo-incidente.all')).then((response) => {
                this.tiposIncidentes = response.data.tiposIncidentes;

            });

            axios.get(route('estado-incidente.all')).then((response) => {
                this.estadosIncidentes = response.data.estadosIncidentes;

            });

            axios.get(route('urbanizacion.all-sin-paginado')).then((response) => {
                this.urbanizaciones = response.data.urbanizaciones;
            });

            axios.get(route('tipo-obstaculo.all')).then((response) => {
                this.tiposObstaculos = response.data.tiposObstaculos;
            });

            axios.get(route('nivel-agua.all')).then((response) => {
                for (let row of response.data.nivelesAgua) {
                    this.rangeSlider.data.push(row.descripcion);
                    this.incidente.inundacion.nivel_agua_id = response.data.nivelesAgua[0].id;
                }

                this.nivelesAgua = response.data.nivelesAgua;
            });
        },
        methods: {
            changeUrbanizacion() {
                for (let row of this.urbanizaciones) {
                    if (row.id == this.incidente.urbanizacion_id) {
                        this.incidente.urbanizacion = row;
                        this.incidente.latitud = row.latitude;
                        this.incidente.longitud = row.longitude;

                        let coordenadas = row.coordenadas;

                        this.polylines = [];

                        if (this.polygon) {
                            this.polygon.remove();
                        }

                        let latitude = this.incidente.latitud ? this.incidente.latitud : -6.7718781;
                        let longitude = this.incidente.longitud ? this.incidente.longitud : -79.8385601;

                        if (!this.map) {
                            this.map = L.map('map', {editable: true}).setView([latitude, longitude], 15);

                            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                                maxZoom: 30,
                                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                                id: 'mapbox.streets'
                            }).addTo(this.map);

                            this.marker = L.marker([latitude, longitude], {draggable: true}).addTo(this.map);
                        }
                        else{
                            let new_center = new L.LatLng(latitude, longitude);
                            this.map.setView(new_center, 15, {animation: true});
                            this.marker.setLatLng(new_center);
                        }
                            
                        
                        this.setMapArea()

                        this.marker.on('dragend', (event) => {
                            let coordinates = event.target.getLatLng();
                            
                            let isWithinPolygon = inside([coordinates.lat, coordinates.lng], this.points);
                            if (isWithinPolygon) {
                                this.incidente.latitud = coordinates.lat;
                                this.incidente.longitud = coordinates.lng;
                            } else {
                                let new_center = new L.LatLng(this.incidente.latitud, this.incidente.longitud);
                                this.map.setView(new_center, 15, {animation: true});
                                this.marker.setLatLng(new_center);
                            }
                        });
                    }
                }
            },
            setPersonaId(data) {
                this.incidente.persona_id = data;
            },
            readFile(event) {
                if (event.target.files && event.target.files.length > 0) {
                    let file = event.target.files[0];

                    let sizeByte = file.size;
                    let sizekiloBytes = Number(sizeByte) / 1024;

                    if (sizekiloBytes <= 20480) {
                        let reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = () => {
                            this.incidente.imagen_file = {
                                filename: file.name,
                                filetype: file.type,
                                value: reader.result.split(',')[1]
                            };

                            this.incidente.src_imagen = reader.result;

                        };
                    }
                }
            },
            setIdNivelAgua(event) {
                for (let row of this.nivelesAgua) {
                    if (row.descripcion == event) {
                        this.incidente.inundacion.nivel_agua_id = row.id;
                    }
                }
            },
            setMarker() {
                GMaps.geocode({
                    address: this.incidente.direccion,
                    callback: (results, status) => {
                        if (status == 'OK') {
                            let latlng = results[0].geometry.location;
                            let isWithinPolygon = google.maps.geometry.poly.containsLocation(latlng, this.polygon);
                            if (isWithinPolygon) {
                                this.incidente.latitud = latlng.lat();
                                this.incidente.longitud = latlng.lng();
                                this.map.setCenter(latlng.lat(), latlng.lng());
                                this.marker.setPosition(latlng);
                            } else {
                                alert('La dirección ingresada esta fuera del cuadrante de la urbanización, selecionada.');
                            }
                        }
                    }
                });
            },
            getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            },
            addPolyline() {
                let path = [
                        this.incidente.urbanizacion.coordenadas[0], this.incidente.urbanizacion.coordenadas[1]
                ];

                let color = this.getRandomColor();

                let polyline = L.polyline(path, {color: color}).addTo(this.map);
                polyline.enableEdit();
                let new_data = {color: color, polyline: polyline, descripcion: ''};
                this.createInfoWindow(new_data);
                this.incidente.polylines.push(new_data);
            },
            setMapArea() {
                if (this.polygon) {
                    this.polygon.remove;
                }

                this.polygon = L.polygon(this.incidente.urbanizacion.coordenadas, {
                    color: 'red',
                    weight: 2,
                    interactive: true
                }).addTo(this.map);

                let polypoint = [];
                this.polygon.getLatLngs().map(function(point) {
                    for (let po of point) {
                        polypoint.push([po.lat, po.lng]);
                    }                                        
                });

                this.points = polypoint;
            },
            removeLine(polyline, index) {
                polyline.polyline.remove();
                this.incidente.polylines.splice(index, 1);
            },
            createInfoWindow(poly) {
                poly.polyline.on('click', (event) => {

                    //console.log(poly.polyline.getLatLngs());
                    L.popup()
                        .setLatLng(event.latlng)
                        .setContent(poly.descripcion)
                        .openOn(this.map);
                });
            }

        }
    }
</script>
