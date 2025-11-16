window.addEventListener("load", () => {

    const userToken = sessionStorage.getItem("token");

    const botonesApply = document.querySelectorAll(".btnApply");
    const botonesFav = document.querySelectorAll(".btnFav");
    const companyData = document.querySelectorAll(".card-CompanyInfo");
    
    
    companyData.forEach(profile => {
        const empresaid = profile.querySelector("#idEmpresa");
        const profilePic = profile.querySelector(".companyPfp");
        const username = profile.querySelector(".companyName");
        fetch(`/API/ApiEmpresa.php?process=offerProfile&id=${empresaid.textContent}`, {
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
                profilePic.src = data.pictureRoute;
                username.textContent = data.username;
            } else {
                console.error('Success es false');
            }
        })
        .catch(error => {
            console.error('Error cargando perfil:', error);
            profilePic.src = "/public/assets/images/cross.svg";
            username.textContent = "Error";
        });

        
    });
    
    botonesApply.forEach(btn => {
    const card = btn.closest('.oferta-card');
    const companyProf = card.querySelector(".companyPfp");
    const companyName = card.querySelector(".companyName");
    const cardTitle = card.querySelector(".card-titulo");
    
    btn.addEventListener("click", (e) => {
        // Asegúrate de que 'crearModal' crea el contenedor general del modal
        // y el elemento con ID="modalContent"
        crearModal(); 
        const modalContent = document.getElementById("modalContent");
        
        // 1. Contenedor principal del cuerpo
        const divContent = document.createElement("div");
        // CLASE: Contenedor principal del cuerpo
        divContent.classList.add("modal-body-container");
        
        // 2. Cabecera (Título y Empresa)
        const divHeader = document.createElement("div");
        // CLASE: Cabecera con título y empresa
        divHeader.classList.add("modal-header-content");
        
        // 3. Título de la Oferta
        const divTitleWrapper = document.createElement("div");
        // CLASE: Envoltorio del título
        divTitleWrapper.classList.add("modal-title-wrapper");
        
        const titulo = document.createElement("h2");
        titulo.textContent = cardTitle.textContent;
        divTitleWrapper.appendChild(titulo);
        
        // 4. Info de la Empresa (Logo y Nombre)
        const divCompany = document.createElement("div");
        // CLASE: Info de la empresa
        divCompany.classList.add("modal-company-info");
        
        const companyProfClone = companyProf.cloneNode(true);
        // Asegurarse de que el logo clonado tenga la clase 'companyPfp'
        companyProfClone.classList.add("companyPfp"); 
        
        const companyNameClone = companyName.cloneNode(true);
        // Asegurarse de que el nombre clonado tenga la clase 'companyName'
        companyNameClone.classList.add("companyName");
        
        divCompany.appendChild(companyProfClone);
        divCompany.appendChild(companyNameClone);
        
        // Estructura la cabecera
        divHeader.appendChild(divTitleWrapper);
        divHeader.appendChild(divCompany);
        
        // 5. Cuerpo (Textarea)
        const divBody = document.createElement("div");
        // CLASE: Cuerpo del modal (que contendrá el textarea)
        divBody.classList.add("modal-body-comments");
        
        const comentarios = document.createElement("textarea");
        // CLASE: Textarea
        comentarios.classList.add("modal-textarea");
        comentarios.placeholder = "Añade un comentario...";
        divBody.appendChild(comentarios);
        
        // Agrega Header y Body al Content
        divContent.appendChild(divHeader);
        divContent.appendChild(divBody);
        
        // 6. Contenedor de Botones
        const divBtns = document.createElement("div");
        // CLASE: Contenedor de botones
        divBtns.classList.add("modal-actions");
        
        const btnConfirmar = document.createElement("button");
        // CLASE: Botón confirmar
        btnConfirmar.classList.add("btn-confirm");
        btnConfirmar.textContent = "Confirmar";
        
        const btnCancelar = document.createElement("button");
        // CLASE: Botón cancelar
        btnCancelar.classList.add("btn-cancel");
        btnCancelar.textContent = "Cancelar";
        
        divBtns.appendChild(btnConfirmar);
        divBtns.appendChild(btnCancelar);
        
        // Agrega todo al modalContent
        modalContent.appendChild(divContent);
        modalContent.appendChild(divBtns);
        
        // Agrega lógica de cierre/confirmación (dejado como estaba)
        btnCancelar.addEventListener("click", () => {
            window.cerrarModal();
        });
        
        btnConfirmar.addEventListener("click", () => {
            // Lógica para confirmar la aplicación
        });
    });
});

    botonesFav.forEach(btn => {
        btn.addEventListener("click", (e) => {
            console.log(btn.value);
        });
    });
});