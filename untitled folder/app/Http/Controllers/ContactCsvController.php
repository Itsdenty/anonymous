<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactCsv;
use Auth;
use App\Contact;
use Carbon\Carbon;
use App\Tag;
use App\ContactTag;
use App\Segment;
use App\Activity;
use Excel;

class ContactCsvController extends Controller
{   
    public function myParseImport(Request $request)
    {
        $user = Auth::user();
        $main_user = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $this->validate($request, [
            'csv_file' => 'required|file'
        ]);

        $request_data = $request->all();

        $contact_attributes = $user->currentAccount->contactAttributes;
        $attribute_array = [];
        foreach($contact_attributes as $attribute)
        {
            array_push($attribute_array, $attribute->name);
        }

        $path = $request->file('csv_file')->getRealPath();
        $data = Excel::load($path)->get();
        $array_data = $data->toArray();

        foreach ($array_data as $row) {
            if(!array_key_exists("email", $row))
            {
                continue;
            }
            
            $contact = new Contact();
            foreach($row as $key=>$value)
            {
                if($key == 'firstname'|| $key == 'lastname' || $key ==  'email')
                {
                    $contact->$key= $value;
                }
            }

            $contact->sub_account_id = $user->currentAccount->id;
            $contact->sub_date = Carbon::now();
            $contact->save();
            Activity::create(['content' => 'Imported to Contact List', 'category' => 'imported', 'contact_id' => $contact->id]);

            foreach($row as $key=>$value)
            {
                if(in_array($key, $attribute_array))
                {
                    $attribute = $contact_attributes->where('name', $key)->first();
                    if($value && $attribute)
                    {
                        $contact->contactAttributes()->attach($attribute->id, ['value' => $value]);
                    }
                }
            }

            $contact->tags()->detach();
            if(isset($request_data["tags"]))
            {
                $contact->tags()->attach($request_data["tags"]);
            }
        }

        return back()->with('status', "Contact Imported Successfully");
    }
}
