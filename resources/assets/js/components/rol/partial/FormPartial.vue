<template>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-9">
                <div class="form-group">
                    <label for="descripcion">Descripcion</label>
                    <input type="text" id="descripcion" class="form-control" placeholder="Ingrese una descripción"
                           name="descripcion" v-model="rol.descripcion">
                    <small class="form-text text-danger" v-if="errors.descripcion"
                           v-for="error in errors.descripcion">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control" v-model="rol.estado">
                        <option value="">Seleccione</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    <small class="form-text text-danger" v-if="errors.estado"
                           v-for="error in errors.estado">
                        {{error}}
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="permisos">Permisos</label>
                <select name="permisos" id="permisos" class="form-control" @change="setPermiso">
                    <option value="">Seleccione</option>
                    <option v-for="(permiso,index) in permisos" :value="index">
                        {{permiso.name}}
                    </option>
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12 table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="number-col">
                            #
                        </th>
                        <th>
                            Nombre
                        </th>
                        <th>
                            Descripción
                        </th>
                        <th class="number-col">

                        </th>
                    </tr>
                    </thead>
                    <tbody v-if="permisos.length > 0">
                        <tr v-for="(permiso, index) in rol.permisos">
                            <td>
                                {{index + 1}}
                            </td>
                            <td>
                                {{permisos[permiso].name}}
                            </td>
                            <td>
                                {{permisos[permiso].description}}
                            </td>
                            <td class="text-center">
                                <button type="button" class="close text-danger" aria-label="Close" @click="removePermiso(index)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['rol', 'errors'],
        data() {
            return {
                permisos: []
            }
        },
        mounted() {
            axios.get(route('rol.permisos')).then(response => {
                this.permisos = response.data.permisos;
            });
        },
        methods:{
            setPermiso(event){

                let valid = true;

                for(let item of this.rol.permisos)
                {
                    if(event.target.value == item){
                        valid = false;
                    }
                }

                if(valid)
                {
                    this.rol.permisos.push(event.target.value);
                }

                event.target.value = "";
            },
            removePermiso(index){
                this.rol.permisos.splice(index,1);
            }
        }
    }
</script>