<?php

namespace App\mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mail;
    private $baseUrl;
    private $logoPath;
    
    // Colores de la paleta extraídos del CSS
    private const COLOR_GREEN_DARK = '#16443D';
    private const COLOR_BEIGE_LIGHT = '#FFFFE9';
    private const COLOR_WHITE_OFF = '#FEFDF8';
    private const COLOR_DARK = '#191919';
    private const COLOR_ACCENT = '#EBD3FD';
    private const COLOR_ALERT = '#E53E3E';

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        
        // URL base (para los enlaces)
        $this->baseUrl = 'http://localhost:80/public/index.php'; 
        
        // RUTA DEL LOGO (Ajustada a tu estructura)
        $this->logoPath = __DIR__ . '/../../public/assets/images/logoDark.svg';

        try {
            // Configuración Server (Docker/Mailhog)
            $this->mail->isSMTP();
            $this->mail->Host       = getenv('MAIL_HOST') ?: 'mailhog';
            $this->mail->Port       = getenv('MAIL_PORT') ?: 1025;
            $this->mail->SMTPAuth   = false;
            $this->mail->SMTPSecure = ''; 
            
            $this->mail->setFrom('no-reply@talentconnect.com', 'Talent Connect');
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';

        } catch (Exception $e) {
            error_log("Error Mailer Construct: {$this->mail->ErrorInfo}");
        }
    }

    // --- 1. MÉTODO GENÉRICO ---
    public function sendEmail($to, $subject, $body)
    {
        try {
            // ⚠️ La incrustación de la imagen debe hacerse antes de sendEmail()
            // Se realiza en sendWithTemplate.
            
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags($body);
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Error enviando a $to: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    // --- 2. PLANTILLAS DE CORREO ---
    
    public function accountConfirmAlumno($email) {
        return $this->sendWithTemplate($email, 'Completa tu Perfil de Alumno', '¡Gracias por registrarte! Para empezar a aplicar, necesitamos que completes tus datos académicos.', $this->baseUrl . '?menu=datosAlumno', 'Rellenar Datos');
    }

    public function accountConfirmEmpresa($email) {
        return $this->sendWithTemplate($email, 'Bienvenida Empresa', 'Por favor, rellena tu ficha de empresa para validar tu cuenta. Esto es necesario antes de publicar ofertas.', $this->baseUrl . '?menu=datosEmpresa', 'Rellenar Ficha');
    }

    public function sendApplicationAccepted($email, $offerTitle, $companyName) {
        // SIN LINK
        $message = "La empresa <strong>$companyName</strong> ha aceptado tu solicitud de trabajo para la oferta: <em>$offerTitle</em>.<br><br><strong>¡Enhorabuena! Te contactaremos pronto.</strong>";
        return $this->sendWithTemplate($email, '¡Candidatura Aceptada!', $message, null, null, false);
    }

    public function sendApplicationRejected($email, $offerTitle) {
        // SIN LINK
        $message = "Te informamos que tu solicitud para la oferta <strong>$offerTitle</strong> ha sido rechazada. <br><br>No has tenido suerte esta vez. ¡Te animamos a aplicar a otras ofertas!";
        return $this->sendWithTemplate($email, 'Estado de Solicitud', $message, null, null, true);
    }

    public function sendPasswordForgot($email, $token) {
        return $this->sendWithTemplate($email, 'Restablecer Contraseña', 'Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el botón de abajo para crear una nueva.', $this->baseUrl . "?menu=passForgot&token=$token", 'Cambiar Contraseña');
    }

    public function sendCompanyVerified($email) {
        return $this->sendWithTemplate($email, '¡Cuenta Verificada!', 'La administración ha validado tu empresa. Ya puedes empezar a usar todos los servicios de la plataforma.', $this->baseUrl . '?menu=login', 'Ir al Dashboard');
    }

    public function sendNewOffers($email) {
        return $this->sendWithTemplate($email, 'Nuevas Oportunidades', 'Hay nuevas ofertas publicadas que encajan con tu perfil. ¡Sé el primero en aplicar!', $this->baseUrl . '?menu=login', 'Ver Ofertas');
    }

    // --- 3. GENERADOR DE DISEÑO HTML (CSS INLINE) ---
    /**
     * Genera la plantilla HTML base y realiza el envío.
     * @param string $btnLink Si es null, no se incluye botón.
     */
    private function sendWithTemplate($to, $title, $message, $btnLink = null, $btnText = null, $isAlert = false)
    {
        // --- COLORES ---
        $bg_color    = self::COLOR_GREEN_DARK;  // NUEVO FONDO: VERDE OSCURO
        $card_bg     = self::COLOR_WHITE_OFF;
        $border_col  = self::COLOR_DARK; 
        $accent_col  = self::COLOR_ACCENT;
        $alert_col   = self::COLOR_ALERT;
        $header_bg   = self::COLOR_BEIGE_LIGHT; // La cabecera (donde va el logo) sigue siendo beige claro

        // Lógica de estilos
        $highlight = $isAlert ? self::COLOR_ALERT : $border_col;
        
        $buttonHtml = '';
        if ($btnLink && $btnText) {
            $btn_bg = $isAlert ? self::COLOR_WHITE_OFF : $accent_col;
            $btn_border = $isAlert ? $alert_col : $border_col;
            $btn_text_color = $isAlert ? $alert_col : $border_col;
            
            $buttonHtml = "
                <div align='center' style='margin-top: 30px;'>
                    <a href='{$btnLink}' style='
                        display: inline-block; padding: 12px 30px;
                        font-family: \"Poppins\", sans-serif; font-weight: 700;
                        text-decoration: none; font-size: 16px;
                        color: $btn_text_color; background-color: $btn_bg;
                        border: 3px solid $btn_border; border-radius: 11px;
                    '>$btnText</a>
                </div>
            ";
        }

        // LOGO: Incrustamos la imagen y obtenemos su ID único (CID)
        $cidLogo = 'logo_talent_connect';
        // Reiniciamos attachments
        $this->mail->clearAllRecipients();
        $this->mail->clearAttachments();
        
        if (file_exists($this->logoPath)) {
            $this->mail->addEmbeddedImage($this->logoPath, $cidLogo, 'logoDark.svg');
            $logoHtml = "<img src='cid:$cidLogo' alt='Talent Connect' style='width: 50px; height: auto; vertical-align: middle; margin-right: 15px;' border='0'>";
        } else {
            $logoHtml = "";
            error_log("Mailer: No se encontró el logo en " . $this->logoPath);
        }

        // HTML COMPLETO
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Poppins:wght@600;700&display=swap');
            </style>
        </head>
        <body style='margin: 0; padding: 0; background-color: $bg_color; font-family: \"Libre Baskerville\", serif;'>
            
            <table width='100%' cellpadding='0' cellspacing='0' role='presentation'>
                <tr>
                    <td align='center' style='padding: 40px 15px;'>
                        
                        <!-- TARJETA PRINCIPAL (Aumentada a 650px) -->
                        <table width='650' cellpadding='0' cellspacing='0' style='background-color: $card_bg; border: 3px solid $border_col; border-radius: 11px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.2);'>
                            
                            <!-- CABECERA CON LOGO Y TEXTO -->
                            <tr>
                                <td align='center' style='padding: 25px; background-color: $header_bg; border-bottom: 2px solid $border_col;'>
                                    
                                    <table cellpadding='0' cellspacing='0'>
                                        <tr>
                                            " . ($logoHtml ? "<td style='padding-right: 10px;'>$logoHtml</td>" : "") . "
                                            
                                            <td style='vertical-align: middle;'>
                                                <span style='
                                                    font-family: \"Poppins\", sans-serif; 
                                                    font-weight: 700; 
                                                    color: $border_col; 
                                                    font-size: 26px; /* Ligeramente más grande */
                                                    letter-spacing: 1px; 
                                                    text-transform: uppercase;
                                                    display: inline-block;
                                                '>
                                                    TALENT CONNECT
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <!-- CUERPO -->
                            <tr>
                                <td style='padding: 40px 30px; color: $border_col; font-size: 16px; line-height: 1.6;'>
                                    <h2 style='margin-top: 0; font-family: \"Poppins\", sans-serif; color: $highlight; font-size: 22px;'>
                                        $title
                                    </h2>
                                    <p style='margin-bottom: 30px;'>$message</p>
                                    
                                    {$buttonHtml}
                                </td>
                            </tr>

                            <!-- FOOTER -->
                            <tr>
                                <td align='center' style='padding: 20px; background-color: $border_col; color: $card_bg; font-size: 12px; font-family: \"Poppins\", sans-serif;'>
                                    <p style='margin: 0;'>&copy; " . date('Y') . " Talent Connect</p>
                                </td>
                            </tr>
                        </table>
                    
                    </td>
                </tr>
            </table>
        </body>
        </html>";

        return $this->sendEmail($to, $title, $html);
    }
}