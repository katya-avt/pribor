<?php

namespace App\Http\Controllers\Admin\MarkedForDeletion\Item;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use App\Services\Admin\MarkedForDeletion\Item\Service;
use function redirect;

class RestoreController extends Controller
{
    public function __invoke(Item $item, Service $service)
    {
        $message = $service->restore($item->id);

        return redirect()->route('admin.marked-for-deletion.items.index')
            ->with('message', $message);
    }
}
