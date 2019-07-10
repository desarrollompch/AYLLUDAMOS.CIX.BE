<template>
    <div>
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Descripción
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                  </thead>

                  <paginate ref="paginator" name="coordinacion" :list="coordinacions" :per="10" tag="tbody">
                    <tr v-if="coordinacions.length == 0">
                        <td>No existen coordinaciones</td>
                    </tr>
                    <tr v-for="(coordinacion, index) in paginated('coordinacion')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{coordinacion.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEdiUrl(coordinacion.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteDirectorio(coordinacion)"></a>
                        </td>
                    </tr>
                  </paginate>                         

                  <tfoot>
                    <td colspan="3">
                         <paginate-links for="coordinacion"
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
                coordinacions: [],
                paginate: ['coordinacion'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getNivelesAgua();
        },
        beforeUpdate(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('coordinacion.getPageSession')).then((response) => {
              console.log(response.data);
              // if(this.nacionalidades.length > 0){
                this.$refs.paginator.goToPage(response.data.paginaActual);
              // }
            }).catch((error) => {
              console.log(error.data);
            });
          },
          onLangsPageChange (toPage, fromPage) {
            this.paginaActual = toPage;
          },
            deleteDirectorio(coordinacion){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('coordinacion.destroy', {id: coordinacion.id})).then((response)=>{
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
                axios.get(route('coordinacion.all')).then((response) => {
                    this.coordinacions = response.data.coordinacions;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('coordinacion.edit', {id: id,pagina:this.paginaActual})
            }

        }
    }
</script>