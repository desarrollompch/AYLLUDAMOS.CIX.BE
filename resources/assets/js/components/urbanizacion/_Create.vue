<template>
    <div class="card">
        <div class="card-header">
            Crear Urbanización
        </div>
        <form action="" v-on:submit.prevent="newUrbanizacion()">
            <urbanizacion-form :urbanizacion="urbanizacion"  :errors="errors"></urbanizacion-form>

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
                urbanizacion: {
                    descripcion: '',
                    territorio_vecinal_id: ''
                },
                errors: [],
                loading: false,
                polygon:null,
                cancel_url: route('urbanizacion.index',0)
            }
        },
        mounted(){
          $('#mute').removeClass('on');
        },
        methods: {
            newUrbanizacion() {
                let valid = this.validatePoints();

                if(!valid)
                {
                    alert('El area marcada esta fuera del cuadrante del territorio');
                    return false;
                }

                this.loading = true;

                delete this.urbanizacion.territorio_points;

                axios.post(route('urbanizacion.store'), this.urbanizacion)
                .then((response) => {
                    if (response.data.success) {
                       window.location.href = this.cancel_url;
                    }
                    this.loading = false;
                }).catch((error) => {
                    console.log(error);
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            },
            validatePoints(){

                if(this.urbanizacion.territorio_points)
                {
                    let coordenadas = this.urbanizacion.coordenadas.split(";");
                    let valid = true;
                    for(let row of coordenadas)
                    {
                      let temp = row.split(',');
                      console.log(temp);
                      let myLatlng = new google.maps.LatLng(temp[0], temp[1]);
                      let isWithinPolygon = google.maps.geometry.poly.containsLocation(myLatlng, this.urbanizacion.territorio_points);
                      if (!isWithinPolygon) {
                          valid = false;
                      }
                    }

                    return valid;
                }
                return false;
            }
        }
    }
</script>