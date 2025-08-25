<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        $defaultCondition = '良好';
        $defaultStatus    = 'for_sale';

        return [
            'name'        => $this->faker->words(2, true),
            'price'       => $this->faker->numberBetween(300, 5000),
            'description' => '説明テキスト',
            'image'       => '/images/dummy.png',
            'condition'   => $defaultCondition,
            'status'      => $defaultStatus,
            'brand'       => 'ノーブランド',
            'seller_id'   => User::factory(),
            'buyer_id'    => null,
        ];
    }

    /** 売却済み */
    public function sold(): self
    {
        return $this->state(function () {
            return [
                'status'   => 'sold',
                'buyer_id' => User::factory(),
            ];
        });
    }

    /** 出品者を指定したいとき */
    public function seller(User $user): self
    {
        return $this->state(fn() => ['seller_id' => $user->id]);
    }
}
