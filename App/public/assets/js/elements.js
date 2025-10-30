

function crearModal() {

    const velo = document.createElement('div');
    velo.className = 'velo';
    

    const modal = document.createElement('div');
    modal.className = 'modal';
    

    modal.innerHTML = `
        <div id=modalContent style="padding: 20px;">
            <button id="cerrarModal">X</button>
        </div>
    `;
    

    document.body.appendChild(velo);
    document.body.appendChild(modal);
    
  
    function cerrarModal() {
        document.body.removeChild(velo);
        document.body.removeChild(modal);
    }
    
   
    const btnCerrar = document.getElementById('cerrarModal');
    btnCerrar.addEventListener('click', cerrarModal);
        
    
    velo.addEventListener('click', cerrarModal);
}


window.crearModal = crearModal;