<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;

class HotspotlinkController extends Controller
{
    public function deleteHotspotlink($hostspotlink_id)
    {
        $hostspotlink = Models\Hotspotlink::find($hostspotlink_id);
        $hostspotlink->delete();
        return response()->json(['status' => 'SUCCESS', 'message' => 'Hotspot Deleted'], 200);
    }
}
