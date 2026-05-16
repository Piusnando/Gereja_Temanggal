<?php
namespace App\Providers;
use App\Models\Announcement;
use App\Models\Activity;
use App\Models\Lingkungan;
use App\Policies\AnnouncementPolicy;
use App\Policies\LingkunganPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Announcement::class => AnnouncementPolicy::class, // <-- Tambahkan ini
        Activity::class => 'App\Policies\ActivityPolicy', // <-- Tambahkan ini
        Lingkungan::class => LingkunganPolicy::class,     // <-- Tambahkan ini
    ];

    public function boot(): void { $this->registerPolicies(); }
}