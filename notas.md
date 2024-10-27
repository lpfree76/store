## 1 - **Comandos Phinx**

Para criar uma tabela no banco de dados, primeiramente deve-se criar a classe contendo a configuração necessária à sua construção.

1. Para criar a classe, utilize o comando:

> vendor/bin/phinx create <nome da classe>

2. Para criar uma migração (tabela), digite:

> vendor/bin/phinx migrate

**Observação:** Este comando, além de criar a tabela, pode populá-la se a classe estiver integrada a biblioteca `Faker`.

3. Para apagar todos os dados da tabela, digite:

> vendor/bin/phinx rollback -e development -t 0

**Obervação:** Este comando apaga os registros de todas as tabelas. Para apagar os registros de uma tabela específica, digite: 

> php vendor/bin/phinx rollback -t <número da versão>


## 2 - **Classe Categories:**

```php

<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Faker\Factory as Faker;

class Categories extends AbstractMigration
{
    public function up(): void
    {
        // Criação da tabela 'categories' caso ela não exista
        if (!$this->hasTable('categories')) {
            $this->table('categories')
                ->addColumn('name', 'string', ['limit' => 255])
                ->create();
        }

        // Populando a tabela com dados fictícios usando Faker
        // $faker = Faker::create('pt-BR');
        // for ($i = 0; $i < 10; $i++) { // Alterado para 5 categorias, como solicitado anteriormente
        //     $this->table('categories')->insert([
        //         'name' => ucfirst($faker->unique()->name), // Nome único e com primeira letra maiúscula
        //     ])->saveData();
        // }

        // Definindo um array com nomes específicos para as categorias
        $categoryNames = ['Eletrônicos', 'Livros', 'Roupas', 'Alimentos', 'Móveis'];

        foreach ($categoryNames as $name) {
            $this->table('categories')->insert([
                'name' => $name,
            ])->saveData();
        }
    }

    public function down(): void
    {
        // Deleta os registros de categorias ao desfazer a migração
        $this->execute('DELETE FROM categories');

        // Reinicia o AUTO_INCREMENT para 1. 
        // Dessa forma o id do produto será reiniciado, assumindo o valor 1
        $this->execute('ALTER TABLE categories AUTO_INCREMENT = 1');
    }
}

```

**Explicando a classe Categories:**

Esse código PHP é uma migração usando a biblioteca **Phinx** para gerenciar a criação e manipulação de tabelas em um banco de dados. Vou explicar cada parte do código em detalhes.

### 1. Declarações Iniciais

```php
declare(strict_types=1);
```
- Essa linha ativa o modo de tipos estritos, que faz com que o PHP exija que os tipos de dados especificados sejam respeitados durante a execução do código. Isso ajuda a evitar erros de tipo.

```php
use Phinx\Migration\AbstractMigration;
use Faker\Factory as Faker;
```
- Aqui, duas classes são importadas:
  - `AbstractMigration`: a classe base da qual todas as migrações devem herdar em Phinx.
  - `Faker\Factory`: uma biblioteca utilizada para gerar dados fictícios. No código, ela é renomeada para `Faker` para facilitar a referência.

### 2. Definição da Classe de Migração

```php
class Categories extends AbstractMigration
```
- Uma classe chamada `Categories` é definida, que herda de `AbstractMigration`. Essa classe contém dois métodos principais: `up` e `down`, que definem as operações a serem realizadas ao aplicar ou reverter a migração.

### 3. Método `up`

Este método é chamado quando a migração é aplicada (ou seja, ao criar ou atualizar a tabela).

```php
public function up(): void
{
    // Criação da tabela 'categories' caso ela não exista
    if (!$this->hasTable('categories')) {
        $this->table('categories')
            ->addColumn('name', 'string', ['limit' => 255])
            ->create();
    }
```
- **Verificação e Criação da Tabela**: 
  - O método `hasTable` verifica se a tabela `categories` já existe. Se não existir, a tabela é criada com uma coluna `name`, do tipo string, com um limite de 255 caracteres.

```php
    // Populando a tabela com dados fictícios usando Faker
    // $faker = Faker::create('pt-BR');
    // for ($i = 0; $i < 10; $i++) { // Alterado para 5 categorias, como solicitado anteriormente
    //     $this->table('categories')->insert([
    //         'name' => ucfirst($faker->unique()->name), // Nome único e com primeira letra maiúscula
    //     ])->saveData();
    // }
```
- **(Comentado) População da Tabela com Faker**: 
  - O código comentado sugere que, inicialmente, a tabela deveria ser populada com 10 categorias geradas aleatoriamente usando a biblioteca Faker. Os nomes seriam únicos e a primeira letra de cada nome seria convertida para maiúscula. O código comentado é uma abordagem alternativa à que está ativa no momento.

```php
    // Definindo um array com nomes específicos para as categorias
    $categoryNames = ['Eletrônicos', 'Livros', 'Roupas', 'Alimentos', 'Móveis'];

    foreach ($categoryNames as $name) {
        $this->table('categories')->insert([
            'name' => $name,
        ])->saveData();
    }
}
```
- **População da Tabela com Nomes Específicos**:
  - Um array chamado `$categoryNames` contém 5 categorias específicas. 
  - O loop `foreach` itera sobre este array e insere cada nome na tabela `categories`. A chamada `saveData()` é usada para efetivar as inserções no banco de dados.

### 4. Método `down`

Este método é chamado quando a migração é revertida (ou seja, quando se desfaz a criação ou atualização da tabela).

```php
public function down(): void
{
    // Deleta os registros de categorias ao desfazer a migração
    $this->execute('DELETE FROM categories');
```
- **Deleção dos Registros**:
  - Este comando executa uma instrução SQL que deleta todos os registros da tabela `categories`, limpando os dados.

```php
    // Reinicia o AUTO_INCREMENT para 1. 
    // Dessa forma o id do produto será reiniciado, assumindo o valor 1
    $this->execute('ALTER TABLE categories AUTO_INCREMENT = 1');
}
```
- **Reinício do AUTO_INCREMENT**:
  - Este comando altera a tabela para reiniciar o contador de AUTO_INCREMENT para 1. Isso significa que, ao inserir novos registros, o primeiro `id` gerado começará a partir de 1 novamente.

### Resumo
Essa migração é responsável por criar a tabela `categories` (se ela ainda não existir) e preenchê-la com cinco categorias específicas. Se a migração for revertida, todos os dados da tabela serão deletados e o contador do AUTO_INCREMENT será reiniciado. Essa abordagem é útil para garantir que a estrutura do banco de dados esteja sempre em um estado esperado durante o desenvolvimento.

## 3 - **Classe Products**

```php

<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Faker\Factory as Faker;

class Products extends AbstractMigration
{
    public function up()
    {
        // Verifica se a tabela "products" não existe e a cria
        if (!$this->hasTable('products')) {
            $table = $this->table('products');
            $table->addColumn('name', 'string', ['limit' => 255])
                  ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
                  ->addColumn('description', 'text')
                  ->addColumn('category_id', 'integer')
                  ->addColumn('used', 'boolean')
                  ->create();
        }

        // Insere dados falsos
       $faker = Faker::create('pt_BR');
        for ($i = 0; $i < 100; $i++) {
            $this->table('products')->insert([
                'name' => ucfirst($faker->word), // Palavra em português com inicial maiúscula
                'price' => $faker->randomFloat(2, 1, 100),
                'description' => $faker->sentence(6, true), // Frase com 6 palavras, garantindo que esteja em português
                'category_id' => $faker->numberBetween(1, 5),
                'used' => $faker->boolean,
            ])->saveData();
        }

    }

    public function down()
    {
        // Exclui todos os dados da tabela products ao reverter a migração
        $this->execute('DELETE FROM products');

        // Reinicia o AUTO_INCREMENT para 1. 
        // Dessa forma o id do produto será reiniciado, assumindo o valor 1
        $this->execute('ALTER TABLE products AUTO_INCREMENT = 1');
    }
}


```
**Explicando o código da Classe Products:**

Esse código PHP também é uma migração usando a biblioteca **Phinx** para gerenciar a criação e manipulação da tabela `products` em um banco de dados. A seguir, explico cada parte do código em detalhes.

### 1. Declarações Iniciais

```php
declare(strict_types=1);
```
- Esta linha ativa o modo de tipos estritos, que garante que os tipos de dados especificados no código sejam respeitados. Isso ajuda a prevenir erros de tipo, tornando o código mais robusto.

```php
use Phinx\Migration\AbstractMigration;
use Faker\Factory as Faker;
```
- Aqui, duas classes são importadas:
  - `AbstractMigration`: a classe base que todas as migrações devem herdar no Phinx.
  - `Faker\Factory`: uma biblioteca utilizada para gerar dados fictícios, renomeada para `Faker` para facilitar sua referência.

### 2. Definição da Classe de Migração

```php
class Products extends AbstractMigration
```
- A classe `Products` é definida, herda de `AbstractMigration` e contém dois métodos principais: `up` e `down`, que são responsáveis por aplicar e reverter a migração, respectivamente.

### 3. Método `up`

Este método é chamado quando a migração é aplicada, ou seja, quando se cria ou atualiza a tabela.

```php
public function up()
{
    // Verifica se a tabela "products" não existe e a cria
    if (!$this->hasTable('products')) {
        $table = $this->table('products');
        $table->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
              ->addColumn('description', 'text')
              ->addColumn('category_id', 'integer')
              ->addColumn('used', 'boolean')
              ->create();
    }
```
- **Verificação e Criação da Tabela**: 
  - O método `hasTable` verifica se a tabela `products` já existe. Se não existir, a tabela é criada com as seguintes colunas:
    - `name`: do tipo string, com um limite de 255 caracteres.
    - `price`: do tipo decimal, com precisão de 10 e escala de 2, o que permite armazenar valores monetários (até 99999999,99).
    - `description`: do tipo texto, para armazenar descrições mais longas.
    - `category_id`: do tipo inteiro, que será usado para referenciar a categoria do produto.
    - `used`: do tipo booleano, que indica se o produto é usado ou não.

```php
    // Insere dados falsos
    $faker = Faker::create('pt_BR');
    for ($i = 0; $i < 100; $i++) {
        $this->table('products')->insert([
            'name' => ucfirst($faker->word), // Palavra em português com inicial maiúscula
            'price' => $faker->randomFloat(2, 1, 100),
            'description' => $faker->sentence(6, true), // Frase com 6 palavras, garantindo que esteja em português
            'category_id' => $faker->numberBetween(1, 5),
            'used' => $faker->boolean,
        ])->saveData();
    }
}
```
- **Inserção de Dados Falsos**:
  - O objeto `$faker` é criado usando a configuração `pt_BR`, que gera dados fictícios em português.
  - Um loop `for` itera 100 vezes para inserir 100 registros na tabela `products`:
    - `name`: gera uma palavra em português, com a primeira letra em maiúscula.
    - `price`: gera um preço aleatório entre 1 e 100, com duas casas decimais.
    - `description`: gera uma frase com 6 palavras, garantida como uma frase coerente em português.
    - `category_id`: gera um número aleatório entre 1 e 5, que corresponde a um `id` de categoria.
    - `used`: gera um valor booleano aleatório (true ou false).
  - A chamada `saveData()` efetiva a inserção dos registros no banco de dados.

### 4. Método `down`

Este método é chamado quando a migração é revertida, ou seja, quando se desfaz a criação ou atualização da tabela.

```php
public function down()
{
    // Exclui todos os dados da tabela products ao reverter a migração
    $this->execute('DELETE FROM products');
```
- **Deleção dos Registros**:
  - Este comando executa uma instrução SQL que deleta todos os registros da tabela `products`, limpando os dados.

```php
    // Reinicia o AUTO_INCREMENT para 1. 
    // Dessa forma o id do produto será reiniciado, assumindo o valor 1
    $this->execute('ALTER TABLE products AUTO_INCREMENT = 1');
}
```
- **Reinício do AUTO_INCREMENT**:
  - Este comando altera a tabela para reiniciar o contador de AUTO_INCREMENT para 1. Isso significa que, ao inserir novos registros após a reversão da migração, o primeiro `id` gerado começará a partir de 1 novamente.

### Resumo
Essa migração é responsável por criar a tabela `products` (caso ela não exista) e preenchê-la com 100 produtos fictícios, gerados usando a biblioteca Faker. Se a migração for revertida, todos os dados da tabela serão deletados e o contador do AUTO_INCREMENT será reiniciado. Essa abordagem é útil para garantir que a estrutura do banco de dados esteja sempre em um estado esperado durante o desenvolvimento, além de permitir a inserção de dados de teste de forma rápida e eficiente.

