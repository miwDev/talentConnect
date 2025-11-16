window.addEventListener("load", function(){

    const togglePass = this.document.getElementById("toggle-password");
    const btnCamara = this.document.getElementById("cameraModal");
    const btnFoto = this.document.getElementById("uploadPicture");
    const subirCV = this.document.getElementById("uploadCV");

    new FormularioManager("formulario");

    togglePass.addEventListener("click", function() {
        const password = document.getElementById("password");
        
        if (password.type === "password") {
            password.type = "text";
        } else if (password.type === "text") {
            password.type = "password";
        }
    });

    let educationCounter = 0;
    const divEducacionContainer = this.document.getElementById("divEducacion");

    function populateSelect(selectElement, dataArray) {
        dataArray.forEach(item => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.nombre;
            selectElement.appendChild(option);
        });
    }

    function createAndAppendEducationRow() {
        
        const currentIndex = educationCounter; 

        const row = document.createElement("div");
        row.className = "form-row education-colum";

        const dateRow = document.createElement("div");
        dateRow.className = "form-row education-dates";
        dateRow.style.display = "none"; 

        const divSelectsWrapper = document.createElement("div");
        divSelectsWrapper.className = "education-selects-wrapper"; 

        const divAdd = document.createElement("div");
        divAdd.className = "form-group education-buttons-group";


        const divFamilia = document.createElement("div");
        divFamilia.className = "form-group half-width";

        const labelFamilia = document.createElement("label");
        labelFamilia.textContent = "Familia Profesional";
        labelFamilia.htmlFor = "familia_" + currentIndex;

        const selFamilias = document.createElement("select");
        selFamilias.id = "familia_" + currentIndex;
        selFamilias.name = "familia[" + currentIndex + "]"; 
        selFamilias.className = "form-select";
        selFamilias.innerHTML = '<option value="-1" selected>--- Seleccione Familia ---</option>';

        const errorFamilia = document.createElement("div");
        errorFamilia.className = "input-error-message";
        errorFamilia.id = "error-familia_" + currentIndex;

        divFamilia.appendChild(labelFamilia);
        divFamilia.appendChild(selFamilias);
        divFamilia.appendChild(errorFamilia);

        const divCiclos = document.createElement("div");
        divCiclos.className = "form-group half-width";

        const labelCiclos = document.createElement("label");
        labelCiclos.textContent = "Ciclo:";
        labelCiclos.htmlFor = "ciclo_" + currentIndex;
        labelCiclos.style.visibility = 'hidden';
        
        const selCiclos = document.createElement("select");
        selCiclos.id = "ciclo_" + currentIndex;
        selCiclos.name = "ciclo[" + currentIndex + "]"; 
        selCiclos.className = "form-select";
        selCiclos.style.visibility = 'hidden'; 
        selCiclos.innerHTML = '<option value="-1" selected>--- Seleccione Ciclo ---</option>';

        const errorCiclo = document.createElement("div");
        errorCiclo.className = "input-error-message";
        errorCiclo.id = "error-ciclo_" + currentIndex;
        errorCiclo.style.visibility = 'hidden';

        divCiclos.appendChild(labelCiclos);
        divCiclos.appendChild(selCiclos);
        divCiclos.appendChild(errorCiclo);

        const labelFantasma = document.createElement("label");
        labelFantasma.style.visibility = "hidden";
        labelFantasma.textContent = "Botones";
        divAdd.appendChild(labelFantasma);

        const buttonWrapper = document.createElement("div");
        buttonWrapper.className = "education-buttons-wrapper";

        const spanAdd = document.createElement("span");
        spanAdd.id = "btnAddEstudios_" + currentIndex;
        spanAdd.className = "add-estudios";
        
        const spanSubtract = document.createElement("span");
        spanSubtract.id = "btnSubtractEstudios_" + currentIndex;
        spanSubtract.className = "subtract-estudios";
        
        if (currentIndex === 0) {
            spanSubtract.style.visibility = "hidden";
        }

        spanSubtract.addEventListener("click", function() {
            row.remove();
            dateRow.remove();
        });

        buttonWrapper.appendChild(spanAdd);
        buttonWrapper.appendChild(spanSubtract); 
        divAdd.appendChild(buttonWrapper);

        const errorFantasma = document.createElement("div");
        errorFantasma.className = "input-error-message";
        divAdd.appendChild(errorFantasma);
        // --- Fin Contenido de divAdd ---


        const divStartDate = document.createElement("div");
        divStartDate.className = "form-group half-width"; 

        const labelStartDate = document.createElement("label");
        labelStartDate.htmlFor = "fecha_inicio_" + currentIndex;
        labelStartDate.textContent = "Fecha inicio";

        const inputStartDate = document.createElement("input");
        inputStartDate.type = "text";
        inputStartDate.id = "fecha_inicio_" + currentIndex;
        inputStartDate.name = "fecha_inicio[" + currentIndex + "]";
        inputStartDate.placeholder = "YYYY-MM-DD";
        // inputStartDate.required = true; // <-- ESTA LÍNEA CAUSA EL ERROR. ELIMINADA.

        // --- AÑADIDO: Span de error para Fecha Inicio ---
        const errorStartDate = document.createElement("div");
        errorStartDate.className = "input-error-message";
        errorStartDate.id = "error-fecha_inicio_" + currentIndex;

        divStartDate.appendChild(labelStartDate);
        divStartDate.appendChild(inputStartDate);
        divStartDate.appendChild(errorStartDate); // --- AÑADIDO ---

        const divEndDate = document.createElement("div");
        divEndDate.className = "form-group half-width"; 

        const labelEndDate = document.createElement("label");
        labelEndDate.htmlFor = "fecha_fin_" + currentIndex;
        labelEndDate.textContent = "Fecha fin";

        const inputEndDate = document.createElement("input");
        inputEndDate.type = "text";
        inputEndDate.id = "fecha_fin_" + currentIndex;
        inputEndDate.name = "fecha_fin[" + currentIndex + "]";
        inputEndDate.placeholder = "YYYY-MM-DD";

        // --- AÑADIDO: Span de error para Fecha Fin ---
        const errorEndDate = document.createElement("div");
        errorEndDate.className = "input-error-message";
        errorEndDate.id = "error-fecha_fin_" + currentIndex;

        divEndDate.appendChild(labelEndDate);
        divEndDate.appendChild(inputEndDate);
        divEndDate.appendChild(errorEndDate); // --- AÑADIDO ---


        dateRow.appendChild(divStartDate);
        dateRow.appendChild(divEndDate);

        spanAdd.addEventListener("click", function() {
            spanAdd.style.visibility = "hidden";
            createAndAppendEducationRow();
        });

        selFamilias.addEventListener("change", function(){
            errorFamilia.textContent = "";
            dateRow.style.display = "none"; 

            if (this.value === "-1") {
                labelCiclos.style.visibility = "hidden";
                selCiclos.style.visibility = "hidden";
                errorCiclo.style.visibility = "hidden";
                selCiclos.innerHTML = '<option value="-1">--- Seleccione Ciclo ---</option>';
                spanAdd.style.visibility = "hidden"; 
                spanSubtract.style.visibility = "hidden"; 
                return;
            }

            const familyId = this.value;
            fetch('/API/ApiCiclo.php?familia=' + familyId)
                .then(x => x.json())
                .then(json => {
                    selCiclos.innerHTML = '<option value="-1">--- Seleccione Ciclo ---</option>';
                    populateSelect(selCiclos, json);

                    labelCiclos.style.visibility = "visible";
                    selCiclos.style.visibility = "visible";
                    errorCiclo.style.visibility = "visible";
                    
                    spanAdd.style.visibility = "visible"; 
                    if (currentIndex > 0) {
                        spanSubtract.style.visibility = "visible"; 
                    }
                })
                .catch(error => {
                    console.error('Error al cargar ciclos:', error);
                    selCiclos.innerHTML = '<option value="-1">Error al cargar</option>';
                    errorCiclo.textContent = "Error al cargar los ciclos.";
                });
        });
        
        selCiclos.addEventListener("change", function(){
            errorCiclo.textContent = ""; 
            
            if (this.value !== "-1") {
                dateRow.style.display = "flex"; 
            } else {
                dateRow.style.display = "none";
            }
        });

        fetch('/API/ApiFamilia.php', { method: 'GET' })
            .then(x => x.json())
            .then(json => populateSelect(selFamilias, json));

        divSelectsWrapper.appendChild(divFamilia);
        divSelectsWrapper.appendChild(divCiclos);

        row.appendChild(divSelectsWrapper);
        row.appendChild(divAdd);
        
        divEducacionContainer.appendChild(row); 
        divEducacionContainer.appendChild(dateRow); 

        educationCounter++;
    }

    createAndAppendEducationRow();
    
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