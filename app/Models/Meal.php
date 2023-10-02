<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    public $translatedAttributes = ["title", "description"];
    public static $defaultPerPageCount = 50;
    private static $attachments = ["category", "tags", "ingredients"];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to get items on a specific page and their specific amount.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePage($query, $page, $perPageCount)
    {
        if(isset($perPageCount))
        {
            return $query->skip((isset($page) ? ((int) $page - 1) : 0) * (int) $perPageCount)->take((int) $perPageCount);
        }
        else if(isset($page)) 
        {
            return $query->skip(((int) $page - 1) * $this->defaultPerPageCount)->take($this->defaultPerPageCount);
        }
        else {
            return $query;
        }
    }

    /**
     * Scope a query to get items that were either created, modified or deleted after diffTime parameter.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDiffTime($query, $diffTime)
    {
        if(!isset($diffTime) || (int) $diffTime < 1){
            return $query;
        }
        $diffDateTime = date("Y-m-d h:i:s", (int) $diffTime);
        return $query->withTrashed()->where("created_at", ">", $diffDateTime)
            ->orWhere("updated_at", ">", $diffDateTime)
            ->orWhere("deleted_at", ">", $diffDateTime);
    }

    /**
     * Scope a query to get items with certain category(ies).
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryId($query, $id)
    {
        if(!isset($id)){
            return $query;
        }
        else if($id == "NULL"){
            return $query->whereNull("category_id");
        }
        else if($id == "!NULL"){
            return $query->whereNotNull("category_id");
        }
        else return $query->where("category_id", (int)$id);
    }

    /**
     * Attach requested attachments to meals.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAttachments($query, $requestedAttachments, $lang)
    {
        if(!isset($requestedAttachments))
        {
            return $query;
        }
        foreach(Meal::$attachments as $attachment)
        {
            if(in_array($attachment, $requestedAttachments)){
                $query = $query->attachment($attachment, $lang);
            }
        }
        return $query;
    }

    /**
     * Attach requested attachment to meals.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAttachment($query, $requestedAttachment, $lang)
    {
        return $query->with([$requestedAttachment => function($attachmentQuery) use ($lang)
        {
            $attachmentQuery->with(["translations" => function($translationQuery) use ($lang)
            {
                $translationQuery->where("locale", $lang);
            }]);
        }]);
    }

    /**
     * Scope a query to get items with given tags.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTags($query, $tags)
    {
        if(!isset($tags))
        {
            return $query;
        }
        return $query->whereHas("tags", function($tagQuery) use ($tags)
            {
                return $tagQuery->whereIn("tag_id", $tags);
            }, "=", count($tags));
    }
}
