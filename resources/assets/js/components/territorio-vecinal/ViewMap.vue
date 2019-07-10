<template>
    <div>
        <div class="row" v-if="!showRegister">
            <div class="col-sm-12">
                <a href="#" class="btn disenio-boton-accion float-right" v-on:click="openRegister()">
                    <i class="fa fa-plus fa-lg" aria-hidden="true"></i>Registrar
                </a>
            </div>
        </div>
        <br>
        <div class="row" v-if="showRegister">
            <div class="row col-sm-12">
              <div class="col-sm-12">
                <label>Descripcion</label>
              </div>
              <div class="col-sm-7">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Ingrese una descripción" name="descripcion" v-model="territorioVecinal.descripcion">
                    <small class="form-text text-danger" v-if="errors.descripcion" v-for="error in errors.descripcion" >
                        {{error}}
                    </small>
                </div>
              </div>

              <div class="col-sm-5">
                <div class="form-group">
                    <!--<input type="text" class="form-control" placeholder="Ingrese una dirección de referencia" name="address" v-model="address" v-on:change="setMap()"> Retiramos el evento de onchange-->
                    <input type="text" class="form-control" placeholder="Ingrese una dirección de referencia" name="address" v-model="address">
                </div>
              </div>

            </div>
            <div class="col-sm-12 text-center">
                <button class="btn disenio-boton-accion" type="submit" :disabled="loading" v-on:click="newTerritorioVecinal()">
                    <i class="fa fa-check fa-lg" aria-hidden="true"></i>Guardar
                </button>
                <a class="btn disenio-boton-accion" href="#" v-on:click="closeRegister()">
                     <i class="fa fa-times fa-lg" aria-hidden="true"></i>Cancelar
                </a>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-sm-9 table-responsive">
                <div id="map_l" style="height: 400px">

                </div>
                <div id="map" style="height: 400px">

                </div>
            </div>
            <div class="col-sm-3">
                Listado de territorios vecinales
                <br><br>
                <ul class="list-group">
                    <li class="list-group-item" v-for="(territorioVecinal, index) in territoriosVecinales">
                        <i class="fa fa-circle" :style="'color:'+ territorioVecinal.color"></i>
                        {{territorioVecinal.descripcion}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                loading:false,
                territoriosVecinales: [],
                map:false,
                territorioVecinal:{},
                errors: [],
                address:'',
                showRegister: false,
                polygon:null
            }
        },
        mounted() {
            this.getTerritoriosVecinales();
        },
        methods: {
            getTerritoriosVecinales(){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('territorio-vecinal.all-sin-paginado')).then((response) => {
                    this.territoriosVecinales = response.data.territoriosVecinales;

                    if(this.territoriosVecinales.length > 0)
                    {
                        let latitude = this.territoriosVecinales[0].latitude ? this.territoriosVecinales[0].latitude: -6.7718781;
                        let longitude = this.territoriosVecinales[0].longitude ? this.territoriosVecinales[0].longitude:-79.8385601;

                        if(!this.map)
                        {
                            this.map = L.map('map_l', {editable: true}).setView([latitude, longitude], 13);
                            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                                maxZoom: 18,
                                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                                id: 'mapbox.streets'
                            }).addTo(this.map);

                        }

                        // Centramos el mapa para la ciudad de cix
                        let new_center = new L.LatLng("-6.7718781", "-79.8385601");
                        this.map.setView(new_center, 13, {animation: true}); 

                        this.territoriosVecinales.map(data =>{
                            this.setPolygon(data);
                        })

                    }
                    $('#mute').removeClass('on');
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
            setPolygon(item){
                item.polygon = {};
                item.color = this.getRandomColor();

                L.polygon(item.coordenadas, {color: item.color, weight: 2, interactive: true}).addTo(this.map);

            },
            closeRegister(){
                this.polygon.remove();
                this.showRegister = false;
                this.territorioVecinal = {};
                this.address = '';
                if(this.polygon)
                {
                    this.polygon.setMap(null);
                    this.polygon = null;
                }
            },
            openRegister(){
                this.showRegister = true;
                this.setMap();
            },
            newTerritorioVecinal() {
                this.loading = true;
                this.getPolygonCoords();

                axios.post(route('territorio-vecinal.store'), this.territorioVecinal).then((response) => {
                    if (response.data.success) {
                        this.getTerritoriosVecinales();
                        this.showRegister = false;
                        this.territorioVecinal = {};
                        this.polygon.disableEdit();
                    }

                    this.loading = false;

                }).catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            },
            setMap(){
                this.points = [
                    [-6.7718781 - 0.001, -79.8385601 - 0.001],
                    [-6.7718781 + 0.001,-79.8385601 - 0.001],
                    [-6.7718781 + 0.001,-79.8385601 + 0.001],
                    [-6.7718781 - 0.001,-79.8385601 + 0.001],
                ];

                this.polygon = L.polygon(this.points, {color: this.getRandomColor(), weight: 2, interactive: true}).addTo(this.map);
                this.polygon.enableEdit();

                this.getPolygonCoords();

            },
            getPolygonCoords(){

                let center = this.polygon.getCenter();

                this.territorioVecinal.latitude = center.lat;
                this.territorioVecinal.longitude = center.lng;

                let bounds = this.polygon.getLatLngs();
                let len = bounds[0].length;
                let coordinates = "";

                for (var i = 0; i < len; i++) {
                    if(i == (len - 1))
                    {
                        coordinates += bounds[0][i].lat+','+bounds[0][i].lng;
                    }else{
                        coordinates += bounds[0][i].lat+','+bounds[0][i].lng + ";";
                    }
                }

                this.territorioVecinal.coordenadas = coordinates;
            }
        }
    }
</script>
