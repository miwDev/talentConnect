// // window.addEventListener("load", function(){
// //     const userToken = sessionStorage.getItem('token');
// //     const role = sessionStorage.getItem('role');
// //     const pfpAlumno = document.getElementById("alumnoPfp");
// //     const username = document.getElementById("user-area-username");
// //     const cerrarSesion = document.getElementById("cerrarSesion");


// //     if (userToken) {
// //         switch (role) {
// //             case "ROLE_ALUMNO":
// //                 fetch("/API/ApiAlumno.php?process=pfp", {
// //                     method: "GET",
// //                     headers: {
// //                         'Authorization': 'Bearer ' + userToken
// //                     }
// //                 })
// //                 .then(response => {
// //                     if (!response.ok) {
// //                         throw new Error(`HTTP error! status: ${response.status}`);
// //                     }
// //                     return response.json();
// //                 })
// //                 .then(data => {
// //                     if (data.success) {
// //                         pfpAlumno.src = data.pictureRoute;
// //                         username.textContent = data.username;
// //                     } else {
// //                         console.error('Success es false');
// //                     }
// //                 })
// //                 .catch(error => {
// //                     console.error('Error en fetch:', error);
// //                     pfpAlumno.src = "/public/assets/images/cross.svg";
// //                     username.textContent = "FETCH ERROR";
// //                 });
// //                 break;
// //             case "ROLE_EMPRESA":
// //                 //logica pfp empresa
// //                 break;
// //             case "ROLE_ADMIN":
// //                 break;
// //         }
// //     } else {
// //         window.location.href = '?menu=home'; 
// //     }

// //     if (cerrarSesion) {
// //         cerrarSesion.addEventListener("click", (ev) => {
// //             ev.preventDefault();

// //             fetch("/API/ApiLogin.php?process=logout", {
// //                 method: "POST",
// //                 headers: {
// //                     'Authorization': 'Bearer ' + userToken
// //                 }
// //             })
// //             .then(response => {
// //                 if (response.status === 204) {
// //                     sessionStorage.removeItem('token');
// //                     sessionStorage.removeItem('role');
// //                     console.log("Logout exitoso. Token local eliminado.");
// //                     window.location.href = '?menu=home'; 
// //                 } else if(response.status === 401){
// //                     sessionStorage.removeItem('token');
// //                     throw new Error("Token inválido o no autorizado.");
// //                 }
// //             })
// //             .catch(error => {
// //                 console.error("Fallo durante el proceso de logout:", error.message);
// //             })
// //         })
// //     }

// // })



// window.addEventListener("load", function() {
//     const waitForToken = setInterval(() => {
//         const userToken = sessionStorage.getItem('token');
        
//         if (userToken) {
//             clearInterval(waitForToken);
//             initializePfpLogic(userToken);
//         }
//     }, 100); // Revisa cada 100ms
    
//     // Timeout de seguridad (5 segundos)
//     setTimeout(() => {
//         clearInterval(waitForToken);
//         const userToken = sessionStorage.getItem('token');
//         if (!userToken) {
//             console.error('Token no disponible después de 5 segundos');
//             window.location.href = '?menu=home';
//         }
//     }, 5000);
// });

// function initializePfpLogic(userToken) {
//     const role = sessionStorage.getItem('role');
//     const pfpAlumno = document.getElementById("alumnoPfp");
//     const username = document.getElementById("user-area-username");
//     const cerrarSesion = document.getElementById("cerrarSesion");


//     if (userToken) {
//         switch (role) {
//             case "ROLE_ALUMNO":
//                 fetch("/API/ApiAlumno.php?process=pfp", {
//                     method: "GET",
//                     headers: {
//                         'Authorization': 'Bearer ' + userToken
//                     }
//                 })
//                 .then(response => {
//                     if (!response.ok) {
//                         throw new Error(`HTTP error! status: ${response.status}`);
//                     }
//                     return response.json();
//                 })
//                 .then(data => {
//                     if (data.success) {
//                         pfpAlumno.src = data.pictureRoute;
//                         username.textContent = data.username;
//                     } else {
//                         console.error('Success es false');
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Error en fetch:', error);
//                     pfpAlumno.src = "/public/assets/images/cross.svg";
//                     username.textContent = "FETCH ERROR";
//                 });
//                 break;
//             case "ROLE_EMPRESA":
//                 //logica pfp empresa
//                 break;
//             case "ROLE_ADMIN":
//                 break;
//         }
//     } else {
//         window.location.href = '?menu=home'; 
//     }

//     if (cerrarSesion) {
//         cerrarSesion.addEventListener("click", (ev) => {
//             ev.preventDefault();

//             fetch("/API/ApiLogin.php?process=logout", {
//                 method: "POST",
//                 headers: {
//                     'Authorization': 'Bearer ' + userToken
//                 }
//             })
//             .then(response => {
//                 if (response.status === 204) {
//                     sessionStorage.removeItem('token');
//                     sessionStorage.removeItem('role');
//                     console.log("Logout exitoso. Token local eliminado.");
//                     window.location.href = '?menu=home'; 
//                 } else if(response.status === 401){
//                     sessionStorage.removeItem('token');
//                     throw new Error("Token inválido o no autorizado.");
//                 }
//             })
//             .catch(error => {
//                 console.error("Fallo durante el proceso de logout:", error.message);
//             })
//         })
//     }
// }