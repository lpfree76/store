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
