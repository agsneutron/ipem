<?php

use BotMan\BotMan\Messages\Conversations\Conversation;

use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\File;
class AyudaCuentaConversation extends Conversation {
   protected $answer;
   public function askCuentaMenu(){
    //Se programa una pregunta donde se establacen dos respuestas por defecto y un fallback por si no es ninguna
    $question =  Question::create('¿Qué deseas hacer?')
                    ->fallback('La opción que elegiste no es válida, inténtalo nuevamente.')//('Lo siento pero...')
                    ->callbackId('que_quieres_hacer')
                    ->addButtons(
                        [Button::create(text: 'Como iniciar sesion')->value('login'),
                         Button::create('Crear Cuenta')->value('loginSigUp'),
                         Button::create('Inscribirse a un curso')->value('signUpCurso'),
                         Button::create('Inscribirse a un taller')->value('signUpTaller'),
                         Button::create('Inscribirse a un diplomado')->value('signUpDiplomado'),
                        ]);
    $this->ask($question, function(Answer $answer) {
      if ($answer->isInteractiveMessageReply()){
        $value = $answer->getValue();
        $text = $answer->getText();
        switch ($value) {
          case 'login':
            $this->login();
            break;
            
        
            
          default;
            break;
        }
      } else {
        $this->askAyudaMenu();
      }
    });
}

    public function run() {
        $this->askCuentaMenu();
    }
}
