<?php

class App_HtmlMailer extends Zend_Mail {

    static $fromName = "Ezequiel SP - ZF1";
    static $fromEmail = "noreply@ezequielsp.rhcloud.com";

    public function newUserCeated($data) {
        $config = array(
                'auth'     => 'login',
                'username' => 'USERNAME',
                'password' => 'SENHAGMAIL',
                'ssl'      => 'ssl',
                'port'     => 465
        ); // Optional port number supplied

        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH .
                    '/modules/home/views/helpers/emails');

        $html->assign('name', $data['name']);
        $html->assign('login', $data['login']);
        
        $html->assign('date', date('d/m/Y H:i:s', $data['timestamp']));
        
        $mail = new Zend_Mail("UTF-8");
        
        $bodyText = $html->render('newUser.phtml');

        $mail->setFrom(self::$fromEmail, self::$fromName);
        $mail->addTo('9892345@gmail.com');

        $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
        $mail->setSubject('Novo usuário cadastrado!');
        $mail->setBodyHtml($bodyText);
        $mail->send($transport);
    }
}

