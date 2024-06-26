<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteByAuthor;
use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\PaginateInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Eloquent\UpdateOrCreateInterface;
use Illuminate\Http\Request;

interface NewsCategoryInterface extends DeleteByAuthor, GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, PaginateInterface, UpdateOrCreateInterface
{
    public function search(mixed $id, Request $request) : mixed;
    public function searchAuthor(mixed $id, Request $request) : mixed;
    public function searchStatus(mixed $id, Request $request) : mixed;
    public function trending() : mixed;
    public function latest() : mixed;
}
