<?php

namespace Database\Seeders;

use App\Enums\PaymentGatewayMethodStatus;
use App\Enums\PaymentMethods;
use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'name' => 'cash on delivery',
                'slug' => PaymentMethods::CashOnDelivery,
                'icon' => 'no',
                'description' => 'Cash on Delivery (COD) is a payment method that allows customers to pay for their purchases in cash at the time of delivery. This option is particularly popular in regions where online payment methods are less trusted or where customers prefer to inspect the product before making payment. COD provides a sense of security for buyers, as they only pay once the item is in their hands. However, it requires effective logistics management to ensure timely delivery and accurate cash handling, making it essential for businesses to plan their operations accordingly.',
                'is_active' => true,
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'stripe',
                'slug' => PaymentMethods::Stripe,
                'icon' => 'no',
                'description' => 'Stripe is a powerful and flexible payment processing platform that enables businesses to accept online payments seamlessly. With support for a wide range of payment methods, including credit and debit cards, as well as popular digital wallets, Stripe offers an intuitive API and extensive documentation to help developers integrate payments effortlessly. Stripe also provides advanced features like recurring billing, subscription management, and fraud prevention tools, making it an ideal choice for businesses looking to scale their operations and enhance their customer experience.',
                'is_active' => true,
                'options' => json_encode([
                    'publishable_key' => 'pk_test_51MuDCaH5rg3QbEsks6QBQcI2hI2WTdl1lj5bpZtw5Fyr36YvhOzrgRuepEMJp3EjFi9IbKrMCau1n94T2yEH43FZ00dPBL9XwZ',
                    'secret_key' => 'sk_test_51MuDCaH5rg3QbEskv0jbllc1QtIKIlGVj94LPCEY1yoDw7J4P2kjSsN2RwVNnNwQ9lknhYIN7AYvlBL9kwgCSgFq00rWHcDu0U',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'paypal',
                'slug' => PaymentMethods::Paypal,
                'icon' => 'no',
                'description' => 'PayPal is one of the most widely recognized and trusted online payment solutions, allowing users to send and receive money securely. With PayPal, customers can make payments using their PayPal balance, linked bank accounts, or credit cards without sharing their financial information with merchants. Its user-friendly interface and robust buyer protection policies make it a popular choice for e-commerce businesses and consumers alike. Additionally, PayPal offers various integration options, including express checkout, enabling a smooth payment experience that can boost conversion rates.',
                'is_active' => true,
                'options' => json_encode([
                    'client_id'         => 'AU44mQ6hczq5x9pF8fgUPmf8EljQTLB-2TnMBeSc_E0US2sgxBTNq3zX0F77BjBsTrwxahIl6mzPufpI',
                    'client_secret'     => 'EBrm8WmKLYJpQ5UjwEV5iTlQfqlFQrfJZDZ2PfsdLB1ZRdGB5QyP4yAHt8mimXcSzZXDmrVASbnaixNy',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [
            //     'name' => 'my fatoorah',
            //     'slug' => PaymentMethods::MyFatoorah,
            //     'description' => '',
            //     'icon' => '',
            // ],
            // [
            //     'name' => 'moyasar',
            //     'slug' => PaymentMethods::Moyasar,
            //     'description' => '',
            //     'icon' => '',
            // ],
            // [
            //     'name' => 'cash on delivery',
            //     'slug' => PaymentMethods::CashOnDelivery,
            //     'description' => '',
            //     'icon' => '',
            // ],
            // [
            //     'name' => PaymentMethods::Paypal,
            //     'slug' => 'paypal',
            //     'description' => '',
            //     'icon' => '',
            //     'status' => PaymentGatewayMethodStatus::Active,
            //     'options' => [],
            // ],
            // [
            //     'name' => PaymentMethods::Moyasar,
            //     'slug' => 'moyasar',
            //     'description' => '',
            //     'icon' => '',
            //     'status' => PaymentGatewayMethodStatus::Active,
            //     'options' => [],
            // ],
            // [
            //     'name' => PaymentMethods::MyFatoorah,
            //     'slug' => 'my-fatoorah',
            //     'description' => '',
            //     'icon' => '',
            //     'status' => PaymentGatewayMethodStatus::Active,
            //     'options' => [],
            // ],

        ]);
    }
}
