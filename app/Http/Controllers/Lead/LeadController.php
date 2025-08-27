<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function index()
    {
        $props = [

        ];

        return Inertia::render('Lead/Index', $props);
    }
}
