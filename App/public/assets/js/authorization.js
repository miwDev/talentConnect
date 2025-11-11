window.addEventListener("load", function(){

    const togglePass = this.document.getElementById("toggle-password");
    const divEducacion = this.document.getElementById("divEducacion");
    const selFamilias = this.document.getElementById("familia");
    const btnCamara = this.document.getElementById("cameraModal");
    const btnFoto = this.document.getElementById("uploadPicture");
    const subirCV = this.document.getElementById("uploadCV");


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
    labelCiclos.htmlFor = "selCiclos";
    labelCiclos.style.visibility = 'hidden';
    
    const selCiclos = document.createElement("select");
    selCiclos.id = "selCiclos";
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

    selFamilias.addEventListener("change", function(){
        const errorFamilia = document.getElementById("error-familia");
        errorFamilia.textContent = "";

        if (this.value === "-1") {
            labelCiclos.style.visibility = "hidden";
            selCiclos.style.visibility = "hidden";
            errorCiclo.style.visibility = "hidden";
            
            // Limpiar ciclos
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

            // Mostrar los campos
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
        h2.textContent = "Camara";

        const divGrid = document.createElement("div");
        divGrid.id = "divGridCamara";

        const divVideo = document.createElement("div");
        divVideo.className = "camara-celda";
        
        const video = document.createElement("video");
        video.playsinline = true;
        video.autoplay = true;
        video.muted = true;
        video.className = "camara-video";

        const btnCapture = document.createElement("input");
        btnCapture.type = "button";
        btnCapture.id = "btnCapture";
        btnCapture.value = "CAPTURAR";

        divVideo.appendChild(video);
        divVideo.appendChild(btnCapture);

        const divCanvas = document.createElement("div");
        divCanvas.className = "camara-celda";

        const canvas = document.createElement("canvas");
        canvas.className = "camara-canvas"; 
        const recorte = document.createElement("div");
        recorte.id = "recorte";

        canvas.width = 640;
        canvas.height = 480;

        const btnCut = document.createElement("input");
        btnCut.type = "button";
        btnCut.id = "btnCut";
        btnCut.value = "RECORTAR";

        divCanvas.appendChild(canvas);
        divCanvas.appendChild(recorte);
        divCanvas.appendChild(btnCut);

        const divBotones = document.createElement("div");
        divBotones.id = "divBotones"; 
        divBotones.className = "camara-celda"; 
        
        const btnAccept = document.createElement("input");
        btnAccept.type = "button";
        btnAccept.id = "btnAdd";
        btnAccept.value = "ACEPTAR";

        const btnCancel = document.createElement("input");
        btnCancel.type = "button";
        btnCancel.id = "btnCancel";
        btnCancel.value = "CANCELAR";
        
        divBotones.appendChild(btnAccept);
        divBotones.appendChild(btnCancel);


        const divResult = document.createElement("div");
        divResult.className = "camara-celda";
        
        const canvasRes = document.createElement("canvas");
        canvasRes.className = "camara-canvas";
        canvasRes.width = 640;
        canvasRes.height = 480;

        divResult.appendChild(canvasRes);


        modalContent.appendChild(h2);
        
        divGrid.appendChild(divVideo);      // Celda Sup-Izq
        divGrid.appendChild(divCanvas);     // Celda Sup-Der
        divGrid.appendChild(divResult);     // Celda Inf-Izq (Tu código original tenía esto)
        divGrid.appendChild(divBotones);    // Celda Inf-Der (Tu código original tenía esto)
        
        modalContent.appendChild(divGrid);


        // ===================================
        //  FUNCIONALIDAD DE CAMARA Y RECORTE 
        // ===================================

        // --- RECORTE ---

        // 1. Redimensionar (Wheel) con Límites
        recorte.addEventListener('wheel',function(e){
            e.preventDefault();

            // Límites del canvas
            const c_left = canvas.offsetLeft;
            const c_top = canvas.offsetTop;
            const c_w = canvas.offsetWidth;
            const c_h = canvas.offsetHeight;
            
            const minSize = 20;
            // Tamaño máximo (desde la pos. actual hasta el borde del canvas)
            const maxW = (c_left + c_w) - this.offsetLeft;
            const maxH = (c_top + c_h) - this.offsetTop;

            const deltaW = this.offsetWidth * 0.05 * Math.sign(e.wheelDelta || -e.deltaY);
            const deltaH = this.offsetHeight * 0.05 * Math.sign(e.wheelDelta || -e.deltaY);

            let newW = this.offsetWidth + deltaW;
            let newH = this.offsetHeight + deltaH;

            // Aplicar límites
            newW = Math.max(minSize, Math.min(newW, maxW));
            newH = Math.max(minSize, Math.min(newH, maxH));
            
            this.style.width = newW + "px";
            this.style.height = newH + "px";
        });

        // 2. Mover (Drag) con Límites (Patrón 'mousedown' mejorado)
        // Este patrón evita que el 'drag' se rompa si mueves el ratón rápido
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
                canvas,      // Canvas Fuente
                sX_res, sY_res, // Coords. Fuente (en píxeles de resolución)
                sW_res, sH_res, // Tamaño Fuente (en píxeles de resolución)
                0, 0,           // Coords. Destino (0,0 del canvasRes)
                sW_res, sH_res  // Tamaño Destino
            );
        });

        // --- VIDEO ---
        const constraints = {
            audio: false,
            video: {
                width: 800, height: 600
            }
        };

        async function init() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
            } catch (e) {
                console.error(`navigator.getUserMedia error:${e.toString()}`);
            }
        }

        let context = canvas.getContext('2d');
        btnCapture.addEventListener('click', async function() {
            context.drawImage(video, 0, 0, 640, 480);
            recorte.style.display = "block"; // Esto estaba bien
        });

        init();


        // ===============================================
        //  FUNCIONALIDAD BOTONES
        // ===============================================

        btnCancel.onclick=()=>{window.cerrarModal()};

        btnAccept.onclick=()=>{
            const imgDestino = document.getElementById('avatarId');
            const hiddeninput = document.getElementById("datosCanvas");

            const dataURL = canvasRes.toDataURL();
            imgDestino.src = dataURL;

            hiddeninput.value = dataURL;
            console.log(dataURL);

            window.cerrarModal();
        };
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
        
        const imgElemento = document.createElement("img");
        imgElemento.className = "img-responsive"; 
        
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

        btnCancel.onclick=()=>{window.cerrarModal()};

        btnAccept.addEventListener("click", () => {
            
            if (previewURLTemporal) {
                const imgDestino = document.getElementById('avatarId'); 
                
                if (imgDestino) {
                    imgDestino.src = previewURLTemporal;
                } else {
                    console.error("No se encontró el <img> de destino en el formulario.");
                }
            }
            window.cerrarModal()
        });

        divBotones.appendChild(btnAccept);
        divBotones.appendChild(btnCancel);

        modalContent.appendChild(h2Modal);
        modalContent.appendChild(divContent);
        modalContent.appendChild(divBotones);
    });
})