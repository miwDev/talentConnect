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

        const btnContainer = btn.parentNode;
        
        btn.addEventListener("click", function(){
            crearModal(); 
            // Correcto: 'this' es el botón btnApply, su valor es el offerId
            const offerId = this.value; 
            const modalContent = document.getElementById("modalContent");
            
            const divContent = document.createElement("div");
            divContent.classList.add("modal-body-container");
            
            const divHeader = document.createElement("div");
            divHeader.classList.add("modal-header-content");
            
            const divTitleWrapper = document.createElement("div");
            divTitleWrapper.classList.add("modal-title-wrapper");
            
            const titulo = document.createElement("h2");
            titulo.textContent = cardTitle.textContent;
            divTitleWrapper.appendChild(titulo);
            
            const divCompany = document.createElement("div");
            divCompany.classList.add("modal-company-info");
            
            const companyProfClone = companyProf.cloneNode(true);
            companyProfClone.classList.add("companyPfp"); 
            
            const companyNameClone = companyName.cloneNode(true);
            companyNameClone.classList.add("companyName");
            
            divCompany.appendChild(companyProfClone);
            divCompany.appendChild(companyNameClone);
            
            divHeader.appendChild(divTitleWrapper);
            divHeader.appendChild(divCompany);
            
            const divBody = document.createElement("div");
            divBody.classList.add("modal-body-comments");
            
            const comentarios = document.createElement("textarea");
            comentarios.classList.add("modal-textarea");
            comentarios.placeholder = "Añade un comentario...";
            divBody.appendChild(comentarios);
            
            divContent.appendChild(divHeader);
            divContent.appendChild(divBody);
            
            const divBtns = document.createElement("div");
            divBtns.classList.add("modal-actions");
            
            const btnConfirmar = document.createElement("button");
            btnConfirmar.classList.add("btn-confirm");
            btnConfirmar.textContent = "Confirmar";
            
            const btnCancelar = document.createElement("button");
            btnCancelar.classList.add("btn-cancel");
            btnCancelar.textContent = "Cancelar";
            
            divBtns.appendChild(btnConfirmar);
            divBtns.appendChild(btnCancelar);
            
            modalContent.appendChild(divContent);
            modalContent.appendChild(divBtns);
            
            btnCancelar.addEventListener("click", () => {
                window.cerrarModal();
            });
            
            btnConfirmar.addEventListener("click", function(){
                const comentario = comentarios.value;
                console.log(comentario);
                console.log(offerId);
                fetch("/API/ApiSolicitud.php", {
                method: "POST",
                headers: {
                    'Authorization': 'Bearer ' + userToken
                },
                body: JSON.stringify({ 
                    ofertaId: offerId,
                    comentario: comentario,
                    finalizado:  true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Hell yea");
                    
                    window.cerrarModal();
                    
                    const appliedMessage = document.createElement("p");
                    appliedMessage.textContent = "Already Applied!";
                    appliedMessage.classList.add('applied-success-message');
                    
                    if (btnContainer) {
                        btnContainer.insertBefore(appliedMessage, btn);
                        btn.style.display = "none";
                    } else {
                        btn.replaceWith(appliedMessage);
                    }
                    
                    btn.removeEventListener("click", this);

                }else{
                    console.log("Hell naah");
                }
            });
        });
    });
    
});

    botonesFav.forEach(btn => {
        btn.addEventListener("click", function(e){

        });
    });
});