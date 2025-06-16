<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'refer_code',
        'dealer',
        'password',
        'role',
        'is_active',
        'refer_by',
        'position',
        'shopping_wallet',
        'points',
        'left_points',
        'right_points',
        'income_wallet',
        'upline_id',
        'left_user_id',
        'right_user_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->refer_code = self::generateReferCode();
        });
    }

    public static function generateReferCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (self::where('refer_code', $code)->exists());

        return $code;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function left()
    {
        return $this->belongsTo(User::class, 'left_user_id');
    }

    public function right()
    {
        return $this->belongsTo(User::class, 'right_user_id');
    }

    public function upline()
    {
        return $this->belongsTo(User::class, 'upline_id');
    }

    public function referer()
    {
        return $this->belongsTo(User::class, 'refer_by');
    }

    // ✅ Recursive left/right points calculation
    public function getTotalLeftPoints(): int
    {
        return $this->sumPointsById($this->left_user_id);
    }

    public function getTotalRightPoints(): int
    {
        return $this->sumPointsById($this->right_user_id);
    }

    protected function sumPointsById($userId): int
    {
        if (!$userId) return 0;

        $user = self::select('id', 'points', 'left_user_id', 'right_user_id')
            ->find($userId);

        if (!$user) return 0;

        return $user->points
            + $this->sumPointsById($user->left_user_id)
            + $this->sumPointsById($user->right_user_id);
    }

    public function loadNetwork(): array
    {
        return $this->buildTree($this);
    }

    protected function buildTree($user): ?array
    {
        if (!$user) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'points' => $user->points,
            'left' => $this->buildTree($user->left),
            'right' => $this->buildTree($user->right),
        ];
    }

    // ✅ Only first 20 downline users (flattened list)
    public function getDownlineUsersLimited(int $limit = 20): array
    {
        $downlines = [];
        $this->collectDownline($this, $downlines);

        return array_slice($downlines, 0, $limit);
    }

    protected function collectDownline($user, array &$result): void
    {
        foreach (['left', 'right'] as $side) {
            $child = $user->$side;
            if ($child) {
                $result[] = [
                    'id' => $child->id,
                    'name' => $child->name,
                    'email' => $child->email,
                    'points' => $child->points,
                    'position' => $child->position,
                ];
                $this->collectDownline($child, $result);
            }
        }
    }
}
