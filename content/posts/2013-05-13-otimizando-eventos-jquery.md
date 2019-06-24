---
title: "Otimizando eventos - jQuery"
description: "Otimizando eventos - jQuery"
date: 2013-05-13
published: true
cover_image: ./images/jquery-events-dom.jpg
tags: [iniciantes, jQuery, DOM, eventos, events]
---
Bom, esse post vai ser dedicado a eventos em jQuery. Esses dias surgiram algumas dúvidas sobre a manipulação de eventos no jQuery, por isso, resolvi falar um pouco sobre isso.

Bom, uma coisa simples de fazer em JS é adicionar *event listeners* no DOM. Contudo, por ser algo trivial de ser feito, muita gente acaba fazendo de qualquer jeito. É o velho princípio do "funciona?! Então, não mexe".

Vejamos o exemplo abaixo:

```html
<body>
	<ul>
		<li>Lorem 1</li>
		<li>Lorem 2</li>
		<li>Lorem 3</li>
	</ul>

	<script src="//code.jquery.com/jquery.min.js"></script>
	<script>
	;(function($, document, undefined) {
		var lista = $('ul li');

		lista.on('click', function() {
			console.log('clickou na LI : ' + $(this).html());
		});
	})(jQuery, document);
	</script>
</body>
```

Esse é um exemplo bem básico, ele adiciona um *event listener* no evento *click* nas LI's. Até ai, blz. Mas esse código pode ser melhorado. Para isso, vamos seguir algumas dicas do site [desenvolvimentoparaweb](http://desenvolvimentoparaweb.com/jquery/otimizar-codigos-jquery-aumentar-performance-front-end/).

Primeiro, podemos otimizar os seletores, assim:

```js
var lista = $('ul li');
```

Ficando assim:

```js
var lista = $("ul").find('li');
```

Legal! Primeiro passo rumo à otimização foi dado!

Agora, digamos que o seja necessário adicionar LI's dinamicamente na lista. Para fazer isso, vamos alterar o código da página, ficando assim:

```html
<body>
	<ul>
		<li>Lorem 1</li>
		<li>Lorem 2</li>
		<li>Lorem 3</li>
	</ul>
	<button id="add-li">Add outra LI</button>

	<script src="//code.jquery.com/jquery.min.js"></script>
	<script>
	;(function($, document, undefined) {
		var lista = $("ul"),
			listaItens = lista.find('li'),
			btnAddLI = $('#add-li');

		btnAddLI.on('click', function() {
			lista.append('<li>Outra LI</li>');                
		});                     

		listaItens.on('click', function() {
			console.log('clickou na LI : ' + $(this).html());
		});
	})(jQuery, document);
	</script>
</body>
```

Ótimo! Agora está concluído, correto? Não! Ao testar, percebemos que o *event listener* que atrelamos às LI's não se aplica as novas LI's. WTF? Em versões anteriores do jQuery alguns simplesmente utilizariam o método *live()*, o que não é tão legal assim, mas resolveria o nosso problema, pois ele adicionaria o *event listener* nas LI's existentes e em LI's que fossem adicionadas ao DOM futuramente.

Essa não é a melhor solução, visto que o *live()* já está depreciado. Outro ponto importante é que quando adicionamos o evento nas LI's, acabamos criando um evento para cada LI, o que não é o "ótimo" da questão.

E se nós, ao invés de atrelarmos o evento nas LI's, fizéssemos isso na UL? "Quando alguma LI dessa UL for clicada, execute isso". Isso também é um dos pontos lá do [desenvolvimentoparaweb](http://desenvolvimentoparaweb.com/jquery/otimizar-codigos-jquery-aumentar-performance-front-end/).

Com o código abaixo, matamos dois coelhos com uma só cajadada:

```html
<body>
	<ul>
		<li>Lorem 1</li>
		<li>Lorem 2</li>
		<li>Lorem 3</li>
	</ul>
	<button id="add-li">Add outra LI</button>

	<script src="//code.jquery.com/jquery.min.js"></script>
	<script>
	;(function($, document, undefined) {
		var lista = $("ul"),
			btnAddLI = $('#add-li');

		btnAddLI.on('click', function() {
			lista.append('<li>Outra LI</li>');                
		});               

		lista.on('click', 'li', function() {
			console.log('clickou na LI : ' + $(this).html());
		});
	})(jQuery, document);
	</script>
</body>
```

## Conclusão

Embora seja um trabalho relativamente simples, há alguns pontos que precisam ser levados em conta ao manipular o DOM e adicionar eventos, etc. Não podemos simplesmente deixar "funcionando". Temos sempre que pensar no "ótimo"! É isso que faz a diferença.  Seja ela mínima, como no exemplo acima, ou gigantesca em aplicações maiores.

Até a próxima!
