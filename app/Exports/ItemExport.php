<?php

namespace App\Exports;

use App\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings; 

class ItemExport implements FromCollection,WithHeadings
{ 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $req = Item::where('store_id',Auth::user()->id)->get();
        
        $data = [];
        foreach ($req as $key) {
            $data[] = array(
                'id'            => $key->id,
                'store_id'      => Auth::user()->id,
                'category_id'   => $key->category_id,
                'name'          => $key->name,
                'description'   => $key->description,
                'status'        => $key->status,
                'img'           => ($key->type_img == 0) ? Asset('upload/item/'.$key->img) : $key->img,
                'small_price'   => $key->small_price,
                'last_price'    => $key->last_price,
                'medium_price'  => $key->medium_price,
                'large_price'   => $key->large_price,
                'xlarge_price'  => $key->xlarge_price,
                'sort_no'       => $key->sort_no,
                'nonveg'        => 0,
                'trending'      => $key->trending,
                's_data'        => $key->s_data,
                'created_at'    => $key->created_at,
                'updated_at '   => $key->updated_at,
            ); 
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'id',
            'store_id',
            'category_id',
            'name',
            'description',
            'status',
            'img',
            'small_price',
            'last_price',
            'medium_price',
            'large_price',
            'xlarge_price',
            'sort_no',
            'nonveg',
            'trending',
            's_data',
            'created_at',
            'updated_at'
        ];
    }
 
}
