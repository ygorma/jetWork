# jetWork

Biblioteca PHP para gerar componentes HTML usando [Materialize CSS](https://materializeweb.com/). A classe principal está em [`builder/buildMaterial.php`](builder/buildMaterial.php).

## Requisitos

- PHP 8 ou superior;
- Materialize CSS e Material Icons carregados na página;
- jQuery para máscaras, autocomplete e envio de formulários;
- os arquivos JavaScript e CSS deste projeto disponíveis na aplicação.

## Início rápido

```php
require_once __DIR__ . '/builder/buildMaterial.php';

$builder = new buildMaterial();
$builder->inputText('nome', 'Nome', 6, true);
$builder->inputPassword('senha', 'Senha', 6);
$builder->inputTextarea('observacao', 'Observação');

echo $builder->buildForm('POST');
```

Os campos são acumulados internamente até que `buildForm()` seja chamado. Cada instância recebe um `ObjID` próprio, usado para associar os campos ao formulário correto.

## Referência da classe `buildMaterial`

### `__construct()`

Inicializa uma instância do builder e atribui um identificador numérico único em `ObjID`. Esse identificador é usado nos atributos `id` e `form` dos elementos HTML.

### `buildModal(string $modalTitle, string $modalContent, ?string $buttonText = null, ?string $buttonIcon = null, ?array $options)`

Retorna um modal Materialize com botão de abertura, título, conteúdo e ações. `$options` deve ser um array no formato `['Texto do botão' => 'comandoJavaScript()']`.

```php
echo $builder->buildModal('Excluir registro', 'Deseja excluir?', 'Excluir', 'delete', ['Confirmar' => 'excluirRegistro()']);
```

### `buildCard(string $titulo, $imagem, string $conteudo, string ...$submits)`

Cria e imprime um card Materialize. Se `$imagem` não for `null`, ela é carregada de `media/img/$imagem`; `$submits` são inseridos na área de ações. O método usa `echo` internamente e não retorna o HTML.

```php
$builder->buildCard('Produto', 'produto.jpg', '<p>Descrição</p>', '<a href="produto.php">Ver</a>');
```

### `buildChip($content)`

Retorna um chip Materialize (`<div class="chip">`) com o conteúdo informado.

```php
echo $builder->buildChip('Ativo');
```

### `buildFloating(string $icon, string $url, ?array $options = null)`

Retorna um botão flutuante fixo. `$icon` é o nome do Material Icon e `$url` é usado no link `?page=$url`. `$options` está reservado e não é utilizado atualmente.

```php
echo $builder->buildFloating('add', 'novo-produto');
```

### `buildForm(string $method, string $display = 'row')`

Retorna o formulário com todos os elementos adicionados. O formulário usa `multipart/form-data`. `$display` pode ser `row`, `inline`, `inline_block` ou `none`. Depois da geração, o conteúdo acumulado é limpo.

```php
$builder->inputText('email', 'E-mail', 12, true);
echo $builder->buildForm('POST', 'inline');
```

### `formSection($name)`

Adiciona ao formulário um título de seção (`<h5>`) e um espaço abaixo dele.

### `inputText(string $name, string $texto, int $gridSize = 12, $required = false, $mask = null, $value = null, $disabled = false)`

Adiciona um campo de texto. `$gridSize` define a largura Materialize; `$required`, `$value` e `$disabled` controlam obrigatoriedade, valor inicial e disponibilidade. `$mask` aplica uma máscara jQuery Mask; `00/00/0000` também ativa o datepicker.

```php
$builder->inputText('cpf', 'CPF', 6, true, '000.000.000-00');
$builder->inputText('nascimento', 'Nascimento', 6, false, '00/00/0000');
```

### `inputTextarea(string $name, ?string $texto, int $gridSize = 12, $required = false, $value = null, $disabled = false, $richText = false)`

Adiciona uma área de texto com altura mínima de 150px. Com `$richText = true`, aplica a classe `richText` para ativar o editor de texto enriquecido.

```php
$builder->inputTextarea('descricao', 'Descrição', 12, true, null, false, true);
```

### `inputHidden(string $name, ?string $value)`

Adiciona um campo oculto. Quando `$name` é `captcha` e `$value` é `true`, usa `CLIENT_KEY` e inclui o script do Google reCAPTCHA.

```php
$builder->inputHidden('id', '42');
$builder->inputHidden('captcha', true);
```

### `inputSelect(string $name, string $texto, int $gridSize = 12, array $options, $required = false, $selected = 'unset', bool $returnHTML = false)`

Cria um `<select>` Materialize. `$options` usa o formato `['valor' => 'Texto']`; `$selected` define a opção inicial. Com `$returnHTML = true`, retorna o HTML; caso contrário, adiciona ao formulário.

```php
$builder->inputSelect('status', 'Status', 6, ['A' => 'Ativo', 'I' => 'Inativo'], true, 'A');
$html = $builder->inputSelect('tipo', 'Tipo', 6, [1 => 'Interno'], false, 'unset', true);
```

### `inputFile($filename, $required = false, $multiple = false)`

Adiciona um campo de upload. O primeiro campo gera também um token oculto `uploadSession`. Os arquivos recebem nomes como `file_1`, `file_2` etc. `$multiple` permite selecionar vários arquivos.

```php
$builder->inputFile('documento', true);
$builder->inputFile('imagens', false, true);
```

### `inputPassword(string $name, string $texto, int $gridSize = 12, bool $returnHTML = false)`

Cria um campo de senha e o adiciona ao formulário. Com `$returnHTML = true`, retorna o HTML em vez de acumulá-lo.

### `inputCheckbox(string $name, ?string $texto, int $gridSize = 6, bool $isSet = false, bool $returnHTML = false)`

Cria uma caixa de seleção. `$isSet` define se ela inicia marcada; `$returnHTML` define se o HTML será retornado ou adicionado ao formulário.

```php
$builder->inputCheckbox('aceito', 'Aceito os termos', 12);
```

### `inputRadio(string $name, string $texto, int $gridSize = 6, $selected = false, string $value)`

Adiciona um botão de rádio. Ele fica marcado quando `$selected` é igual a `$value`.

```php
$builder->inputRadio('sexo', 'Masculino', 6, 'M', 'M');
$builder->inputRadio('sexo', 'Feminino', 6, 'M', 'F');
```

### `buildFormSubmit(string $type, string $texto, string $action, string $icon = 'send', ?string $target = null)`

Retorna uma ação de formulário. Os tipos disponíveis são `a` (link que submete), `button` (submit com `formaction`), `button_redirect` (redirecionamento via `window.location`) e `inline_float` (botão flutuante compacto com tooltip).

```php
$acao = $builder->buildFormSubmit('button', 'Salvar', 'salvar.php', 'save');
$builder->addToForm($acao);
```

### `inputAutocomplete(string $name, string $texto, int $gridSize = 12, $required = false, array $options, $selected = null)`

Adiciona um autocomplete Materialize. O campo visível mostra o texto e o campo oculto com o nome original recebe o id escolhido. `$options` deve ter o formato `['id' => 'Texto']`; `$selected` define a seleção inicial. Ao escolher uma opção, o JavaScript chama `onAutocompleteSelection(id)`.

```php
$builder->inputAutocomplete('cliente_id', 'Cliente', 12, true, [10 => 'Acme', 20 => 'JetWork'], 10);
```

### `roundedButton($icon, $url, $tooltip, $new_tab = false)`

Retorna um botão circular com ícone, tooltip e link. Com `$new_tab = true`, abre o link em nova aba.

```php
echo $builder->roundedButton('edit', 'editar.php?id=1', 'Editar');
```

### `addToForm($html)`

Adiciona HTML personalizado ao formulário e retorna o conteúdo acumulado.

```php
$builder->addToForm('<p>Texto adicional</p>');
```

### `printMasks()`

Pretende retornar as máscaras registradas pelo builder. Na implementação atual, a propriedade de máscaras não está disponível; portanto, esse método não deve ser usado até ser corrigido.

### `tableSearch(...$columns)`

Adiciona uma caixa de pesquisa à tabela, chamando `tableSearch(ObjID)` no evento `keyup`. O parâmetro `$columns` é aceito, mas ainda não é usado para limitar a busca a colunas específicas.

### `tableHeading(...$columns)`

Inicia uma tabela Materialize com classe `striped`, cria o cabeçalho e abre o `<tbody>`. Cada argumento vira uma coluna `<th>`.

```php
$builder->tableHeading('Nome', 'E-mail', 'Status');
```

### `tableRow(...$columns)`

Adiciona uma linha à tabela atual; cada argumento vira uma célula `<td>`.

```php
$builder->tableRow('Maria', 'maria@example.com', 'Ativo');
```

### `tableFooter()`

Fecha o corpo e a tabela (`</tbody></table>`).

### `buildTable()`

Retorna o HTML acumulado por `tableSearch()`, `tableHeading()`, `tableRow()` e `tableFooter()`.

```php
echo $builder->buildTable();
```

### `addOrReturn($HTML, bool $returnMethod)`

Método auxiliar dos componentes que aceitam `$returnHTML`: retorna `$HTML` quando `$returnMethod` é `true`; caso contrário, adiciona-o ao formulário.

## Exemplo completo

```php
require_once __DIR__ . '/builder/buildMaterial.php';

$builder = new buildMaterial();
$builder->formSection('Cadastro');
$builder->inputText('nome', 'Nome', 6, true);
$builder->inputText('email', 'E-mail', 6, true);
$builder->inputSelect('perfil', 'Perfil', 6, ['admin' => 'Administrador', 'user' => 'Usuário'], true, 'user');
$builder->inputFile('avatar');
$builder->addToForm($builder->buildFormSubmit('button', 'Salvar', 'salvar.php', 'save'));

echo $builder->buildForm('POST');
```

## Dependências e agradecimentos

- [Materialize CSS](https://github.com/materializecss/materialize);
- [RichText](https://github.com/webfashionist/RichText);
- [jQuery Redirect](https://github.com/mgalante/jquery.redirect);
- jQuery Mask.
