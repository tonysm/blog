---
layout: post
title: "Command-Oriented Architecture"
date: 2014-09-23 11:43
comments: true
categories: [architecture, php, commands, domain, events]
---

Alguns meses atrás escrevi um post sobre Commands e Domain Events para o PHP-PB e esqueci de postar aqui também.

Bom, aqui vai um link: [Commands e Domain Events](http://php-pb.net/2014/06/23/commands-e-domain-events/)

Resumindo um pouco o que eu falo no post, esse padrão de arquitetura está em alta nos ultimos tempos:

<!-- more -->

Basicamente temos:

<ol>
    <li>Fronteiras usam Commands (DTO) para usar nosso app</li>
    <li>Commands são executados por um e somente 1 Handlers</li>
    <li>Handlers podem usar Services e/ou Repositories para interagir com os Domain Objects</li>
    <li>Domain Objects geram Domain Events (podendo ser 1 ou mais)</li>
    <li>Handlers disparam os Domain Events dos Domain Objects afetados na ação</li>
    <li>Domain Events podem ser ouvidos por 1 ou vários Event Listeners</li>
    <li>Listeners podem executar Commands (volte ao item 2)</li>
    <li>Handlers devolvem o que está em seus contratos para as Fronteiras</li>
    <li>Fim do request</li>
</ol>

## Otimização utilizando Queues

Claro, podemos otimizar esse request disparando os Domain Events em Background utilizando Queues, já que os events ocorrem depois das ações
na nossa aplicação, não faz sentido esperar um envio de e-mail, por exemplo (que deveria estar num listener) para responder a request.

## Conclusão

Bom, pra saber mais sobre o assunto, basta dar uma lida no meu post lá. Dou uma explicação mais detalhada sobre a arquitetura e
ainda tem várias referências legais. :)

Até a próxima!
