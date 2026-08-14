<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'user_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function mergeGuest(?string $token, User $user): void
    {
        if (! $token) {
            return;
        }

        $guestCart = Cart::query()
            ->where('token', $token)
            ->whereNull('user_id')
            ->first();

        if (! $guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['token' => (string) Str::uuid()]
        );

        if ($guestCart->id === $userCart->id) {
            return;
        }

        $guestCart->items->each(function (CartItem $item) use ($userCart) {
            $existing = $userCart->items()
                ->where('product_id', $item->product_id)
                ->first();

            if ($existing) {
                $existing->update([
                    'quantity' => $existing->quantity + $item->quantity,
                ]);
                $item->delete();
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        });

        $guestCart->delete();
    }
}
