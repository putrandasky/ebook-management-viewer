<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index()
    {
        $data = Models\Ebook::with([
            'chapters' => function ($query) {
                $query->where('order', 1);
            },
            'chapters.pages' => function ($query) {
                $query->where('order', 1);
            },
        ])
            ->withCount('chapters')
            ->get();
        return $data;
    }
    public function show($slug)
    {
        $data['ebook'] = Models\Ebook::ebookSlug($slug)->first();
        $chapters = Models\Chapter::ebookId($data['ebook']->id)
            ->reOrder()
            ->withCount('pages')
            ->with('pages')
            ->get();
        $data['chapters'] = collect($chapters)->map(function ($key) {
            $data['id'] = $key['id'];
            $data['name'] = $key['name'];
            $data['order'] = $key['order'];
            $data['alias'] = $key['alias'];
            $data['type'] = $key['type'];
            if ($key['type'] == "file") {
                $data['pages']['id'] = $key['pages'][0]['id'];
                $data['pages']['original_name'] = $key['pages'][0]['original_name'];
            }
            if ($key['type'] == "folder") {
                $data['pages_count'] = $key['pages_count'];
            }

            return $data;
        });
        return $data;

    }
    public function update(Request $request, $ebook_id)
    {
        $rules = [
            'bookName' => 'required|string|min:6|max:255',

        ];
$this->validate($request, $rules);

        $ebook = Models\Ebook::find($ebook_id);
        $ebook->name = ucwords($request->bookName);
        $ebook->slug = str_replace(' ', '-', $request->slug);
        $ebook->save();
        return response()->json(['status' => 'SUCCESS', 'message' => 'Book Updated'], 200);

    }
    public function create(Request $request)
    {
        $rules = [
            'bookName' => 'required|string|min:6|max:255',

        ];

        $this->validate($request, $rules);

        $newEbook = new Models\Ebook();
        $newEbook->name = ucwords($request->bookName);
        $newEbook->slug = str_replace(' ', '-', $request->slug);
        $newEbook->save();
        $ebook = Models\Ebook::where('id', $newEbook->id)->with('chapters')->withCount('chapters')->first();
        return $ebook;

        // $data_ebook = Models\Ebook::where('slug', $slug);
        // if ($data_ebook->exists()) {
        //     $slug = $slug . '-' . $ebook->id;
        // }
        // $ebook->slug = $slug;
        // $ebook->save();

    }
    public function delete($ebook_id)
    {
        $ebook = Models\Ebook::find($ebook_id);
        $ebook->delete();
        return response()->json(['status' => 'SUCCESS', 'message' => 'Book Deleted'], 200);

    }
}
