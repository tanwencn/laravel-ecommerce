<?php

use Illuminate\Database\Seeder;
use \Tanwencn\Ecommerce\Models;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AbilitiesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->addCurd(Models\Product::class);

        $this->addCurd(Models\ProductCategory::class);

        $this->addCurd(Models\ProductTag::class);

        $this->addCurd(Models\ProductAttribute::class);

    }

    protected function addCurd($model_name, $title=null)
    {
        $name = $title?:snake_case(class_basename($model_name));

        $this->addAbility('index', [$name], $model_name);

        $this->addAbility('add', "add_{$name}", $model_name);

        $this->addAbility('edit', "edit_{$name}", $model_name);

        $this->addAbility('delete', "delete_{$name}", $model_name);
    }

    protected function addAbility($name, $title = null, $type = null)
    {
        if(!$title)
            $title = $name;

        Bouncer::ability()->create([
            'name' => $name,
            'title' => is_array($title) ? trans_choice("ecommerce.{$title[0]}", 0) : trans("admin.{$title}"),
            'entity_type' => $type
        ]);

    }
}
