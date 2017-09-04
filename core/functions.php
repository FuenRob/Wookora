<?php
// Class with all functions
class Core
{
    // Variables
    protected $time;
    
    // Construct
    public function __construct() {
        $this->time = date("d/m/Y G:i");
    }   
    
    // Function Login
    public function login($username, $password){
        $db = new db();
        $pass = md5($password);
        $conditions = '`email` = "'. $username .'" AND `password` = "'. $pass . '"';
        
        $db->connect();
        $result = $db->select('*','users', $conditions);
        if($result->num_rows > 0){
            while($user = mysqli_fetch_array($result)){
                $id_user = $user['id'];
                $name = $user['name'];
            }
            $array = array(1,$id_user, $name);
        }else{
            $array = array(0,"Oops... We have a problem","Error credenciales incorrectas.");
        }
        $db->disconnect();
        echo $state = json_encode($array);
        
    }
    
    // Function Logout
    public function logout($id_user){
        setcookie("session_us", "", time() - 3600);
    }
    
    // Function add new user
    public function setUser($name, $email, $pass){
        $log = new log();
        $password = md5($pass);
        $message1='You can start to develop a new web app';

        $log->registerLogs(' [DATA FORM: NAME] '.$name.' [DATA FORM: EMAIL] '.$email);
        $db = new db();
        $db->connect();
        $id_user = $db->insert('`name`, `email`, `password`, `create_date`','"'. $name .'","'. $email .'","'. $password .'","'. $this->time .'"','users');
        if($id_user){
            $db->disconnect();
            $sentMail = Core::sentMail($message1, $email, 'Welcome to Wookora', false);
            if($sentMail){
                $array = array(1,$id_user, $name);
            }else{
                $array = array(0, "Oops... We have a problem", "No send mail");
                $log->applicationLogs(' [METHOD] setUser() [ERROR] No send mail [DATA FORM: NAME] '.$name.' [DATA FORM: EMAIL] '.$email, 'ERR-functions-setUser-002');
            }
        }else{
            $array = array(0, "Oops... We have a problem", "The user could not be save");
        }
        
        echo $state = json_encode($array);
    }
    
    // Function for generate the user's list
    public function getUsers(){
        $db = new db();
        $db->connect();
        $array = array();
        $result = $db->select('*','users');
        if($result->num_rows > 0){
            while($user = mysqli_fetch_array($result)){
                $name = Core::eliminar_tildes($user['name']);
                
                $new_line['user'] = array("id" => $user['id'],
                                          "name" => $name,
                                          "email" => $user['email'],
                                          "dateCreation" => $user['create_date']
                                          
                        );
                array_push($array,$new_line['user']);
            }
        }else{
            $array = array(0,"No client registrered");
        }
        $db->disconnect();
        echo $state = json_encode($array);
        
    }
    
    // Send mail
    public function sentMail($message = "", $to = "", $subject = "", $attachment = false){
        $log = new log();
        $mail=new PHPMailer();
        $mail->CharSet = 'UTF-8';
        
        $mail->IsSMTP();
        // Host SMTP for send mail. Example: smtp.gmail.com
        $mail->Host       = MAIL_HOST;
        // Security Protocol: tls or ssl
        $mail->SMTPSecure = MAIL_SECURITY;
        // Port
        $mail->Port       = MAIL_PORT;
        // Log type: 0 = off
        $mail->SMTPDebug  = 0;
        // Need login?
        $mail->SMTPAuth   = MAIL_LOGIN;
        $mail->Username   = MAIL_USER;
        $mail->Password   = MAIL_PASSWORD;
        
        // From 
        $mail->AddReplyTo(MAIL_FROM,MAIL_NAME);
        // Subject
        if(!empty($subject)){
            $mail->Subject = $subject;
        }else{
            $mail->Subject = 'Test';
        }
        
        // Body
        $mail->MsgHTML($message);
        
        // To
        if(!empty($to)){
            $mail->AddAddress($to);
        }else{
            $mail->AddAddress('example@gmail.com');
        }
        
        // BCC
        //$mail->AddBCC('example@gmail.com');
        
        // With attached file?
        if($attachment) {
            $fileName = "sendPDF.pdf";
            $mail->AddAttachment("../assets/attachment/".$fileName, "name-example.pdf");
        }
        
        if(!$mail->send()){
            $log->applicationLogs(' [METHOD] sentMail()', 'ERR-functions-sentMail-001');
            return false;
        }else{
            return true;
        }
    }
    
    function eliminar_tildes($cadena){
        
        $cadena = utf8_encode($cadena);
        
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return $cadena;
    }
    
}
?>
