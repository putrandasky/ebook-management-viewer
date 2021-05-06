<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;
use Illuminate\Http\Request;
use ImageResize;

class ChapterController extends Controller
{
    public function show($slug, $chapter_id)
    {
        $ebook_id = Models\Ebook::ebookSlug($slug)->first()->id;

        $chapter = Models\Chapter::where([
            'id' => $chapter_id,
            'ebook_id' => $ebook_id,
        ])->with(['pages' => function ($query) {
            $query->orderBy('order');
        }])->withCount('pages')
            ->first();
        return $chapter;
    }
    public function createFolder(Request $request)
    {
        $rules = [
            'chapterName' => 'required|string|min:6|max:255',

        ];
        $this->validate($request, $rules);

        $chapter = $this->createChapter($request->slug, $request->chapterName, $request->alias, "folder");
        return response()->json(['status' => 'SUCCESS', 'message' => 'New Folder Created', 'data' => $chapter], 200);

    }
    public function createFile(Request $request, $slug)
    {

        $ebook_id = Models\Ebook::ebookSlug($slug)->first()->id;
        $basename = pathinfo($request->file('file')->getClientOriginalName())['filename'];
        $chapter = $this->createChapter($slug, $basename, null, "file");
        if ($request->file('file') == null) {
            return response()->json(['status' => 'error', 'message' => 'No Image Added'], 500);
        }
        $filename = $this->storeFile($request, $ebook_id);
        $chapter->pages()->create([
            'order' => 1,
            'name' => $basename,
            'original_name' => $filename,
            'is_marked' => false,
        ]);
        return $chapter->pages()->first();

    }

    public function createChapter($slug, $name, $alias, $type)
    {
        $ebook_id = Models\Ebook::ebookSlug($slug)->first()->id;

        $chapter_last_order = Models\Chapter::ebookId($ebook_id)->max('order');
        $chapter = new Models\Chapter();
        $chapter->name = ucwords($name);
        $chapter->order = $chapter_last_order + 1;
        $chapter->type = $type;
        $chapter->ebook_id = $ebook_id;
        $chapter->alias = $alias ?? '';
        $chapter->save();
        return $chapter;
    }
    public function update(Request $request, $chapter_id)
    {
        $chapter = Models\Chapter::find($chapter_id);
        $chapter->name = $request->chapterName;
        $chapter->alias = $request->chapterAlias ?? '';
        $chapter->save();
        return response()->json(['status' => 'SUCCESS', 'message' => 'Chapter Updated'], 200);

    }
    public function reorder(Request $request)
    {
        for ($i = 0; $i < count($request['chapters']); $i++) {
            $chapter = Models\Chapter::find($request['chapters'][$i]['id']);
            $chapter->order = $i + 1;
            $chapter->save();
        }
        return response()->json(['status' => 'SUCCESS', 'message' => 'Chapters Reordered'], 200);
    }
    public function deleteChapter($chapter_id)
    {
        // $hostspotlink = Models\Hotspotlink::where('page_id', $page_id)->delete();

        $chapter = Models\Chapter::where('id', $chapter_id)->with('pages.hotspotlinks')->first();
        foreach ($chapter->pages as $page) {
            foreach ($page->hotspotlinks as $hotspotlink) {
                $hotspotlink->delete();
            }
            $page->delete();
        }
        $chapter->delete();

        return response()->json(['status' => 'SUCCESS', 'message' => 'Chapter Deleted'], 200);
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
}
