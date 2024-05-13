<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function read(Request $request)
    {
        //TODO: ambil semua data class di tabel classes
    }
    public function readById(Request $request)
    {
        //TODO: ambil satu row berdasarkan id di tabel classes
    }
    public function create(Request $request)
    {
        //TODO: insert data ke tabel classes
    }
    public function update(Request $request)
    {
        //TODO: ambil satu row berdasarkan id, edit datanya, lalu update ke tabel classes
    }
    public function delete(Request $request)
    {
        //TODO: ambil satu row berdasarkan id, delete data itu di tabel classes
    }
}
