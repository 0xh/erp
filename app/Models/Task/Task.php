<?php

namespace App\Models\Task;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package App\Models
 * @version November 1, 2016, 11:12 am CST
 */
class Task extends Model
{
    use SoftDeletes;

    public $table = 'tasks';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'title',
        'content',
        'hours',
        'price',
        'end_at',
        'task_id',
        'user_id',
        'status_id',
        'type_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'hours' => 'decimal',
        'price' => 'integer',
        'end_at' => 'datetime',
        'task_id' => 'integer',
        'user_id' => 'integer',
        'status_id' => 'integer',
        'type_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'task_id' => 'required',
        'user_id' => 'required',
        'status_id' => 'required',
        'type_id' => 'required',
        'end_at' => 'date_format:"Y-m-d H:i:s"',
    ];

    public function getTasktypeNameAttribute()
    {
        return $this->tasktype ? $this->tasktype->name : '';
    }

    public function getTaskstatusNameAttribute()
    {
        return $this->taskstatus ? $this->taskstatus->name : '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function taskgroup()
    {
        return $this->hasMany(\App\Models\Task\Taskgroup::class, 'task_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function taskstatus()
    {
        return $this->belongsTo(\App\Models\Task\Taskstatus::class, 'status_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tasktype()
    {
        return $this->belongsTo(\App\Models\Task\Tasktype::class, 'type_id', 'id');
    }

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::addGlobalScope('user_id', function(Builder $builder) {
//                $builder->orderBy('created_at', 'desc');
//        });
//    }
}
