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
