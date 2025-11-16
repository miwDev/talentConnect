window.addEventListener("load", function(){

    const userToken = sessionStorage.getItem("token");


    const botonesEdit = document.querySelectorAll(".btn-edit");
    const botonesDelete = document.querySelectorAll(".btn-delete");
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

    botonesEdit.forEach(btn => {
        const card = btn.closest('.oferta-card');
        const companyProf = card.querySelector(".companyPfp");
        const companyName = card.querySelector(".companyName");
        const cardTitle = card.querySelector(".card-titulo");

        btn.addEventListener("click", function(){
            crearModal(); 

            const applianceId = this.value; 
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

            const divBody = document.createElement("div");
            divBody.classList.add("modal-body-comments");
            
            const comentarios = document.createElement("textarea");
            comentarios.classList.add("modal-textarea");
            comentarios.value = card.querySelector(".card-comentarios").textContent;

            divBody.appendChild(comentarios);
            divHeader.appendChild(divTitleWrapper);
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
            divContent.appendChild(divBtns);

            modalContent.appendChild(divContent);

            btnCancelar.addEventListener("click", function(){
                cerrarModal();
            });

            btnConfirmar.addEventListener("click", function(){
                const contentTextArea = divContent.querySelector(".modal-textarea");

                //fetch
                console.log(contentTextArea.value);

                fetch("/API/ApiSolicitud.php", {
                    method: "PUT",
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + userToken
                    },
                    body: JSON.stringify({ 
                        solicitudId: applianceId,
                        comentario: contentTextArea.value,
                        finalizado:  true
                    })

                })
            })
            

        });
    });

    botonesDelete.forEach(btn => {
        const card = btn.closest('.oferta-card');
        const companyProf = card.querySelector(".companyPfp");
        const companyName = card.querySelector(".companyName");
        const cardTitle = card.querySelector(".card-titulo");

        
    });

})