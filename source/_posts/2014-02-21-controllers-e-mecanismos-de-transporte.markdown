---
layout: post
title: "Controllers e Mecanismos de transporte"
date: 2014-02-21 11:45
comments: true
categories: [PHP, Controller, Architecture, Arquitetura, Camadas, Layers, Transport, transporte]
---

O trabalho de um Controller é pegar informações HTTP e passar para a aplicação (como um mecanismo de transporte), o que faz todo sentido, já que não queremos ter Controllers sabendo demais. Mas, acontece que não é tão simples organizar o código, é uma tarefa bastante complicada, na verdade. Comecei a usar o ___Repository Pattern___, mas acabei acomplando meus Controllers a vários repositórios, o que acaba sendo custoso, visto que para cada request, vários repositórios são carregados..

Mas não acho que seja papel do Controller interagir diretamente com models ou Repositórios, então, isso sempre me incomodou. Até que, assistindo a um vídeo do [Uncle Bob](http://cleancoders.com) em que ele mensiona os Interactors, pensei "É isso! Faz todo sentido!".

Quem nunca se pegou pensando "como faço para utilizar o método desse controller em outro lugar?". A resposta é: "Você não deve utilizar seus controllers em outros lugares!".

Para quem não viu, aqui vai um resumo.

<!-- more -->

## Interactors

Com os Interactors, o papel do seu controller é basicamente capturar qualquer informação do protocolo utilizado (HTTP) como, por exemplo, qual o usuário autenticado no sistema, qual o id do _resource_ que foi passado e entregar essas informações como argumentos para os _interactors_, assim como tratar as _exceções_ disparadas pelos mesmos e converter essas informações para a resposta do _client_ (HTML, JSON ou, quem sabe, (uhh!) XML)... ou melhor, passar essa responsabilidade de conversão das respostas para outra camada de _parsers_.

O mais legal disso é que a sua aplicação fica desacoplada do mecanismo de transporte. Isso é, para fazer essa mesma funcionalidade via __cli__ (terminal), por exemplo, bastaria chamar o Interactor e supri-lo com os mesmos parâmetros que são passados pelo controller (tratar as exceptions também) e voilà! Temos um _cli command_ que faz o mesmo que o controller, só que usando protocolos diferentes.

## O que o seu Interactor deve saber

Qualquer coisa que não seja relacionada com funções de outras camadas da sua aplicação. Isso é, não devemos persistir dados diretamente do Interactor, por exemplo. Entretanto, podemos utilizar os repositórios da aplicação diretamente nele. Dessa forma, poderiamos ter um _Interactor_ por _Use Case_, como é sugerido pelo próprio Uncle Bob. Ou seja, um Controller teria conhecimento dos Interactors (ou Use Cases) que ele é responsável. Fez todo sentido para mim quando ouvi falar disso. Mas ainda estou aprendendo a colocar em prática esse padrão. Farei um video de teste desse padrão e atualizarei o _post_ colocando o link aqui.

É isso! O que vocês acham desse padrão? Como vocês organizam suas aplicações? Deixem um comentário ai e até a próxima!
