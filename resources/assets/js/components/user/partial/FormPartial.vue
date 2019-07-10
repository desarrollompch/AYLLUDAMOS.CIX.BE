<template>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-7">
                <div class="form-group">
                    <label>
                        Persona
                    </label>
                    <search-persona :persona="user.persona" @onSelectItem="setPersonaId"></search-persona>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label>
                        Rol
                    </label>
                    <multiselect
                            v-model="user.roles"
                            :options="roles"
                            label="descripcion"
                            :multiple="true"
                            :preserve-search="true"
                            track-by="id"
                    />
                    <small class="form-text text-danger" v-if="errors.rol_id"
                           v-for="error in errors.rol_id">
                        {{error}}
                    </small>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="email">
                        Email
                    </label>
                    <input type="text" class="form-control" name="email" placeholder="Ingrese email" id="email"
                           v-model="user.email">
                    <small class="form-text text-danger" v-if="errors.email"
                           v-for="error in errors.email">
                        {{error}}
                    </small>
                </div>
            </div>
            <!-- <div class="col-sm-3" v-if="!user.id"> -->
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="password">
                        Contraseña
                    </label>
                    <input type="password" class="form-control" name="password" placeholder="Ingrese password"
                           id="password" v-model="user.password">
                    <small class="form-text text-danger" v-if="errors.password"
                           v-for="error in errors.password">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="state">
                        Estado
                    </label>
                    <select name="state" id="state" v-model="user.state" class="form-control">
                        <option value="">Selecciona</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    <small class="form-text text-danger" v-if="errors.state"
                           v-for="error in errors.state">
                        {{error}}
                    </small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Multiselect from 'vue-multiselect';

    export default {
        props: ['user', 'errors'],
        components: { Multiselect },
        data() {
            return {
                roles: []
            }

        },
        mounted() {

            axios.get(route('rol.all')).then((response) => {
                this.roles = response.data.roles;
            });

        },
        methods: {
            setPersonaId(data){
                this.user.persona_id = data.id;
                this.user.email = data.mail;
                this.user.name = data.nombres;
                this.user.last_name = data.ape_paterno;
            }
        }
    }
</script>