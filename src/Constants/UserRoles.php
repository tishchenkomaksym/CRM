<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 06.03.2019
 * Time: 14:11
 */

namespace App\Constants;

interface UserRoles
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ACCOUNT_MANAGER = 'ROLE_ACCOUNT_MANAGER';
    public const ROLE_SDT_REQUEST = 'ROLE_SDT_REQUEST';
    public const ROLE_PHP_MANAGER = 'ROLE_PHP_MANAGER';
    public const ROLE_PHP_DEVELOPER = 'ROLE_PHP_DEVELOPER';
    public const ROLE_TOM = 'ROLE_TOM';
    public const ROLE_RECRUITER = 'ROLE_RECRUITER';
    public const ROLE_RECRUITING_DEPARTMENT_MANAGER = 'ROLE_RECRUITING_DEPARTMENT_MANAGER';
    public const ROLE_HR = 'ROLE_HR';
    public const ROLE_MANAGE_HOLIDAYS = 'ROLE_MANAGE_HOLIDAYS';
    public const ROLE_MANAGE_MONTHLY_SDT = 'ROLE_MANAGE_MONTHLY_SDT';
    public const ROLE_TOP_MANAGER = 'ROLE_TOP_MANAGER';
    public const ROLE_VACANCY_VIEWER_USER = 'ROLE_VACANCY_VIEWER_USER';
    public const ROLE_CANDIDATES_DATABASE_USER = 'ROLE_CANDIDATES_DATABASE_USER';
    public const ROLE_VACANCY_WATCHER_USER = 'ROLE_VACANCY_WATCHER_USER';
    public const ROLE_VIEW_SALARY_REPORT = 'ROLE_VIEW_SALARY_REPORT';
    public const ROLE_MANAGE_ROLES = 'ROLE_MANAGE_ROLES';
    public const ROLE_QA = 'ROLE_QA_AGENT';
    public const ROLE_QA_MANGER = 'ROLE_QA_MANGER';
}