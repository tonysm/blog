---
extends: _layouts.post
section: content
title: "Controllers e Mecanismos de transporte"
date: 2014-02-21
cover_image: /assets/images/posts/transport-doctor-who.jpg
categories: [PHP, Controller, Architecture, Arquitetura, Camadas, Layers, Transport, transporte]
---

O trabalho de um Controller é pegar informações HTTP e passar para a aplicação (como um mecanismo de transporte), o que faz todo sentido, já que não queremos ter Controllers sabendo demais. Mas, acontece que não é tão simples organizar o código, é uma tarefa bastante complicada, na verdade. Comecei a usar o ___Repository Pattern___, mas acabei acomplando meus Controllers a vários repositórios, o que acaba sendo custoso, visto que para cada request, vários repositórios são carregados..

Mas não acho que seja papel do Controller interagir diretamente com models ou Repositórios, então, isso sempre me incomodou. Até que, assistindo a um vídeo do [Uncle Bob](http://cleancoders.com) em que ele mensiona os Interactors, pensei "É isso! Faz todo sentido!".

Quem nunca se pegou pensando "como faço para utilizar o método desse controller em outro lugar?". A resposta é: "Você não deve utilizar seus controllers em outros lugares!".

Para quem não viu, aqui vai um resumo.

## Interactors

Com os Interactors, o papel do seu controller é basicamente capturar qualquer informação do protocolo utilizado (HTTP) como, por exemplo, qual o usuário autenticado no sistema, qual o id do _resource_ que foi passado e entregar essas informações como argumentos para os _interactors_, assim como tratar as _exceções_ disparadas pelos mesmos e converter essas informações para a resposta do _client_ (HTML, JSON ou, quem sabe, (uhh!) XML)... ou melhor, passar essa responsabilidade de conversão das respostas para outra camada de _parsers_.

O mais legal disso é que a sua aplicação fica desacoplada do mecanismo de transporte. Isso é, para fazer essa mesma funcionalidade via __cli__ (terminal), por exemplo, bastaria chamar o Interactor e supri-lo com os mesmos parâmetros que são passados pelo controller (tratar as exceptions também) e voilà! Temos um _cli command_ que faz o mesmo que o controller, só que usando protocolos diferentes.

## O que o seu Interactor deve saber

Qualquer coisa que não seja relacionada com funções de outras camadas da sua aplicação. Isso é, não devemos persistir dados diretamente do Interactor, por exemplo. Entretanto, podemos utilizar os repositórios da aplicação diretamente nele. Dessa forma, poderiamos ter um _Interactor_ por _Use Case_, como é sugerido pelo próprio Uncle Bob. Ou seja, um Controller teria conhecimento dos Interactors (ou Use Cases) que ele é responsável. Fez todo sentido para mim quando ouvi falar disso. Mas ainda estou aprendendo a colocar em prática esse padrão. Farei um video de teste desse padrão e atualizarei o _post_ colocando o link aqui.

## Updated: Show me the code!

Enquanto não gravo o vídeo, resolvi compartilhar um pouco de código aqui pra exemplificar melhor. Vamos lá!

Dado o seguinte caso de uso: Passar uma task para outro usuário em um sistema de gerenciamento de tarefas. Precisamos atualizar a task e notificar o novo usuário que o mesmo tem uma nova task. Normalmente, teriamos um controller assim:

```php
<?php

use Acme\Repositories\TaskRepository;
use Acme\Repositories\UserRepository;
use Acme\Mailers\UserMailer;

class TasksController extends Controller
{
    /**
     * @var Acme\Repositories\TaskRepository
     */
    protected $tasks;

    /**
     * @var Acme\Repositories\UserRepository
     */
    protected $users;

    /**
     * @var Acme\Mailers\UserMailer
     */
    protected $mailer;

    /**
     * @param TaskRepository $tasks
     * @param UserRepository $users
     * @param UserMailer $mailer
     */
    public function __construct(
        TaskRepository $tasks, 
        UserRepository $users, 
        UserMailer $mailer
    ) {
        $this->tasks = $tasks;
        $this->users = $users;
        $this->mailer = $mailer;
    }

    /**
     * @param string|int $task_id
     * @param string|int $user_id
     * @return mixed
     */
    public function transfer($task_id, $user_id)
    {
        $userTo = $this->users->find($user_id);
        $task = $this->tasks->find($task_id);

        $task->setUser($userTo);

        if (! $this->tasks->save($task)) {
            return Redirect::to('tasks')->withErrors($this->tasks->getErrors());
        }

        $this->mailer->notifyTaskTransference($task, $userTo);

        return Redirect::to('tasks')->with(['message' => Lang::get('tasks.transfer.success']);
    }
}
```

O código até que tá limpo, mas ainda dá pra melhorar.. Nosso Controller, que faz está fora da camada da nossa aplicação (faz parte do front-end, por assim dizer), sabe que temos repositórios, mailers, etc, etc.. Idealmente, nosso Controller deve saber apenas QUEM realiza suas tarefas e os possíveis erros. Uma forma muito mais limpa para tal modelo é utilizando Interactors, como mostrado abaixo:

```php
<?php namespace Acme\Interactors\Tasks;

use Acme\Repositories\TaskRepository;
use Acme\Repositories\UserRepository;
use Acme\Mailers\UserMailer;
use Acme\Interactors\Exceptions\CannotTransferTaskException;

class TransferenceInteractor
{
    /**
     * @var Acme\Repositories\TaskRepository
     */
    protected $tasks;

    /**
     * @var Acme\Repositories\UserRepository
     */
    protected $users;

    /**
     * @var Acme\Mailers\UserMailer
     */
    protected $mailer;

    /**
     * @param TaskRepository $tasks
     * @param UserRepository $users
     * @param UserMailer $mailer
     */
    public function __construct(
        TaskRepository $tasks,
        UserRepository $users,
        UserMailer $mailer
    ) {
        $this->tasks = $tasks;
        $this->users = $users;
        $this->mailer = $mailer;
    }

    /**
     * @param string|int $task_id
     * @param string|int $user_id
     * @return void
     * @throws CannotTransferTaskException
     */
    public function transfer($task_id, $user_id)
    {
        $userTo = $this->users->find($user_id);
        $task = $this->tasks->find($task_id);

        $task->setUser($userTo);

        if (! $this->tasks->save($task)) {
            throw new CannotTransferTaskException($this->tasks->getErros());
        }

        $this->mailer->notifyTaskTransference($task, $userTo);
    }
}
```

Com isso, nosso interactor seria responsável por fazer a transferência da task e disparar exceptions em caso de erros. Nosso Controller ficaria muito mais limpo, assim:

```php
<?php

use Acme\Interactors\Tasks\TransferenceInteractor;
use Acme\Interactors\Exceptions\CannotTransferTaskException;

class TasksController extends Controller
{
    /**
     * @var Acme\Interactors\Tasks\TransferenceInteractor
     */
    protected $tasksDelivery;

    /**
     * @param TransferenceInteractor $tasksDelivery
     */
    public function __construct(TransceferenceInteractor $tasksDelivery)
    {
        $this->tasksDelivery = $tasksDelivery;
    }

    /**
     * @param string|int $task_id
     * @param string|int $user_id
     * @return mixed
     */
    public function transfer($task_id, $user_id)
    {
        try {
            $this->tasksDelivery->transfer($task_id, $user_id);

            return Redirect::to('tasks')->with(['message' => Lang::get('tasks.transfer.success')]);
        } catch(CannotTransferTaskException $e) {
            return Redirect::to('tasks')->withErrors($e->getErrorMessages());
        }
    }
}
```

Pronto! Agora, nosso controller não sabe mais como fazemos as transferências das tasks. Apenas sabem QUEM faz e os possíveis erros retornados. Assim. Esse approach é muito mais elegante e limpo. Assim como muito mais fácil de testar e adicionar features e error handlers. Digamos que você tenha um watcher analisando as tasks em background para balancear as tasks com os desenvolvedores mais "folgados". Seria feito um cli-command para isso, assim:

```php
<?php

use Symfony\Component\Console\Input\InputArgument;
use Acme\Interactors\Tasks\TransferenceInteractor;
use Acme\Interactors\Exceptions\CannotTransferTaskException;

class TaskTransferenceCommand extends Command
{
    /**
     * @var Acme\Interactors\Tasks\TransferenceInteractor
     */
    protected $tasksDelivery;

    /**
     * @var string
     */
    protected $name = "acme:transfer-task";

    /**
     * @var string
     */
    protected $description = "Transfers a task to a given user";

    /**
     * @param TransferenceInteractor $tasksDelivery
     */
    public function __construct(TransceferenceInteractor $tasksDelivery)
    {
        parent::__construct();

        $this->tasksDelivery = $tasksDelivery;
    }

    /**
     * handles the command
     *
     * @return void
     */
    public function fire()
    {
        try {
            $task_id = $this->argument('task_id');
            $user_id = $this->argument('user_id');

            $this->taskDelivery->transfer($task_id, $user_id);

            $this->info(Lang::get('tasks.transfer.success'));
        } catch(CannotTransferTaskException $e) {
            foreach ($e->getErrorMessages() as $message)
            {
                $this->error($message);
            }
        }
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            ['task_id', InputArgument::REQUIRED, 'The ID of the task to be transfered'],
            ['user_id', InputArgument::REQUIRED, 'The id of the user to transfer the task to']
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
```

O exemplo do command não foi dos melhores, mas espero que dê pra entender onde quero chegar com isso.

É isso! O que vocês acham desse padrão? Como vocês organizam suas aplicações? Deixem um comentário ai e até a próxima!
