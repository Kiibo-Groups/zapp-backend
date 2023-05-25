<?php

namespace App\Exports;
 
use App\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class MetaExport implements FromView,WithHeadings,WithTitle
{
    public $folder  = "meta.";
    /**
    * @return \Illuminate\Support\Collection
    */
   
    public function headings(): array
    {
        return [
            'id',
            'title',
            'description',
            'availability',
            'condition',
            'price',
            'link',
            'image_link',
            'brand'
        ];
    }
    
    public function title(): string
    {
        return 'Items for META';
    }

    public function view(): view
    {
        $res = new Item;
		return View($this->folder.'xml_fb',[
            'data' => $res->ExportItemsMeta()
        ]);
    }
}
