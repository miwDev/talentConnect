window.addEventListener("load", function(){

    const togglePass = this.document.getElementById("toggle-password");
    const divEducacion = this.document.getElementById("divEducacion");
    const selFamilias = this.document.getElementById("familia");
    const btnCamara = this.document.getElementById("cameraModal");
    const btnFoto = this.document.getElementById("uploadPicture");
    const subirCV = this.document.getElementById("uploadCV");


    new FormularioManager("formulario");


    //metodos

    function populateSelect(selectElement, dataArray) {
        dataArray.forEach(item => {
            const option = document.createElement("option");
            
            option.value = item.id;
        
            option.textContent = item.nombre;

            selectElement.appendChild(option);
        });
    }

    //eventos

    togglePass.addEventListener("click", function() {
        const password = document.getElementById("password");
        
        if (password.type === "password") {
            password.type = "text";
        } else if (password.type === "text") {
            password.type = "password";
        }
    });

    const defaultOption = document.createElement("option");
    defaultOption.value = "-1";
    defaultOption.textContent = "--- Seleccione Familia ---";
    defaultOption.selected = true;

    fetch('/API/ApiFamilia.php', {
            method: 'GET'
        })
        .then((x)=>x.json())
        .then((json)=>populateSelect(selFamilias, json));

    selFamilias.appendChild(defaultOption);

    const divCiclos = document.createElement("div");
    divCiclos.className = "form-group half-width";

    const labelCiclos = document.createElement("label");
    labelCiclos.textContent = "Ciclo:";
    labelCiclos.htmlFor = "ciclo";
    labelCiclos.style.visibility = 'hidden';
    
    const selCiclos = document.createElement("select");
    selCiclos.id = "ciclo";
    selCiclos.name = "ciclo";
    selCiclos.className = "form-select";
    selCiclos.style.visibility = 'hidden'; 

    const defaultOption1 = document.createElement("option");
    defaultOption1.value = "-1";
    defaultOption1.textContent = "--- Seleccione Ciclo ---";
    defaultOption1.selected = true;

    selCiclos.appendChild(defaultOption1);

    const errorCiclo = document.createElement("div");
    errorCiclo.className = "input-error-message";
    errorCiclo.id = "error-ciclo";
    errorCiclo.style.visibility = 'hidden';

    selCiclos.addEventListener("change", function(){
        const errorCiclo = document.getElementById("error-ciclo");
        errorCiclo.textContent = ""; 
    });


    selFamilias.addEventListener("change", function(){
        const errorFamilia = document.getElementById("error-familia");
        errorFamilia.textContent = "";

        if (this.value === "-1") {
            labelCiclos.style.visibility = "hidden";
            selCiclos.style.visibility = "hidden";
            errorCiclo.style.visibility = "hidden";
            
            selCiclos.innerHTML = '<option value="-1">--- Seleccione Ciclo ---</option>';
            return;
        }

        const familyId = this.value;

        fetch('/API/ApiCiclo.php?familia=' + familyId, {
            method: 'GET'
        })
        .then((x)=>x.json())
        .then((json)=>{
            
            selCiclos.innerHTML = '';
            const defaultOption = document.createElement("option");
            defaultOption.value = "-1";
            defaultOption.textContent = "--- Seleccione Ciclo ---";
            defaultOption.selected = true;
            selCiclos.appendChild(defaultOption);

            
            populateSelect(selCiclos, json);

            labelCiclos.style.visibility = "visible";
            selCiclos.style.visibility = "visible";
            errorCiclo.style.visibility = "visible";
        })
        .catch(error => {
            console.error('Error al cargar ciclos:', error);
            selCiclos.innerHTML = '<option value="-1">Error al cargar</option>';
            errorCiclo.textContent = "Error al cargar los ciclos. Intente nuevamente.";
        });
    });

    divCiclos.appendChild(labelCiclos);
    divCiclos.appendChild(selCiclos);
    divCiclos.appendChild(errorCiclo);

    divEducacion.appendChild(divCiclos);

    


    btnCamara.addEventListener("click", function(){
        crearModal();
        const modalContent = document.getElementById("modalContent");

        let h2 = document.createElement("h2");
        h2.textContent = "Paso 1: Capturar Foto";

        let videoStream = null; 

        const divVideo = document.createElement("div");
        divVideo.className = "camara-step"; 
        divVideo.id = "step-video";
        
        const video = document.createElement("video");
        video.playsinline = true;
        video.autoplay = true;
        video.muted = true;
        video.className = "camara-video"; 

        const btnCapture = document.createElement("input");
        btnCapture.type = "button";
        btnCapture.id = "btnCapture";
        btnCapture.value = "CAPTURAR FOTO";

        divVideo.appendChild(video);
        divVideo.appendChild(btnCapture);

        const divCanvas = document.createElement("div");
        divCanvas.className = "camara-step"; 
        divCanvas.id = "step-canvas";

        const canvas = document.createElement("canvas");
        canvas.className = "camara-canvas"; 
        const recorte = document.createElement("div");
        recorte.id = "recorte"; 

        canvas.width = 640;
        canvas.height = 640;

        const btnCut = document.createElement("input");
        btnCut.type = "button";
        btnCut.id = "btnCut";
        btnCut.value = "RECORTAR IMAGEN";

        divCanvas.appendChild(canvas);
        divCanvas.appendChild(recorte);
        divCanvas.appendChild(btnCut);

        const divResult = document.createElement("div");
        divResult.className = "camara-step"; 
        divResult.id = "step-result";
        
        const canvasRes = document.createElement("canvas");
        canvasRes.className = "camara-canvas"; 
        canvasRes.width = 640;
        canvasRes.height = 480;

        const divBotones = document.createElement("div");
        divBotones.id = "divBotones"; 
        
        const btnVolver = document.createElement("input");
        btnVolver.type = "button";
        btnVolver.id = "btnVolver"; 
        btnVolver.value = "VOLVER A CAPTURAR";

        const btnAccept = document.createElement("input");
        btnAccept.type = "button";
        btnAccept.id = "btnAdd";
        btnAccept.value = "ACEPTAR";

        const btnCancel = document.createElement("input");
        btnCancel.type = "button";
        btnCancel.id = "btnCancel";
        btnCancel.value = "CANCELAR";
        
        divBotones.appendChild(btnVolver);
        divBotones.appendChild(btnAccept);
        divBotones.appendChild(btnCancel);

        divResult.appendChild(canvasRes);
        divResult.appendChild(divBotones);

        modalContent.appendChild(h2);
        modalContent.appendChild(divVideo);   
        modalContent.appendChild(divCanvas);  
        modalContent.appendChild(divResult);  

        recorte.addEventListener('wheel',function(e){
            e.preventDefault();

            const c_left = canvas.offsetLeft;
            const c_top = canvas.offsetTop;
            const c_w = canvas.offsetWidth;
            const c_h = canvas.offsetHeight;
            
            const minSize = 20;

            const maxW = (c_left + c_w) - this.offsetLeft;
            const maxH = (c_top + c_h) - this.offsetTop;
            
            const delta = this.offsetWidth * 0.05 * Math.sign(e.wheelDelta || -e.deltaY);
            let newSize = this.offsetWidth + delta;

            const maxSafeSize = Math.min(maxW, maxH);

            newSize = Math.max(minSize, Math.min(newSize, maxSafeSize));
            
            this.style.width = newSize + "px";
            this.style.height = newSize + "px";
        });

        recorte.addEventListener("mousedown", function(e_down) {
            e_down.preventDefault();
            function onMouseMove(e_move) {
                const minX = canvas.offsetLeft;
                const minY = canvas.offsetTop;
                const maxX = canvas.offsetLeft + canvas.offsetWidth - recorte.offsetWidth;
                const maxY = canvas.offsetTop + canvas.offsetHeight - recorte.offsetHeight;
                let newX = recorte.offsetLeft + e_move.movementX;
                let newY = recorte.offsetTop + e_move.movementY;
                newX = Math.max(minX, Math.min(newX, maxX));
                newY = Math.max(minY, Math.min(newY, maxY));
                recorte.style.left = newX + "px";
                recorte.style.top = newY + "px";
            }
            function onMouseUp() {
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
            }
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        });

        recorte.addEventListener("mouseover", function() {
            recorte.style.cursor = "all-scroll"; 
        });

        function stopStream() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
        }

        const constraints = {
            audio: false,
            video: { width: 800, height: 600 }
        };

        async function init() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                videoStream = stream; 
                video.srcObject = videoStream;
                
                divVideo.style.display = "flex"; 
                divCanvas.style.display = "none";
                divResult.style.display = "none";
                recorte.style.display = "none"; 
                h2.textContent = "Paso 1: Capturar Foto";

            } catch (e) {
                console.error(`navigator.getUserMedia error:${e.toString()}`);
            }
        }

        let context = canvas.getContext('2d');
        
        btnCapture.addEventListener('click', async function() {
            const sHeight = video.videoHeight;
            const sWidth = sHeight * (canvas.width / canvas.height); 
            const sX = (video.videoWidth - sWidth) / 2; 
            const sY = 0;
            
            context.drawImage(video, sX, sY, sWidth, sHeight, 0, 0, canvas.width, canvas.height);
            
            recorte.style.display = "block"; 

            divVideo.style.display = "none";
            divCanvas.style.display = "flex"; 
            divResult.style.display = "none";
            h2.textContent = "Paso 2: Recortar Imagen";
            
            stopStream();
        });
        
        btnCut.addEventListener("click", async function(e){
            const scaleX = canvas.width / canvas.offsetWidth;
            const scaleY = canvas.height / canvas.offsetHeight;
            const x_recorte_css = recorte.offsetLeft;
            const y_recorte_css = recorte.offsetTop;
            const w_recorte_css = recorte.offsetWidth;
            const h_recorte_css = recorte.offsetHeight;
            const x_canvas_css = canvas.offsetLeft;
            const y_canvas_css = canvas.offsetTop;
            const sX_css = x_recorte_css - x_canvas_css;
            const sY_css = y_recorte_css - y_canvas_css;
            const sX_res = sX_css * scaleX;
            const sY_res = sY_css * scaleY;
            const sW_res = w_recorte_css * scaleX;
            const sH_res = h_recorte_css * scaleY;
            
            canvasRes.width = sW_res;
            canvasRes.height = sH_res;
            
            let contextRes = canvasRes.getContext('2d');
            contextRes.drawImage(
                canvas, sX_res, sY_res, sW_res, sH_res, 
                0, 0, sW_res, sH_res
            );

            divVideo.style.display = "none";
            divCanvas.style.display = "none";
            divResult.style.display = "flex"; 
            h2.textContent = "Paso 3: Confirmar Resultado";
        });

        btnVolver.addEventListener("click", function() {
            context.clearRect(0, 0, canvas.width, canvas.height);
            let contextRes = canvasRes.getContext('2d');
            contextRes.clearRect(0, 0, canvasRes.width, canvasRes.height);
            
            init(); 
        });

        btnCancel.onclick=()=>{
            stopStream(); 
            window.cerrarModal();
        };

        btnAccept.onclick=()=>{
            const imgDestino = document.getElementById('avatarId');
            const hiddeninput = document.getElementById("hiddenFotoPerfil");

            const dataURL = canvasRes.toDataURL();
            imgDestino.src = dataURL;
            hiddeninput.value = dataURL;
            
            stopStream(); 
            window.cerrarModal();
        };

        init();
    });

    btnFoto.addEventListener("click", function() {

        let archivoParaSubir = null;
        let previewURLTemporal = null;
        
        crearModal(); 
        
        const modalContent = document.getElementById("modalContent");
        const h2Modal = document.createElement("h2");
        h2Modal.textContent = "Subir imagen";

        const divContent = document.createElement("div");
        divContent.id = "divContent";

        const inputArchivo = document.createElement("input");
        inputArchivo.type = "file";
        inputArchivo.accept = "image/*";
        inputArchivo.style.display = "none";

        const divSubida = document.createElement("div");
        divSubida.id = "divSubida";
        divSubida.textContent = "Subir archivos"; 

        const imgElemento = document.createElement("img");
        imgElemento.className = "img-responsive"; 
        imgElemento.style.display = 'none';

        divSubida.addEventListener("click", () => {
            inputArchivo.click();
        });

        inputArchivo.addEventListener("change", (event) => {
            const archivo = event.target.files[0];
            if (archivo) {
                archivoParaSubir = archivo; 
                
                if (previewURLTemporal) {
                    URL.revokeObjectURL(previewURLTemporal);
                }
                previewURLTemporal = URL.createObjectURL(archivo);

                imgElemento.src = previewURLTemporal;
                imgElemento.style.display = 'block';
            }
        });

        divSubida.appendChild(inputArchivo);

        const divCanva = document.createElement("div");
        divCanva.id = "divCanva";
        
        divCanva.appendChild(imgElemento);
        divContent.appendChild(divSubida);
        divContent.appendChild(divCanva); 

        const divBotones = document.createElement("div");
        divBotones.id = "divBotones";

        const btnAccept = document.createElement("input");
        btnAccept.type = "button";
        btnAccept.id = "btnAdd";
        btnAccept.value = "ACEPTAR";

        const btnCancel = document.createElement("input");
        btnCancel.type = "button";
        btnCancel.id = "btnCancel";
        btnCancel.value = "CANCELAR";

        btnCancel.onclick=()=>{
            if (previewURLTemporal) {
                URL.revokeObjectURL(previewURLTemporal);
            }
            window.cerrarModal()
        };

        btnAccept.addEventListener("click", () => {
            
            if (archivoParaSubir) {
                const imgDestino = document.getElementById('avatarId'); 
                const hiddenInput = document.getElementById('hiddenFotoPerfil');
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    const dataURL = e.target.result; 
                    
                    imgDestino.src = dataURL;
                    
                    hiddenInput.value = dataURL;
                    
                    window.cerrarModal();
                };
                reader.readAsDataURL(archivoParaSubir);
            
            } else {
                window.cerrarModal();
            }

            if (previewURLTemporal) {
                URL.revokeObjectURL(previewURLTemporal);
            }
        });

        divBotones.appendChild(btnAccept);
        divBotones.appendChild(btnCancel);

        modalContent.appendChild(h2Modal);
        modalContent.appendChild(divContent);
        modalContent.appendChild(divBotones);
    });


    subirCV.onclick = function() {
        const form = document.getElementById('formulario');

        let input = form.querySelector('input[name="cv"]');

        if (!input) {
            input = document.createElement("input");
            input.type = "file";
            input.name = "cv";
            input.style.display = "none";
            input.accept = ".pdf,application/pdf";
            input.required = true;
            
            form.appendChild(input);
        }

        input.onchange = () => {
            if (input.files.length > 0) {
                const fileName = input.files[0].name;
                this.textContent = fileName;
            } else {
                this.textContent = "Subir CV (PDF)";
            }
        };

        input.click();
    };
})