<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Member hanya boleh mengupdate (kembalikan) pinjaman miliknya.
     */
    public function update(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }

    /**
     * Admin boleh menghapus pinjaman, member tidak.
     */
    public function delete(User $user, Loan $loan): bool
    {
        return $user->isAdmin();
    }

    /**
     * Admin boleh mengembalikan pinjaman siapa pun.
     */
    public function return(User $user, Loan $loan): bool
    {
        return $user->isAdmin();
    }
}