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
    this.erroresSpans = this.form.querySelectorAll('span.input-error-message');
    this.erroresSpans.forEach(errorSpan => { 
        errorSpan.textContent = '';
    });
    const generalError = this.form.querySelector('#login-error-display');
    if (generalError) {
        generalError.textContent = '';
    }
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

    if(!dni.value.esDni()){
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

    const familias = this.form.querySelectorAll('select[name^="familia["]');
    
    if (familias.length === 0) {
        this.displayError('login-error-display', "Debe añadir al menos un estudio.");
        esValido = false;
    } else {
        familias.forEach((familiaSelect) => {
            const idIndex = familiaSelect.id.split('_')[1];
            const cicloSelect = this.form.querySelector(`#ciclo_${idIndex}`);
            const fechaInicioInput = this.form.querySelector(`#fecha_inicio_${idIndex}`);
            const fechaFinInput = this.form.querySelector(`#fecha_fin_${idIndex}`);

            if (familiaSelect.value === "-1") {
                this.displayError(`familia_${idIndex}`, "Seleccione una familia");
                esValido = false;
            } else {
                if (cicloSelect.value === "-1") {
                    this.displayError(`ciclo_${idIndex}`, "Seleccione un ciclo");
                    esValido = false;
                } else {
                    if (!fechaInicioInput.value) {
                        this.displayError(`fecha_inicio_${idIndex}`, "Campo requerido");
                        esValido = false;
                    } else if (!fechaInicioInput.value.esFecha()) { 
                        this.displayError(`fecha_inicio_${idIndex}`, "Fecha inválida (YYYY-MM-DD)");
                        esValido = false;
                    }

                    // --- CAMBIO AQUÍ ---
                    // El campo es opcional. Solo se valida si NO está vacío.
                    if (fechaFinInput.value && !fechaFinInput.value.esFecha()) { 
                        this.displayError(`fecha_fin_${idIndex}`, "Fecha inválida (YYYY-MM-DD)");
                        esValido = false;
                    }
                    // --- FIN DEL CAMBIO ---
                }
            }
        });
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
              alert("¡Registro exitoso! Bienvenido.");
              window.location.href = data.redirect;
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