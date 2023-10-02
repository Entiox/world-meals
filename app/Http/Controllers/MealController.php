<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealRequest;
use App\Models\Meal;

class MealController extends Controller
{
    public function getMeals(MealRequest $request)
    {
        $validated = $request->validated();

        $result =  Meal::diffTime($validated["diff_time"])
            ->with(["translations" => function($query) use ($validated)
                {
                    $query->where("locale", $validated["lang"]);
                }])
            ->tags($validated["tags"])
            ->attachments($validated["with"], $validated["lang"])
            ->categoryId($validated["category"]);
        
        $totalMeals = $result->count();
        $result = $result->page($validated["page"], $validated["per_page"])->get();

        return [
            "meta" => [
                "currentPage" => isset($validated["page"]) ? (int) $validated["page"] : 1,
                "totalItems" => $totalMeals,
                "itemsPerPage" => isset($validated["per_page"]) ? (int) $validated["per_page"] : (isset($validated["page"]) ? Meal::$defaultPerPageCount : $totalMeals),
                "totalPages" => isset($validated["per_page"]) ? ceil($totalMeals / (int) $validated["per_page"]) : 
                    (isset($validated["page"]) ? ceil($totalMeals / Meal::$defaultPerPageCount) : 1),
            ],
            "data" => $result->map(function($value) use ($validated)
                {
                    $modifiedValue["id"] = $value["id"];
                    $modifiedValue["title"] = $value["translations"][0]["title"];
                    $modifiedValue["description"] = $value["translations"][0]["description"];

                    if(isset($validated["diff_time"]))
                    {
                        $diffTime = (int) $validated["diff_time"];
                        if($value["updated_at"]->timestamp > $diffTime && $value["updated_at"]->timestamp > $value["created_at"]->timestamp
                            && (isset($value["deleted_at"]) || $value["updated_at"]->timestamp > $value["deleted_at"]->timestamp))
                        {
                            $modifiedValue["status"] = "modified";
                        }
                        else if(isset($value["deleted_at"]->timestamp) && $value["deleted_at"]->timestamp > $diffTime)
                        {
                            $modifiedValue["status"] = "deleted";
                        }
                        else
                        {
                            $modifiedValue["status"] = "created";
                        }
                    }
                    else
                    {
                        $modifiedValue["status"] = "created";
                    }

                    if(isset($validated["with"]))
                    {
                        if(in_array("category", $validated["with"]))
                        {
                            if(isset($value["category"]))
                            {
                                $modifiedValue["category"] = $this->modifyAttachment($value["category"]);
                            }
                            else
                            {
                                $modifiedValue["category"] = $value["category"];
                            }
                        }
                        if(in_array("tags", $validated["with"]))
                        {
                            $modifiedValue["tags"] = $this->modifyAttachments($value["tags"]);
                        }
                        if(in_array("ingredients", $validated["with"]))
                        {
                            $modifiedValue["ingredients"] = $this->modifyAttachments($value["ingredients"]);
                        }
                    }
                    return $modifiedValue;
                })
        ];
    }

    private function modifyAttachment($attachmentValue){
        $modifiedValue["id"] = $attachmentValue["id"];
        $modifiedValue["title"] = $attachmentValue["translations"][0]["title"];
        $modifiedValue["slug"] = $attachmentValue["slug"];
        return $modifiedValue;
    }

    private function modifyAttachments($value){
        return $value->map(function($attachmentValue)
            {
                $modifiedValue = array();
                $modifiedValue["id"] = $attachmentValue["id"];
                $modifiedValue["title"] = $attachmentValue["translations"][0]["title"];
                $modifiedValue["slug"] = $attachmentValue["slug"];
                return $modifiedValue;
            });
    }
}
