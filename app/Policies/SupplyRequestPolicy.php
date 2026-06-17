<?php

namespace App\Policies;

use App\Models\SupplyRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class SupplyRequestPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupplyRequest $supplyRequest): bool
    {
        if ($supplyRequest->employee_id !== $user-> id){
            return false;
            }
            return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupplyRequest $supplyRequest): bool
    {
        if ($this->view($user, $supplyRequest) && $supplyRequest->isDraft()) {
            return true ;
        }
        return Response::deny('No tienes permiso para ver esta solicitud.');

    }

}
