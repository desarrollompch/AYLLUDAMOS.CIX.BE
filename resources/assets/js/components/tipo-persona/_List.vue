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
                        <th class="action-col">
                            
                        </th>
                    </tr>
                </thead>
                <tbody v-if="tiposPersonas.length == 0">
                    <tr>
                        <td colspan="3">No existen tipos de personas registrados</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="(tipoPersona, index) in tiposPersonas" :key="index">
                        <!-- <td>{{ tipoPersona.id }}</td> -->
                        <td>
                            {{ parseInt(index) + 1}}
                        </td>
                        <td>
                            {{tipoPersona.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(tipoPersona.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoPersona(tipoPersona)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <td colspan="3">
                        <ul class="paginate-links tiposPersonas pagination">
                            <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                            </li>
                        </ul>
                    </td>
                </tfoot>

                  <!-- <paginate name="persona" :list="tiposPersonas" :per="5" tag="tbody">
                    <tr v-for="(tipoPersona, index) in paginated('persona')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{tipoPersona.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(tipoPersona.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoPersona(tipoPersona)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                  </paginate>                         -->

                 <!-- <tfoot>
                    <td colspan="3">
                        <paginate-links for="persona"
                          :hide-single-page="true"
                          :classes="{
                            'ul': 'pagination',
                            'li': 'page-item',
                            'a':  'page-link'
                          }"
                        ></paginate-links>
                    </td>
                </tfoot> -->

            </table>
        </div>
    </div>
</template>

<script>
    // import VuePaginate from 'vue-paginate';
    // Vue.use(VuePaginate);
    export default {
        data(){
            return {
                tiposPersonas: [],
                pagination: {
                    "total": 0,
                    "current_page": 0,
                    "per_page": 0,
                    "last_page": 0,
                    "from": 0,
                    "to": 0
                },
                paginaActual:1,
                pagina: "",
                offset: 5,
                // paginate: ['persona']
            }
        },
        mounted() {
            // this.getTiposPersonas();
            this.getResultadosEnSession();
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
        },
        methods: {
            getResultadosEnSession(){
              axios.get(route('tipo-persona.getPageSession')).then((response) => {
                  if (response.data.success){
                    console.log(response.data.paginaActual);
                    this.getTiposPersonas(response.data.paginaActual);
                  } else {
                    alert(response.data.message);
                  }
              }).catch((error) => {
                  console.log(error.data);
              });
            },
            deleteTipoPersona(tipoPersona){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('tipo-persona.destroy', tipoPersona.id)).then((response)=>{
                        if(response.data.success){
                            this.getTiposPersonas();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getTiposPersonas(page){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('tipo-persona.all', {"page": page})).then((response) => {
                    this.tiposPersonas = response.data.tiposPersonas.data;
                    this.pagination = response.data.pagination;
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id){
                return route('tipo-persona.edit', {id:id,pagina:this.paginaActual});
            },
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page;
                this.getTiposPersonas(page);
            },

        }
    }
</script>