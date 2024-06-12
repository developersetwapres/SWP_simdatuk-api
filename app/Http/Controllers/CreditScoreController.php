<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreditScore\CreateCreditScoreRequest;
/**
 * @group Credit Score
 * @subgroupDescription These endpoints allow you to perform CRUD operations on Credit Score, enabling the retrieval, creation, updating and deleting of credit score records as needed.
 */
class CreditScoreController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Get Detail Credit Scores by User ID
     *
     * Retrieve Credit Scores for a specific user.
     * @subgroup Credit Score
     * @authenticated
     * @urlParam id Refers to the ID of a user. Example: 1
     * @response 404 {"code": 404,"message": "Credit Score.","data": null}
     * @response 200 {"code":200,"message":"success","data":[{"id":1,"position":"ahli muda","period":1,"year":"2024","last_credit_score":50,"name":"Lurhur Raden Januar","user_id":3}]}
     */
    public function show()
    {
        $creditScores = DB::table('user_credit_score as ucs');
        $creditScores->join('users', 'ucs.user_id', '=', 'users.id');
        $creditScores->select(
            'ucs.id',
            'ucs.position',
            'ucs.period',
            'ucs.year',
            'ucs.last_credit_score',
            'users.name',
            'users.id as user_id');
        $creditScores->where('ucs.user_id', '=', $this->request->id);
        $creditScores = $creditScores->get();
        return $this->response(200, 'success', $creditScores);
    }

    /**
     * Create a New Credit Score
     *
     * Add a new Credit Score entry for a data of Credit Score.
     * @subgroup Credit Score
     * @authenticated
     * @response 200 {"code": 200,"message": "Credit Score berhasil ditambah.","data": null}
     */
    public function create(CreateCreditScoreRequest $request)
    {
        $validatedData = $request->validated();
        DB::table('user_credit_score')->insertTs($validatedData);
        return $this->response(200, 'Credit Score berhasil ditambahkan');
    }


    /**
     * Update Credit Score by ID
     *
     * Update an existing institution entry.
     * @subgroup Credit Score
     * @authenticated
     * @urlParam id Refers to the ID of Credit Score. Example: 1
     * @response 404 {"code": 404,"message": "Credit Score tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Credit Score berhasil diupdate.","data": null}
     */
    public function update()
    {
        $creditScores = DB::table('user_credit_score');
        $creditScores->where('id', $this->request->id);
        $creditScores->select('id');
        $creditScores = $creditScores->first();

        if (!$creditScores) {
            return $this->response(404, 'Credit Score tidak ditemukan.');
        }
        $creditScores = DB::table('user_credit_score');
        $creditScores->where('id', $this->request->id);
        $creditScores = $creditScores->updateTs($this->posted);

        return $this->response(200, 'Credit Score berhasil diupdate');
    }

    /**
     * Delete Credit Score by ID
     *
     * Delete a specific credit score entry.
     * @subgroup Credit Score
     * @authenticated
     * @urlParam id Refers to the ID of Credit Score. Example: 1
     * @response 404 {"code": 404,"message": "Credit SCore tidak ditemukan.","data": null}
     * @response 200 {"code": 200,"message": "Credit SCore berhasil dihapus.","data": null}
     */
    public function delete()
    {
        $creditScores = DB::table('user_credit_score');
        $creditScores->where('id', $this->request->id);
        $creditScores->select('id');
        $creditScores = $creditScores->first();

        if (!$creditScores) {
            return $this->response(404, 'Credit Score tidak ditemukan.');
        }
        $creditScores = DB::table('user_credit_score');
        $creditScores->where('id', $this->request->id);
        $creditScores = $creditScores->delete();

        return $this->response(200, 'Credit Score berhasil dihapus');
    }
}
