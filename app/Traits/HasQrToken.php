<?php

namespace App\Traits;

use App\Models\QrToken;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasQrToken
{
    /**
     * All QR tokens associated with this model.
     *
     * @return MorphMany<QrToken, $this>
     */
    public function qrTokens(): MorphMany
    {
        return $this->morphMany(QrToken::class, 'owner');
    }

    /**
     * Get the currently active QR token.
     *
     * @return MorphOne<QrToken, $this>
     */
    public function activeQrToken(): MorphOne
    {
        return $this->morphOne(QrToken::class, 'owner')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('id');
    }

    /**
     * Get the permanent active QR token or create one if it does not exist.
     */
    public function getOrCreateQrToken(?CarbonInterface $expiresAt = null): QrToken
    {
        $active = $this->activeQrToken;
        if ($active) {
            return $active;
        }

        return $this->generateQrToken($expiresAt);
    }

    /**
     * Generate a new signed random QR token, revoking prior active tokens.
     */
    public function generateQrToken(?CarbonInterface $expiresAt = null): QrToken
    {
        return DB::transaction(function () use ($expiresAt) {
            $this->revokeQrToken();

            return $this->qrTokens()->create([
                'token' => Str::random(40),
                'is_active' => true,
                'expires_at' => $expiresAt,
            ]);
        });
    }

    /**
     * Revoke all active QR tokens for this model.
     */
    public function revokeQrToken(): void
    {
        $this->qrTokens()->where('is_active', true)->update([
            'is_active' => false,
        ]);
    }
}
