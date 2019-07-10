<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <div class="row">
                <div class="col-sm-3">
                    <label for="fecha">
                        Fecha
                    </label>
                    <datepicker v-model="fecha" :input-class="'form-control'" id="fecha"
                                :language="languages['es']" :format="'dd/MM/yyyy'" @input="searchFilter()"
                                :typeable="true"
                    ></datepicker>
                </div>
                <div class="col-sm-3">
                    <label for="territorio_vecinal_id">Territorios Vecinales</label>
                    <select name="territorio_vecinal_id" id="territorio_vecinal_id" class="form-control"
                            v-model="territorio_vecinal_id" v-on:change="searchFilter()">
                        <option value="">Selecione..</option>
                        <option v-for="territorioVecinal in territoriosVecinales" :value="territorioVecinal.id">
                            {{territorioVecinal.descripcion}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="urbanizacion_id">Urbanización</label>
                    <select name="urbanizacion_id" id="urbanizacion_id" class="form-control"
                            v-model="urbanizacion_id" v-on:change="searchFilter()">
                        <option value="">Selecione..</option>
                        <option v-for="urbanizacion in urbanizaciones" :value="urbanizacion.id">
                            {{urbanizacion.descripcion}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="estado_incidente_s_id">Estado</label>
                    <select name="estado_incidente_id" id="estado_incidente_s_id" class="form-control"
                            v-model="estado_incidente_id" v-on:change="searchFilter()">
                        <option value="">Selecione..</option>
                        <option v-for="estadoIncidente in estadosIncidentes" :value="estadoIncidente.id">
                            {{estadoIncidente.descripcion}}
                        </option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a :href="getExportUrl()" class="btn disenio-boton-accion">
                       <i class="fa fa-cloud-upload" aria-hidden="true"></i>Exportar a Excel
                    </a>

                    <a href="#" class="btn disenio-boton-accion" v-on:click="showMapAll()" v-if="!showMap">
                       <i class="fa fa-map fa-lg" aria-hidden="true"></i>Ver Mapa
                    </a>

                    <a href="#" class="btn disenio-boton-accion" v-on:click="showMapAll()" v-else="showMap">
                       Ver Todo
                    </a>

                </div>
            </div>
            <br>
            <div v-if="!showMap">
                <table class="table table bordered">
                    <thead>
                        <tr>
                            <th class="number-col">#</th>
                            <th>
                                Fecha
                            </th>
                            <th>
                                Territorio Vecinal
                            </th>
                            <th>
                                Urbanización
                            </th>
                            <th>
                                Descripción
                            </th>
                            <th>
                                Estado de atención
                            </th>
                            <th>

                            </th>
                        </tr>
                    </thead>
                    <tbody v-if="incidentes.length == 0">
                        <tr>
                            <td colspan="7">No existen incidentes registrados</td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr v-for="(incidente, index) in incidentes" :key="index">
                            <td>
                                {{parseInt(index) + 1}}
                            </td>
                            <td>
                                {{incidente.hora}}
                            </td>
                            <td>
                                {{incidente.urbanizacion.territorio_vecinal.descripcion}}
                            </td>
                            <td>
                                {{incidente.urbanizacion.descripcion}}
                            </td>
                            <td>
                                {{incidente.descripcion}}
                            </td>
                            <td>
                                <span class="badge badge-secondary" :style="'background: ' + incidente.estado_incidente.color">{{incidente.estado_incidente.descripcion}}</span>
                            </td>
                            <td>
                                <a :href="getEditUrl(incidente.id)" class="btn disenio-boton-accion" >
                                    <i class="fa fa-asterisk fa-lg" aria-hidden="true"></i>Detalle
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <td colspan="3">
                            <ul class="paginate-links incidentes pagination">
                                <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                    <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                                </li>
                            </ul>
                        </td>
                    </tfoot>

                <!-- <paginate name="incidente" :list="incidentes" :per="5" tag="tbody">
                    <tr v-for="(incidente, index) in paginated('incidente')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{incidente.hora}}
                        </td>
                        <td>
                            {{incidente.urbanizacion.territorio_vecinal.descripcion}}
                        </td>
                        <td>
                            {{incidente.urbanizacion.descripcion}}
                        </td>
                        <td>
                            {{incidente.descripcion}}
                        </td>
                        <td>
                            <span class="badge badge-secondary" :style="'background: ' + incidente.estado_incidente.color">{{incidente.estado_incidente.descripcion}}</span>
                        </td>
                        <td>
                            <a :href="getEditUrl(incidente.id)" class="btn disenio-boton-accion" >
                                <i class="fa fa-asterisk fa-lg" aria-hidden="true"></i>Detalle
                            </a>
                        </td>
                    </tr>
                </paginate>                       -->

                <!-- <tfoot>
                        <td colspan="7">
                            <paginate-links for="incidente"
                            :hide-single-page="true"
                            :classes="{
                            'ul': 'pagination',
                            'li': 'page-item',
                            'a':  'page-link'
                            }"
                        ></paginate-links>
                        </td>
                    </tfoot> -->
                    
                </table>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div style="width: 100%; height: 300px; display: none" id="mapAll">

                    </div>
                    <div style="display: none;" id="leyendaMap">
                        <ul class="items-ul">
                            <li v-for="(estadoIncidente, index) in estadosIncidentes" :key="index" class="flotar-izquierda">
                                <span>{{ estadoIncidente.descripcion }}</span> <input type="color" style="width: 30px; height: 30px; border: none;" :value="estadoIncidente.color" disabled />
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Mapa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="map" style="height: 250px; width: 100%">

                                </div>
                            </div>
                            <div class="col-sm-12">
                                <br>
                                <br>
                                <a href="#" class="btn btn-success" v-on:click="addPolyline()">Agregar Linea</a>
                                <br>
                                <br>
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="(polyline, index) in polylines">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <a href="#">
                                                    <i class="fas fa-circle" :style="'color:'+ polyline.color"></i>
                                                </a>
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea name="line_descripcion" id="line_descripcion" rows="1" v-model="polyline.descripcion"
                                                          class="form-control"></textarea>
                                            </div>
                                            <div class="col-sm-1">
                                                <a href="#" class="text-danger float-right"
                                                   v-on:click="removeLine(polyline, index)">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" :disabled="loading"
                                v-on:click="updateIncidente()">Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalState" tabindex="-1" role="dialog" aria-labelledby="modalState"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStateLabel">Cambiar de Estado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="estado_incidente_id">Estado</label>
                                    <select name="estado_incidente_id" id="estado_incidente_id" class="form-control"
                                            v-model="selectedIncidente.estado_incidente_id">
                                        <option value="">Selecione..</option>
                                        <option v-for="estadoIncidente in estadosIncidentes"
                                                :value="estadoIncidente.id">
                                            {{estadoIncidente.descripcion}}
                                        </option>
                                    </select>
                                    <small class="form-text text-danger" v-if="errors.estado_incidente_id"
                                           v-for="error in errors.estado_incidente_id">
                                        {{error}}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" :disabled="loading"
                                v-on:click="updateIncidente()">Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCoordinacion" tabindex="-1" role="dialog" aria-labelledby="modalCoordinacion"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCoordinacionLabel">Registrar Coordinaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="estado_incidente_id">Coordinaciones</label>
                                    <select name="coordinacion_id" id="coordinacion_id" class="form-control"
                                            v-model="coordinacion_id">
                                        <option value="">Selecione..</option>
                                        <option v-for="coordinacion in coordinacions" :value="coordinacion.id">
                                            {{coordinacion.descripcion}}
                                        </option>
                                    </select>
                                    <small class="form-text text-danger" v-if="errors.coordinacion_id"
                                           v-for="error in errors.coordinacion_id">
                                        {{error}}
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion" id="descripcion" rows="3" v-model="atencion_descripcion"
                                              class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" :disabled="loading"
                                v-on:click="registrarCoordinacion()">Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalOptions" tabindex="-1" role="dialog" aria-labelledby="modalOptions"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalOptionsLabel">Opciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="selectedIncidente.id">
                        <a href="#" class="btn btn-info" style="margin: 3px" v-on:click="showDetailModal()">
                            Detalle
                        </a>
                        <a href="#" class="btn btn-info" style="margin: 3px" v-on:click="showIncidenteMap()">
                            Ubicación
                        </a>
                        <a href="#" class="btn btn-info" style="margin: 3px" v-on:click="showEstadoModal()">
                            Estado
                        </a>
                        <a href="#" class="btn btn-info" style="margin: 3px" v-on:click="showCoordinacionModal()">
                            Coordinación
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-labelledby="modalDetails"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="overflow-y: initial !important">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailsLabel">Detalles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="height: 350px;
    overflow-y: auto;">
                        <dl class="row">
                            <dt class="col-sm-3">Fecha</dt>
                            <dd class="col-sm-9">{{selectedIncidente.fecha}}</dd>

                            <dt class="col-sm-3">Ciudadano</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.persona">{{selectedIncidente.persona.nombres}}
                                {{selectedIncidente.persona.ape_paterno}} {{selectedIncidente.persona.ape_materno}}
                            </dd>

                            <dt class="col-sm-3">Dirección</dt>
                            <dd class="col-sm-9">{{selectedIncidente.direccion}}</dd>

                            <dt class="col-sm-3">Urbanización</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.urbanizacion">
                                {{selectedIncidente.urbanizacion.descripcion}}
                            </dd>

                            <dt class="col-sm-3">Territorio Vecinal</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.urbanizacion">
                                {{selectedIncidente.urbanizacion.territorio_vecinal.descripcion}}
                            </dd>

                            <dt class="col-sm-3">Descripcion</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.urbanizacion">
                                {{selectedIncidente.descripcion}}
                            </dd>

                            <dt class="col-sm-3">Tipo de Incidente</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.tipo_incidente">
                                {{selectedIncidente.tipo_incidente.descripcion}}
                            </dd>

                            <dt class="col-sm-3" v-if="selectedIncidente.tipo_incidente_id == 1">Nivel de Agua</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.tipo_incidente_id == 1">
                                {{selectedIncidente.inundacion.nivel_agua.descripcion}}
                            </dd>

                            <dt class="col-sm-3" v-if="selectedIncidente.tipo_incidente_id == 2">Tipo de Obstaculo</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.tipo_incidente_id == 2">
                                {{selectedIncidente.calle_obstaculo.tipo_obstaculo.descripcion}}
                            </dd>


                            <dt class="col-sm-3">Estado</dt>
                            <dd class="col-sm-9" v-if="selectedIncidente.estado_incidente">
                                {{selectedIncidente.estado_incidente.descripcion}}
                            </dd>
                        </dl>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="mapDetail" style="width: 100%; height: 300px;">
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
                                    <tr v-for="(row, index) in selectedIncidente.atencion_incidente">
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
                                <img :src="selectedIncidente.src_imagen" alt="incidente" class="img-thumbnail" v-if="selectedIncidente.src_imagen">
                            </div>

                            <div class="col-sm-3" v-for="row in selectedIncidente.incidentesmedia">
                                <img :src="row.incidente_media_url" alt="incidente" class="img-thumbnail">
                            </div>
                        </div>
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
    // import VuePaginate from 'vue-paginate';
    // Vue.use(VuePaginate);

    export default {
        components: {
            Datepicker
        },
        data() {
            return {
                incidentes: [],
                // paginate: ['incidente'],
                map: null,
                marker: null,
                selectedIncidente: {},
                polgygon: null,
                polylines: [],
                loading: false,
                estadosIncidentes: [],
                coordinacions: [],
                errors: [],
                coordinacion_id: '',
                atencion_descripcion: '',
                languages: lang,
                fecha:'',
                territoriosVecinales: [],
                urbanizaciones: [],
                estado_incidente_id:'',
                urbanizacion_id:'',
                territorio_vecinal_id:'',
                mapAll: null,
                showMap: false,
                markers: [],
                infoWindow: null,
                mapDetail:null,
                pagination: {
                    "total": 0,
                    "current_page": 0,
                    "per_page": 0,
                    "last_page": 0,
                    "from": 0,
                    "to": 0
                },
                paginaActual:1,
                pagina: "",
                offset: 5,
            }
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
        },
        mounted() {
            axios.get(route('estado-incidente.all')).then((response) => {
                this.estadosIncidentes = response.data.estadosIncidentes;
            });

            axios.get(route('coordinacion.all')).then((response) => {
                this.coordinacions = response.data.coordinacions;
            });

            axios.get(route('urbanizacion.all-sin-paginado')).then((response) => {
                this.urbanizaciones = response.data.urbanizaciones;
            });

            axios.get(route('territorio-vecinal.all-sin-paginado')).then((response) => {
                this.territoriosVecinales = response.data.territoriosVecinales;
            });

            this.infoWindow = new google.maps.InfoWindow({
                content: ''
            });

            // this.getIncidentes();
            this.getResultadosEnSession();

            $('#mapModal').on('shown.bs.modal', () => {

                this.polylines = [];

                this.map = new GMaps({
                    div: '#map',
                    zoom: 17,
                    lat: this.selectedIncidente.latitud ? this.selectedIncidente.latitud : -6.7718781,
                    lng: this.selectedIncidente.latitud ? this.selectedIncidente.longitud : -79.8385601
                });


                this.marker = this.map.addMarker({
                    lat: this.selectedIncidente.latitud ? this.selectedIncidente.latitud : -6.7718781,
                    lng: this.selectedIncidente.longitud ? this.selectedIncidente.longitud : -79.8385601,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });

                this.setMapArea();

                this.marker.addListener('dragend', (event) => {

                    let isWithinPolygon = google.maps.geometry.poly.containsLocation(event.latLng, this.polygon);

                    if (isWithinPolygon) {
                        this.map.setCenter(this.marker.getPosition().lat(), this.marker.getPosition().lng());
                        let myLatlng = new google.maps.LatLng(this.map.getCenter().lat(), this.map.getCenter().lng());
                        this.marker.setPosition(myLatlng);
                    } else {
                        let myLatlng = new google.maps.LatLng(this.selectedIncidente.urbanizacion.latitude, this.selectedIncidente.urbanizacion.longitude);
                        this.map.setCenter(this.selectedIncidente.urbanizacion.latitude, this.selectedIncidente.urbanizacion.longitude);
                        this.marker.setPosition(myLatlng);
                    }

                    this.selectedIncidente.latitud = this.marker.getPosition().lat();
                    this.selectedIncidente.longitud = this.marker.getPosition().lng();

                });

                for (let polyline of this.selectedIncidente.polylines) {
                    let g_polyline = this.map.drawPolyline({
                        path: polyline.coordinates,
                        strokeColor: 'blue',
                        strokeOpacity: 1,
                        strokeWeight: 6,
                        editable: true
                    });

                    let new_data = {color: 'blue', polyline: g_polyline, descripcion: polyline.descripcion};

                    this.createInfoWindow(new_data);

                    this.polylines.push(new_data);

                }
            });

            $('#modalDetails').on('shown.bs.modal', () => {

                this.mapDetail = new GMaps({
                    div: '#mapDetail',
                    zoom: 17,
                    lat: this.selectedIncidente.latitud ? this.selectedIncidente.latitud : -6.7718781,
                    lng: this.selectedIncidente.latitud ? this.selectedIncidente.longitud : -77.028333
                });

                this.marker = this.mapDetail.addMarker({
                    lat: this.selectedIncidente.latitud ? this.selectedIncidente.latitud : -6.7718781,
                    lng: this.selectedIncidente.longitud ? this.selectedIncidente.longitud : -79.8385601,
                    animation: google.maps.Animation.DROP
                });

                this.polygon = this.mapDetail.drawPolygon({
                    paths: this.selectedIncidente.urbanizacion.coordenadas,
                    strokeColor: '#e9e513',
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    fillColor: '#da151b',
                    fillOpacity: 0.6
                });

                for (let polyline of this.selectedIncidente.polylines) {
                    let g_polyline = this.mapDetail.drawPolyline({
                        path: polyline.coordinates,
                        strokeColor: 'blue',
                        strokeOpacity: 1,
                        strokeWeight: 6,
                        editable: true
                    });

                    let new_data = {color: 'blue', polyline: g_polyline, descripcion: polyline.descripcion};

                    g_polyline.addListener('click', (event) => {
                        // infowindow.content = content;
                        this.infoWindow.setContent(polyline.descripcion);
                        // infowindow.position = event.latLng;
                        this.infoWindow .setPosition(event.latLng);
                        this.infoWindow .open(this.mapDetail.map);
                    });
                    this.polylines.push(new_data);
                }
            });
            // this.getResultadosEnSession();
            //this.getIncidentes();
          
        },
        methods: {
            getResultadosEnSession(){
              axios.get(route('incidente.getPageSession')).then((response) => {
                  if (response.data.success){
                    this.getIncidentes(response.data.paginaActual);
                  } else {
                    alert(response.data.message);
                  }
              }).catch((error) => {
                  console.log(error.data);
              });
            },
            deleteTipoPersona(incidente) {
                let r = confirm('Estas seguro de eliminar este registro');

                if (r === true) {
                    axios.delete(route('incidente.destroy', {id: incidente.id})).then((response) => {
                        if (response.data.success) {
                            this.getIncidentes();
                        } else {
                            alert(response.data.message);
                        }
                    }).catch((error) => {
                        console.log(error.data);
                    });
                }
            },
            getIncidentes(page) {
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('incidente.all', {date:this.fecha,urbanizacion: this.urbanizacion_id, estado: this.estado_incidente_id, territorio: this.territorio_vecinal_id, "page": page})).then((response) => {
                    this.incidentes = response.data.incidentes.data;
                    this.pagination = response.data.pagination;
                    this.setMarkers();
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id) {
                return route('incidente.detalle', {incidente: id,pagina:this.paginaActual});
            },
            getExportUrl() {
                return route('incidente.export', {date:this.fecha,urbanizacion: this.urbanizacion_id, estado: this.estado_incidente_id, territorio: this.territorio_vecinal_id});
            },
            setMap(row) {
                this.coordinacion_id = '';
                this.atencion_descripcion = '';
                this.selectedIncidente = row;
            },
            setMapArea() {
                if (this.polygon) {
                    this.polygon.setMap(null);
                }

                this.polygon = this.map.drawPolygon({
                    paths: this.selectedIncidente.urbanizacion.coordenadas,
                    strokeColor: '#e9e513',
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    fillColor: '#da151b',
                    fillOpacity: 0.6
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
                    this.selectedIncidente.urbanizacion.coordenadas[0],
                    this.selectedIncidente.urbanizacion.coordenadas[1]
                ];

                let polyline = this.map.drawPolyline({
                    path: path,
                    strokeColor: 'blue',
                    strokeOpacity: 1,
                    strokeWeight: 6,
                    editable: true
                });

                let new_data = {color: 'blue', polyline: polyline, descripcion: ''};

                this.createInfoWindow(new_data);

                this.polylines.push(new_data);
            },
            updateIncidente() {
                let polylines = [];

                for (let polyline of this.polylines) {
                    let len = polyline.polyline.getPath().getLength();
                    let coordinates = "";
                    for (var i = 0; i < len; i++) {
                        if (i == (len - 1)) {
                            coordinates += polyline.polyline.getPath().getAt(i).toUrlValue(5);
                        } else {
                            coordinates += polyline.polyline.getPath().getAt(i).toUrlValue(5) + ";";
                        }
                    }

                    polylines.push({coordinates:coordinates, descripcion:polyline.descripcion});
                }

                this.selectedIncidente.polylines = polylines;

                this.loading = true;
                axios.put(route('incidente.update', {id: this.selectedIncidente.id}), this.selectedIncidente).then((response) => {
                    $('#mapModal').modal('hide');
                    $('#modalState').modal('hide');
                    this.getIncidentes();
                    this.loading = false;
                }).catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                    this.loading = false;
                })
            },
            removeLine(polyline, index) {
                polyline.polyline.setMap(null);
                this.polylines.splice(index, 1);
            },
            registrarCoordinacion() {
                this.loading = true;
                axios.post(route('incidente.registrar-coordinacion', {id: this.selectedIncidente.id}),
                    {
                        coordinacion_id: this.coordinacion_id,
                        descripcion: this.atencion_descripcion
                    }
                ).then((response) => {
                    $('#modalCoordinacion').modal('hide');
                    this.loading = false;
                }).catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                    this.loading = false;
                })
            },
            searchFilter(){
                this.fecha = moment(this.fecha).format('DD/MM/YYYY');
                if(this.fecha == 'Invalid date')
                {
                    this.fecha = '';
                }
                this.getIncidentes();
            },
            showMapAll(){

                if(!this.showMap)
                {
                    $('#mapAll').show();
                    this.mapAll = new GMaps({
                        div: '#mapAll',
                        zoom: 15,
                        lat: this.incidentes[0].latitud ? this.incidentes[0].latitud : -6.7718781,
                        lng: this.incidentes[0].latitud ? this.incidentes[0].longitud : -79.8385601
                    });

                    this.setMarkers();

                    this.setPolylines();

                    $('#leyendaMap').show();

                }else{
                    $('#mapAll').hide();
                    $('#leyendaMap').hide();
                }


                this.showMap = !this.showMap;
            },
            pinSymbol(color) {
                return {
                    path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
                    fillColor: color,
                    fillOpacity: 1,
                    strokeColor: '#000',
                    strokeWeight: 2,
                    scale: 1,
                };
            },
            setMarkers(){
                if(this.mapAll)
                {
                    console.log("aa");
                    this.markers.map(marker =>{
                       marker.setMap(null);
                    });

                    this.markers = [];

                    this.incidentes.map( data =>{
                        let marker = this.mapAll.addMarker({
                            lat: data.latitud ? data.latitud : -12.043333,
                            lng: data.longitud ? data.longitud : -77.028333,
                            draggable: false,
                            animation: google.maps.Animation.DROP,
                            icon: this.pinSymbol(data.estado_incidente.color)
                        });

                        marker.addListener('click', () => {
                            if(data.estado_incidente_id == 1 || data.estado_incidente_id == 2)
                            {
                                if (document.exitFullscreen) {
                                    document.exitFullscreen();
                                } else if (document.mozCancelFullScreen) {
                                    document.mozCancelFullScreen();
                                } else if (document.webkitCancelFullScreen) {
                                    document.webkitCancelFullScreen();
                                }

                                this.selectedIncidente  = data;
                                $('#modalOptions').modal('show');
                            }
                        });

                        this.markers.push(marker);
                    });
                }else {
                    console.log("IMRPRIMIR ALGO");
                }
            },
            setPolylines(){
                if(this.mapAll)
                {
                    this.polylines.map(polyline =>{
                        polyline.polyline.setMap(null);
                    });

                    this.polylines = [];

                    this.incidentes.map( data =>{

                        for (let polyline of data.polylines) {
                            let g_polyline = this.mapAll.drawPolyline({
                                path: polyline.coordinates,
                                strokeColor: 'blue',
                                strokeOpacity: 1,
                                strokeWeight: 6
                            });

                            let new_data = {color: 'blue', polyline: g_polyline, descripcion: polyline.descripcion};

                            g_polyline.addListener('click', (event) => {
                                // infowindow.content = content;
                                this.infoWindow.setContent(polyline.descripcion);
                                // infowindow.position = event.latLng;
                                this.infoWindow .setPosition(event.latLng);
                                this.infoWindow .open(this.mapAll.map);
                            });

                            this.polylines.push(new_data);
                        }

                    });
                }
            },
            showIncidenteMap(){
                $('#modalOptions').modal('hide');
                $('#mapModal').modal('show');
            },
            createInfoWindow(poly) {
                poly.polyline.addListener('click', (event) => {
                    // infowindow.content = content;
                    this.infoWindow.setContent(poly.descripcion);
                    // infowindow.position = event.latLng;
                    this.infoWindow .setPosition(event.latLng);
                    this.infoWindow .open(this.map.map);
                });
            },
            showEstadoModal(){
                $('#modalOptions').modal('hide');
                $('#modalState').modal('show');
            },
            showCoordinacionModal(){
                $('#modalOptions').modal('hide');
                $('#modalCoordinacion').modal('show');
            },
            showDetailModal(){
                $('#modalOptions').modal('hide');
                $('#modalDetails').modal('show');
            },
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page;
                this.getIncidentes(page);
            },
        }
    }
</script>