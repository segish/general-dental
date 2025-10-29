<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\CauserResolver;

trait ActivityLogTrait
{

    protected $activityLogUserSuffix = "user"; // Default value
    protected $activityLogCauser;

    public function setActivityLogUserSuffix($suffix)
    {
        $this->activityLogUserSuffix = $suffix;
        return $this;
    }

    public function customizeActivitylogOptions(): LogOptions
    {
        $causerResolver = app(CauserResolver::class);

        if ($this->activityLogCauser) {
            $causerResolver->setCauser($this->activityLogCauser);
        }

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->setDescriptionForEvent(function (string $eventName) {
                return $this->getActivityLogDescription($eventName);
            });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->customizeActivitylogOptions();
    }

    protected function getActivityLogDescription($eventName)
    {
        if ($this->activityLogCauser) {
            $name = $this->activityLogCauser->l_name && $this->activityLogCauser->f_name
                ? $this->activityLogCauser->f_name . ' ' . $this->activityLogCauser->l_name
                : $this->activityLogCauser->name;

            $description = $name . $this->getEventName($eventName) . $this->activityLogUserSuffix;

            if (isset($this->id)) {
                $description .= ' with id ' . $this->id;
            }

            return $description;
        }

        return 'Unauthenticated User ' . $this->getEventName($eventName) . $this->activityLogUserSuffix .
            (isset($this->full_name) ? ' called ' . $this->full_name : '');
    }

    public function getEventName($eventName)
    {
        switch ($eventName) {
            case 'created':
                return "  created a new ";
                break;
            case 'updated':
                return "  updated the ";
                break;
            case 'deleted':
                return "  deleted the ";
                break;
            default:
                return "  performed an unknown action";
        }
    }
}
