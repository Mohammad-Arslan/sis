<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLoggerService
{
    public function log(
        string $event,
        ?Model $model = null,
        ?array $old = null,
        ?array $new = null,
        ?array $additional = null
    ): ActivityLog {
        $data = [
            'user_id' => Auth::id(),
            'event' => $event,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $old,
            'new_values' => $new ?? $additional,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ];

        return ActivityLog::create($data);
    }

    public function logModelCreated(Model $model): ActivityLog
    {
        return $this->log(
            event: 'created',
            model: $model,
            new: $model->getAttributes()
        );
    }

    public function logModelUpdated(Model $model): ActivityLog
    {
        return $this->log(
            event: 'updated',
            model: $model,
            old: $model->getOriginal(),
            new: $model->getChanges()
        );
    }

    public function logModelDeleted(Model $model): ActivityLog
    {
        return $this->log(
            event: 'deleted',
            model: $model,
            old: $model->getAttributes()
        );
    }

    public function logLogin(?int $userId = null): ActivityLog
    {
        return $this->log(
            event: 'login',
            additional: ['user_id' => $userId ?? Auth::id()]
        );
    }

    public function logLogout(?int $userId = null): ActivityLog
    {
        return $this->log(
            event: 'logout',
            additional: ['user_id' => $userId ?? Auth::id()]
        );
    }

    public function logFailedLogin(string $email): ActivityLog
    {
        return $this->log(
            event: 'failed_login',
            additional: ['email' => $email]
        );
    }

    public function logApiRequest(
        string $method,
        string $url,
        int $statusCode,
        ?int $userId = null
    ): ActivityLog {
        return $this->log(
            event: 'api_request',
            additional: [
                'method' => $method,
                'url' => $url,
                'status_code' => $statusCode,
                'user_id' => $userId ?? Auth::id(),
            ]
        );
    }

    public function logCommand(string $commandName): ActivityLog
    {
        $data = [
            'user_id' => Auth::id(),
            'event' => 'command_ran',
            'model_type' => 'Command',
            'model_id' => null,
            'old_values' => null,
            'new_values' => ['command' => $commandName],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ];

        return ActivityLog::create($data);
    }

    public function logJob(string $jobName, ?array $payload = null): ActivityLog
    {
        return $this->log(
            event: 'job_processed',
            additional: [
                'job' => $jobName,
                'payload' => $payload,
            ]
        );
    }
}

