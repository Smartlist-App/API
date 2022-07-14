<?php
ini_set('display_errors', 1);
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/lib/phpmailer/Exception.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/lib/phpmailer/phpmailer.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/lib/phpmailer/SMTP.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/cred.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/app/encrypt.php';
require dirname($_SERVER['DOCUMENT_ROOT']).'/api/v2/header.php';

function SendVerificationEmail($email, $code) {
$mail = new PHPMailer(true);

try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    /* $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'in-v3.mailjet.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '84dec880c182ed697f8e48b1486db527';
        $mail->Password   = '44b5b1d2e278c6e73c5f36b1c4588393';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 45;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    */
    //Recipients
    $mail->setFrom('hello@smartlist.tech', 'Smartlist');
    $mail->addAddress($email, 'Manu');     //Add a recipient

  	$mail->isHTML(true);
    $mail->Subject = 'Your account verification code is...';

 $codeArray = str_split((string)$code);
$codeHtml = "";
foreach($codeArray as $number) {
    if(is_numeric($number)) $codeHtml .= '<td style="background: #eee;padding:14px;font-size:17px;border-radius:4px">'.$number.'</td>';
}


    $mail->Body    = <<<HEREDOC
    <!DOCTYPE html>
    <html
        lang="en"
        style="background-color: #f3f4f8; font-size: 0; line-height: 0"
        >
    <head
            xmlns="http://www.w3.org/1999/xhtml"
            lang="en"
            xml:lang="en"
            style="font-family: 'Outfit', sans-serif"
            >
        <meta charset="UTF-8" style="font-family: Outfit, sans-serif" />
        <style>
        /* latin */
        @font-face {
            font-family: "Outfit";
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/outfit/v4/QGYyz_MVcBeNP4NjuGObqx1XmO1I4TC1O4a0Ew.woff2)
            format("woff2");
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6,
            U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193,
            U+2212, U+2215, U+FEFF, U+FFFD;
        }
        </style>
        <title style="font-family: Outfit, sans-serif">Title</title>
        <meta
            http-equiv="Content-Type"
            content="text/html; charset=utf-8"
            style="font-family: 'Outfit', sans-serif"
            />
        <meta
            name="viewport"
            content="width=device-width"
            style="font-family: 'Outfit', Arial, sans-serif"
            />
    </head>
    <body
            style="
                -moz-box-sizing: border-box;
                -ms-text-size-adjust: 100%;
                -webkit-box-sizing: border-box;
                -webkit-text-size-adjust: 100%;
                background-color: #f3f4f8;
                box-sizing: border-box;
                color: #0a0a0a;
                font-family: 'Outfit', sans-serif;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.43;
                min-width: 600px;
                text-align: left;
                width: 100% !important;
                margin: 0;
                padding: 0;
                "
            bgcolor="#f3f4f8"
            >
        <div style="display: none; max-height: 0px; overflow: hidden; mso-hide: all">
        Thanks for creating an account with us. Enter the code below to access your Smartlist account
        </div>
        <table
            align="center"
            width="600"
            cellpadding="0"
            cellspacing="0"
            style="
                    border-collapse: collapse;
                    border-spacing: 0;
                    font-family: 'Outfit', sans-serif;
                    max-width: 600px;
                    min-width: 600px;
                    text-align: left;
                    vertical-align: top;
                    padding: 0;
                    "
            >
        <tbody>
            <tr
                style="
                    font-family: 'Outfit', sans-serif;
                    text-align: left;
                    vertical-align: top;
                    padding: 0;
                    "
                align="left"
                >
            <td
                style="
                        -moz-hyphens: auto;
                        -webkit-hyphens: auto;
                        border-collapse: collapse !important;
                        color: #0a0a0a;
                        font-family: 'Outfit', sans-serif;
                        font-size: 14px;
                        font-weight: 400;
                        hyphens: auto;
                        line-height: 1.43;
                        text-align: left;
                        vertical-align: top;
                        word-wrap: break-word;
                        margin: 0;
                        padding: 43px 0 0;
                        "
                align="left"
                valign="top"
                >
                <div
                    style="
                            background-color: #fff;
                            font-family: 'Outfit', sans-serif;
                            border-radius: 8px;
                            "
                    >
                <div
                    style="
                            font-family: 'Outfit', sans-serif;
                            height: 100%;
                            min-height: 100px;
                            padding: 0 40px;
                            "
                    >
                    <table
                        style="
                                border-collapse: collapse;
                                border-spacing: 0;
                                font-family: 'Outfit', sans-serif;
                                text-align: left;
                                vertical-align: top;
                                width: 100%;
                                padding: 0;
                                "
                        >
                    <tbody>
                        <tr
                            style="
                                font-family: 'Outfit', sans-serif;
                                text-align: left;
                                vertical-align: top;
                                padding: 0;
                                "
                            align="left"
                            >
                        <td
                            style="
                                    -moz-hyphens: auto;
                                    -webkit-hyphens: auto;
                                    border-collapse: collapse !important;
                                    color: #0a0a0a;
                                    font-family: 'Outfit', sans-serif;
                                    font-size: 14px;
                                    font-weight: 400;
                                    hyphens: auto;
                                    line-height: 1.43;
                                    text-align: left;
                                    vertical-align: top;
                                    width: 50%;
                                    word-wrap: break-word;
                                    margin: 0;
                                    padding: 32px 0 0;
                                    "
                            align="left"
                            valign="top"
                            >
                            <a
                            href="http://whatis.customeriomail.com"
                            target="_blank"
                            style="
                                    color: #2a79ff;
                                    font-family: 'Outfit', sans-serif;
                                    font-weight: 400;
                                    line-height: 1.43;
                                    text-align: left;
                                    text-decoration: none;
                                    margin: 0;
                                    padding: 0;
                                    "
                            >
                            <img
                                src="https://i.ibb.co/28j7cX0/image.png"
                                style="
                                        -ms-interpolation-mode: bicubic;
                                        clear: both;
                                        display: block;
                                        font-family: 'Outfit', sans-serif;
                                        height: 30px;
                                        max-height: 100%;
                                        max-width: 100%;
                                        outline: 0;
                                        text-decoration: none;
                                        width: auto;
                                        border: none;
                                        "
                                />
                            </a>
                        </td>
                        <td
                            style="
                                    -moz-hyphens: auto;
                                    -webkit-hyphens: auto;
                                    border-collapse: collapse !important;
                                    color: #0a0a0a;
                                    font-family: 'Outfit', sans-serif;
                                    font-size: 14px;
                                    font-weight: 400;
                                    hyphens: auto;
                                    line-height: 1.43;
                                    text-align: right;
                                    vertical-align: top;
                                    width: 50%;
                                    word-wrap: break-word;
                                    margin: 0;
                                    padding: 26px 0 0;
                                    "
                            align="right"
                            valign="top"
                            >
                            <a
                            href="https://login.smartlist.tech"
                            target="_blank"
                            style="
                                    background-color: #3f53d9;
                                    border-radius: 15px;
                                    box-sizing: border-box;
                                    color: #fff !important;
                                    cursor: pointer;
                                    display: inline-block;
                                    font-family: 'Outfit', sans-serif;
                                    font-size: 15px !important;
                                    font-stretch: normal;
                                    font-style: normal;
                                    font-weight: 400;
                                    height: 40px;
                                    letter-spacing: normal;
                                    line-height: 40px !important;
                                    text-align: center;
                                    text-decoration: none;
                                    white-space: nowrap;
                                    width: 130px;
                                    padding: 0;
                                    border: none;
                                    "
                            >Login</a
                            >
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <div
                    style="font-family: 'Outfit', sans-serif"
                    >
                    <div
                        style="
                                font-family: 'Outfit', sans-serif;
                                background: no-repeat center / 100% auto;
                                padding: 40px 40px 36px;
                                "
                        >
                    <div
                        style="
                                color: #050038;
                                font-family: 'Outfit', sans-serif;
                                font-size: 42px !important;
                                font-stretch: normal;
                                font-style: normal;
                                font-weight: 700;
                                letter-spacing: normal;
                                line-height: 1.24;
                                "
                        >
                        Verify your email
                    </div>
                    <div
                        style="
                                color: #050038;
                                font-family: 'Outfit', sans-serif;
                                font-size: 20px !important;
                                font-stretch: normal;
                                font-style: normal;
                                font-weight: 400;
                                letter-spacing: normal;
                                line-height: 1.4;
                                margin-top: 16px;
                                opacity: 0.6;
                                "
                        >
                        Thanks for creating an account with us. Enter the code below to access your Smartlist account
                    </div>

                    <div
                        style="
                                color: #050038;
                                font-family: 'Outfit', sans-serif;
                                font-size: 20px !important;
                                font-stretch: normal;
                                font-style: normal;
                                font-weight: 400;
                                letter-spacing: normal;
                                line-height: 1.4;
                                margin-top: 16px;
                                opacity: 0.6;
                                "
                        >
                        If you did not create a Smartlist account, you can safely ignore this email
                    </div>

                    <table style="border-spacing: 5px;margin-top:10px;">
                        $codeHtml
                    </table>

                    </div>
                </div>
                </div>
            </td>
            </tr>
        </tbody>
        </table>

        <!-- prevent Gmail on iOS font size manipulation -->
        <div style="display: none; white-space: nowrap; font: 15px courier"></div>
    </body>
    </html>
    HEREDOC;

    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';
} catch (Exception $e) {
    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} 

}

if(isset($_POST['id'])) {
    $url = "https://hcaptcha.com/siteverify";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Content-Type: application/x-www-form-urlencoded",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = "response=" . urlencode($_POST['h-captcha-response']) . "&secret=" . App::captchaSecret;

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = json_decode(curl_exec($curl));
curl_close($curl);

if ($resp->success !== true) {
   die(json_encode([
       "success" => false,
       "error" => "Invalid captcha"
   ]));
}

     try {
        $dbh = new PDO("mysql:host=" . App::server . ";dbname=" . App::database, App::user, App::password);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = $dbh->prepare("SELECT `email`, `verifyToken` FROM Accounts WHERE verifiedEmail = 0 AND id = :id");
        $sql->execute([":id" => $_POST['id']]);
        $users = $sql->fetchAll();
        foreach ($users as $row) {
            SendVerificationEmail(Encryption::decrypt($row['email']), intval($row['verifyToken']));
        }
    } catch (PDOException $e) {
        var_dump($e);
    }
    die(json_encode([
       "success" => true,
       "error" => "Resent email"
   ]));
}