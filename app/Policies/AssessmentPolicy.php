<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\User;

class AssessmentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Assessment $assessment): bool
    {
        // Admin can view any assessment
        if ($user->isAdmin()) {
            return true;
        }
        
        // Teacher can view their own assessments
        if ($user->isTeacher() && $assessment->teacher_id === $user->id) {
            return true;
        }
        
        // Student can view assessments from classes they're enrolled in
        if ($user->isStudent()) {
            return $user->enrolledClasses()
                ->whereHas('assessments', function ($query) use ($assessment) {
                    $query->where('assessments.id', $assessment->id);
                })
                ->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Assessment $assessment): bool
    {
        // Admin can update any assessment
        if ($user->isAdmin()) {
            return true;
        }
        
        // Teacher can update their own assessments
        return $user->isTeacher() && $assessment->teacher_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Assessment $assessment): bool
    {
        // Admin can delete any assessment
        if ($user->isAdmin()) {
            return true;
        }
        
        // Teacher can delete their own assessments (if no attempts exist)
        return $user->isTeacher() && $assessment->teacher_id === $user->id;
    }
}