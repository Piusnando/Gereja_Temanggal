<?php
namespace App\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    // Admin & Pengurus boleh melakukan apa saja
    public function before(User $user, $ability)
    {
        if (in_array($user->role, ['admin', 'pengurus_gereja'])) {
            return true;
        }
    }

    // Siapa yang boleh melihat daftar data?
    public function viewAny(User $user) { return true; }

    // Siapa yang boleh melihat detail data?
    public function view(User $user, $model)
    {
        if ($user->role === 'ketua_wilayah') {
            return $user->territory_id === $model->territory_id;
        }
        if ($user->role === 'ketua_lingkungan') {
            return $user->lingkungan_id === $model->lingkungan_id;
        }
        return false; // Role lain tidak boleh
    }

    // Siapa yang boleh membuat data? (Semua role yang diberi akses)
    public function create(User $user) { return true; }

    // Siapa yang boleh mengedit data ini?
    public function update(User $user, $model) { return $this->view($user, $model); }

    // Siapa yang boleh menghapus data ini?
    public function delete(User $user, $model) { return $this->view($user, $model); }
}