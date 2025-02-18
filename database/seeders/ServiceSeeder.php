<?php

namespace Database\Seeders;

use App\Enums\ServiceStatus;
use App\Enums\TypeOfServices;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::insert([
            [
                'name' => 'dairy products',
                'slug' => 'dairy_products',
                'status' => ServiceStatus::Active,
                'code' => TypeOfServices::DAIRY_PRODUCTS,
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptat accusantium doloremqlaudantium. Sed ut perspiciatis',
            ],
            [
                'name' => 'store services',
                'slug' => 'store_services',
                'status' => ServiceStatus::Active,
                'code' => TypeOfServices::STORE_SERVICES,
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptat accusantium doloremqlaudantium. Sed ut perspiciatis',
            ],
            [
                'name' => 'delivery services',
                'slug' => 'delivery_services',
                'status' => ServiceStatus::Active,
                'code' => TypeOfServices::DELIVERY_SERVICES,
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptat accusantium doloremqlaudantium. Sed ut perspiciatis',
            ],
            [
                'name' => 'agricultural services',
                'slug' => 'agricultural_services',
                'status' => ServiceStatus::Active,
                'code' => TypeOfServices::AGRICULTURAL_SERVICES,
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptat accusantium doloremqlaudantium. Sed ut perspiciatis',
            ],
            [
                'name' => 'organic products',
                'slug' => 'organic_products',
                'status' => ServiceStatus::Active,
                'code' => TypeOfServices::ORGANIC_PRODUCTS,
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptat accusantium doloremqlaudantium. Sed ut perspiciatis',
            ],
            [
                'name' => 'fresh vegetables',
                'slug' => 'fresh_vegetables',
                'status' => ServiceStatus::Active,
                'code' => TypeOfServices::FRESH_VEGETABLES,
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptat accusantium doloremqlaudantium. Sed ut perspiciatis',
            ],
        ]);
    }
}
