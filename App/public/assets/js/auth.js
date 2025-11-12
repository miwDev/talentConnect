// auth.js
window.addEventListener("load", function() {
    
    fetch('/API/ApiLogin.php', {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            sessionStorage.setItem("token", data.token);
            sessionStorage.setItem("role", data.role);
            loadUserProfile(data.token, data.role);
        }
    })
    .catch(error => {
        console.error('Error obteniendo token:', error);
    });
    
});

function loadUserProfile(userToken, role) {
    const pfpAlumno = document.getElementById("alumnoPfp");
    const username = document.getElementById("user-area-username");
    const cerrarSesion = document.getElementById("cerrarSesion");

    if (cerrarSesion) {
        cerrarSesion.addEventListener("click", function(ev) {
            ev.preventDefault();
            
            fetch("/API/ApiLogin.php?process=logout", {
                method: "POST",
                headers: {
                    'Authorization': 'Bearer ' + userToken
                }
            })
            .then(response => {
                if (response.status === 204) {
                    sessionStorage.removeItem('token');
                    sessionStorage.removeItem('role');
                    console.log("Logout exitoso. Token local eliminado.");
                    window.location.href = '?menu=home';
                } else if (response.status === 401) {
                    sessionStorage.removeItem('token');
                    sessionStorage.removeItem('role');
                    throw new Error("Token inválido o no autorizado.");
                }
            })
            .catch(error => {
                console.error("Fallo durante el proceso de logout:", error.message);
                sessionStorage.removeItem('token');
                sessionStorage.removeItem('role');
                window.location.href = '?menu=home';
            });
        });
    }
    
    if (userToken && role === "ROLE_ALUMNO") {
        
        if (!pfpAlumno || !username) {
            console.warn('Elementos de Alumno no encontrados en el DOM');
            return;
        }

        fetch("/API/ApiAlumno.php?process=pfp", {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + userToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                pfpAlumno.src = data.pictureRoute;
                username.textContent = data.username;
            } else {
                console.error('Success es false');
            }
        })
        .catch(error => {
            console.error('Error cargando perfil:', error);
            pfpAlumno.src = "/public/assets/images/cross.svg";
            username.textContent = "Error";
        });

    } else if(userToken && role === "ROLE_EMPRESA"){
        
        console.log("EMPRESA #TODO")

    } else if (userToken && role === "ROLE_ADMIN") {
        
        console.log("Admin logueado.");
        
    }
}