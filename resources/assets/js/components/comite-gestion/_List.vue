<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Persona
                        </th>
                        <th>
                            Territorio Vecinal
                        </th>
                        <th class="action-col">
                            
                        </th>
                    </tr>
                </thead>
                <tbody v-if="comitesGestion.length == 0">
                    <tr>
                        <td colspan="3">No existen comités de gestión registrados</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="(comiteGestion, index) in comitesGestion" :key="index">
                        <!-- <td>{{ comiteGestion.id }}</td> -->
                        <td>
                            {{ parseInt(index) + 1}}
                        </td>
                        <td>
                            {{comiteGestion.persona.nombres}} {{comiteGestion.persona.ape_paterno}} {{comiteGestion.persona.ape_materno}}
                        </td>
                        <td>
                            {{comiteGestion.territorio_vecinal.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEdiUrl(comiteGestion.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteComiteGestion(comiteGestion)">
                                <i class="fa fa-minus fa-lg" aria-hidden="true"></i>Baja
                            </a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <td colspan="3">
                        <ul class="paginate-links comitesGestion pagination">
                            <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                            </li>
                        </ul>
                    </td>
                </tfoot>

                  <!-- <paginate name="comites" :list="comitesGestion" :per="5" tag="tbody">
                    <tr v-for="(comiteGestion, index) in paginated('comites')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{comiteGestion.persona.nombres}} {{comiteGestion.persona.ape_paterno}} {{comiteGestion.persona.ape_materno}}
                        </td>
                        <td>
                            {{comiteGestion.territorio_vecinal.descripcion}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEdiUrl(comiteGestion.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteComiteGestion(comiteGestion)">
                                <i class="fa fa-minus fa-lg" aria-hidden="true"></i>Baja
                            </a>
                        </td>
                    </tr>
                  </paginate>                           -->

                  <!-- <tfoot>
                    <td colspan="4">
                         <paginate-links for="comites"
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
                comitesGestion: [],
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
                // paginate: ['comites']
            }
        },
        mounted() {
            // this.getComites();
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
              axios.get(route('comite-gestion.getPageSession')).then((response) => {
                  if (response.data.success){
                    console.log(response.data.paginaActual);
                    this.getComites(response.data.paginaActual);
                  } else {
                    alert(response.data.message);
                  }
              }).catch((error) => {
                  console.log(error.data);
              });
            },
            deleteComiteGestion(comiteGestion){
                let r = confirm('Estas seguro de dar de baja este registro');

                if(r === true)
                {
                    axios.delete(route('comite-gestion.destroy', {id: comiteGestion.id})).then((response)=>{
                        if(response.data.success){
                            this.getComites(this.paginaActual);
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getComites(page){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('comite-gestion.all', {"page": page})).then((response) => {
                    this.comitesGestion = response.data.comitesGestion.data;
                    this.pagination = response.data.pagination;
                    $('#mute').removeClass('on');
                });
            },
            getEdiUrl(id){
                return route('comite-gestion.edit', {id: id,pagina:this.paginaActual})
            },
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page;
                this.getComites(page);
            },

        }
    }
</script>