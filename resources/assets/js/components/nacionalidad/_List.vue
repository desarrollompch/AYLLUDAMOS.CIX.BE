<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Nacionalidad
                        </th> 
                        <th>
                            Cantidad Digitos
                        </th>
                        <th class="action-col">
                            
                        </th>
                    </tr>
                  </thead>

                  <paginate ref="paginator" name="nacionalidad" :list="nacionalidades" :per="5" tag="tbody">
                    <tr v-for="(nacionalidad, index) in paginated('nacionalidad')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{nacionalidad.nacionalidad}}
                        </td>
                        <td>
                            {{nacionalidad.cantidad_digitos}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(nacionalidad.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoPersona(nacionalidad)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                  </paginate>                        

                <tfoot>
                    <td colspan="4">
                        <paginate-links for="nacionalidad"
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
                nacionalidades: [],
                paginate: ['nacionalidad'],
                paginaActual:1,
                isIniciado : false,
            }
        },
        mounted() {
            this.getNacionalidades();
        },
        beforeUpdate(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('nacionalidad.getPageSession')).then((response) => {
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
            deleteTipoPersona(nacionalidad){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('nacionalidad.destroy',{id:nacionalidad.id} )).then((response)=>{
                        if(response.data.success){
                            this.getNacionalidades();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getNacionalidades(){
              $('#mute').addClass('on').addClass('h100');
              // this.nacionalidades = null;
              axios.get(route('nacionalidad.all')).then((response) => {
                this.nacionalidades = response.data.nacionalidades;
                $('#mute').removeClass('on');
              });
            },
            getEditUrl(id){
                return route('nacionalidad.edit',{id:id,pagina:this.paginaActual})
            }
        }
    }
</script>