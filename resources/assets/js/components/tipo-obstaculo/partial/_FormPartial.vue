<template>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label>Descripcion</label>
                <input type="text" class="form-control" placeholder="Ingrese una descripción" name="descripcion" v-model="tipoObstaculo.descripcion">
                <small id="passwordHelpBlock" class="form-text text-danger" v-if="errors.descripcion" v-for="error in errors.descripcion" >
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" class="form-control" name="imagen" id="imagen" @change="readFile">
                <small class="form-text text-danger" v-if="errors.imagen" v-for="error in errors.imagen">
                    {{error}}
                </small>
            </div>
            <br>
            <div class="img-container">
                <img :src="tipoObstaculo.src_imagen" alt="imagen" class="img-imagen" v-if="tipoObstaculo.src_imagen">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['tipoObstaculo', 'errors'],
        methods: {
            readFile(event) {
                if (event.target.files && event.target.files.length > 0) {
                    let file = event.target.files[0];

                    let sizeByte = file.size;
                    let sizekiloBytes = Number(sizeByte) / 1024;

                    if (sizekiloBytes <= 20480) {
                        let reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = () => {
                            this.tipoObstaculo.imagen_file = {
                                filename: file.name,
                                filetype: file.type,
                                value: reader.result.split(',')[1]
                            };

                            this.tipoObstaculo.src_imagen = reader.result;
                        };
                    }
                }
            }
        }
    }
</script>