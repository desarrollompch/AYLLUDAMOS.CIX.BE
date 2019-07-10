<template>
    <div class="card-body">
    <div class="row">
      <div class="col-sm-12">
        <label>Descripcion</label>
      </div>
      <div class="col-sm-5">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Ingrese una descripción" name="descripcion" v-model="territorioVecinal.descripcion">
            <small id="passwordHelpBlock" class="form-text text-danger" v-if="errors.descripcion" v-for="error in errors.descripcion" >
                {{error}}
            </small>
        </div>
      </div>
      <div class="col-sm-7">
        <div class="form-group">
            <!--<input type="text" class="form-control" placeholder="Ingrese una dirección de referencia" name="address" v-model="address" v-on:change="setMap()"> Se retira el evento onchenge porque los puntos ya no cargan por la dirección--> 
            <input type="text" class="form-control" placeholder="Ingrese una dirección de referencia" name="address" v-model="address">
        </div>
      </div>
      </div>

        <div class="form-group">
            <div id="map" style="height: 300px; width: 100%">

            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['territorioVecinal', 'errors'],
        data() {
            return {
                address: '',
                map:false,
                polygon: {},
                marker: {},
                points:[],
                territoriosVecinales: []
            }
        },
        mounted(){
            axios.get(route('territorio-vecinal.all-sin-paginado')).then((response) => {
                this.territoriosVecinales = response.data.territoriosVecinales;
                // this.territoriosVecinales = response.data.result.data;

                if(this.territoriosVecinales.length > 0 && !this.map)
                {
                    let latitude = this.territoriosVecinales[0].latitude ? this.territoriosVecinales[0].latitude: -6.7718781;
                    let longitude = this.territoriosVecinales[0].longitude ? this.territoriosVecinales[0].longitude:-79.8385601;

                    this.map = L.map('map', {editable: true}).setView([latitude, longitude], 13);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        maxZoom: 18,
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox.streets'
                    }).addTo(this.map);

                    this.territoriosVecinales.map(data =>{
                        if(data.id != this.territorioVecinal.id)
                        {
                            this.setPolygon(data);
                        }

                    });

                    if(this.territorioVecinal.coordenadas)
                    {
                        this.points = this.territorioVecinal.coordenadas;
                    }else{
                        this.points = [
                            [-6.7718781 - 0.001, -79.8385601 - 0.001],
                            [-6.7718781 + 0.001,-79.8385601 - 0.001],
                            [-6.7718781 + 0.001,-79.8385601 + 0.001],
                            [-6.7718781 - 0.001,-79.8385601 + 0.001]
                        ];
                    }

                    this.polygon =  L.polygon(this.points, {color: this.getRandomColor(), weight: 2, interactive: true}).addTo(this.map);
                    this.polygon.enableEdit();
                    this.getPolygonCoords();

                    this.polygon.on('mouseout',  (event)=> {
                        this.getPolygonCoords();
                    });

                    // Centramos el mapa para la ciudad de cix
                    let new_center = new L.LatLng("-6.7718781", "-79.8385601");
                    this.map.setView(new_center, 13, {animation: true});       
                }

            });            

           /* this.marker = this.map.addMarker({
                lat: this.territorioVecinal.latitude ? this.territorioVecinal.latitude: -12.043333,
                lng: this.territorioVecinal.longitude ? this.territorioVecinal.longitude:-77.028333,
                title: 'Lima',
                click: function(e) {
                    alert('You clicked in this marker');
                }
            });*/
            this.setMap();

        },
        methods:{
            setMap(){
                this.polygon.remove();
                this.points = [
                    [-6.7718781 - 0.001, -79.8385601 - 0.001],
                    [-6.7718781 + 0.001,-79.8385601 - 0.001],
                    [-6.7718781 + 0.001,-79.8385601 + 0.001],
                    [-6.7718781 - 0.001,-79.8385601 + 0.001]
                ];

                this.polygon = L.polygon(this.points, {color: this.getRandomColor(), weight: 2, interactive: true}).addTo(this.map);
                this.polygon.enableEdit();
                this.getPolygonCoords();
                this.polygon.on('mouseout',  (event) =>{
                    this.getPolygonCoords();
                });
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


            },
            setPolygon(item){
                item.polygon = {};
                item.color = this.getRandomColor();

                L.polygon(item.coordenadas, {color: item.color, weight: 2, interactive: true}).addTo(this.map);

            },
            getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        }
    }
</script>
