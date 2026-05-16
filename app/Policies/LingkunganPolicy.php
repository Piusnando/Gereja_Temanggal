<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Lingkungan;
use Illuminate\Auth\Access\HandlesAuthorization;

class LingkunganPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if (in_array($user->role, ['admin', 'pengurus_gereja'])) {
            return true;
        }
    }
    
    // Siapa yang boleh melihat detail lingkungan?
    public function view(User $user, Lingkungan $lingkungan)
    {
        if ($user->role === 'ketua_wilayah') {
            return $user->territory_id === $lingkungan->territory_id;
        }
        if ($user->role === 'ketua_lingkungan') {
            return $user->lingkungan_id === $lingkungan->id;
        }
        return false;
    }
    
    // Siapa yang boleh mengedit lingkungan ini?
    public function update(User $user, Lingkungan $lingkungan) { return $this->view($user, $lingkungan); }

    // Admin/Pengurus tidak bisa dihapus oleh before(), jadi kita definisikan disini.
    public function create(User $user) { return false; }
    public function delete(User $user, Lingkungan $lingkungan) { return false; }
}