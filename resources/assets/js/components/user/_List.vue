<template>
  <!-- <div class="row" v-if="!users">
    <div class="col-md-12" style="text-align:center">
      <img :src="urlLoader" alt="logo">
    </div>
  </div> -->
  <div class="row">
    <div class="row col-md-12">
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="form-control" name="dni" placeholder="Dni" id="dni" v-model="dni">
        </div>
      </div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="form-control" name="nombres" placeholder="Nombres" id="nombre" v-model="nombres">
        </div>
      </div>
      <div class="col-sm-3">
        <div class="input-group">
          <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" id="apellidos" v-model="apellidos">
        </div>
      </div>
      <div class="col-sm-1">
        <div class="input-group">
          <div class="input-group-append">
            <a href="#" class="btn disenio-boton-accion" v-on:click="getUsuariosFiltros()">
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
        <div v-if="users" class="col-sm-12 table-responsive">
            <br>
            <table class="table table bordered">
                <thead>
                <tr>
                    <th class="number-col">#</th>
                    <th>Dni</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Rol</th>
                    <th>Fecha de Registro</th>
                    <th colspan="4" class="text-center">Opciones</th>
                </tr>
                </thead>

                <paginate ref="paginator" name="usuarios" :list="users" :per="5" tag="tbody">
                  <tr v-for="(user, index) in paginated('usuarios')">
                    <td>{{index + 1}}</td>
                    <td>{{user.dni}}</td>
                    <td>{{user.persona.nombres}} {{user.persona.ape_paterno}} {{user.persona.ape_materno}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.state}}</td>
                    <td>
                        <span v-for="(rol,index) in user.roles">
                            {{rol.descripcion + ((index +1 ) < user.roles.length ? ' ,' : '' )}}
                        </span>
                    </td>
                    <td>{{user.created_at}}</td>
                    <td>
                        <a href="#" class="btn disenio-boton-accion" v-on:click="blockUser(user)" v-if="user.state == 'Activo'">
                          <i class="fa fa-ban fa-lg"></i>Bloquear
                        </a>
                    </td>
                    <td>
                        <a href="#" class="btn disenio-boton-accion" v-on:click="activateUser(user)" v-if="user.state == 'Inactivo'">
                          <i class="fa fa-check-circle-o fa-lg"></i>Activar
                        </a>
                    </td>
                    <td>
                        <a :href="getEditUrl(user.id)" class="btn disenio-boton-accion">
                            <i class="fa fa-pencil fa-lg"></i>Editar
                        </a>
                    </td>
                    <td>
                        <a href="#" class="btn disenio-boton-accion" v-on:click="deleteTipoUser(user)">
                            <i class="fa fa-times-circle-o fa-lg"></i>Eliminar
                        </a>
                    </td>
                </tr>
                </paginate>
                
                <tfoot>
                    <td colspan="10">
                        <paginate-links
                        for="usuarios"
                        @change="onLangsPageChange"
                        :classes="{
                          'ul': 'pagination',
                          'li': 'page-item',
                          'a':  'page-link'
                        }"
                        >
                        </paginate-links>
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
      data() {
        return {
          users: null,
          paginate: ['usuarios'],
          dni: '',
          nombres: '',
          apellidos: '',
          urlLoader: null,
          paginaActual:1,
          isIniciado : false,
          isBusqueda : false
        }
      },
        mounted() {
          this.getUsers();
        },
        updated(){
          if(!this.isIniciado){
            this.getResultadosEnSession();
            this.isIniciado = true;
          }
          if(this.isBusqueda){
            this.isBusqueda = false;
            this.$refs.paginator.goToPage(1);
          }
        },
        methods: {
          getUsuariosFiltros(){
            $('#mute').addClass('on').addClass('h100');
            this.users = null;
            axios.get(route('user.all')+'?dni='+this.dni+'&nombres='+this.nombres+'&apellidos='+this.apellidos)
            .then((response) => {
              this.isBusqueda = true;
              this.users = response.data.users;
              $('#mute').removeClass('on');
            });
          },
          getResultadosEnSession(){
            axios.get(route('user.getPageSession')).then((response) => {
              if (response.data.success){
                console.log(response.data.paginaActual);
                if (this.$refs.paginator) {
                  this.$refs.paginator.goToPage(response.data.paginaActual);
                }
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
            return route('user.export', {dni:this.dni,nombres: this.nombres, apellidos: this.apellidos});
          },
          deleteTipoUser(user) {
              let r = confirm('Estas seguro de eliminar este registro');
              if (r === true) {
                  axios.delete(route('user.destroy', {id: user.id})).then((response) => {
                      if (response.data.success) {
                          this.getUsers();
                      } else {
                          alert(response.data.message);
                      }
                  }).catch((error) => {
                      console.log(error.data);
                  });
              }
          },
          getUsers() {
            $('#mute').addClass('on').addClass('h100');
            this.users = null;
            axios.get(route('user.all')+'?dni='+this.dni+'&nombres='+this.nombres+'&apellidos='+this.apellidos)
            .then((response) => {
              this.users = response.data.users;
              $('#mute').removeClass('on');
            });
          },
          getEditUrl(id) {
              return route('user.edit', {id: id,pagina:this.paginaActual});
          },
          updateUser(user)
          {
              axios.put(route('user.update', {id:user.id}), user).then( (response) =>{
                this.loading = false;
              }).catch((error) =>{
                this.loading = false;
              })
          },
          activateUser(user){
              user.state= 'Activo';
              this.updateUser(user);
          },
          blockUser(user){
              user.state= 'Inactivo';
              this.updateUser(user);
          }
        }
    }
</script>