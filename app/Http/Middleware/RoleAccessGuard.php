<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\FeatureNotAllowedException;

class RoleAccessGuard
{
    /**
     * Get qualified role name
     * */
    private function getQualifiedName ($role) {
        $roles = [
            'su' => 'Super User',
            'sh' => 'Share Holder',
            'mod' => 'Moderator',
            'editor' => 'Editor',
            'author' => 'Author',
            'publisher' => 'Publisher',
            'finance' => 'Finance Manager',
            'admin' => 'Admin',
            'staff' => 'Staff'
        ];
        return array_key_exists($role, $roles) ? $roles[$role] : 'N/A';
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roleName = $this->getQualifiedName($role);
        $userRoles = auth()->user()->roles;

        $passed = $this->checkRole($userRoles, $roleName);
        if ($passed) {
            return $next($request);
        }
        throw new FeatureNotAllowedException;
    }

    /**
     * Check if user has that role
     * */
    private function checkRole ($roles, $checkAgainst) {
        $allowedRoles = [];

        // Hierarchy
        if ($checkAgainst === 'Author') {
            $allowedRoles = [$this->getQualifiedName('su'), $this->getQualifiedName('sh'), $this->getQualifiedName('mod'), $this->getQualifiedName('editor')];
        }

        if ($checkAgainst === 'Editor') {
            $allowedRoles = [$this->getQualifiedName('su'), $this->getQualifiedName('sh'), $this->getQualifiedName('publisher')];
        }

        if ($checkAgainst === 'Publisher') {
            $allowedRoles = [$this->getQualifiedName('su'), $this->getQualifiedName('sh')];
        }


        foreach ($roles as $role) {
            // Everything is allowed for roles higher than the role being checked
            if ( in_array($role->name, $allowedRoles) || ($role->name === $checkAgainst) ) {
                return true;
            }
        }
        return false;
    }
}
