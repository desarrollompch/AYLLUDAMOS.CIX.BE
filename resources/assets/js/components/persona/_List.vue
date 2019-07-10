<template>
    <div class="row">
      <div class="row col-md-12" style="margin-bottom:30px">
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="form-control" name="nombre" placeholder="Nombre" id="nombre" v-model="nombre">
          </div>
        </div>
        <div class="col-sm-3">
          <!-- <div class="input-group">
            <input type="text" class="form-control" name="tipopersona" placeholder="Tipo Persona" id="tipopersona" v-model="tipopersona">
          </div> -->
          <!-- <div class="input-group"> -->
          <select class="form-control" v-model="tipopersona">
            <option value="" selected>Seleccione...</option>
            <option v-for="tp in tipoPersonaArr" v-bind:value="tp.id">
              {{ tp.descripcion }}
            </option>
          </select>
          <!-- </div> -->
        </div>
        <div class="col-sm-3">
          <div class="input-group">
            <input type="text" class="form-control" name="dni" placeholder="Dni" id="dni" v-model="dni">
          </div>
        </div>
        <div class="col-sm-1">
          <div class="input-group">
            <div class="input-group-append">
              <a href="#" class="btn disenio-boton-accion" v-on:click="getPersonasFiltros()">
                <i class="fa fa-search" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-sm-1">
          <a style="color:white" target="_blank" :href="getExportUrl()" class="btn disenio-boton-accion">
            <i class="fa fa-cloud-upload" aria-hidden="true"></i>Exportar a Excel
          </a>
        </div>
      </div>
        <div class="col-sm-12 table-responsive">
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th class="number-col">#</th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Tipo Persona
                        </th>
                        <th>
                            DNI
                        </th>
                        <th class="action-col">
                            
                        </th>
                    </tr>
                </thead>
                <tbody v-if="personas!=null && personas.length == 0">
                    <tr>
                        <td colspan="5">No existen personas registradas</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="(persona, index) in personas" :key="index">
                        <!-- <td>{{ persona.id }}</td> -->
                        <td>
                            {{ parseInt(index) + 1 }}
                        </td>
                        <td>
                            {{persona.nombres}} {{persona.ape_paterno}} {{persona.ape_materno}}
                        </td>
                        <td>
                            {{persona.tipopersona.descripcion}}
                        </td>
                        <td>
                            {{persona.dni}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(persona.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoPersona(persona)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <td colspan="3">
                        <ul class="paginate-links personas pagination">
                            <li v-for="page in pagesNumber" v-bind:class="['number page-item', (page == isActived) ? 'active': '']">
                                <a href="#" @click.prevent="cambiarPagina(page)" class="page-link">{{ page }}</a>
                            </li>
                        </ul>
                    </td>
                </tfoot>

                  <!-- <paginate name="persona" :list="personas" :per="5" tag="tbody">
                    <tr v-for="(persona, index) in paginated('persona')">
                        <td>
                            {{index + 1}}
                        </td>
                        <td>
                            {{persona.nombres}} {{persona.ape_paterno}} {{persona.ape_materno}}
                        </td>
                        <td>
                            {{persona.tipopersona.descripcion}}
                        </td>
                        <td>
                            {{persona.dni}}
                        </td>
                        <td class="botones-horizontal">
                            <a :href="getEditUrl(persona.id)" class="btn disenio-boton-accion">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>Editar
                            </a>
                            <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoPersona(persona)">
                                <i class="fa fa-times-circle-o fa-lg" aria-hidden="true"></i>Eliminar
                            </a>
                        </td>
                    </tr>
                  </paginate>                         -->

                <!-- <tfoot>
                    <td colspan="5">
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
                personas: [],
                pagination: {
                    "total": 0,
                    "current_page": 0,
                    "per_page": 0,
                    "last_page": 0,
                    "from": 0,
                    "to": 0
                },
                nombre: '',
                tipopersona: '',
                dni: '',
                tipoPersonaArr: null,
                paginaActual:1,
                pagina: "",
                offset: 5,
                isIniciado : false,
                isBusqueda : false
            }
        },
        mounted() {
          // this.getPersonas();
          this.getTipoPersona();
          this.getResultadosEnSession();
        },
        // updated(){
        //   if(!this.isIniciado){
        //     this.getResultadosEnSession();
        //     this.isIniciado = true;
        //   }
        //   if(this.isBusqueda){
        //     this.isBusqueda = false;
        //     // this.$refs.paginator.goToPage(1);
        //   }
        // },
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
          getPersonasFiltros(){
            $('#mute').addClass('on').addClass('h100');
            this.personas = null;
            axios.get(route('persona.all')+'?nombre='+this.nombre+'&tipopersona='+this.tipopersona+'&dni='+this.dni)
            .then((response) => {
              this.isBusqueda = true;
              this.personas = response.data.personas.data;
              this.pagination = response.data.pagination;
              $('#mute').removeClass('on');
            });
          },
          getResultadosEnSession(){
            axios.get(route('persona.getPageSession')).then((response) => {
                if (response.data.success){
                  console.log(response.data.paginaActual);
                  this.getPersonas(response.data.paginaActual);
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
            getExportUrl() {
              return route('persona.export', {nombre:this.nombre,tipopersona: this.tipopersona, dni: this.dni});
            },
            deleteTipoPersona(persona){
                let r = confirm('Estas seguro de eliminar este registro');

                if(r === true)
                {
                    axios.delete(route('persona.destroy', {id:persona.id})).then((response)=>{
                        if(response.data.success){
                            this.getPersonas();
                        }else{
                            alert(response.data.message);
                        }
                    }).catch((error)=>{
                        console.log(error.data);
                    });
                }
            },
            getPersonas(page){
              $('#mute').addClass('on').addClass('h100');
                axios.get(route('persona.all')+'?page='+page+'&nombre='+this.nombre+'&tipopersona='+this.tipopersona+'&dni='+this.dni).then((response) => {
                    this.personas = response.data.personas.data;
                    this.pagination = response.data.pagination;
                    console.log(this.pagination);
                    $('#mute').removeClass('on');
                });
            },
            getEditUrl(id){
                return route('persona.edit', {id:id,pagina:this.paginaActual});
            },
            cambiarPagina: function(page) {
                this.pagination.current_page = page;
                this.paginaActual = page;
                console.log(page);
                this.getPersonas(page);
            },
            getTipoPersona(){
              axios.get(route('persona.getTipoPersona')).then((response) => {
                this.tipoPersonaArr = response.data.tipopersona;
              });
            }
        }
    }
</script>