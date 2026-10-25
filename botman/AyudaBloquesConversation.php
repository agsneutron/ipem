<?php
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Image;

//const FONT_AWESOME = '<link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">';
if (!defined('FONT_AWESOME')) {
  define('FONT_AWESOME', '<link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">');
}

class  AyudaBloquesConversation extends Conversation {
    public function getBloquesGeneral () {
        $question =  Question::create("¿Qué deseas hacer? Seleccione una opción")
        ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
                        ->callbackId('que_quieres_hacer')
                        ->addButtons(
                            [
                                Button::create('Información Plataforma')->value(value: '1'),
                                Button::create('Plataforma Incluyente')->value('2'),
                                Button::create('Registro')->value('3'),
                                Button::create('Navegación')->value('4'),

                            ]);
                            $this->ask($question, function(Answer $answer) {
                                if ($answer->isInteractiveMessageReply()){
                                  $value = $answer->getValue();
                                  $text = $answer->getText();
                                  switch ($value) {
                                    case '1':
                                        $this->getBlock1();
                                    break;
                                    case '2':
                                        $this->getBlock2();
                                    break;
                                    case '3':
                                        $this->getBlock3();
                                    break;
                                    case '4':
                                        $this->getBlock4();
                                    break;
                                      default;
                                      break;
                                  }
                                } else {
                                  $this->ayudaMenu();
                                }
                              });
    }
    
    public function  getBlock1(){
        $question =  Question::create("¿Qué deseas hacer? Seleccione una opción")
        ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
        ->callbackId('que_quieres_hacer')
        ->addButtons(
            [
                Button::create(text: '1 Qué opciones oferta la plataforma?')->value(value: '11'),
                Button::create('2 ¿Qué tengo que hacer para inscribirme a un curso?')->value('12'),
                Button::create('3 ¿Cómo puedo ver la lista de cursos?')->value('13'),
                // Button::create('4 Si quiero registrarme para poder inscribir en un curso, ¿Cuáles pasos tengo que realizar?')->value('14'),
                Button::create('Menu general')->value('mg'),
    
            ]);
            $this->ask($question, function(Answer $answer) {
                if ($answer->isInteractiveMessageReply()){
                $value = $answer->getValue();
                $text = $answer->getText();
                switch ($value) {
                    case '11':
                        $this->pregunta11('Qué opciones oferta la plataforma?');
                    break;
                    case '12':
                        $this->pregunta12('¿Qué tengo que hacer para inscribirme a un curso?');
                    break;
                    case '13':
                        $this->pregunta13('¿Cómo puedo ver la lista de cursos?');
                    break;
                    // case '14':
                    //     $this->pregunta14('¿Cuáles pasos tengo que realizar para inscribirme a un curso?');
                    // break;
                    default;
                        $this->getBloquesGeneral();   
                    break;
                }
                } else {
                $this->getBloquesGeneral();
                }
            });      
    }
    public function  getBlock2(){
        $question =  Question::create("¿Qué deseas hacer? Seleccione una opción")
        ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
        ->callbackId('que_quieres_hacer')
        ->addButtons(
            [
                Button::create('5 ¿Qué información adicional puedo encontrar en el CAD-SeMujeres?')->value('21'),
                Button::create('6 ¿La plataforma es inclusiva?')->value('22'),
                Button::create('7 ¿Dónde se encuentran las opciones inclusivas que ofrece la plataforma?')->value('23'),
                Button::create('8 ¿Dónde ubico las redes sociales de la Secretaría de la Mujer en la plataforma?')->value('24'),
                Button::create('Menu general')->value('mg'),
    
            ]);
            $this->ask($question, function(Answer $answer) {
                if ($answer->isInteractiveMessageReply()){
                $value = $answer->getValue();
                $text = $answer->getText();
                switch ($value) {
                    case '21':
                        $this->pregunta21('¿Qué información adicional puedo encontrar en el CAD-SeMujeres?');
                    break;
                    case '22':
                        $this->pregunta22('¿La plataforma es inclusiva?');
                    break;
                    case '23':
                        $this->pregunta23('¿Dónde se encuentran las opciones inclusivas que ofrece la plataforma?');
                    break;
                    case '24':
                        $this->pregunta24('¿Dónde ubico las redes sociales de la Secretaría de la Mujer en la plataforma?');
                    break;
                    default;
                        $this->getBloquesGeneral();   
                    break;
                }
                } else {
                $this->getBloquesGeneral();
                }
            });      
    }
    public function  getBlock3(){
        $question =  Question::create("¿Qué deseas hacer? Seleccione una opción")
        ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
        ->callbackId('que_quieres_hacer')
        ->addButtons(
            [
                Button::create('9 ¿Cómo inicio mi sesión como alumno?')->value('31'),
                Button::create('10 ¿Cómo recupero mi contraseña?')->value('35'),
                Button::create('11 ¿Cómo recupero mi usuario?')->value('36'),
                Button::create('12 ¿Cómo puedo registrarme a un curso?')->value('32'),
                Button::create('13 ¿Dónde puedo ver mis cursos activos?')->value('33'),
                Button::create('14 ¿Cómo puedo saber cuáles cursos he acreditado?')->value('34'),
                Button::create('Menu general')->value('mg'),
    
            ]);
            $this->ask($question, function(Answer $answer) {
                if ($answer->isInteractiveMessageReply()){
                $value = $answer->getValue();
                $text = $answer->getText();
                switch ($value) {
                    case '31':
                        $this->pregunta31('¿Cómo inicio mi sesión como alumno?');
                    break;
                    case '32':
                        $this->pregunta32('¿Cómo puedo registrarme a un curso?');
                    break;
                    case '33':
                        $this->pregunta33('¿Dónde puedo ver mis cursos activos?');
                    break;
                    case '34':
                        $this->pregunta34('¿Cómo puedo saber cuáles cursos he acreditado?');
                    break;
                    case '35':
                      $this->pregunta35('¿Cómo recupero mi contraseña?');
                    break;
                    case '36':
                      $this->pregunta36('¿Cómo recupero mi usuario?');
                    break;
                    default;
                        $this->getBloquesGeneral();   
                    break;
                }
                } else {
                $this->getBloquesGeneral();
                }
            });      
    }
    public function  getBlock4(){
        $question =  Question::create("¿Qué deseas hacer? Seleccione una opción")
        ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
        ->callbackId('que_quieres_hacer')
        ->addButtons(
            [
                Button::create('15 ¿Dónde puedo descargar mis constancias generadas por los cursos acreditados?')->value('41'),
                Button::create('16 Para cerrar mi sesión como alumno. ¿Qué debo realizar?')->value('42'),
                Button::create('17 ¿A dónde me puedo dirigir si tengo dudas sobre los cursos o la plataforma?')->value('43'),
                Button::create('Menu general')->value('mg'),
    
            ]);
            $this->ask($question, function(Answer $answer) {
                if ($answer->isInteractiveMessageReply()){
                $value = $answer->getValue();
                $text = $answer->getText();
                switch ($value) {
                    case '41':
                        $this->pregunta41('¿Dónde puedo descargar mis constancias generadas por los cursos acreditados?');
                    break;
                    case '42':
                        $this->pregunta42('¿Qué debo realizar para cerrar mi sesión?');
                    break;
                    case '43':
                        $this->pregunta43('¿A dónde me puedo dirigir si tengo dudas sobre los cursos o la plataforma?');
                    break;
                    case '44':
                        //$this->pregunta14();
                    break;
                    default;
                        $this->getBloquesGeneral();   
                    break;
                }
                } else {
                $this->getBloquesGeneral();
                }
            });      
    }
        
    private function pregunta11($text){
                $mensaje = '
              <!DOCTYPE html> '. 
                $this->header() .'
                  <body> <p class="negritas">'.$text.'</p>
                  <p>La plataforma te permite ingresar a: </p>
                      <ul>   
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> Cursos.</li>
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> Talleres.</li>
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> Diplomados.</li>
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> Recursos adicionales.</li>
                      </ul>
                  </body>
                </html>';
                $this->say($mensaje);
                $this->mensajeMenu(1);
            }
    private function pregunta12($text) {
                $mensaje = '
              <!DOCTYPE html> '. 
                $this->header() .'
                  <body> <p class="negritas">'.$text.'</p>
                  <p>Debes registrarte, realizando los siguientes pasos:</p> 
                    <ul>
                      <li>a. <p> Ir a la opción <span class="negritas">"Registrate"</span> del menú principal que se encuentra en la parte superior derecha. </p></li>
                      <li>b. <p> Llenar los campos con tu información. Hay algunos campos indispensables para el registro, si no se captura la información solicitada, no se podrá realizar tu registro</p></li>
                      <li>c. <p> Una vez completados, dar clic al botón <span class="negritas">"Crear mi cuenta nueva"</span>.</p></li>
                      <li>d. <p> Recibirás un correo electrónico, el cual tendrás que confirmar para concluir el proceso.</p></li>
                    </ul>
                  </body>
                </html>';
                $this->say($mensaje);
                $this->mensajeMenu(1);
            }
        
            private function pregunta13($text) {
                $mensaje = '
              <!DOCTYPE html> '. 
                $this->header() .'
                  <body> <p class="negritas">'.$text.'</p>
                  <p style ="text-align: justify;">Debes ir a la sección de <span class="negritas">"Recursos"</span> de la página principal y seleccionar la opción de <span class="negritas">“Cursos”</span>. Los mismos pasos deberás seguir si deseas ingresar a otro tipo de recurso como <span class ="negritas">“Taller”</span> o <span class ="negritas">“Diplomado”</span>.  </p>
                  </body>
                </html>';
                $this->say($mensaje);
                $this->mensajeMenu(1);
            }
            
            private function pregunta14($text) {
                $mensaje = '
              <!DOCTYPE html> '. 
                $this->header() .'
                  <body> <p class="negritas">'.$text.'</p>
                      <ul>   
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> <div> Ir a la opción del menú principal que dice <span style =" font-weight: bold;">"Regístrate"</span>.</div></li>
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> <div style ="text-align: justify;"> Llenar los campos que te solicita y cumplir con los que son requeridos, en caso de que algún campo requerido no sea capturado, no permitirá realizar el registro.</div></li>
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> <div> Una vez completados, dar clic al botón <span style =" font-weight: bold;">“Crear mi cuenta nueva”</span>.</div></li>
                        <li><i class="fa fa-user-o" aria-hidden="true"></i> <div> Recibirás un correo, el cual tendrás que confirmar para concluir el proceso.</div></li>
                      </ul>
                  </body>
                </html>';
                $this->say($mensaje);
                $this->mensajeMenu(1);
            }
            private function pregunta21($text) {
                $mensaje = '
              <!DOCTYPE html> '. 
                $this->header() .'
                  <body> <p class="negritas">'.$text.'</p>
                  <p>Existe más información en la sección de <span class="negritas"> “Recursos Adicionales”</span>, la cual se encuentra en la parte superior derecha como parte del menú principal del portal. Entre la información disponible se encuentran <span class="negritas"> “Manuales”</span>, <span class="negritas"> “Infografías”</span> y <span class="negritas"> “otros enlaces”</span></p>
                  </body>
                </html>';
                $this->say($mensaje);
                $this->mensajeMenu(2);
            }
            private function pregunta22($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Si, la plataforma te ofrece una serie de opciones donde puedes ampliar el texto, resaltar encabezados, resaltar links, ampliar el tamaño del cursor, cambiar a negro o blanco el cursor para ubicarlo mejor en la navegación.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(2);
            }
            private function pregunta23($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Del lado inferior derecho se encuentra el icono en color azul donde al hacer clic puedes activar las opciones que te permitan mejorar la experiencia de navegación.</p>
                    <p>Entre las que se encuentran: discapacidad visual, convulsiones y epilépticos, color de visión deficiente, ADHD, dislexia, aprendizaje, así como también te permite realizar ajustes como son: tamaño de letra, resaltar colores, resaltar encabezados, resaltar botones, resaltar enlaces. También podrás hacer ma´s grande el tamaño del texto, el epsaciado. Ajustar color al contraste y ajustar orientación.</p>
                    </body>
                  </html>';
        
                // $attachment=new Image('./img/access.jpg');
                //        $response=OutgoingMessage::create($mensaje)
                //            ->withAttachment($attachment);
                //        $this->say($response);
                $this->say($mensaje);
        
                $this->mensajeMenu(2);
            }
            private function pregunta24($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>En la parte inferior izquierda se encuentran los iconos de <span class="negritas">Facebook</span> de la Secretaría de la Mujer, así como <span class="negritas">Instagram</span> y <span class="negritas">X (twitter)</span>.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(2);
            }
            private function pregunta31($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Debes ir a la parte superior derecha y hacer clik sobre el icono que tiene un candado y el texto <span class="negritas">Iniciar Sesión</span>. Deberás proporcionar tu usuario y contraseña.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(3);
            }
            private function pregunta32($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Ingresas a la sección de <span class="negritas">“Cursos”</span>, donde visualizarás la lista de cursos y podrás elegir el de tu interés al hacer clic sobre la ficha del curso, en la ficha se encuentra el botón de <span class="negritas">“Inscríbete ahora”</span>.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(3);
            }
            private function pregunta33($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Cuando ingresas con tu usuario, visualizarás tu tablero. En la sección <span class="negritas">“Mis cursos”</span> podras ver los que tienes activos.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(3);
            }
            private function pregunta34($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Cuando ingresas con tu usuario, visualizarás tu tablero.En la sección <span>"Elementos acreditados recientemente"</span> ubicada del lado derecho podrś ver los cursos acreditados.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(3);
            }
            private function pregunta35($text) {
              $mensaje = '
              <!DOCTYPE html> '. 
                $this->header() .'
                  <body> <p class="negritas">'.$text.'</p>
                  <p>Debes ir a la parte superior derecha, hacer clik en la opción de <span class="negritas">“iniciar sesión”</span>, ir a la opción que dice <span class="negritas">“¿olividó su usuario o contraseña?"</span>, donde te solicitará usuario o dirección de correo. El sistema realizará su propia búsqueda donde en caso de tener registrado algunos de los dos datos de opción para recuperar la contraseña le enviará un email con instrucciones para recuperar su contraseña y volver a ingresar a la plataforma con tu usuario previamente registrado. </p>
                  </body>
                </html>';
              $this->say($mensaje);
              $this->mensajeMenu(3);
            }
            private function pregunta36($text) {
              $mensaje = '
              <!DOCTYPE html> '. 
                $this->header() .'
                  <body> <p class="negritas">'.$text.'</p>
                  <p>Debes ir a la parte superior derecha, hacer clik en la opción de <span class="negritas">“iniciar sesión”</span>, ir a la opción que dice <span class="negritas">“¿olividó su usuario o contraseña?</span>, donde te solicitará tu usuario o dirección de correo. En este caso como no cuenta con tu usuario, es necesario que proporciones la cuenta de correo que asignaste como medio de contacto en la plataforma. Una vez que proporciones el correo electrónico el sistema realizará la búsqueda de tus datos y te enviará un email con instrucciones para recuperar tu usuario y volver a ingresar a la plataforma con tu usuario previamente registrado. </p>
                  </body>
                </html>';
              $this->say($mensaje);
              $this->mensajeMenu(3);
            }
            private function pregunta41($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Cuando ingresas con tu usuario, visualizarás tu tablero de usuario. En la sección <span class="negritas">“Elementos accedidos recientemente”</span>, ubicada del lado superior derecho puedes ver la opción que dice <span class="negritas">“Constancia de…”</span>, dependerá del curso que ya te emitió la constancia, debes hacer clic en el apartado, donde al ingresar te permitirá ver el certificado al hacer clic en el botón <span class="negritas">“ver certificado”</span> .</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(4);
            }
            private function pregunta42($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>Debes ir a la parte superior derecha e ingresar a la opción de tu usuario, donde te abrirá tu tarjeta de usuario, en ésta se encuentra incluida la opción de <span class="negritas">“Salir”</span>.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(4);
            }
            private function pregunta43($text) {
                $mensaje = '
                <!DOCTYPE html> '. 
                  $this->header() .'
                    <body> <p class="negritas">'.$text.'</p>
                    <p>En el home de la plataforma, en la parte inferior se encuentra la opción <span class="negritas">“Acerca de sitio”</span>, la primera opción refiere a nuestro enlace de contacto, desde ahí podrás escribirnos acerca de dudas o sugerencias.</p>
                    </body>
                  </html>';
                $this->say($mensaje);
                $this->mensajeMenu(4);
            }

    
        function header() {
            return '
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0"> '. 
                FONT_AWESOME .' 
                <style> .negritas {font-weight: bold;}  
                        p  {
                            text-align: justify;
                            }
                        div  {
                            text-align: justify;
                            }
                </style>
            </head>';
      
          }    
    private function mensajeMenu($padre){
        //return $this->say('Para volver a ver el menu escribe <span style =" font-weight: bold;">Menu</span>.');
        $question =  Question::create("¿Qué deseas hacer? Seleccione una opción")
    ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
    ->callbackId('que_quieres_hacer')
    ->addButtons(
        [
            Button::create('Regresar')->value(value: $padre),
            Button::create('Menu general')->value('mg'),
    
        ]);
        $this->ask($question, function(Answer $answer) {
            if ($answer->isInteractiveMessageReply()){
              $padre = $answer->getValue();
              switch ($padre) {
                case 1:
                    $this->getBlock1();
                break;
                case 2:
                    $this->getBlock2();
                break;
                case 3:
                    $this->getBlock3();
                break;
                case 4:
                    $this->getBlock4();
                break;
                
                default;
                    $this->getBloquesGeneral();   
                break;
        
               }
            } else {
              $this->getBloquesGeneral();
            }
          });
    }

    public function run() {
        $this->getBloquesGeneral();
    }

}