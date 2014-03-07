---
layout: post
title: "Desenvolvendo uma API - Parte 2"
date: 2014-03-07 16:58
comments: true
categories: [PHP, API, development, REST]
---

{% img center /images/posts/api-cloud-tumb.png %}

<!-- more -->

Atualmente o modelo de API's mais utilizado é o REST. Não conhece? Explicarei um pouco aqui do funcionamento desse modelo. A teoria é bem simples, na verdade, mas muitas vezes subestimada.

Para começo de conversa, vou linkar aqui um video que abriu a minha cabeça para o que é REST de verdade. O video se chama "Teach a dog to REST" e é incrível! Esse video caiu como uma luva para mim, pois eu tinha acabado de fazer uma API num antigo trabalho e não tinha o menor conhecimento sobre REST, acabei cometendo vários erros como as URLs mostradas no vídeo. Sem mais delongas, vamos ao video.

<center>{% vimeo 17785736 %}</center>

Construir uma API é um processo que parece simples no começo, mas, acreditem, é complexo pra caramba! REST é o conceito básico das API hoje em dia, existem outros modelos como o SOAP, mas não vou entrar em detalhes, até porque nunca os usei na prática.

Uma boa API é um problema de design, como é falado no vídeo. A API que citei acredito que ainda está em uso hoje em dia e alguns erros foram cometidos no processo de desenvolvimento. Não por ser um time ruim, mas porque eramos todos novos no ramo e não conheciamos as melhores práticas e todo o universo por trás das APIs.

Fizemos URL's feias, como essas:

<pre>
/getAllUsersWithTasks
/getAllFinishedTasks
/getAllPendingTasksWithUser/{user\_id}
</pre>

Qual o recurso negóciado nessas URL's? Não dá pra saber só de olhar a URL, temos que tentar ler a mesma e compreender o que pode vir dela. Ah, e basicamente só utilizavamos dois métodos HTTP para os requests GET e POST.

## O que é necessário

Vou listar aqui o que eu acredito ser necessário para uma boa API REST.

* Utilizar muito bem os vérbos HTTP
* Utilizar apenas recursos nas URLs
* Toda complexidade deve ser tratada fora da URL (depois do ?)
* Linkar muito bem os recursos (Relacionamentos)
* Pense sempre RESTful

## Utilizando 110% do HTTP

Todos os _clients_ que irão utilizar nossas API's REST a farão (até onde sei) via HTTP. HTTP é um protocolo de comunicação que é a base da nossa WEB. Para melhor entendermos REST, precisamos entender o HTTP. Existem cerca de 9 métodos HTTP, conforme a [Wikipédia](http://pt.wikipedia.org/wiki/Hypertext_Transfer_Protocol#M.C3.A9todos), mas falaremos basicamente de 6, são eles: GET, POST, PATCH, PUT, DELETE e OPTIONS.

Abaixo, vamos entender melhor o que são cada um desses métodos:

* GET - solicitar recursos;
* POST - criar recursos;
* PUT - atualizar um recurso por completo;
* PATCH - atualizar parte de um recurso;
* DELETE - excluir um recurso;
* OPTIONS - utilizado por apps front-end para saber quais métodos estão disponíveis na nossa API (ver [CORs](http://pt.wikipedia.org/wiki/Cross-origin_resource_sharing));

Falaremos mais sobre o CORs depois.

## Exemplos

Um exemplo de request para a nossa API usando GET:

<pre>
GET /dogs HTTP1.1
Host: www.example.com
</pre>

Esse exemplo acima poderia ser utilizado caso o usuário precisasse listar todos os cachorros, por exemplo. Não estamos considerando paginação ainda.

Para criar um novo cachorro na API, utilizaria o método POST:

<pre>
POST /dogs HTTP 1.1
Host: www.example.com
Payload: {"name": "Luke", "race": "unknown"}
</pre>

Você pode ver que a URL é a mesma, porém o método HTTP agora é POST e enviamos para o servidor os dados do novo cachorro via payload.

Agora, digamos que queremos atualizar o nome do cachorro acima e que o mesmo é representado pelo ID 1, temos então um PATCH:

<pre>
PATCH /dogs/1 HTTP 1.1
Host: www.example.com
Payload: {"name": "Luke teste"}
</pre>

Pronto. Com isso, atualizamos o nome do nosso cachorro. Um detalhe aqui é que utilizamos o método PATCH e não PUT. Por que? Porque o PUT é utilizado quando queremos atualizar todos os atributos de um recurso. É um _replace_, praticamente.

Para deletar o cachorro cadastrado, utilizamos o método DELETE:

<pre>
DELETE /dogs/1 HTTP 1.1
Host: www.example.com
</pre>

Pronto. Nosso cachorro foi excluído.

Vejam que não entramos em detalhes sobre o payload ou sobre as respostas, isso será tratado mais para frente, em outro post.

## Complexidades

Mas, claro, nem tudo são flores. Digamos que eu queira listar apenas os cachorros sem raça, como eu faria? Seguindo o padrão (errado) apresentado no começo do artigo, seria algo mais ou menos assim:

<pre>
GET /dogsWithUnknownRace HTTP 1.1
Host: www.example.com
</pre>

Certo. Já vimos que isso é errado, então, não façam assim. Mas de que outra forma podemos passar complexidades para nossas URL's? Resposta: Não passe. Mantenha a complexidade fora da URL. Como? Assim:

<pre>
GET /dogs?race=unknown HTTP 1.1
Host: www.example.com
</pre>

Dessa forma, sabemos exatamente que estamos listando cachorros apenas com raça desconhecida. Assim, mantemos a complexidade fora da URL, e essa fica apenas com o nosso recurso principal. Vamos para mais um exemplo: Digamos que você agora quer listar todos os cachorros de raça desconhecida da cor preta.

<pre>
GET /dogs?race=unknown&color=black HTTP 1.1
Host: www.example.com
</pre>

Bom, espero que tenham entendido onde quero chegar.

## Relacionamentos

Digamos que os usuários da nossa API são os donos dos cachorros. Para saber qual o dono do cachorro de ID 1, por exemplo, temos o seguinte request:

<pre>
GET /dogs/1/owners HTTP 1.1
Host: www.example.com
</pre>

Agora, digamos que o dono do cachorro de ID 1, tenha outros 2 cachorros e seu ID na API é 42, para saber quais os cachorros que esse usuário tem, podemos fazer assim:

<pre>
GET /owners/42/dogs HTTP 1.1
Host: www.example.com
</pre>

Como exemplificado acima, podemos ter mais de uma forma para acessar um recurso. Fica a cargo do time escolher quais os recursos principais e mais coesos e onde e quando utilizar os recursos de relacionamento.

Um ponto importante sobre relacionamentos é que o ultimo recurso da URL deve ser o foco da requisição. Ou seja, na url _/dogs/42/owners_ estamos falando dos _owners_ e não dos cachorros. O cachorro só está ai por conta do relacionamento.

## Concluindo...

Bom, é isso. Isso é o básico do REST. Não entrei em detalhes sobre HEADERS e outros detalhes mais que pretendo falar mais pra frente. A ideia principal aqui é ser coeso. Não colocar complexidades nas URLs e essas serem apenas recursos, não verbos. Utilizar ao máximo o que o HTTP tem para nos oferecer e documentar tudo que pudermos.

Uma boa documentação é uma excelente aliada. Outro ponto importante é sempre usar o plural nos recursos, faz mais sentido (basta ler para perceber) do que usar singulares. Não precisa usar IDs não URLs também. Podemos utilizar qualquer atributo único no lugar do ID, embora eu prefira os IDs.

É isso, pessoal. Espero ter deixado claro o funcionamento básico do REST. Qualquer dúvida, podem falar.
