---
layout: post
title: "Concatenando campos de Models Relacionados - CakePHP"
date: 2013-05-16 23:40
comments: true
categories: [association, cakephp, models, belongsTo, virtualFields]
---
{% img center /images/posts/cakephp-banner.jpg CakePHP %}
<!-- more -->

Olá! Hoje o post vai ser mais rápido. Vou mostrar uma solução para concatenar campos de Modelos associados em um [virtualField][1] no CakePHP. Essa dúvida surgiu na lista oficial do CakeTuga e eu resolvi tentar fazer. Após algumas buscas, encontrei uma [*thread*][2] onde o cara conseguiu fazer isso. Legal, vamos a minha implementação.

## Show me the code!

Primeiro, vamos definir nossa base de dados com duas tableas: *users* e *companies*, com a seguinte estrutura:

{% codeblock lang:sql %}
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `companies` (`id`, `name`) VALUES
(1, 'company 1'),
(2, 'company 2');

INSERT INTO `users` (`id`, `name`, `company_id`) VALUES
(1, 'user 1', 2),
(2, 'user 2', 1);
{% endcodeblock %}

Agora, vamos definir os nosso modelos, começando pelo Model Company:

{% codeblock lang:php %}
<?php
App::uses('AppModel', 'Model');

class Company extends AppModel
{

	public $displayField = 'name';

	public $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'company_id',
			'dependent' => false
		)
	);

}
{% endcodeblock %}

Vamos ser mais diretos, sem códigos desnecessários para o exemplo. O importante aqui é definir os relacionamentos. Agora, vamos para o model User:

{% codeblock lang:php %}
<?php
App::uses('AppModel', 'Model');

class User extends AppModel 
{
	public $displayField = 'name';
	
	public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id'
		)
	);
}

{% endcodeblock %}

Perfeito! Temos os models e seus relacionamentos bem definidos! Agora, digamos que você queria apresentar os nomes dos usuários da aplicação concatenados com o nome da empresa ao qual ele pertence em um combobox. Dado o problema, vamos a primeira solução: adicionar o virtualField no model User...

{% codeblock lang:php %}
<?php
public $virtualFields = array(
	"user_comp" => "SELECT 
						CONCAT(U.name, ' - ', C.name) 
					FROM 
						users U
					LEFT JOIN 
						companies 
						ON (U.company_id = C.id)
					WHERE 
					U.id = User.id"
);
{% endcodeblock %}

Pronto! Agora, é só fazer um *find* na action que o combobox será apresentado e tá finalizado, certo? Errado. Adicionamos uma complexidade a mais no model usuários, essa complexidade será adicionada em todos os finds que o model User aparecer. Não é isso que queremos, certo? Queremos apenas apresentar o nome do usuário concatenado ao nome da empresa em um combobox em uma action específica. Achei melhor criar um método específico que adicione o virtualField em tempo de execução no model User e me traga o que eu quero, uma lista de usuários, vamos ao método:

{% codeblock lang:php %}
<?php
public function findListUsersConcatWithCompanyName()
{
	$this->virtualFields['user_comp'] = "SELECT 
										CONCAT(U.name, ' - ', C.name) 
									FROM 
										{$this->useTable} U
									LEFT JOIN 
										{$this->Company->useTable} C
										ON (U.company_id = C.id)
									WHERE 
										U.id = User.id";

	return $this->find('list', array('fields' => array('User.id', 'User.user_comp')));
}
{% endcodeblock %}

Pronto! Agora é removemos o virtualField anterior e apenas usamos esse método na action específica, ou quando quisermos.

No fim, o model User se parece com isso:

{% codeblock lang:php %}
<?php
App::uses('AppModel', 'Model');

class User extends AppModel 
{
	public $displayField = 'name';

	public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    /**
     * returns an array list with the id of the user as the index and
     * the name of the user concatenated with the company's name that 
     * the user belongs to
     * 
     * @return array
     */
	public function findListUsersConcatWithCompanyName()
	{
		$this->virtualFields['user_comp'] = "SELECT 
											CONCAT(U.name, ' - ', C.name) 
										FROM 
											{$this->useTable} U
										LEFT JOIN 
											{$this->Company->useTable} C
											ON (U.company_id = C.id)
										WHERE 
											U.id = User.id";

		return $this->find('list', array(
			'fields' => array(
				'User.id', 'User.user_comp'
			)
		));
	}
}

{% endcodeblock %}

No código final, ainda podemos mudar a vontade o nome das tabelas dos models que o método ainda funciona, pois ele está usando o atributo useTable dos models em questão.

O resultado do método apresentado acima é esse:

{% codeblock lang:html %}
Array
(
    [1] => user 1 - company 2
    [2] => user 2 - company 1
)
{% endcodeblock %}

## Conclusão

Bom pessoal, é isso. A mensagem aqui é que sempre devemos pensar no efeito que as nossas pequenas alterações fazem na aplicação como um todo e tentar otimizar isso ao máximo.

Novamente, se fiz alguma cagada, façam o favor de comentar!

Até a próxima!

[1]: http://book.cakephp.org/2.0/en/models/virtual-fields.html
[2]: http://ask.cakephp.org/questions/view/using_associated_tables_with_virtualfield