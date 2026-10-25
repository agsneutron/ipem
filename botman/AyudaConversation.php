<?php    
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Image;

const FONT_AWESOME = '<link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">';
class  AyudaConversation extends Conversation {
    
    public function ayudaMenu(){
        //require_once __DIR__ . '/../config.php';
        // global $remotedb;
        // $conditions['deleted'] = 0;
        // $userlist = array_keys($remotedb->get_records('user', $conditions));
        //$course = $DB->get_record('course', array('id'=>3), '*');
        //$this->say($course->fullname);


        //Se programa una pregunta donde se establacen dos respuestas por defecto y un fallback por si no es ninguna
        $question =  Question::create("¿Qué deseas hacer? Seleccion una opción")
                        ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
                        ->callbackId('que_quieres_hacer')
                        ->addButtons(
                            [
                                Button::create('1 ¿Cuáles opciones oferta la plataforma?')->value(value: '1'),
                                Button::create('2 ¿Qué tengo que hacer para ingresar a un curso?')->value('2'),
                                Button::create('3 ¿Cómo puedo entrar a ver la lista de cursos?')->value('3'),
                                Button::create('4 Si quiero registrarme para poder inscribir en un curso, ¿Cuáles pasos tengo que realizar?')->value('4'),
                                Button::create('5 ¿Dónde puedo encontrar más información sobre los temas que atiende la Secretaría a través de sus cursos formativos?')->value('5'),
                                Button::create('6 ¿La plataforma es inclusiva?')->value('6'),
                                Button::create('7 ¿Dónde se encuentran las opciones inclusivas que ofrece la plataforma?')->value('7'),
                                Button::create('8 ¿Dónde ubico las redes sociales de la Secretaría de la Mujer en la plataforma?')->value('8'),
                                Button::create('9 ¿Cómo inicio mi sesión como alumno?')->value('9'),
                                Button::create('10 ¿Cómo puedo registrarme a un curso?')->value('10'),
                                Button::create('11 ¿Dónde puedo ver mis cursos activos?')->value('11'),
                                Button::create('12 ¿Cómo puedo saber cuáles cursos he acreditado?')->value('12'),
                                Button::create('13 ¿Dónde puedo consultar mis constancias generadas por los cursos acreditados?')->value('13'),
                                Button::create('14 Para cerrar mi sesión como alumno. ¿Cuáles pasos tengo que realizar?')->value('14'),
                                Button::create('15 ¿A dónde me puedo dirigir si tengo dudas sobre los cursos o la plataforma?')->value('15'),

                            ]);
        $this->ask($question, function(Answer $answer) {
                                if ($answer->isInteractiveMessageReply()){
                                  $value = $answer->getValue();
                                  $text = $answer->getText();
                                  switch ($value) {
                                    case '1':
                                        $this->pregunta1();
                                      break;
                                      
                                    case '2':
                                        $this->pregunta2();
                                      break;
                                      
                                    case '3':
                                        $this->pregunta3();
                                      break;
                                      
                                    case '4':
                                        $this->pregunta4();
                                      break;
                                      
                                    case '5':
                                        $this->pregunta5();
                                      break;
                                    
                                    case '6':
                                        $this->pregunta6();
                                      break;

                                    case '7':
                                        $this->pregunta7();
                                    break;

                                    case '8':
                                        $this->pregunta8();
                                    break;

                                    case '9':
                                        $this->pregunta9();
                                    break;
                                                                        
                                    case '10':
                                        $this->pregunta10();
                                    break;

                                    case '11':
                                        $this->pregunta11();
                                        break;

                                    case '12':
                                        $this->pregunta12();
                                    break;
                                    
                                    case '13':
                                        $this->pregunta13();
                                    break;

                                    case '14':
                                        $this->pregunta14();
                                    break;
                                        
                                    case '15':
                                        $this->pregunta15();
                                    break;
                                      
                                    default;
                                      break;
                                  }
                                } else {
                                  $this->ayudaMenu();
                                }
                              });

    }

    private function pregunta1(){
        $mensaje = '
      <!DOCTYPE html> '. 
        $this->header() .'
          <body>
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
        $this->mensajeMenu();
    }

    private function pregunta2() {
        $mensaje = '
      <!DOCTYPE html> '. 
        $this->header() .'
          <body>
          <p>Debes registrarte.</p> 
          <p>Esto lo puedes realizar ingresando a la opción <span style =" font-weight: bold;">“Regístrate”</span> que se encuentra en la parte superior derecha </p>
          </body>
        </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }

    private function pregunta3() {
        $mensaje = '
      <!DOCTYPE html> '. 
        $this->header() .'
          <body>
          <p>Debes ir a la sección de Recursos de la página principal y hacer clic a la opción de <span style =" font-weight: bold;">“Cursos”</span>. Los mismos pasos deberás seguir si deseas ingresar a otro tipo de recurso como <span style =" font-weight: bold;">“Taller”</span> o <span style =" font-weight: bold;">“Diplomado”</span>  </p>
          </body>
        </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    
    private function pregunta4() {
        $mensaje = '
      <!DOCTYPE html> '. 
        $this->header() .'
          <body>
              <ul>   
                <li><i class="fa fa-user-o" aria-hidden="true"></i> <p>Ir a la opción del menú principal que dice <span style =" font-weight: bold;">"Regístrate"</span>.</p></li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Llenar los campos que te solicita y cumplir con los que son requeridos, en caso de que algún campo requerido no sea capturado, no permitirá realizar el registro.</li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> <p>Una vez completados, dar clic al botón <span style =" font-weight: bold;">“Crear mi cuenta nueva”</span>.</p></li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Recibirás un correo, el cual tendrás que confirmar para concluir el proceso.</li>
              </ul>
          </body>
        </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta5() {
        $mensaje = '
      <!DOCTYPE html> '. 
        $this->header() .'
          <body>
          <p>Existe más información en la sección de <span class="negritas"> “Recursos Adicionales”</span>, la cual se encuentra en la parte superior derecha como parte del menú principal del portal. Entre la información disponible se encuentran <span class="negritas"> “Manuales”</span>, <span class="negritas"> “Infografías”</span> y <span class="negritas"> “otros enlaces”</span></p>
          </body>
        </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta6() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Si, la plataforma te ofrece una serie de opciones donde puedes ampliar el texto, resaltar encabezados, resaltar links, ampliar el tamaño del cursor, cambiar a negro o blanco el cursor para ubicarlo mejor en la navegación.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta7() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Las puedes activar del lado derecho, se encuentra un icono en color azul con blanco donde al hacer clik ingresarás a las opciones que te permitirá mejorar la experiencia de navegación.</p>
            </body>
          </html>';
        //$this->say($mensaje);

        $attachment=new Image('./img/access.jpg');
               $response=OutgoingMessage::create($mensaje)
                   ->withAttachment($attachment);
               $this->say($response);

        $this->mensajeMenu();
    }
    private function pregunta8() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>En la parte inferior izquierda se encuentran los iconos de <span class="negritas">Facebook</span> de la Secretaría de la Mujer, así como <span class="negritas">Instagram</span> y <span class="negritas">X (twitter)</span>.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta9() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Debes ir a la parte superior derecha y hacer clik sobre el icono que tiene un candado y el texto <span class="negritas">Iniciar Sesión</span>. Deberás proporcionar tu usuario y contraseña.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta10() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Ingresas a la sección de “Cursos”, donde visualizarás la lista de cursos y podrás elegir el de tu interés al hacer clic sobre la ficha del curso, en la ficha se encuentra el botón de <span class="negritas">“Inscríbete ahora”</span>.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta11() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Cuando ingresas con tu usuario, visualizarás tu tablero de usuario, en la sección “Mis cursos” te listará los cursos que tienes activos y que estás cursando.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta12() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Cuando ingresas con tu usuario, visualizarás tu tablero de usuario, en la sección que se encuentra del lado derecho titulada <span class="negritas">“Elementos acreditados"</span>.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta13() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Cuando ingresas con tu usuario, visualizarás tu tablero de usuario, en la sección <span class="negritas">“Elementos accedidos recientemente”</span> te listará las constancias generadas de tus cursos.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta14() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>Debes ir a la parte superior derecha e ingresar a la opción de tu usuario, donde te abrirá tu tarjeta de usuario, en ésta se encuentra incluida la opción de <span class="negritas">“Salir”</span>.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function pregunta15() {
        $mensaje = '
        <!DOCTYPE html> '. 
          $this->header() .'
            <body>
            <p>En el home de la plataforma, en la parte inferior se encuentra la opción de <span class="negritas">“Acerca de sitio”</span>, la primera opción refiere a nuestro enlace de contacto, desde ahí podrás escribirnos acerca de dudas o sugerencias.</p>
            </body>
          </html>';
        $this->say($mensaje);
        $this->mensajeMenu();
    }
    private function header() {
        return '
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0"> '. 
            FONT_AWESOME .' 
            <style> .negritas {font-weight: bold;} </style>
        </head>';
  
      }

      private function mensajeMenu(){
        return $this->say('Para volver a ver el menu escribe <span style =" font-weight: bold;">Menu</span>.');
      }
    public function run() {
            $this->ayudaMenu();
        }
}