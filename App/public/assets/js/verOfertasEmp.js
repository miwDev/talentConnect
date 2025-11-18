window.addEventListener("load", () => {

    const userToken = sessionStorage.getItem("token");
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
});
