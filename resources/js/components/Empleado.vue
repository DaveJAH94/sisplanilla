<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Administrador</a></li>
                <li class="breadcrumb-item active">Empleados</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Empleados
                        <button type="button" @click="abrirModal('empleado','registrar')" class="btn btn-secondary" >
                            <i class="icon-plus" ></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="primer_nombre,segundo_nombre">Nombres de Empleado</option>
                                      <option value="primer_apellido,segundo_apellido">Apellidos de Empleado</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEmpleado(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEmpleado(1,buscar,criterio)"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Primer Nombre</th>
                                    <th>Segundo Nombre</th>
                                    <th>Primer Apellido</th>
                                    <th>Segundo Apellido</th>
                                    <th>Email-Personal</th>
                                    <th>Email-Institucional</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="empleado in arrayEmpleado" :key="empleado.id">

                                    <td style="text-aling:center;">
                                        <button type="button" @click="abrirModal('empleado','actualizar',empleado)" class="btn btn-warning btn-sm"  >
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="empleado.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarEmpleado(empleado.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>   
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarEmpleado(empleado.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template> 
                                    </td>
                                    <td v-text="empleado.primer_nombre"></td>
                                    <td v-text="empleado.segundo_nombre"></td>
                                    <td v-text="empleado.primer_apellido"></td>
                                    <td v-text="empleado.segundo_apellido"></td>
                                    <td v-text="empleado.email_personal"></td>
                                    <td v-text="empleado.email_institucional"></td>
                                    
                                    <td>
                                        <div v-if="empleado.condicion">
                                              <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                              <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page>1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page-1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item " v-for="page in pagesNumber" :key="page" :class="[page==isActived? 'active' :'']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                        
                                <li class="page-item" v-if="pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; overflow-y:auto;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true" style="Color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding:4%">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                  <label class="col-md-5 form-control-label" for="text-input"><b>Label Datos según DUI</b></label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nombres</label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="primer_nombre" class="form-control" placeholder="Primer nombre">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" v-model="segundo_nombre" class="form-control" placeholder="Segundo nombre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text_input">Apellidos</label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="primer_apellido" class="form-control" placeholder="Primer Apellido">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" v-model="segundo_apellido" class="form-control" placeholder="Segundo Apellido">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo Personal</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email_personal" class="form-control" placeholder="Ingrese Correo Personal">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text_input">Correo Institucional</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email_institucional" class="form-control" placeholder="Ingrese Correo Institucional">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text_input">Fecha Nacimiento</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="fecha_nacimiento" class="form-control" placeholder="Ingresela en el formato dd-mm-yyy">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text_input">Identificador ISSS</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="identificador_isss" class="form-control" placeholder="Ingrese el identificador de ISSS">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text_input">Identificador NUP</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="identificador_nup" class="form-control" placeholder="Ingrese el identificador de NUP">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text_input">Identificador NIT</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="identificador_nit" class="form-control" placeholder="Ingrese el identificador de NIT">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text_input">Numero de dentificacion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="numero_documento_identificacion" class="form-control" placeholder="Ingrese DUI">
                                    </div>
                                </div>
                                <div class="form-group row">
                                <div class="col-md-6 form-control-label">
                                    <label for="text_input">Codigo de profesion</label>
                                    <select v-model="codigo_profesion" class="form-control">
                                        <option value="0" selected>-- Seleccione el codigo de profesion --</option>
                                        <option v-for="profesion in arraycodigo_profesion" :key="profesion.codigo_profesion" :value="profesion.codigo_profesion" v-text="profesion.nombre"></option>
                                    </select>
                                 </div>
                                </div>
                                <div class="form-group row">
                                 <div class="col-md-6 form-control-label">
                                    <label for="direcciones">Direccion</label>
                                    <select v-model="codigo_profesion" class="form-control">
                                        <option value="0" selected>-- Seleccione su Ubicacion --</option>
                                        <option v-for="direcciones in arrayid_direccion" :key="direcciones.id_direccion" :value="direcciones.Ubicacion" v-text="direcciones.Ubicacion"></option>
                                    </select>
                                    
                                </div>
                                </div>
                                <div class="form-group row">
                                 <div class="col-md-6 form-control-label">
                                    <label for="estados_civiles">Estado Civil</label>
                                    
                                    <select v-model="estados_civiles" class="form-control">
                                        
                                        <option value="0" selected>-- Seleccione el Estado Civil --</option>
                                        <option v-for="estados_civiles in arrayid_estado_civil" :key="estados_civiles.id_estado_civil" :value="estados_civiles.id_estado_civil" v-text="estados_civiles.titulo"></option>
                                        
                                    </select>
                                    
                                </div>
                                </div>
                                <div class="form-group row">
                                <div class="col-md-6 form-control-label">
                                    <label for="generos">Genero</label>
                                    
                                    <select v-model="generos" class="form-control">
                                        <option value="0" selected>-- Seleccione el Genero --</option>
                                        <option v-for="generos in array" :key="generos.id_genero" :value="generos.id_genero" v-text="generos.titulo"></option>
                                    </select>
                                    
                                </div>
                                </div>
                                 <div class="form-group row">
                                 <div class="col-md-6 form-control-label">
                                    <label for="contactos_telefonicos">Contacto</label>
                                    
                                    <select v-model="contactos_telefonicos" class="form-control">
                                        <option value="0" selected>-- Seleccione el tipo de contacto Telefonico --</option>
                                        <option v-for="contactos_telefonicos in arrayProfesion" :key="contactos_telefonicos.id_contacto_telefonico" :value="contactos_telefonicos.id_contacto_telefonico" v-text="contactos_telefonicos.numero"></option>
                                    </select>
                                    
                                </div>
                                 </div>
                                
                                <div v-show="errorEmpleado" class="form-group row div-error">
                                       <div class="text-center text-error">

                                           <div v-for="error in errorMostrarMsjEmpleado" :key="error" v-text="error">

                                           </div>

                                       </div> 
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEmpleado()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpleado()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
          
        </main>
</template>

<script>
    export default {
        data(){
          return {
  
              
              prinombre: '',
              segNombre:'',
              priApellido: '',
              segApellido:'',
              email_personal:'',
              email_institucional:'',
              fecha_nacimiento:'',
              identificador_isss:'',
              identificador_nup:'',
              identificador_nit:'',
              codigo_profesion: 0,
              id_direccion:0,
              numero_documento_identificacion:'',
              id_estado_civil:0,
              id_genero:0,
              id_contacto_telefonico:0,
              arraycodigo_profesion:[],
              arrayid_direccion:[],
              arrayid_estado_civil:[],
              arraygenero:[],
              modal:0,
              tituloModal:'',
              tipoAccion: 0,
              errorEmpleado: 0,
              errorMostrarMsjEmpleado:[],
              Empleado_id:0,
              pagination:{
                  'total':0,        
                  'current_page':0,
                  'per_page':0,     
                  'last_page':0,    
                  'from':0,         
                  'to':0           
              },
              offset:3,
              criterio:'nombre',
              buscar:''
            
          }   
        },
        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginacion
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }
                var from=this.pagination.current_page-this.offset;
                if(from<1){
                    from=1;
                }

                var to=from+(this.offset*2);
                if(to>=this.pagination.last_page){
                    to=this.pagination.last_page;
                }
                var pagesArray=[];
                while(from <=to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;

            }


        },
        methods:{
            listarEmpleado(page,buscar,criterio){
                let me=this;
                var url='/empleado?page='+ page +'&buscar='+ buscar +'&criterio='+criterio;
                axios.get(url).then(function (response) {
                     var respuesta=response.data;
                     me.arrayEmpleado=respuesta.empleados.data;
                     me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                        console.log(error);
                });
            },
            selectProfesion(){
                let me=this;
                var url= '/profesion/selectProfesion';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arraycodigo_profesion = respuesta.profesion;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            selectDireccion(){
                let me=this;
                var url= '/direccion/selectDireccion';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayid_direccion = respuesta.direccion;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            selectEstadoCivil(){
                let me=this;
                var url= '/estadocivil/selectEstadoCivil';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayid_estado_civil = respuesta.estado_civil;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            selectGenero(){
                let me=this;
                var url= '/genero/selectGenero';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arraygenero = respuesta.genero;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            selectContacto(){
                let me=this;
                var url= '/contacto/selectContacto';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayid_contacto_telefonico = respuesta.contacto;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            cambiarPagina(page,buscar,criterio){
                  let me = this;
                  //Actualizar la Pagina Actual
                  me.pagination.current_page=page; 
                  //Envia la Peticion para visualizar la data de esa Pagina
                  me.listarEmpleado(page,buscar,criterio);
            },
             desactivarEmpleado(id){
                    const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta seguro de desactivar esta Empleado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                            let me=this; 
                    axios.put('/empleado/desactivar',{
                        'id':id
                    }).then(function (response) {
                            me.listarEmpleado(1,'','primer_nombre');
                            swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El Registro ha sido desactivado con Exito.',
                            'success'
                            )
                        })
                        .catch(function (error) {
                                console.log(error);
                        });
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Operacion Cancelada',
                    'La Operacion ah sido Cancelada con Exito :)',
                    'error'
                    )
                }
                })
             },
             activarEmpleado(id){
                    const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta seguro de Activar esta Empleado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                            let me=this; 
                    axios.put('/empleado/activar',{
                        'id':id
                    }).then(function (response) {
                            me.listarEmpleado(1,'','primer_nombre');
                            swalWithBootstrapButtons.fire(
                            'Ativado!',
                            'El Registro ha sido activado con Exito.',
                            'success'
                            )
                        })
                        .catch(function (error) {
                                console.log(error);
                        });
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Operacion Cancelada',
                    'La Operacion ah sido Cancelada con Exito :)',
                    'error'
                    )
                }
                })
             },
             validarEmpleado(){
               this.errorEmpleado=0;
               this.errorMostrarMsjEmpleado=[]; 
               if(!this.primer_nombre)this.errorMostrarMsjEmpleado.push("El Nombre de la Empleado no puede estar Vacio");
               
               if(this.errorMostrarMsjEmpleado.length) this.errorEmpleado=1;

               return this.errorEmpleado;

             },
             registrarEmpleado(){
                if(this.validarEmpleado()){
                  return;

                }
                let me=this; 
               axios.post('/empleado/registrar',{
                   'primer_nombre':this.segundo_nombre,
                   'segundo_nombre':this.segundo_nombre,
                   'primer_apellido':this.primer_apellido,
                   'segundo_apellido':this.segundo_apellido,  
                   'email_personal':this.email_personal,
                   'email_institucional':this.email_institucional,
                   'fecha_nacimiento':this.fecha_nacimiento,
                   'identificador_isss':this.identificador_isss,
                   'identificador_nup':this.identificador_nup,
                   'identificador_nit':this.identificador_nit,
                   'codigo_profesion':this.codigo_profesion,
                   'id_direccion':this.id_direccion,
                   'numero_documento_identificacion':this.numero_documento_identificacion,
                   'id_estado_civil':this.id_estado_civil,
                   'id_genero':this.id_genero,
                   'id_contacto_telefonico':this.id_contacto_telefonico

               }).then(function (response) {
                     me.cerrarModal();
                     me.listarEmpleado(1,'','primer_nombre');
                })
                .catch(function (error) {
                        console.log(error);
                });

            }, 
            actualizarEmpleado(){
                if(this.validarEmpleado()){
                   return;

                 }
                let me=this; 
               axios.put('/empleado/actualizar',{
                   'primer_nombre':this.segundo_nombre,
                   'segundo_nombre':this.segundo_nombre,
                   'primer_apellido':this.primer_apellido,
                   'segundo_apellido':this.segundo_apellido,  
                   'email_personal':this.email_personal,
                   'email_institucional':this.email_institucional,
                   'fecha_nacimiento':this.fecha_nacimiento,
                   'identificador_isss':this.identificador_isss,
                   'identificador_nup':this.identificador_nup,
                   'identificador_nit':this.identificador_nit,
                   'codigo_profesion':this.codigo_profesion,
                   'id_direccion':this.id_direccion,
                   'numero_documento_identificacion':this.numero_documento_identificacion,
                   'id_estado_civil':this.id_estado_civil,
                   'id_genero':this.id_genero,
                   'id_contacto_telefonico':this.id_contacto_telefonico
               }).then(function (response) {
                     me.cerrarModal();
                     me.listarEmpleado(1,'','primer_nombre');
                })
                .catch(function (error) {
                        console.log(error);
                });
            },
            cerrarModal(){
               this.modal=0;
               this.tituloModal='';
               this.primer_nombre='';
               this.segundo_nombre='';
               this.primer_apellido='';
               this.segundo_apellido=''
            },
            abrirModal(modelo,accion,data=[]){
                switch(modelo){
                    case "empleado":
                    {
                        switch(accion){
                                 case 'registrar':
                                     {
                                          this.modal=1; 
                                          this.tituloModal='Registrar Empleado';
                                          this.primer_nombre='';
                                          this.segundo_nombre='';
                                          this.primer_apellido='';
                                          this.segundo_apellido='',
                                          this.email_personal='';
                                          this.email_institucional='';
                                          this.fecha_nacimiento='';
                                          this.identificador_isss='';
                                          this.identificador_nup='';
                                          this.identificador_nit='';
                                          this.codigo_profesion=0;
                                          this.id_direccion=0;
                                          this.numero_documento_identificacion='';
                                          this.id_estado_civil=0;
                                          this.id_genero=0;
                                          this.id_contacto_telefonico=0;

                                          this.tipoAccion= 1;
                                          break;
                                     }
                                 case 'actualizar':
                                     {
                                         //console.log(data);
                                         this.modal=2;
                                         this.tituloModal='Actualizar Empleado';
                                         this.tipoAccion=2;
                                         this.primer_nombre=data['primer_nombre'];
                                         this.segundo_nombre=data['segundo_nombre'];
                                         this.primer_apellido=data['primer_apellido'];
                                         this.segundo_apellido=data['segundo_apellido'];
                                         this.email_personal=data['email_personal']
                                         this.email_institucional=data['email_institucional'];
                                         this.fecha_nacimiento=data['fecha_nacimiento'];
                                         this.identificador_isss=data['identificador_isss'];
                                         this.identificador_nup=data['identificador_nit'];
                                         this.identificador_nit=data['identificador_nit'];
                                         this.codigo_profesion=data['codigo_profesion'];
                                         this.id_direccion=data['id_direccion'];
                                         this.numero_documento_identificacion=data['numero.documento_identificacion'];
                                         this.id_estado_civil=data['estado_civil'];
                                         this.id_genero=data['id_genero'];
                                         this.id_contacto_telefonico=data['id_contacto_telefonico'];

                                         break;
                                     }
                        } 

                    }
                }
                this.selectProfesion();
                this.selectDireccion();
                this.selectEstadoCivil();
                this.selectoGenero();
                this.selectContacto();
            }
        },
        mounted() {
            this.listarEmpleado(1,this.buscar,this.criterio);
        }
    }
</script>

<style >
       .modal-content{
           width: 100% !important;
           position:absolute !important;
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
           color:red !important;
           font-weight: bold;
       }
</style>