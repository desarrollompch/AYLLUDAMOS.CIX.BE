<template>
    <div class="card-body">
     <div class="row col-md-12">
        <div class="form-group col-md-4">
            <label for="territorio_vecinal_id">Territorio Vecinal</label>
            <select name="territorio_vecinal_id" id="territorio_vecinal_id" class="form-control"
                    v-model="urbanizacion.territorio_vecinal_id" v-on:change="changeTerritorioId()">
                <option value="">Selecione..</option>
                <option v-for="territorio in territoriosVecinales" :value="territorio.id">
                    {{territorio.descripcion}}
                </option>
            </select>
            <small class="form-text text-danger" v-if="errors.territorio_vecinal_id"
                   v-for="error in errors.territorio_vecinal_id">
                {{error}}
            </small>
        </div>
        <div class="form-group col-md-8">
            <label for="descripcion">Descripcion</label>
            <input type="text" id="descripcion" class="form-control" placeholder="Ingrese una descripción"
                   name="descripcion" v-model="urbanizacion.descripcion">
            <small class="form-text text-danger" v-if="errors.descripcion"
                   v-for="error in errors.descripcion">
                {{error}}
            </small>
        </div>
      </div>
        <div class="form-group">
            <div id="map-l" style="height: 300px; width: 100%">

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['urbanizacion', 'errors'],
        data() {
            return {
                address: '',
                map: {},
                marker: {},
                points: [],
                territoriosVecinales: [],
                territorioVecinal: {},
                urbanizacionPoints:[],
                urbanizacionPolygon:null
            }
        },
        mounted() {

            axios.get(route('territorio-vecinal.all-sin-paginado')).then((response) => {
                this.territoriosVecinales = response.data.territoriosVecinales;
                if (this.urbanizacion.id) {
                    this.setMap();
                }
            });

            if(this.urbanizacion.coordenadas)
            {
                this.urbanizacionPoints = this.urbanizacion.coordenadas;
            }else{
                this.urbanizacionPoints = [
                    [Number(-6.7718781) - 0.001, Number(-79.8385601) - 0.001],
                    [Number(-6.7718781) + 0.001,Number(-79.8385601) - 0.001],
                    [Number(-6.7718781) + 0.001,Number(-79.8385601) + 0.001],
                    [Number(-6.7718781) - 0.001,Number(-79.8385601) + 0.001]
                ]
            }

            let latitude = this.urbanizacion.latitude ? this.urbanizacion.latitude : -6.7718781;
            let longitude = this.urbanizacion.longitude ? this.urbanizacion.longitude : -79.8385601;

            this.map  = L.map('map-l', {editable: true}).setView([latitude, longitude], 15);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                maxZoom: 30,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                id: 'mapbox.streets'
            }).addTo(this.map);

            this.marker  = L.marker([latitude, longitude], {draggable: true}).addTo(this.map);

            this.marker.on('dragend', (event)=>{
                let coordinates = event.target.getLatLng();
                let isWithinPolygon = inside([coordinates.lat,coordinates.lng],this.points);

                if (isWithinPolygon) {
                    let new_center = new L.LatLng(coordinates.lat,coordinates.lng);
                    this.map.setView(new_center,15,{animation: true});
                }else{
                    let new_center = new L.LatLng(this.territorioVecinal.latitude,this.territorioVecinal.longitude);
                    this.map.setView(new_center,15,{animation: true});
                    this.marker.setLatLng(new_center);

                    coordinates.lat =this.territorioVecinal.latitude;
                    coordinates.lng =this.territorioVecinal.longitude;
                }

                this.urbanizacion.latitude = coordinates.lat;
                this.urbanizacion.longitude = coordinates.lng;

                this.urbanizacionPoints = [
                    [Number(coordinates.lat) - 0.001, Number(coordinates.lng) - 0.001],
                    [Number(coordinates.lat) + 0.001,Number(coordinates.lng) - 0.001],
                    [Number(coordinates.lat) + 0.001,Number(coordinates.lng) + 0.001],
                    [Number(coordinates.lat) - 0.001,Number(coordinates.lng) + 0.001]
                ];

                if(this.urbanizacionPolygon)
                {
                    this.urbanizacionPolygon.remove();
                }

                this.setUrbanizacionPolygon();


            });

            this.setUrbanizacionPolygon();


        },
        methods: {
            setMap() {
                let invalid = true;
                for (let row of this.territoriosVecinales) {
                    if (row.id == this.urbanizacion.territorio_vecinal_id) {
                        this.points = row.coordenadas;
                        this.territorioVecinal = row;
                        if (this.polygon) {
                            this.polygon.remove();
                        }

                        this.polygon =  L.polygon(this.points, {color: 'blue', weight: 2, interactive: true}).addTo(this.map);
                        this.urbanizacion.territorio_points = row.coordenadas;
                        invalid = false;
                    }
                }

                if (invalid) {
                    if (this.polygon) {
                        this.polygon.remove();
                    }
                }
            },
            setCoordinates() {

                let new_center = new L.LatLng(this.territorioVecinal.latitude,this.territorioVecinal.longitude);

                this.map.setView(new_center,15,{animation: true});
                this.marker.setLatLng(new_center);
                this.urbanizacion.latitude = this.territorioVecinal.latitude;
                this.urbanizacion.longitude = this.territorioVecinal.longitude;
                this.urbanizacionPoints = [
                    [Number(this.territorioVecinal.latitude) - 0.001, Number(this.territorioVecinal.longitude) - 0.001],
                    [Number(this.territorioVecinal.latitude) + 0.001,Number(this.territorioVecinal.longitude) - 0.001],
                    [Number(this.territorioVecinal.latitude) + 0.001,Number(this.territorioVecinal.longitude) + 0.001],
                    [Number(this.territorioVecinal.latitude) - 0.001,Number(this.territorioVecinal.longitude) + 0.001],
                ];

                if(this.urbanizacionPolygon)
                {
                    this.urbanizacionPolygon.remove();
                }

                this.setUrbanizacionPolygon();

            },
            changeTerritorioId(){
                this.setMap();
                this.setCoordinates();
            },
            setUrbanizacionPolygon(){

                this.urbanizacionPolygon =  L.polygon(this.urbanizacionPoints, {color: 'red', weight: 2, interactive: true}).addTo(this.map);
                this.urbanizacionPolygon.enableEdit();
                this.getPolygonCoords();

                this.urbanizacionPolygon.on('mouseout',  (event) =>{
                    this.getPolygonCoords();
                });
            },
            getPolygonCoords(){

                let center = this.urbanizacionPolygon.getCenter();

                this.urbanizacion.latitude = center.lat;
                this.urbanizacion.longitude = center.lng;

                let bounds = this.urbanizacionPolygon.getLatLngs();
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

                this.urbanizacion.coordenadas = coordinates;

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
