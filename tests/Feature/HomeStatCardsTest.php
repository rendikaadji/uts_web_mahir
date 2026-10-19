<?php

namespace Tests\Feature;

use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeStatCardsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin_madrasah']);
    }

    /**
     * @return array<string, string|null>
     */
    private function profilePayload(?string $graduation, ?string $achievements): array
    {
        return [
            'school_name' => 'MTsN 32 Jakarta',
            'headmaster_name' => 'Kepala Madrasah',
            'headmaster_welcome' => 'Selamat datang.',
            'graduation_rate' => $graduation,
            'national_achievements' => $achievements,
        ];
    }

    public function test_cards_are_hidden_while_values_are_empty(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Lanjut SMA/MA');
        $response->assertDontSee('Prestasi Tingkat Nasional');
    }

    public function test_admin_values_appear_on_home_page(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.school.profile.update'), $this->profilePayload('98%', '12+'))
            ->assertSessionHasNoErrors();

        $this->assertSame('98%', SchoolProfile::first()->graduation_rate);

        $response = $this->get(route('home'));
        $response->assertSee('data-counter="98%"', false);
        $response->assertSee('data-counter="12+"', false);
        $response->assertSee('Lanjut SMA/MA');
        $response->assertSee('Prestasi Tingkat Nasional');
    }

    public function test_clearing_a_value_hides_only_that_card(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.school.profile.update'), $this->profilePayload('98%', '12+'));
        $this->actingAs($this->admin())
            ->put(route('admin.school.profile.update'), $this->profilePayload(null, '12+'));

        $response = $this->get(route('home'));
        $response->assertDontSee('Lanjut SMA/MA');
        $response->assertSee('Prestasi Tingkat Nasional');
    }

    public function test_non_numeric_values_are_rejected(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.school.profile.update'), $this->profilePayload('hampir semua', '<b>9</b>'))
            ->assertSessionHasErrors(['graduation_rate', 'national_achievements']);
    }
}
