window.addEventListener("load", function(){

    const tbody = document.querySelectorAll("tbody")[0];
    const btnBorrar = document.getElementById("borrar");
    const btnAdd = document.getElementById("add");
    let posiFila = -1;

    // Rutas actualizadas
    fetch("/mock/alumno.json")
        .then((x)=>x.json())
        .then((json)=>pintarTabla(json))

    function pintarTabla(json){
        console.log(json);

        size = json.length

        for(let i = 0; i<size; i++){
            let fila = document.createElement("tr");
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            c1.innerHTML=json[i].id;
            c2.innerHTML=json[i].nombre;
            c3.innerHTML=json[i].apellidos;
            c4.innerHTML=json[i].email;
            fila.appendChild(c1);
            fila.appendChild(c2);
            fila.appendChild(c3);
            fila.appendChild(c4);

            fila.addEventListener("click", function(){
                posiFila = i;
                let form = document.createElement("form");
                let id = document.createElement("input");
                    id.setAttribute("type", "text");
                 let nombre = document.createElement("input");
                    nombre.setAttribute("type", "text");
                 let apellido = document.createElement("input");
                    apellido.setAttribute("type", "text");
                let email = document.createElement("input");
                    email.setAttribute("type", "text");
                let direccion = document.createElement("input");
                    direccion.setAttribute("type", "text");
                let ciclo = document.createElement("input");
                    ciclo.setAttribute("type", "text");

                form.appendChild(id);
                form.appendChild(nombre);
                form.appendChild(apellido);
                form.appendChild(email);
                form.appendChild(direccion);
                form.appendChild(ciclo);
                
                // Ruta actualizada
                fetch("/mock/alumno12.json")
                    .then((x) => x.json())
                    .then((json) => {
                        id.value = json.id;
                        nombre.value = json.nombre;
                        apellido.value = json.apellidos; 
                        email.value = json.email;
                        direccion.value = json.direccion;
                        ciclo.value = json.ciclo;
                    });

                document.body.appendChild(form);
                
            })

            tbody.appendChild(fila);
        }

        btnBorrar.addEventListener("click", function(){
            // Ruta actualizada
            fetch("/mock/borrar8.json")
                .then((x)=>x.json())
                .then((json)=>alert("Alumno 8 borrado = " + json.borrado))
        })

        btnAdd.addEventListener("click", function(){
            // Ruta actualizada
            fetch("/mock/crearUsuario.json")
                .then((x)=>x.json())
                .then((json)=>{
                    
                    let fila = document.createElement("tr");
                    let c1 = document.createElement("td");
                    let c2 = document.createElement("td");
                    let c3 = document.createElement("td");
                    let c4 = document.createElement("td");
                    c1.innerHTML=json.id;
                    c2.innerHTML=json.nombre;
                    c3.innerHTML=json.apellidos;
                    c4.innerHTML=json.email;
                    fila.appendChild(c1);
                    fila.appendChild(c2);
                    fila.appendChild(c3);
                    fila.appendChild(c4);
                    
                    tbody.appendChild(fila);
                })
        })
    }
})