<?php
require __DIR__.'/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function get_notice($message = 'Configurações salvas.', $type = 'success'){
    //esc_attr
   return sprintf('<div id="simple-send-mail-notice" class="notice notice-%s settings-error is-dismissible"> 
        <p><strong>%s</strong></p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dispensar este aviso.</span>
        </button>
    </div>', $type, $message);
}

function send_mail_to($data = []) {
    $status_message = "Mensagem enviada com Sucesso!";
    $status_error = null;
    $status = false;

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = get_option('smtp_host');
        $mail->SMTPAuth   = true;
        $mail->Username   = get_option('smtp_username');
        $mail->Password   = get_option('smtp_password');
        $mail->SMTPSecure = get_option('smtp_protocol', PHPMailer::ENCRYPTION_SMTPS);
        $mail->Port =  get_option('smtp_protocol', 587);

        $mail->setFrom($data['email'], "{$data['name']} {$data['surname']}");
        $mail->addAddress(get_option('smtp_host'), 'Web Site');
   
        $mail->isHTML(true);
        $mail->Subject = "Mensagem de contato do Site: {$data['name']} {$data['surname']}";
        $mail->Body = "
            <h1>Mensagem de contato do Site</h1>
            <b>Nome:</b> {$data['name']} {$data['surname']} <br>
            <b>Telefone:</b> {$data['phone']} <br>
            <b>Email:</b> {$data['email']} <br>
            <br><br>
            Mensagem: <br>
            {$data['message']}
        ";
        $mail->AltBody = $data['message'];

        $status = $mail->send();
    } catch (Exception $e) {
        $status_message = "Ocorreu um erro ao tentar enviar a mensagem.";
        $status_error = $e->getMessage();
    }

    return [
        "status" => $status,
        "message" => $status_message,
        "error" => $status_error,
        "notice" => get_notice($status_message, $status ? "success" : "warning")
    ];
}

function send_mail($request) {
    return send_mail_to($request->get_json_params());
}

function send_mail_test($request) {
    return send_mail_to(array_merge($request->get_json_params(), [
        'name' => 'Tim',
        'surname' => 'Maia',
        'message' => 'Mensagem de Teste!',
        'phone' => '99999999999',
    ]));
}

add_action('rest_api_init', function () {
    register_rest_route("simple-send-mail" , '/send', array(
      'methods' => 'POST',
      'callback' => 'send_mail',
    ));
    register_rest_route("simple-send-mail" , '/test', array(
        'methods' => 'POST',
        'callback' => 'send_mail_test',
    ));
});