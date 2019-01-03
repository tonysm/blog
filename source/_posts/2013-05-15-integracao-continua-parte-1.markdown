---
extends: _layouts.post
section: content
title: "Integração Contínua - parte 1"
date: 2013-05-15
cover_image: /assets/images/posts/tempos-modernos.jpg
categories: [introducao, introdução, integração contínua, ci, software, development, team, iniciante]
---
Olá, senhoras e senhores. Hoje vou falar de Integração Contínua! Tenho tentado estudar esse assunto, gostaria de ter um pouco mais de tempo pra isso, mas vamos que vamos!

Bom, segundo [Martin Fowler][1]:

> Integração contínua é uma prática de desevolvimento de *software* em que os membros de um time frequentemente integram seus trabalhos (...)

Em outras palavras, é uma prática para automatizar e otimizar a integração do código de uma equipe.

## Um exemplo vale mais

Acredito que a melhor forma de explicar o que é integração contínua é com um exemplo. Para tal, usarei uma versão simplificada do exemplo do próprio [Martin Fowler][1]. Vamos a ele, então.

Digamos que eu esteja trabalhando em um projeto com uma equipe de desenvolvedores. Para completar minha tarefa, eu baixo a versão mais recente do repositório desse *software*.

Altero alguns códigos de produção, sempre guiados por testes para garantir que tudo está funcionando. Temos aqui um ponto interessante, ao meu ver, não há integração contínua sem testes automatizados.

Ao termino do meu trabalho, eu me certifico de que a aplicação está funcionando localmente, fazendo um *build* automatizado local e executando todos os testes. Só consideramos como __sucesso__ se todos os testes passarem.

Uma vez que os testes passaram, ai sim eu posso pensar em mandar as minhas alterações para o repositório. O lance é que, assim como eu estava desenvolvendo minhas tasks, outros desenvolvedores também estavam. Antes de mandar meu código, eu atualizo o código local com o repositório. Em caso de conflito, é minha responsabilidade resolve-los!

Sem conflitos e com os testes passando, agora eu mando o código para o repositório. Meu trabalho está terminado, correto?! Não! Agora, uma nova build será feita automaticamente, pois há sempre a possibilidade de ter diferenças entre o meu ambiente local e o ambiente de *staging/produção* (esse problema pode ser minizado ou até mesmo removido usando [Vagrant][2], por exemplo). Só quando as minhas alterações são integradas com sucesso ao *software* (aplicação é construída, todos os testes são executados, etc, etc) é que posso dizer que meu trabalho está terminado.

Quando um *build* falha, o mesmo deve ser rapidamente corrigido!

## Conclusão

O resultado de todo esse trabalho, é que a equipe passa menos tempo procurando erros e mais tempo em novos desafios (erro não é desafio, se é que me entendem).

Embora seja um conceito muito foda, as empresas parecem não ligar muito para isso. Alguns até dizem que "o importante é funcionar" ou "não quero código limpo, quero funcionalidade", o que acho ridículo e imaturo, profissionalmente falando.

Na verdade, nem mesmo testes automatizados se encontra fácil por ai. Vejam bem, não falo que a comunidade PHP não usa, e sim empresas de pequeno/médio porte. Muito pelo contrário, vemos que as empresas que realmente se importam com seus códigos estão fazendo um belo trabalho nesse sentido.

Bem, fica aqui meu pensamento.

Até a próxima!

[1]: http://martinfowler.com/articles/continuousIntegration.html
[2]: http://www.vagrantup.com/
