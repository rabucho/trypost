<?php

declare(strict_types=1);

namespace App\Enums\User;

enum Goal: string
{
    case SaveTime = 'save_time';
    case AiContent = 'ai_content';
    case PlanCalendar = 'plan_calendar';
    case StayOnBrand = 'stay_on_brand';
    case GrowAudience = 'grow_audience';
    case DriveSales = 'drive_sales';
    case ManageClients = 'manage_clients';
    case TeamCollaboration = 'team_collaboration';
    case AutomateApi = 'automate_api';
    case TrackPerformance = 'track_performance';
    case JustExploring = 'just_exploring';
    case Other = 'other';
}
