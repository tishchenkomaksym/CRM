<?php


namespace App\Service\AllRolesDataProvider;


use App\Constants\UserRoles;

class AllRolesDataProvider implements UserRoles
{
    private $roles = [
        self::ROLE_USER => self::ROLE_USER,
        self::ROLE_RECRUITER => self::ROLE_RECRUITER,
        self::ROLE_PHP_DEVELOPER => self::ROLE_PHP_DEVELOPER,
        self::ROLE_ACCOUNT_MANAGER => self::ROLE_ACCOUNT_MANAGER,
        self::ROLE_CANDIDATES_DATABASE_USER => self::ROLE_CANDIDATES_DATABASE_USER,
        self::ROLE_HR => self::ROLE_HR,
        self::ROLE_MANAGE_HOLIDAYS => self::ROLE_MANAGE_HOLIDAYS,
        self::ROLE_MANAGE_MONTHLY_SDT => self::ROLE_MANAGE_MONTHLY_SDT,
        self::ROLE_MANAGE_ROLES => self::ROLE_MANAGE_ROLES,
        self::ROLE_PHP_MANAGER => self::ROLE_PHP_MANAGER,
        self::ROLE_RECRUITING_DEPARTMENT_MANAGER => self::ROLE_RECRUITING_DEPARTMENT_MANAGER,
        self::ROLE_SDT_REQUEST => self::ROLE_SDT_REQUEST,
        self::ROLE_TOM => self::ROLE_TOM,
        self::ROLE_TOP_MANAGER => self::ROLE_TOP_MANAGER,
        self::ROLE_VACANCY_VIEWER_USER => self::ROLE_VACANCY_VIEWER_USER,
        self::ROLE_VACANCY_WATCHER_USER => self::ROLE_VACANCY_WATCHER_USER,
        self::ROLE_VIEW_SALARY_REPORT => self::ROLE_VIEW_SALARY_REPORT
    ];

    public function getRoles(): array
    {
        return $this->roles;
    }
}