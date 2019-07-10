<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Descripción
                        </th>
                        <th>
                           Estado
                        </th>
                        <th>
                            Puntaje
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                  </thead>

                  <paginate ref="paginator" name="actividadPuntuacion" :list="actividadesPuntuacion" :per="10" tag="tbody">
                    <tr v-for="(actividadPuntuacion, index) in paginated('actividadPuntuacion')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{actividadPuntuacion.descripcion}}
                        </td>
                        <td>
                            {{actividadPuntuacion.estado_incidente.descripcion}}
                        </td>
                        <td>
                            {{actividadPuntuacion.puntaje}}
                        </td>
                        <td>
                            <a :href="getEdiUrl(actividadPuntuacion.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteActividadPuntuacion(actividadPuntuacion)"></a>
                        </td>
                    </tr>
                  </paginate>             
                
                  <tfoot>
                    <td colspan="4">
                        <paginate-links for="actividadPuntuacion"
                          @change="onLangsPageChange"
                          :hide-single-page="true"
                          :classes="{
                            'ul': 'pagination',
                            'li': 'page-item',
                            'a':  'page-link'
                          }"
                        ></paginate-links>
                    </td>
                </tfoot>

            </table>
        </div>
    </div>
</template>

<script>
    import VuePaginate from 'vue-paginate';
    Vue.use(VuePaginate);
    export default {
        data(){
            return {
                actividadesPuntuacion: [],
                paginate: ['actividadPuntuacion'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getNivelesAgua();
        },
        updated(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('actividad-puntuacion.getPageSession')).then((response) => {
              if(this.actividadesPuntuacion.length > 0){
                this.$refs.paginator.goToPage(response.data.paginaActual);
              }
            }).catch((error) => {
              console.log(error.data);
            });
          },
          onLangsPageChange (toPage, fromPage) {
            this.paginaActual = toPage;
          },
            deleteActividadPuntuacion(actividadPuntuacion){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('actividad-puntuacion.destroy', {id: actividadPuntuacion.id})).then((response)=>{
                        if(response.data.success){
                            this.getNivelesAgua();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getNivelesAgua(){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('actividad-puntuacion.all')).then((response) => {
                    this.actividadesPuntuacion = response.data.actividadesPuntuacion;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('actividad-puntuacion.edit', {id: id,pagina:this.paginaActual})
            }

        }
    }
</script>