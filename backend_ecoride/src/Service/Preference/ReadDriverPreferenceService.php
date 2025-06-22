<?php

namespace App\Service\Preference;

use App\Entity\User;
use App\DTO\Preference\AggregatedPrefReadDTO;

class ReadDriverPreferenceService
{
    public function getCurrentUserPref(User $user): AggregatedPrefReadDTO
    {
        return AggregatedPrefReadDTO::fromEntity($user);
    }

}

?>