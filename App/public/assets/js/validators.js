window.addEventListener("load", function(){

    // Validacion de cadenas

    const nombreRegexp = /^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+([ '\-][A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+)*$/;

    String.prototype.esNombreValido = function() {
        return nombreRegexp.test(this);
    };

    const emailRegexp = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    String.prototype.esEmailValido = function() {
        return emailRegexp.test(this);
    };

    const passwordRegexp = /^.{8,}$/;

    String.prototype.esContrasenaValida = function() {
        return passwordRegexp.test(this);
    };

    const telefonoRegexp = /^(\+34|0034)?[ -]?[679]([ -]?\d){8}$/;

    String.prototype.esTelefonoValido = function() {
        return telefonoRegexp.test(this);
    };

    const direccionRegexp = /^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9\s,.\-ºª/]+$/;

    String.prototype.esDireccionValida = function() {
        return direccionRegexp.test(this);
    };

    String.prototype.esComentarioValido = function() {
        const texto = this.trim();
        return texto.length > 0 && texto.length <= 250;
    };


    String.prototype.esFecha=function(){
        let p = this.split("-");
        let respuesta = false;

        if(p.length==3){
            let f=new Date(p[0],p[1]-1,p[2]); 
            if(f!="Invalid Date"){
                if(f.getFullYear()==p[0] && 
                    f.getMonth()==p[1]-1 && 
                    f.getDate()==p[2]){
                        respuesta=true;
                }
            }
        }
        return respuesta;
    }

    String.prototype.esDni=function(){
        let respuesta = false;
        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        let p=(/^(\d{8})([TRWAGMYFPDXBNJZSQVHLCKE])$/).exec(this.toUpperCase());
        if(p!=null){
            if(letras[p[1]%23]==p[2]){
                respuesta = true;
            }
        }
        return respuesta;
    }

})