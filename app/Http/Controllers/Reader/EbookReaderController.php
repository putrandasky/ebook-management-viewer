<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Tfpdf;

class EbookReaderController extends Controller
{
    public function show(Request $request)
    {
        $data['ebook'] = Models\Ebook::ebookSlug($request->slug)->first();
        $chapters = Models\Chapter::ebookId($data['ebook']->id)->reOrder();
        $data['pages'] = $chapters->withPages()->get()->pluck('pages')->flatten();
        $data['chapters'] = $chapters->withMarkedPages()->get();
        return $data;
    }
    public function generateShareableCropImage(Request $request)
    {
        // $openedFile = $request->file('cropImage');
        $image = $request->cropImage; // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '.png';

        Storage::disk('public')->put('shared/' . $imageName, base64_decode($image));
        return '/shared/' . $imageName;
        //  $image = $request->image;  // your base64 encoded
        // $image = str_replace('data:image/png;base64,', '', $image);
        // $image = str_replace(' ', '+', $image);
        // $imageName = str_random(10).'.'.'png';
        // \File::put(storage_path(). '/' . $imageName, base64_decode($image));

    }
    public function showCropImage($imageName)
    {
        $newpathToFile = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix("shared/{$imageName}");
        return response()->file($newpathToFile);
    }
    public function showPdf($pdfname)
    {
        $newpathToFile = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix("pdf/{$pdfname}");
        return response()->file($newpathToFile);
    }
    public function generatePdf($fpdi_class, $first_array, $last_array, $ebook_id, $pages)
    {
        for ($a = $first_array; $a < $last_array; $a++) {
            $file = public_path() . "/storage/book/{$ebook_id}/{$pages[$a]['original_name']}";
            $data = getimagesize($file);
            $fpdi_class->AddPage('potrait', [$data[0], $data[1]]);
            $fpdi_class->Image($file, 0, 0, $data[0], $data[1], 'JPG');
        }
    }
    public function generateRangePdf(Request $request)
    {
        $fpdi = new Tfpdf\Fpdi();
        $chapters = Models\Chapter::ebookId($request->ebook_id)->reOrder();
        $pages = $chapters->withPages()->get()->pluck('pages')->flatten();
        $page_index = $request['page_index'];
        $object_count = count($page_index);
        for ($i = 0; $i < $object_count; $i++) {
            if (isset($page_index[$i][1])) {
                $this->generatePdf($fpdi, $page_index[$i][0], $page_index[$i][1] + 1, $request->ebook_id, $pages);
            } else {
                $this->generatePdf($fpdi, $page_index[$i][0], $page_index[$i][0] + 1, $request->ebook_id, $pages);
            }
        }
        $filename = Str::random(10) . '.pdf';

        Storage::disk('public')->put('pdf/' . $filename, $fpdi->Output('S'));
        return '/pdf/' . $filename;
    }
    public function generateEntirePdf(Request $request)
    {
        $fpdi = new Tfpdf\Fpdi();
        $chapters = Models\Chapter::ebookId($request->ebook_id)->reOrder();
        $pages = $chapters->withPages()->get()->pluck('pages')->flatten();
        $this->generatePdf($fpdi, 0, count($pages), $request->ebook_id, $pages);
        $filename = Str::random(10) . '.pdf';

        Storage::disk('public')->put('pdf/' . $filename, $fpdi->Output('S'));
        return '/pdf/' . $filename;

        // for ($i = 0; $i < count($pages); $i += 2) {
        //     $file_odd = public_path() . "/storage/book/{$ebook_id}/{$pages[$i]['original_name']}";
        //     $data_odd = getimagesize($file_odd);
        //     $file_even = null;
        //     $data_even = null;
        //     $page_width = $data_odd[0] * 2;
        //     if (!empty($pages[$i + 1])) {
        //         $file_even = public_path() . "/storage/book/{$ebook_id}/{$pages[$i + 1]['original_name']}";
        //         $data_even = getimagesize($file_even);
        //         $page_width = $data_odd[0] + $data_even[0];

        //     }

        //     $fpdi->AddPage('landscape', [$page_width, $data_odd[1]]);
        //     $fpdi->Image($file_odd, 0, 0, $data_odd[0], $data_odd[1], 'JPG');
        //     if (!is_null($file_even)) {
        //         # code...
        //         $fpdi->Image($file_even, $data_odd[0], 0, $data_odd[0], $data_odd[1], 'JPG');
        //     }

        // }

        // return response($fpdi->Output('I', "TEST-SIGN.pdf", true), 200);

    }
}
