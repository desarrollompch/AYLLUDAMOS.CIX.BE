<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Descripcion
                        </th>
                        <th>
                            Valor
                        </th>
                        <th class="action-col">
                            
                        </th>
                    </tr>
                  </thead>

                    <paginate ref="paginator" name="configuracion" :list="configuracions" :per="5" tag="tbody">
                    <tr v-for="(configuracion, index) in paginated('configuracion')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{configuracion.nombre}}
                        </td>
                        <td>
                            {{configuracion.descripcion}}
                        </td>
                        <td>
                            {{configuracion.valor}}
                        </td>
                        <td>
                            <a :href="getEdiUrl(configuracion.id)" class="btn btn-primary">
                                Editar
                            </a>
                            <a href="#" class="btn btn-danger" v-on:click="deleteDirectorio(configuracion)">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                    </paginate> 

                  <tfoot>
                    <td colspan="4">
                        <paginate-links for="configuracion"
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
                configuracions: [],
                paginate: ['configuracion'],
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
            axios.get(route('configuracion.getPageSession')).then((response) => {
              console.log(response.data.paginaActual);
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
            deleteDirectorio(configuracion){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('configuracion.destroy', {id: configuracion.id})).then((response)=>{
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
                axios.get(route('configuracion.all')).then((response) => {
                    this.configuracions = response.data.configuracions;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('configuracion.edit', {id: id,pagina:this.paginaActual})
            }

        }
    }
</script>