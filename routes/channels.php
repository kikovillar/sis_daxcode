<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Assessment-related channels for real-time features
Broadcast::channel('assessment.{assessmentId}', function ($user, $assessmentId) {
    // Check if user can access this assessment
    return $user->canAccessAssessment(\App\Models\Assessment::find($assessmentId));
});