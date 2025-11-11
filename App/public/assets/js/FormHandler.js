class FormularioManager {

  constructor(formId) {
    this.form = document.getElementById(formId);
    this.erroresSpans = this.form ? this.form.querySelectorAll('span.input-error-message') : [];

    if (this.form) {
      this.iniciarManejador();
    } else {
        console.error("Form no encontrado");
    }
  }

  iniciarManejador() {
    this.form.addEventListener('submit', this.onSubmit.bind(this));
  }

  clearErrors(){
    this.erroresSpans.forEach(errorSpan => { 
        errorSpan.textContent = '';
    });
  }

  displayError(inputName, message) {
    const errorSpan = this.form.querySelector(`#error-${inputName}`);
    
    if (errorSpan) {
        errorSpan.textContent = message;
    }
  }

  validar(){
    let esValido = true;

    const nombre = this.form.elements['nombre'];
    const apellido = this.form.elements['apellido'];
    const dni = this.form.elements['dni'];
    const email = this.form.elements['email'];
    const password = this.form.elements['password'];
    const telefono = this.form.elements['telefono'];
    const provincia = this.form.elements['provincia'];
    const localidad = this.form.elements['localidad'];
    const direccion = this.form.elements['direccion'];

    const familia = this.form.elements['familia'];
    const ciclo = this.form.elements['ciclo'];

    const cvInput = this.form.elements['cv'];

    this.clearErrors(); 

    if(!nombre.value.esNombreValido()){
        this.displayError('nombre', "Nombre Inválido");
        esValido = false;
    }

    if(!apellido.value.esNombreValido()){
        this.displayError('apellido', "Apellido Inválido");
        esValido = false;
    }

    if(!dni.value.esDniValido()){
        this.displayError('dni', "DNI Inválido");
        esValido = false;
    }

    if(!email.value.esEmailValido()){
        this.displayError('email', "Email Inválido");
        esValido = false;
    }

    if(!password.value.esContrasenaValida()){
        this.displayError('password', "Contraseña Inválida");
        esValido = false;
    }

    if(!telefono.value.esTelefonoValido()){
        this.displayError('telefono', "Teléfono Inválido");
        esValido = false;
    }
    
    if(!provincia.value.esNombreValido()){
        this.displayError('provincia', "Provincia Inválida"); 
        esValido = false;
    }

    if(!localidad.value.esNombreValido()){
        this.displayError('localidad', "Localidad Inválida");
        esValido = false;
    }

    if(!direccion.value.esDireccionValida()){
        this.displayError('direccion', "Dirección Inválida");
        esValido = false;
    }


    if(familia.value === "-1"){
        this.displayError('familia', "Seleccione una familia profesional");
        esValido = false;
    }
    
    if(ciclo.value === "-1"){
        this.displayError('ciclo', "Seleccione un ciclo formativo");
        esValido = false;
    }
    
    if (!cvInput || cvInput.files.length === 0) {
        console.error("No se ha subido un CV");
        alert("Por favor, sube tu CV en formato PDF.");
        esValido = false;
    }
    
    return esValido;
  }

  enviarDatosAPI() {
    const formData = new FormData(this.form);
    
    fetch("/API/ApiAlumno.php?process=newStudent", {
        method: 'POST',
        body: formData
    })
    .then(response => {
        return response.text();
    })
    .then(text => {
        console.log("Respuesta cruda:", text);
        try {
            const data = JSON.parse(text);
            if(data.success){
                console.log("Éxito:", data);
            }else{
                console.error("Error:", data);
            }
        } catch(e) {
            console.error("Error parseando JSON:", e);
            console.error("Respuesta recibida:", text);
        }
    })
    .catch(error => console.error("Error de red:", error));
}


  onSubmit(event){
    event.preventDefault(); 

    const imgAvatar = document.getElementById('avatarId');
    const hiddenFotoInput = document.getElementById('hiddenFotoPerfil');
    
    if (!hiddenFotoInput.value) {
        if (!imgAvatar.src.startsWith('data:image')) {
            hiddenFotoInput.value = 'default';
        }
    }

    if (this.validar()) {
      this.enviarDatosAPI(); 
    } else {
      console.log('Formulario no enviado: Errores de validación.');
    }
  }


}