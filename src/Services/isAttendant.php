<?php

namespace App\Services;

class isAttendant
{
    public function checkIsAttendant(array $attendantList, $user): bool
    {
        foreach ($attendantList as $attendant) {

                // si l'id de l'utilisateur courant est présent dans la liste des participants
            if ($attendant['id'] == $user->getId()) {

                    // alors oui c'est un participant
                return true;
            }
        }

        // sinon non ce n'est pas un participant
        return false;
    }
}
