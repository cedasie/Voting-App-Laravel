<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Idea extends Model
{
    use HasFactory, Sluggable;

    const PAGINATION_COUNT = 10;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }


    public function getStatusClasses()
    {
        // $allStatuses = [
        //     'Open' => 'bg-gray-200',
        //     'Considering' => 'bg-purple text-white',
        //     'In Progress' => 'bg-yellow text-white',
        //     'Implemented' => 'bg-green text-white',
        //     'Closed' => 'bg-red text-white',
        // ];

        // return $allStatuses[$this->status->name];

        // if ($this->status->name === 'Open') {
        //     return 'bg-gray-200';
        // } elseif ($this->status->name === 'Considering') {
        //     return 'bg-purple text-white';
        // } elseif ($this->status->name === 'In Progress') {
        //     return 'bg-yellow text-white';
        // } elseif ($this->status->name === 'Implemented') {
        //     return 'bg-green text-white';
        // } elseif ($this->status->name === 'Closed') {
        //     return 'bg-red text-white';
        // }

        // return 'bg-gray-200';
    }

    public function votes()
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function isVotedByUser(?User $user)
    {
        if (!$user) {
            return false;
        }

        return Vote::where('user_id', $user->id)
            ->where('idea_id', $this->id)
            ->exists();
    }

    public function vote(User $user)
    {
        Vote::create([
            'idea_id' => $this->id,
            'user_id' => $user->id,
        ]);
    }
    public function removeVote(User $user)
    {
        Vote::where('idea_id', $this->id)
            ->where('user_id', $user->id)
            ->first()
            ->delete();
    }
}
