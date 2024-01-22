<div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div id="simple-send-mail-notice" class="notice settings-error is-dismissible" style="display: none;"> 
                <p><strong></strong></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dispensar este aviso.</span>
                </button>
            </div>

            <hr>
            <h2>Configuraçõs SMTP</h2>
            <form action="options.php" method="post">
                <?php settings_fields('md-simple-send-mail-group'); ?>

                <table class="form-table">
                    <tbody>
                        <tr class="form-group">
                            <th scope="row"><label class="form-label" for="smtp_host">Servidor SMTP</label></th>
                            <td><input type="text" name="smtp_host" id="smtp_host" class="form-control regular-text" placeholder="smtp.gmail.com" value="<?php echo esc_attr(get_option('smtp_host')); ?>" /></td>
                        </tr>

                        <tr class="form-group">
                            <th scope="row"><label class="form-label" for="smtp_username">Usuário SMTP</label></th>
                            <td><input type="text" name="smtp_username" id="smtp_username" class="form-control regular-text" placeholder="seu_email@gmail.com" value="<?php echo esc_attr(get_option('smtp_username')); ?>" /></td>
                        </tr>

                        <tr class="form-group">
                            <th scope="row"><label class="form-label" for="smtp_password">Senha SMTP</label></th>
                            <td><input type="password" name="smtp_password" id="smtp_password" class="form-control regular-text" placeholder="************" value="<?php echo esc_attr(get_option('smtp_password')); ?>" /></td>
                        </tr>

                        <tr class="form-group">
                            <th scope="row"><label class="form-label" for="smtp_port">Porta SMTP</label></th>
                            <td><input type="number" name="smtp_port" id="smtp_port" placeholder="587" class="form-control regular-text" value="<?php echo (int) get_option('smtp_port'); ?>" /></td>
                        </tr>

                        <tr class="form-group">
                            <th scope="row"><label class="form-label" for="smtp_protocol">TLS ou SSL</label></th>
                            <td><input type="text" maxlength="3" name="smtp_protocol" id="smtp_protocol" placeholder="TLS/SSL" class="form-control regular-text" value="<?php echo esc_attr(get_option('smtp_protocol')); ?>" /></td>
                        </tr>

                    </tbody>
                </table>

                <?php
                    do_settings_sections('md-simple-send-mail-group');
                    submit_button(__('Salvar Configurações', 'textdomain'));
                ?>
            </form>

            <hr>

            <h2>Testar de Envio</h2>
            <form id="test-form-contact" action="/wp-json/simple-send-mail/test" method="post">
                <table class="form-table">
                    <tbody>
                        <tr class="form-group">
                            <th scope="row"><label class="form-label" for="email">Enviar Para:</label></th>
                            <td>
                                <input type="text" name="email" id="email" class="form-control regular-text" placeholder="todosend@email.com" value="messiasdias.ti@gmail.com" />
                                <p class="description">Endereço de email ao qual será enviada um mensagem de teste.</p>
                            </td>
                        </tr>

                        <tr class="form-group">
                            <th scope="row"><label class="form-label" for="submit"></label></th>
                            <td><input type="submit" name="submit-test" id="submit-test" class="button button-success" value="Testar Envio" /> </td>
                        </tr>
                    </tbody>
                </table>
            </form>

        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        var h1 = document.querySelector('h1');
        var form_contact = document.getElementById('test-form-contact');
        form_contact.addEventListener('submit', (event) => {
            event.preventDefault();

            let notice = document.getElementById('simple-send-mail-notice')
            if(notice) {
                notice
                    .querySelector('.notice-dismiss')
                    .addEventListener('click', (event) => notice.style.display = "none")
            }
            
            let body = {}
            Object
                .values(Array.from(new FormData(form_contact)))
                .forEach(value => body[value[0]] = value[1])

            fetch("/wp-json/simple-send-mail/send", {
                method: "POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(body)
            })
            .then(res => res.json())
            .then(res => {
                notice.style.display = "block";
                notice.querySelector('p>strong').innerHTML = res.message;
                notice.classList.add(res.status ? 'notice-success' : 'notice-warning');
            })
        });
    }, 10)
</script>