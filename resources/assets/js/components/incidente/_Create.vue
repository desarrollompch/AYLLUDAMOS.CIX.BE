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

                let polylines = [];

                let old_polylines =this.incidente.polylines;

                for (let polyline of this.incidente.polylines) {
                    let len = polyline.polyline.getPath().getLength();
                    let coordinates = "";
                    for (var i = 0; i < len; i++) {
                        if (i == (len - 1)) {
                            coordinates += polyline.polyline.getPath().getAt(i).toUrlValue(5);
                        } else {
                            coordinates += polyline.polyline.getPath().getAt(i).toUrlValue(5) + ";";
                        }
                    }

                    polylines.push( {descripcion: '', coordinates: coordinates});
                }

                this.incidente.polylines = polylines;


                this.loading = true;
                axios.post(route('incidente.store'), this.incidente).then((response) => {
                    if (response.data.success) {
                       window.location.href = this.cancel_url;
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