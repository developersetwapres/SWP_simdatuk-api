<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\UpdateNoteByUserIdRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @group Compare
 * @subgroupDescription These endpoints allow you to manage notes for specific user.
 */
class NoteController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get List of Notes by User ID
     *
     * Retrieve notes for specific User ID.
     * @authenticated
     * @urlParam userid Refers to the ID of User. Example: 1
     * @response 200 {"code": 200,"message": "success","data": [{"id": 3,"giver_id": 7153,"giver_name": "Ayu Setiarini","description": "Catatan 1","created_at": "2024-06-09 01:22:01"},{"id": 4,"giver_id": 7153,"giver_name": "Ayu Setiarini","description": "Catatan 2","created_at": "2024-06-09 01:22:01"}]}
     */
    public function show()
    {
        $notes = DB::table('user_notes as un');
        $notes->leftJoin('users as u1', 'u1.id', '=', 'un.user_id');
        $notes->leftJoin('users as u2', 'u2.id', '=', 'un.giver_id');
        $notes->select(
            'un.id',
            'u2.id as giver_id',
            'u2.name as giver_name',
            'un.description',
            'un.created_at',
        );
        $notes->where('u1.id', $this->request->userid);
        $notes = $notes->get();
        return $this->response(200, 'success', $notes);
    }

    /**
     * Update List of Notes by User ID
     *
     * Update an existing notes entry.
     * @authenticated
     * @urlParam userid Refers to the ID of User. Example: 1
     * @response 200 {"code": 200,"message": "Catatan berhasil diupdate.","data": null}
     */
    public function update(UpdateNoteByUserIdRequest $request)
    {
        // Get existing data
        $userNotes = DB::table('user_notes');
        $userNotes->where('user_id', $this->request->userid);
        $userNotes->select('id');
        $userNotes = $userNotes->get();

        if (is_null($this->request->notes)) {
            // Delete data
            DB::table('user_notes')->where('user_id', $this->request->userid)->delete();
            return $this->response(200, 'Catatan berhasil diupdate.');
        } else {
            $array1 = Arr::pluck($userNotes, 'id');
            $array2 = Arr::pluck($this->request->notes, 'id');
            $result = array_diff($array1, $array2);
            DB::table('user_notes')->whereIn('id', $result)->delete();

            $notes = array();
            foreach ($this->request->notes as $item) {
                $item['giver_id'] = $this->request->user()->id;
                $item['user_id'] = $this->request->userid;
                if (is_null($item['id'])) {
                    // Insert new data
                    unset($item['id']);
                    array_push($notes, $item);
                } else {
                    // Update data
                    DB::table('user_notes')->where('id', $item['id'])->updateTs($item);
                }
            }

            if (count($notes) > 0) {
                DB::table('user_notes')->insertTs($notes);
            }
            return $this->response(200, 'Catatan berhasil diupdate.');
        }
    }
}
