<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;
use Illuminate\Http\Request;
use ImageResize;
use Storage;

class PageController extends Controller
{
    public function createFile(Request $request, $chapter_id)
    {
        $ebook_id = Models\Chapter::where('id', $chapter_id)->first()->ebook_id;
        $basename = pathinfo($request->file('file')->getClientOriginalName())['filename'];
        if ($request->file('file') == null) {
            return response()->json(['status' => 'error', 'message' => 'No Image Added'], 500);
        }
        $filename = $this->storeFile($request, $ebook_id);
        $page_last_order = Models\Page::chapterId($chapter_id)->max('order');

        $page = Models\Page::create([
            'order' => $page_last_order + 1,
            'name' => $basename,
            'original_name' => $filename,
            'is_marked' => false,
            'chapter_id' => $chapter_id,
        ]);

        return $page;

    }
    public function createHotspotlink(Request $request, $page_id)
    {

        for ($i = 0; $i < count($request->data); $i++) {
            Models\Hotspotlink::updateOrCreate(
                ['id' => $request->missing("data.{$i}.id") ? null : $request['data'][$i]['id']],
                [
                    'address' => $request['data'][$i]['address'],
                    'area_height' => $request['data'][$i]['area_height'],
                    'area_left' => $request['data'][$i]['area_left'],
                    'area_top' => $request['data'][$i]['area_top'],
                    'area_width' => $request['data'][$i]['area_width'],
                    'parent_height' => $request['data'][$i]['parent_height'],
                    'parent_width' => $request['data'][$i]['parent_width'],
                    'page_id' => $page_id,
                ]
            );
        }
        return response()->json(['status' => 'SUCCESS', 'message' => 'Hotspot Link Updated'], 200);

    }

    public function updateDetail(Request $request, $page_id)
    {
        $rules = [
            'name' => 'required|string|min:6|max:255',

        ];

        $this->validate($request, $rules);

        $page = Models\Page::findOrFail($page_id);
        $page->fill([
            'name' => $request->name,
            'is_marked' => $request->is_marked,
        ]);
        $page->save();
        return response()->json(['status' => 'SUCCESS', 'message' => 'Page Updated'], 200);
    }
    public function showDetail($page_id)
    {
        $page = Models\Page::where('id', $page_id)->with('hotspotlinks', 'chapter.ebook')->first();
        return $page;
    }
    public function reorder(Request $request)
    {
        // dd(count($request['data']));
        for ($i = 0; $i < count($request['pages']); $i++) {
            $page = Models\Page::find($request['pages'][$i]['id']);
            $page->order = $i + 1;
            $page->save();
            # code...
        }
        return response()->json(['status' => 'SUCCESS', 'message' => 'Pages Reordered'], 200);
    }
    public function storeFile($request, $ebook_id)
    {
        $path = 'public/book/' . $ebook_id;
        $request->file('file')->store($path);
        $hashName = $request->file('file')->hashName();
        $img256 = ImageResize::make($request->file('file'));
        $img256->resize(256, 256, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/public/book/' . $ebook_id . '/tmb256-' . $hashName));
        $img128 = ImageResize::make($request->file('file'));
        $img128->resize(128, 128, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/public/book/' . $ebook_id . '/tmb128-' . $hashName));

        return $hashName;

    }
    public function deletePage($page_id)
    {
        $hostspotlink = Models\Hotspotlink::where('page_id', $page_id)->delete();

        $page = Models\Page::where('id', $page_id)->with('chapter.ebook')->first();
        $ebook_id = $page->chapter->ebook->id;
        $page->delete();
        Storage::delete("public/book/{$ebook_id}/{$page->original_name}");
        Storage::delete("public/book/{$ebook_id}/tmb128-{$page->original_name}");
        Storage::delete("public/book/{$ebook_id}/tmb256-{$page->original_name}");

        return response()->json(['status' => 'SUCCESS', 'message' => 'Page Deleted'], 200);
    }
}
