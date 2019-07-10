<template>
    <div class="card">
        <div class="card-header">
            Crear Incidente
        </div>
        <form action="" v-on:submit.prevent="newIncidente()">
            <incidente-form :incidente="incidente"  :errors="errors"></incidente-form>

            <div class="card-footer text-center">
                <button class="btn disenio-boton-accion" type="submit" :disabled="loading">
                    <i class="fa fa-check fa-lg" aria-hidden="true"></i>Guardar
                </button>
                <a class="btn disenio-boton-accion" :href="cancel_url">
                    <i class="fa fa-times fa-lg" aria-hidden="true"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                incidente: {
                    state:'Activo',
                    tipo_incidente_id:'',
                    estado_incidente_id: '',
                    urbanizacion_id: '',
                    src_imagen:'',
                    calle_obstaculo: {},
                    inundacion:{},
                    polylines:[]
                },
                errors: [],
                loading: false,
                polygon:{},
                cancel_url:route('incidente.index',0)
            }
        },
        mounted(){
          $('#mute').removeClass('on');
        },
        methods: {
            newIncidente() {

                this.loading = true;

                let polylines = [];

                let old_polylines =this.incidente.polylines;
                

                for (let polyline of this.incidente.polylines) {

                    let coordinates_data = polyline.polyline.getLatLngs();
                    let i = 0;
                    let coordinates = '';
                    for (let coordinate of coordinates_data)
                    {
                        if (i == (coordinates_data.length - 1)) {
                            coordinates += coordinate.lat +','+coordinate.lng;
                        } else {
                            coordinates += coordinate.lat +','+coordinate.lng + ";";
                        }

                        i++;
                    }

                    polylines.push( {descripcion: '', coordinates: coordinates});
                }

                this.incidente.polylines = polylines;


                
                axios.post(route('incidente.store'), this.incidente).then((response) => {
                    if (response.data.success) {
                       window.location.href = this.cancel_url;
                    }
                    else
                    {
                        console.log(response);
                    }

                    this.loading = false;

                }).catch((error) => {
                    if (error.response && error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }else{
                        console.log(error);
                    }

                    this.incidente.polylines = old_polylines;

                    this.loading = false;

                })
            }
        }
    }
</script>
