<template>
  <div>
    <div class="row col-md-12">
      <div class="col-sm-3">
          <label for="fecha">Fecha</label>
          <datepicker v-model="fecha" :input-class="'form-control'" id="fecha"
          :language="languages['es']" :format="'dd/MM/yyyy'" @input="searchFilter()" placeholder="Seleccionar Fecha" 
          ></datepicker>
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
      <div class="col-sm-3">
        <a style="color:white" target="_blank" :href="getExportUrl()" class="btn disenio-boton-accion">
          <i class="fa fa-cloud-upload" aria-hidden="true"></i>Exportar a Excel
        </a>
      </div>
    </div>
        <div class="col-sm-12 table-responsive">
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
                            Estado
                        </th>
                        <th>
                            Opciones
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
                            {{incidente.fecha}}
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
                        <td >
                            <span class="badge badge-secondary" :style="'background: ' + incidente.estado_incidente.color">{{incidente.estado_incidente.descripcion}}</span>
                        </td>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#mapModal" class="btn btn-primary"
                            v-on:click="setMap(incidente)">
                                Ubicación
                            </a>
                            <a :href="getEditUrl(incidente.id)" class="btn btn-primary" >
                                Detalle
                            </a>
                            <a href="#" class="btn btn-primary" v-on:click="setMap(incidente)" data-toggle="modal"
                            data-target="#modalState">
                                Estado
                            </a>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalCoordinacion"
                            v-on:click="setMap(incidente)">
                                Registrar Coordinación
                            </a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <td colspan="7">
                        <ul class="paginate-links incidentes pagination">
                            <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                            </li>
                        </ul>
                    </td>
                </tfoot>

              <!-- <paginate name="atencion" :list="incidentes" :per="5" tag="tbody">
                <tr v-for="(incidente, index) in paginated('atencion')">
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
                    <td >
                        <span class="badge badge-secondary" :style="'background: ' + incidente.estado_incidente.color">{{incidente.estado_incidente.descripcion}}</span>
                    </td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#mapModal" class="btn btn-primary"
                           v-on:click="setMap(incidente)">
                            Ubicación
                        </a>
                        <a :href="getEditUrl(incidente.id)" class="btn btn-primary" >
                            Detalle
                        </a>
                        <a href="#" class="btn btn-primary" v-on:click="setMap(incidente)" data-toggle="modal"
                           data-target="#modalState">
                            Estado
                        </a>
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalCoordinacion"
                           v-on:click="setMap(incidente)">
                            Registrar Coordinación
                        </a>
                    </td>
                </tr>
              </paginate> -->

              <!-- <paginate-links for="atencion"
                :hide-single-page="true"
                :classes="{
                  'ul': 'pagination',
                  'li': 'page-item',
                  'a':  'page-link'
                }"
              ></paginate-links> -->

            </table>
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
                    <div class="modal-body" style="height: 350px; overflow-y: auto;">
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
                                                    <i class="fa fa-circle" :style="'color:'+ polyline.color"></i>
                                                </a>
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea name="line_descripcion" id="line_descripcion" rows="1" v-model="polyline.descripcion"
                                                          class="form-control"></textarea>
                                            </div>
                                            <div class="col-sm-1">
                                                <a href="#" class="text-danger float-right"
                                                   v-on:click="removeLine(polyline, index)" alt="Eliminar Línea" title="Eliminar Línea">
                                                    <i class="fa fa-times"></i>
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

    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import * as lang from "vuejs-datepicker/src/locale";
    import * as moment from 'moment';
    import VuePaginate from 'vue-paginate';
    Vue.use(VuePaginate);
    export default {
        components: {
            Datepicker
        },
        data() {
            return {
                languages: lang,
                fecha: "",
                fechaconsulta:'',
                estado_incidente_id:'',
                incidentes: [],
                paginate: ['atencion'],
                map: null,
                marker: null,
                points: [],
                selectedIncidente: {},
                polgygon: null,
                polylines: [],
                loading: false,
                estadosIncidentes: [],
                coordinacions: [],
                errors: [],
                coordinacion_id: '',
                atencion_descripcion: '',
                infoWindow: null,
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
                offset: 5
            }
        },
        mounted() {
            axios.get(route('estado-incidente.list')).then((response) => {
                this.estadosIncidentes = response.data.estadosIncidentes;
            });

            axios.get(route('coordinacion.list')).then((response) => {
                this.coordinacions = response.data.coordinacions;
            });

            this.getResultadosEnSession();

            this.infoWindow = new google.maps.InfoWindow({
                content: ''
            });

            $('#mapModal').on('shown.bs.modal', () => {

                if (this.polygon) {
                    this.polygon.remove;
                }

                this.polylines = [];
                this.points = [];

                let latitude = this.selectedIncidente.latitud ? this.selectedIncidente.latitud: -6.7718781;
                let longitude = this.selectedIncidente.longitud ? this.selectedIncidente.longitud:-79.8385601;

                if (!this.map) {
                    this.map = L.map('map', {editable: true}).setView([latitude, longitude], 17);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        maxZoom: 17,
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox.streets'
                    }).addTo(this.map);
                }
                else{
                    this.clearMap(this.map);
                }

                this.marker = L.marker([latitude, longitude], {draggable: true}).addTo(this.map);

                this.setMapArea();

                this.marker.on('dragend', (event)=>{
                    let coordinates = event.target.getLatLng();
                    let isWithinPolygon = inside([coordinates.lat,coordinates.lng],this.points);
                    
                    if (isWithinPolygon) {
                        let new_center = new L.LatLng(coordinates.lat,coordinates.lng);
                        this.map.setView(new_center,15,{animation: true});
                    }else{
                        let new_center = new L.LatLng(latitude,longitude);
                        this.map.setView(new_center,15,{animation: true});
                        this.marker.setLatLng(new_center);

                        coordinates.lat = latitude;
                        coordinates.lng = longitude;
                    }
                });

                for (let polyline of this.selectedIncidente.polylines) {
                    let color = 'blue';
                    let path = polyline.coordinates;                    
                    let g_polyline = L.polyline(path, {color: color}).addTo(this.map);
                    g_polyline.enableEdit();
                    let new_data = {color: color, polyline: g_polyline, descripcion: polyline.descripcion};
                    
                    g_polyline.on('click', (event) => {
                        L.popup()
                            .setLatLng(event.latlng)
                            .setContent(polyline.descripcion)
                            .openOn(this.map);
                    });                    
                    this.polylines.push(new_data);
                }
            });
          $('#mute').removeClass('on');
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
        methods: {
            getExportUrl() {
              return route('incidente.exportarAtenciones', {fecha:this.fechaconsulta,estadoIncidente: this.estado_incidente_id});
            },
            searchFilter() {
                this.fechaconsulta = moment(this.fecha).format('DD/MM/YYYY');
                if(this.fechaconsulta == 'Invalid date')
                {
                    this.fecha = '';
                    this.fechaconsulta = '';
                }
                this.getIncidentes();
            },
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
                axios.get(route('incidente.attentions', {date: this.fechaconsulta, estado: this.estado_incidente_id, "page": page})).then((response) => {
                    this.incidentes = response.data.incidentes.data;
                    this.pagination = response.data.pagination;
                });
            },
            getEditUrl(id) {
              return route('incidente.detalleatencion', {incidente: id,pagina:this.paginaActual});
            },
            setMap(row) {
                this.coordinacion_id = '';
                this.atencion_descripcion = '';
                this.selectedIncidente = row;
            },
            setMapArea() {
                if (this.polygon) {
                    this.polygon.remove;
                }

                this.polygon = L.polygon(this.selectedIncidente.urbanizacion.coordenadas, {
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
                        this.selectedIncidente.urbanizacion.coordenadas[0], this.selectedIncidente.urbanizacion.coordenadas[1]
                ];

                let color = this.getRandomColor();

                let polyline = L.polyline(path, {color: color}).addTo(this.map);
                polyline.enableEdit();
                let new_data = {color: color, polyline: polyline, descripcion: ''};
                
                polyline.on('click', (event) => {                                
                    L.popup()
                    .setLatLng(event.latlng)
                    .setContent(polyline.descripcion)
                    .openOn(this.map);
                });

                this.polylines.push(new_data);
            },
            updateIncidente() {
                let polylines = [];

                for (let polyline of this.polylines) {
                    let len = polyline.polyline.getLatLngs().length;
                    let i = 1;
                    let coordinates = "";                    
                    polyline.polyline.getLatLngs().forEach(function (latLng){
                        if (i == len) {
                            coordinates += latLng.lat + "," + latLng.lng;
                        } else {
                            coordinates += latLng.lat + "," + latLng.lng + ";";
                        }
                        i += 1;
                    });

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
                polyline.polyline.remove();
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
            /*createInfoWindow(poly) {
                poly.polyline.addListener('click', (event) => {
                    // infowindow.content = content;
                    this.infoWindow.setContent(poly.descripcion);
                    // infowindow.position = event.latLng;
                    this.infoWindow .setPosition(event.latLng);
                    this.infoWindow .open(this.map.map);
                });
            },*/
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page; 
                this.getIncidentes(page);
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
        },
        //  beforeMount(){
        //   this.cambiarPagina(6);
        // },
    }
</script>