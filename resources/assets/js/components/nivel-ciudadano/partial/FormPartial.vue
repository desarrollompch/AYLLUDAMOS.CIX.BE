<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Descripcion</label>
                <input type="text" class="form-control" placeholder="Ingrese una descripción" name="descripcion"
                       v-model="nivelCiudadano.descripcion">
                <small class="form-text text-danger" v-if="errors.descripcion" v-for="error in errors.descripcion">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="icono">Icono</label>
                <input type="file" class="form-control" name="icono" id="icono" @change="readFile">
                <small class="form-text text-danger" v-if="errors.icono" v-for="error in errors.icono">
                    {{error}}
                </small>
            </div>
            <br>
            <div class="img-container">
                <img :src="nivelCiudadano.src_icono" alt="icono" class="img-icono" v-if="nivelCiudadano.src_icono">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="total_minimo">Puntaje Minimo</label>
                <component-cleave-js
                        id="total_minimo"
                        type="text"
                        class="form-control"
                        name="total_minimo"
                        placeholder="0"
                        :options="{ numeral: true,
                        numeralDecimalScale: 0,
                        numeralPositiveOnly: true}"
                        @oninput="setTotalMinimo"
                        :value="nivelCiudadano.total_minimo"
                >
                </component-cleave-js>
                <small class="form-text text-danger" v-if="errors.total_minimo" v-for="error in errors.total_minimo">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="total_maximo">Puntaje Maximo</label>
                <component-cleave-js
                        id="total_maximo"
                        type="text"
                        class="form-control"
                        name="total_maximo"
                        placeholder="0"
                        :options="{ numeral: true,
                        numeralDecimalScale: 0,
                        numeralPositiveOnly: true}"
                        @oninput="setTotalMaximo"
                        :value="nivelCiudadano.total_maximo"
                >
                </component-cleave-js>
                <small class="form-text text-danger" v-if="errors.total_maximo" v-for="error in errors.total_maximo">
                    {{error}}
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['nivelCiudadano', 'errors'],
        methods: {
            setTotalMinimo(val){
                this.nivelCiudadano.total_minimo = val;
            },
            setTotalMaximo(val){
                this.nivelCiudadano.total_maximo = val;
            },
            readFile(event){
                if (event.target.files && event.target.files.length > 0) {
                    let file = event.target.files[0];

                    let sizeByte = file.size;
                    let sizekiloBytes = Number(sizeByte) / 1024;

                    if (sizekiloBytes <= 20480) {
                        let reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = () => {
                            this.nivelCiudadano.icono_file = {
                                filename: file.name,
                                filetype: file.type,
                                value: reader.result.split(',')[1]
                            };

                            this.nivelCiudadano.src_icono = reader.result;
                        };
                    }
                }
            }
        }
    }
</script>