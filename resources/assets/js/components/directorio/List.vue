<template>
    <div>
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Dirección
                        </th>
                        <th>
                            Telefono
                        </th>
                        <th class="action-col">
                            Opciones
                        </th>
                    </tr>
                  </thead>

                  <paginate ref="paginator" name="directorio" :list="directorios" :per="10" tag="tbody">
                    <tr v-if="directorios.length == 0">
                        <td colspan="5">No existen registros en el directorio</td>
                    </tr>
                    <tr v-for="(directorio, index) in paginated('directorio')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{directorio.nombre}}
                        </td>
                        <td>
                            {{directorio.direccion}}
                        </td>
                        <td>
                            {{directorio.telefono}}
                        </td>
                        <td>
                            <a :href="getEdiUrl(directorio.id)" class="icono-editar"></a>
                            <a href="#" class="icono-eliminar" v-on:click="deleteDirectorio(directorio)"></a>
                        </td>
                    </tr>
                  </paginate>                        

              <tfoot>
                    <td colspan="5">
                        <paginate-links for="directorio"
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
                directorios: [],
                paginate: ['directorio'],
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
            axios.get(route('directorio.getPageSession')).then((response) => {
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
            deleteDirectorio(directorio){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('directorio.destroy', {id: directorio.id})).then((response)=>{
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
                axios.get(route('directorio.all')).then((response) => {
                    this.directorios = response.data.directorios;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('directorio.edit', {id: id,pagina:this.paginaActual})
            }

        }
    }
</script>