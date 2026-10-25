<?php

use BotMan\BotMan\Messages\Conversations\Conversation;

use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\File;

const FONT_AWESOME = '<link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.min.css">';
class AyudaGeneralConversation extends Conversation {
    public function askAyudaMenu(){
        //Se programa una pregunta donde se establacen dos respuestas por defecto y un fallback por si no es ninguna
        $question =  Question::create('¿Qué deseas hacer?')
                        ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
                        ->callbackId('que_quieres_hacer')
                        ->addButtons(
                            [Button::create(text: '¿Cómo iniciar sesión?')->value(value: 'login'),
                             Button::create('¿Cómo crear cuenta?')->value('loginSignUp'),
                             Button::create('¿Cómo inscribirse a un curso?')->value('signUpCurso'),
                             Button::create('¿Cómo inscribirse a un taller?')->value('signUpTaller'),
                             Button::create('¿Cómo inscribirse a un diplomado?')->value('signUpDiplomado'),
                             Button::create('¿Cómo recuperar cuenta?')->value('recoveryCuenta'),
                            ]);
        $this->ask($question, function(Answer $answer) {
          if ($answer->isInteractiveMessageReply()){
            $value = $answer->getValue();
            $text = $answer->getText();
            switch ($value) {
              case 'login':
                $this->login();
                break;
                
              case 'loginSignUp':
                $this->loginSignUp();
                break;
                
              case 'signUpCurso':
                $this->signUpCurso();
                break;
                
              case 'signUpTaller':
                $this->signUpTaller();
                break;
                
              case 'signUpDiplomado':
                $this->signUpDiplomado();
                break;
              
              case 'recoveryCuenta':
                $this->startConversation(new AyudaCuentaConversation);
                break;
                
              default;
                break;
            }
          } else {
            $this->askAyudaMenu();
          }
        });
    }

    public function login(){
      $mensaje = '
      <!DOCTYPE html> '. 
        $this->header() .'
          <body>
          <p>Para iniciar sesión: </p>
              <ol>   
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Dar clic en el boton "Iniciar Sesión".</li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Introducir usuario y contraseña.</li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Clic en el boton Iniciar sesión (ingresar)</li>
              </ol>
          </body>
        </html>';
        
        $this->say($mensaje);
        //$this->askAyudaMenu();
        $this->say('Para volver a ver el menu escribe <span style =" font-weight: bold;">Menu</span>.');

    }

    public function loginSignUp(){
      $mensaje = '
      <!DOCTYPE html> '. 
        $this->header() .'
          <body>
          <p>Para crear cuenta: </p>
              <ol>   
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Dar clic en el boton "Iniciar Sesión".</li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Dar clic en "Comience ahora creando una cuenta nueva".</li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Llenar todos los campos requeridos.</li>
                <li><i class="fa fa-user-o" aria-hidden="true"></i> Clic en el boton Crear mi cuenta nueva</li>
              </ol>
          </body>
        </html>';
        
        $this->say($mensaje);
        $this->say('Para volver a ver el menu escribe <span style =" font-weight: bold;">Menu</span>.');

    }

    public function signUpCurso() {

    }

    public function signUpTaller() {
      
    }

    public function signUpDiplomado() {
      
    }

    private function header() {
      return '
      <html lang="en">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0"> '. 
          FONT_AWESOME .' 
      </head>';

    }




    // public function askWhatToDo(){

    //     //Se programa una pregunta donde se establacen dos respuestas por defecto y un fallback por si no es ninguna de las dos
    //     $question =  Question::create('¿Qué deseas hacer?')
    //                     ->fallback('Lo siento pero...')
    //                     ->callbackId('que_quieres_hacer')
    //                     ->addButtons(
    //                         [Button::create('¿Ver todos los Cursos?')->value('cursos'),
    //                          Button::create('¿Ver todas las Talleres?')->value('talleres'),
    //                          Button::create('¿Ver todos los Diplomados?')->value('diplomados')]);
    //     $this->ask($question, function(Answer $answer) {

    //       if ($answer->isInteractiveMessageReply()){
    //         $value = $answer->getValue();
    //         $text = $answer->getText();
    //         // $this->say('Opcion, '.$value.' '.$text);
    //         switch ($value) {
    //             case 'cursos':
                    
    //                 $attachment = new File('https://universidadean.edu.co/sites/default/files/institucion/manuales/manual-de-usabilidad-pagina-web.pdf',
    //                                         ['custom_payload' => true,]);
    //                 break;
                
    //             case 'diplomados':
                        
    //                     break;
                
    //             case 'talleres':
    //                 break;
                
    //             default:
    //                 # code...
    //                 break;
    //         }

    //         $message = OutgoingMessage::create($text)
    //             ->withAttachment($attachment);
    //             //$bot->reply($message);
    //             $this->say($message);
    //            // $this->display();

    //         //    $attachment=new Image('https://picsum.photos/200/300');
    //         //    $response=OutgoingMessage::create('Este es mi nuevo producto')
    //         //        ->withAttachment($attachment);
    //         //    $this->say($response);


    //       }else{
    //         $this->say('Selccion no valida');
    //         $this->askWhatToDo();
    //       }
    //     });
    // }


    public function run() {
        $this->askAyudaMenu();
    }
}