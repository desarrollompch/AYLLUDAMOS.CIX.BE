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
            <div id="map" style="height: 300px; width: 100%">

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

            this.map = new GMaps({
                div: '#map',
                zoom: 15,
                lat: this.urbanizacion.latitude ? this.urbanizacion.latitude : -12.043333,
                lng: this.urbanizacion.longitude ? this.urbanizacion.longitude : -77.028333
            });

            this.marker = this.map.addMarker({
                lat: this.urbanizacion.latitude ? this.urbanizacion.latitude : -12.043333,
                lng: this.urbanizacion.longitude ? this.urbanizacion.longitude : -77.028333,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            if(this.urbanizacion.coordenadas)
            {
                this.urbanizacionPoints = this.urbanizacion.coordenadas;
            }else{
                this.urbanizacionPoints = [
                    [Number(-12.043333) - 0.001, Number(-77.028333) - 0.001],
                    [Number(-12.043333) + 0.001,Number(-77.028333) - 0.001],
                    [Number(-12.043333) + 0.001,Number(-77.028333) + 0.001],
                ]
            }

            this.marker.addListener('dragend', (event) => {

                let isWithinPolygon = google.maps.geometry.poly.containsLocation(event.latLng, this.polygon);

                if (isWithinPolygon) {
                    this.map.setCenter(this.marker.getPosition().lat(), this.marker.getPosition().lng());
                    let myLatlng = new google.maps.LatLng(this.map.getCenter().lat(), this.map.getCenter().lng());
                    this.marker.setPosition(myLatlng);
                } else {
                    let myLatlng = new google.maps.LatLng(this.territorioVecinal.latitude, this.territorioVecinal.longitude);
                    this.map.setCenter(this.territorioVecinal.latitude, this.territorioVecinal.longitude);
                    this.marker.setPosition(myLatlng);
                }

                this.urbanizacion.latitude = this.marker.getPosition().lat();
                this.urbanizacion.longitude = this.marker.getPosition().lng();

                this.urbanizacionPoints = [
                    [Number(this.map.getCenter().lat()) - 0.001, Number(this.map.getCenter().lng()) - 0.001],
                    [Number(this.map.getCenter().lat()) + 0.001,Number(this.map.getCenter().lng()) - 0.001],
                    [Number(this.map.getCenter().lat()) + 0.001,Number(this.map.getCenter().lng()) + 0.001],
                ];

                if(this.urbanizacionPolygon)
                {
                    this.urbanizacionPolygon.setMap(null);
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
                            this.polygon.setMap(null);
                        }

                        this.polygon = this.map.drawPolygon({
                            paths: this.points,
                            strokeColor: '#e92b34',
                            strokeOpacity: 1,
                            strokeWeight: 3,
                            fillColor: '#2242da',
                            fillOpacity: 0.6
                        });
                        this.urbanizacion.territorio_points = this.polygon;
                        invalid = false;
                    }
                }

                if (invalid) {
                    if (this.polygon) {
                        this.polygon.setMap(null);
                    }
                }
            },
            setCoordinates() {
                let myLatlng = new google.maps.LatLng(this.territorioVecinal.latitude, this.territorioVecinal.longitude);
                this.map.setCenter(this.territorioVecinal.latitude, this.territorioVecinal.longitude);
                this.marker.setPosition(myLatlng);
                this.urbanizacion.latitude = this.territorioVecinal.latitude;
                this.urbanizacion.longitude = this.territorioVecinal.longitude;
                this.urbanizacionPoints = [
                    [Number(this.map.getCenter().lat()) - 0.001, Number(this.map.getCenter().lng()) - 0.001],
                    [Number(this.map.getCenter().lat()) + 0.001,Number(this.map.getCenter().lng()) - 0.001],
                    [Number(this.map.getCenter().lat()) + 0.001,Number(this.map.getCenter().lng()) + 0.001],
                ];

                if(this.urbanizacionPolygon)
                {
                    this.urbanizacionPolygon.setMap(null);
                }

                this.setUrbanizacionPolygon();

            },
            changeTerritorioId(){
                this.setMap();
                this.setCoordinates();
            },
            setUrbanizacionPolygon(){
                this.urbanizacionPolygon = this.map.drawPolygon({
                    paths: this.urbanizacionPoints,
                    strokeColor: '#e9e513',
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    fillColor: '#da151b',
                    fillOpacity: 0.6,
                    editable:true
                });

                google.maps.event.addListener(this.urbanizacionPolygon.getPath(), "insert_at", ()=>{
                    this.getPolygonCoords();
                });
                google.maps.event.addListener(this.urbanizacionPolygon.getPath(), "set_at", ()=>{
                    this.getPolygonCoords();
                });
                this.getPolygonCoords();
            },
            getPolygonCoords(){
                let len = this.urbanizacionPolygon.getPath().getLength();
                let coordinates = "";
                for (var i = 0; i < len; i++) {
                    if(i == (len - 1))
                    {
                        coordinates += this.urbanizacionPolygon.getPath().getAt(i).toUrlValue(5);
                    }else{
                        coordinates += this.urbanizacionPolygon.getPath().getAt(i).toUrlValue(5) + ";";
                    }
                }

                this.urbanizacion.coordenadas = coordinates;
            }
        }
    }
</script>