<div class="card-CompanyInfo">
        <a href="?menu=ver-empresa&empresa=<?php echo $empresa->id; ?>">
                <span id="idEmpresa"><?= $empresa->id ?></span>
                <img src="/public<?php echo $empresa->logo ?? '/assets/images/genericAvatar.svg'; ?>">
        </a>
</div>