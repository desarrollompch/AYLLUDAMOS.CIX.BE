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
                    <input type="text" class="form-control" placeholder="Ingrese una dirección de referencia" name="address" v-model="address" v-on:change="setMap()">
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
                <div id="map" style="height: 400px">

                </div>
            </div>
            <div class="col-sm-3">
                <br>
                <ul class="list-group">
                    <li class="list-group-item" v-for="(territorioVecinal, index) in territoriosVecinales">
                        <i class="fas fa-circle" :style="'color:'+ territorioVecinal.color"></i>
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
                map:{},
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
                        this.map = new GMaps({
                            div: '#map',
                            zoom: 13,
                            lat: this.territoriosVecinales[0].latitude ? this.territoriosVecinales[0].latitude: -6.7718781,
                            lng: this.territoriosVecinales[0].longitude ? this.territoriosVecinales[0].longitude:-79.8385601
                        });

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

                item.polygon = this.map.drawPolygon({
                    paths: item.coordenadas,
                    strokeColor: this.getRandomColor(),
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    fillColor: item.color,
                    fillOpacity: 0.6
                });

            },
            closeRegister(){
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
            },
            newTerritorioVecinal() {
                this.loading = true;
                axios.post(route('territorio-vecinal.store'), this.territorioVecinal).then((response) => {
                    if (response.data.success) {
                        this.getTerritoriosVecinales();
                        this.showRegister = false;
                        this.territorioVecinal = {};
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
                GMaps.geocode({
                    address: this.address,
                    callback: (results, status) => {
                        if (status == 'OK') {
                            let latlng = results[0].geometry.location;
                            this.territorioVecinal.latitude = latlng.lat();
                            this.territorioVecinal.longitude = latlng.lng();
                            this.map.setCenter(latlng.lat(), latlng.lng());
                            //this.marker.setPosition(latlng);
                            this.points = [
                                [Number(latlng.lat()) - 0.001, Number(latlng.lng()) - 0.001],
                                [Number(latlng.lat()) + 0.001,Number(latlng.lng()) - 0.001],
                                [Number(latlng.lat()) + 0.001,Number(latlng.lng()) + 0.001],
                            ];

                            if(this.polygon)
                            {
                                this.polygon.setMap(null);
                            }

                            this.polygon = this.map.drawPolygon({
                                paths: this.points,
                                editable: true,
                                strokeColor: '#e92b34',
                                strokeOpacity: 1,
                                strokeWeight: 3,
                                fillColor: '#2242da',
                                fillOpacity: 0.6
                            });

                            google.maps.event.addListener(this.polygon.getPath(), "insert_at", ()=>{
                                this.getPolygonCoords();
                            });
                            google.maps.event.addListener(this.polygon.getPath(), "set_at", ()=>{
                                this.getPolygonCoords();
                            });

                            this.getPolygonCoords();

                        }
                    }
                });
            },
            getPolygonCoords(){
                let len = this.polygon.getPath().getLength();
                let coordinates = "";

                for (var i = 0; i < len; i++) {
                    if(i == (len - 1))
                    {
                        coordinates += this.polygon.getPath().getAt(i).toUrlValue(5);
                    }else{
                        coordinates += this.polygon.getPath().getAt(i).toUrlValue(5) + ";";
                    }
                }

                this.territorioVecinal.coordenadas = coordinates;
            }
        }
    }
</script>