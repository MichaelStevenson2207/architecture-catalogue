<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PasswordReset;
// use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\AuditsActivity;

class User extends Authenticatable
{
    use Notifiable;
    // use LogsActivity;
    use AuditsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'provider_name', 'provider_id', 'admin', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static $logAttributes = [
                'name', 'email', 'email_verified_at', 'provider_name', 'provider_id', 'admin', 'role',
    ];

    /**
     * Check if the user is an admin.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin == 1;
    }

    /**
     * Check if the user is in the reader role.
     *
     * @return boolean
     */
    public function isReader()
    {
        return $this->role == 'reader';
    }

    /**
     * Check if the user is in the contributor role.
     *
     * @return boolean
     */
    public function isContributor()
    {
        return $this->role == 'contributor';
    }

    /**
     * Send the custom password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($this, $token));
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('services.slack.webhook_url');
    }

    /**
     * Do not trigger events on saving a model.
     *
     * @param  array $options
     * @return $this
     */
    public function saveWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
