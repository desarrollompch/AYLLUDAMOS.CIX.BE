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
                        <th class="action-col text-center">
                          Opción
                        </th>
                    </tr>
                </thead>
                <paginate ref="paginator" name="roles" :list="roles" :per="5" tag="tbody">
                    <tr v-for="(rol, index) in paginated('roles')">
                        <td>{{index + 1}}</td>
                        <td>{{rol.descripcion}}</td>
                        <td class="botones-horizontal">
                          <a :href="getEditUrl(rol.id)" class="btn disenio-boton-accion">
                            <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                          </a>
                          <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoPersona(rol)">
                            <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                          </a>
                        </td>
                    </tr>
                </paginate>                        

                <tfoot>
                    <td colspan="3">
                        <paginate-links 
                        for="roles"
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
    import VuePaginate from 'vue-paginate'
    Vue.use(VuePaginate)

    export default {
        data(){
            return {
                roles: [],
                paginate: ['roles'],
                paginaActual:1,
                isIniciado : false
            }
        },
        mounted() {
            this.getRoles();
        },
        updated(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
        },
        methods: {
          getResultadosEnSession(){
            axios.get(route('rol.getPageSession')).then((response) => {
              if (response.data.success){
                this.$refs.paginator.goToPage(response.data.paginaActual)
              } else {
                alert(response.data.message);
              }
            }).catch((error) => {
              console.log(error.data);
            });
          },
          onLangsPageChange (toPage, fromPage) {
            this.paginaActual = toPage;
          },
            deleteTipoPersona(rol){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('rol.destroy',{id:rol.id} )).then((response)=>{
                        if(response.data.success){
                            this.getRoles();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getRoles(){
              $('#mute').addClass('on').addClass('h100');
              this.roles = null;
                axios.get(route('rol.all')).then((response) => {
                    this.roles = response.data.roles;
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id){
              return route('rol.edit',{id:id,pagina:this.paginaActual})
            }
        }
    }
</script>