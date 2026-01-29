<?php

namespace App\Http\Controllers;

use App\Helpers\Document;
use App\Helpers\Responser;
use App\Traits\HasPermissions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, Responser, Document, HasPermissions;
}
