<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Jefe de RRHH</li>
                <li class="breadcrumb-item">Mantenimiento</li>
                <li class="breadcrumb-item">Gestión de Puestos y Salarios</li>
                <li class="breadcrumb-item active">Categorías Salariales</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías Salariales
                        <button @click="abrirModal('categoriaSalarial', 'registrar')" type="button" class="btn btn-success btn-nuevo">
                            <i class="icon-plus"></i> <span style="margin-left:2%">Nuevo</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="id_categoria_salarial">ID</option>
                                      <option value="salario_minimo">Salario Mínimo</option>
                                      <option value="salario_maximo">Salario Máximo</option>
                                      <option value="id_catalogo_isr">Tramo ISR</option>
                                      <option value="todos">Todos</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCategoriasSalariales(1, buscar, criterio)" class="form-control col-md-5" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCategoriasSalariales(1, buscar, criterio)" class="btn btn-primary btn-buscar"><i class="fa fa-search"></i> Buscar</button>
                                </div>             
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="text-align: center">ID</th>
                                    <th>Salario Mínimo</th>
                                    <th>Salario Máximo</th>
                                    <th>Tramo ISR</th>
                                    <th style="text-align: center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoriaSalarial in arrayCategoriaSalarial" :key="categoriaSalarial.id_categoria_salarial">
                                    <td v-text="categoriaSalarial.id_categoria_salarial" style="text-align: center"></td>
                                    <td v-text="categoriaSalarial.salario_minimo" style="text-align: right"></td>
                                    <td v-text="categoriaSalarial.salario_maximo" style="text-align: right"></td>
                                    <td v-text="categoriaSalarial.id_catalogo_isr" style="text-align: center"></td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="abrirModal('categoriaSalarial', 'actualizar', categoriaSalarial)" class="btn btn-warning btn-sm btn-circle-text-white">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModalEliminar(categoriaSalarial)" class="btn btn-danger btn-sm btn-circle">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary" role="document" style="border-radius: 4px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding:4%">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> 
                                <div class="form-group">
                                    <label for="salario_minimo">Salario Mínimo</label>
                                    <input type="number" step="0.01" min="0" v-model="salario_minimo" class="form-control" placeholder="00.00">
                                </div>
                                <div class="form-group">
                                    <label for="salario_maximo">Salario Máximo</label>
                                    <input type="number" step="0.01" min=salario_minimo v-model="salario_maximo" class="form-control" placeholder="00.00">
                                </div>
                                <div class="form-group">
                                    <label for="id_catalogo_isr">Tramo según Impuesto Sobre Renta</label>
                                    <select v-model="id_catalogo_isr" class="form-control" name="id_catalogo_isr">
                                        <option value="" selected>-- Seleccione el tramo del ISR --</option>
                                        <option value="1">Salario anual menor a $4,064.00</option>
                                        <option value="2">Salario anual entre $4,064.01 y $9,142.86</option>
                                        <option value="3">Salario anual entre $9,142.87 y $22,857.14</option>
                                        <option value="4">Salario anual mayor a $22,857.14</option>
                                    </select>
                                </div>                                    
                                <div v-show="errorCategoriaSalarial" class="form-group div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoriaSalarial" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" @click="registrarCategoriaSalarial()" class="btn btn-primary btn-circle">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarCategoriaSalarial()"  class="btn btn-primary btn-circle">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" :class="{'mostrar' : modalEliminar}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Categoría Salarial</h4>
                            <button type="button" class="close" @click="cerrarModalEliminar()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estas seguro de eliminar la categoría salarial? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModalEliminar()">Cerrar</button>
                            <button type="button" class="btn btn-danger btn-circle" @click="eliminarCategoriaSalarial(id_categoria_salarial)">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>
    export default {
        data(){
            return{
                id_categoria_salarial:0,
                salario_minimo:'',
                salario_maximo:'',
                id_catalogo_isr:'',
                arrayCategoriaSalarial: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorCategoriaSalarial: 0,
                errorMostrarMsjCategoriaSalarial: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,                    
                },
                offset: 3,
                criterio: 'id_categoria_salarial', //Antes era 'Nombre'
                buscar: '',
                modalEliminar: 0
            }
        },

        //LOGICA DE PAGINACION
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },

        //METODOS UTILIZADOS PARA ACCIONAR BOTONES Y VISUALIZAR MODALS
        methods: {

            //LISTAR OBJETOS
            listarCategoriasSalariales(page, buscar, criterio){
                if(criterio=='todos'){ criterio='id_categoria_salarial'; buscar='';}//Antes decía 'Nombre'

                let me=this;
                var url= '/categoriaSalarial?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.arrayCategoriaSalarial = respuesta.categorias_salariales.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },

            //PAGINACION: CAMBIO DE PAGINA
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCategoriasSalariales(page, buscar, criterio);
            },

            //REGISTRAR OBJETO
            registrarCategoriaSalarial(){
                if (this.validarCategoriaSalarial()){
                    return;
                }

                let me=this;
                axios.post('/categoriaSalarial/registrar', {
                    'salario_minimo': this.salario_minimo,
                    'salario_maximo': this.salario_maximo,
                    'id_catalogo_isr': this.id_catalogo_isr
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarCategoriasSalariales(1, '', 'id_categoria_salarial');//Antes decía nombre
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //ACTUALIZAR OBJETO
            actualizarCategoriaSalarial(){
                if (this.validarCategoriaSalarial()){
                    return;
                }

                let me=this;
                axios.put('/categoriaSalarial/actualizar', {
                    'salario_minimo': this.salario_minimo,
                    'salario_maximo': this.salario_maximo,
                    'id_catalogo_isr': this.id_catalogo_isr,
                    'id_categoria_salarial': this.id_categoria_salarial
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarCategoriasSalariales(1, '', 'id_categoria_salarial');//Antes decía 'Nombre'
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //VALIDACION DE CAMPOS
            validarCategoriaSalarial(){
                this.errorCategoriaSalarial = 0;
                this.errorMostrarMsjCategoriaSalarial = [];

                if (!this.salario_minimo) this.errorMostrarMsjCategoriaSalarial.push("La cantidad mínima de salario no puede estar vacía.");
                if (!this.salario_maximo) this.errorMostrarMsjCategoriaSalarial.push("La cantidad máxima de salario no puede estar vacía.");
                if (this.salario_minimo<0.01 || this.salario_maximo<0.01) this.errorMostrarMsjCategoriaSalarial.push("La cantidad debe de ser mayor que 0");
                if (!this.id_catalogo_isr) this.errorMostrarMsjCategoriaSalarial.push("Debe seleccionar la categoría de ISR.");

                if (this.errorMostrarMsjCategoriaSalarial.length) this.errorCategoriaSalarial = 1;

                return this.errorCategoriaSalarial;
            },

            //CERRAR MODAL
            cerrarModal(){
                this.modal = 0;
                this.salario_minimo = '';
                this.salario_maximo = '';
                this.id_catalogo_isr = '';
                this.tituloModal = '';
                this.errorMostrarMsjCategoriaSalarial = [];
                this.errorCategoriaSalarial = 0;
            },

            //ABRIR MODAL REGISTRAR/ACTUALIZAR
            abrirModal(modelo, accion, data = []){
            switch(modelo)
            {
                case "categoriaSalarial":
                    {
                        switch(accion){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.salario_minimo = '';
                                    this.salario_maximo = '';
                                    this.id_catalogo_isr = '';
                                    this.tituloModal = 'Registrar Categoría Salarial';
                                    this.tipoAccion = 1;
                                    break
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.salario_minimo = data['salario_minimo'];
                                    this.salario_maximo = data['salario_maximo'];
                                    this.id_catalogo_isr = data['id_catalogo_isr'];
                                    this.id_categoria_salarial = data['id_categoria_salarial'];
                                    this.tituloModal = 'Actualizar Categoría Salarial';
                                    this.tipoAccion = 2;
                                    break
                                }
                        }
                    }
            }
        },

        //ELIMINAR Categoría Salarial
        eliminarCategoriaSalarial(id_categoria_salarial){
            let me=this;
            axios.put('/categoriaSalarial/eliminar', {
                'id_categoria_salarial': this.id_categoria_salarial
            })
            .then(function (response) {
                me.cerrarModalEliminar();
                me.listarCategoriasSalariales(1, '', 'id_categoria_salarial');//Antes decía "nombre"
            }).catch(function (error) {
                console.log(error);
            });
        },
        
        //ABRIR MODAL ELIMINAR
        abrirModalEliminar(data = []){
            this.modalEliminar = 1;
            //this.nombre = data['nombre']; //¿Qué debería poner aquí?
            this.id_categoria_salarial = data['id_categoria_salarial'];
        },

        //CERRAR MODAL
        cerrarModalEliminar(){
            this.modalEliminar = 0;
            this.nombre = '';
            this.id_categoria_salarial = 0;
        },

    },

    //PARTE DEL CICLO DE VIDA DE VUE
    mounted() {
        this.listarCategoriasSalariales(1, this.buscar, this.criterio);
    }
    }
</script>

<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: normal !important;
        font-style:italic;
    }
    .btn-circle{
        border-radius: 4px;
    }
    .btn-circle-text-white{
        border-radius: 4px; color:white;
    }
    .btn-nuevo{
        margin-left:1%;
        margin-top:0.2% !important; 
        border-radius: 6px; 
        color:white !important;
    }
    .btn-buscar{
        border-top-right-radius:4px;
        border-bottom-right-radius:4px;
    }
</style>
