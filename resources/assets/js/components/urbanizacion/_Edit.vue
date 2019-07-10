<template>
    <div class="card">
        <div class="card-header">
            Editar Urbanización
        </div>
        <form action="" v-on:submit.prevent="editTipoPersona()">
            <div class="card-body">
                <urbanizacion-form v-if="urbanizacion.id" :urbanizacion="urbanizacion" :errors="errors"></urbanizacion-form>
            </div>
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
        data(){
            return {
                urbanizacion: {
                },
                errors: [],
                loading:false,
                cancel_url:route('urbanizacion.index',1)
            }
        },
        mounted(){
            let id = this.getIDfromURL();

            axios.get(route('urbanizacion.show', {id:id})).then((response) => {
                this.urbanizacion = response.data.urbanizacion;
                $('#mute').removeClass('on');
            });

        },
        methods: {
            editTipoPersona(){
                let valid = this.validatePoints();

                if(!valid)
                {
                    alert('El area marcada esta fuera del cuadrante del territorio');
                    return false;
                }

                this.loading = true;
                delete this.urbanizacion.territorio_points;

                axios.put(route('urbanizacion.update', {id:this.urbanizacion.id}), this.urbanizacion).then( (response) =>{
                    if(response.data)
                    {
                        window.location.href = this.cancel_url;
                    }

                    this.loading = false;

                }).catch((error) =>{
                    if(error.response.status == 422)
                    {
                        this.errors = error.response.data.errors;
                    }

                    this.loading = false;

                })
            },
            getIDfromURL(){
                let path = window.location.pathname;
                let segments = path.split("/");
                let id = null;

                for(let segment of segments)
                {
                    if(!isNaN(segment))
                    {
                        if(!id)
                        {
                            id = segment;
                        }
                    }
                }

                return id;
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